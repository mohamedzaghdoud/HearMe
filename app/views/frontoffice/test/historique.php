<?php 
include __DIR__ . '/../layout/header_frontoffice.php'; 

// Préparer les données
$resultats = [];
$dates = [];
$scores = [];

if(isset($stmt) && $stmt->rowCount() > 0): 
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        $resultats[] = $row;
        $dates[] = date('d/m', strtotime($row['date_test']));
        $scores[] = intval($row['score_total']);
    endwhile;
endif;
?>

<section class="historique-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header text-center mb-5">
                    <h2><i class="fas fa-history text-primary"></i> Mon Historique Émotionnel</h2>
                    <p class="text-muted">Visualise l'évolution de ton bien-être dans le temps</p>
                </div>

                <?php if(count($resultats) > 0): ?>
                <!-- Graphique -->
                <div class="card mb-4 shadow">
                    <div class="card-header bg-gradient text-white" 
                         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line"></i> Évolution de ton Score
                            <small class="float-end">(<?= count($resultats) ?> tests)</small>
                        </h5>
                    </div>
                    <div class="card-body" style="min-height: 400px;">
                        <canvas id="chartEvolution"></canvas>
                    </div>
                </div>

                <!-- Liste des résultats -->
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Historique Détaillé</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach($resultats as $row): ?>
                        <div class="result-item p-3 mb-3 border rounded">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <div class="score-badge" style="width: 70px; height: 70px; 
                                         border-radius: 50%; 
                                         background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                         display: flex; align-items: center; justify-content: center;
                                         margin: 0 auto;">
                                        <h3 class="text-white mb-0"><?= $row['score_total'] ?></h3>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h5 class="mb-2">
                                        <?php
                                        if($row['score_total'] <= 5) {
                                            echo '<span class="badge bg-success">Calme</span>';
                                        } elseif($row['score_total'] <= 10) {
                                            echo '<span class="badge bg-warning">Léger stress</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">Tension élevée</span>';
                                        }
                                        ?>
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <?= htmlspecialchars($row['conseil_genere']) ?>
                                    </p>
                                </div>
                                <div class="col-md-3 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i>
                                        <?= date('d/m/Y à H:i', strtotime($row['date_test'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php else: ?>
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucun historique disponible</h4>
                        <p>Passe ton premier test pour commencer à suivre ton évolution émotionnelle</p>
                        <a href="?action=test" class="btn btn-primary btn-lg">
                            <i class="fas fa-play-circle"></i> Commencer le test
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="?action=accueil" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-arrow-left"></i> Retour à l'accueil
                    </a>
                    <a href="?action=test" class="btn btn-primary btn-lg">
                        <i class="fas fa-redo"></i> Refaire le test
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.result-item {
    transition: all 0.3s ease;
    background: white;
}

.result-item:hover {
    transform: translateX(5px);
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
}

.card {
    border: none;
}
</style>

<?php if(!empty($scores)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if(typeof Chart === 'undefined') {
        document.getElementById('chartEvolution').parentElement.innerHTML = 
            '<div class="alert alert-danger">Erreur: Impossible de charger le graphique</div>';
        return;
    }
    
    const canvas = document.getElementById('chartEvolution');
    if(!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const dates = <?= json_encode(array_reverse($dates)) ?>;
    const scores = <?= json_encode(array_reverse($scores)) ?>;
    
    try {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Score Émotionnel',
                    data: scores,
                    backgroundColor: 'rgba(102, 126, 234, 0.2)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointBackgroundColor: 'rgba(102, 126, 234, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: 'rgba(118, 75, 162, 1)',
                    pointHoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                family: "'Segoe UI', Arial, sans-serif"
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 2,
                        padding: 15,
                        displayColors: false,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let niveau = '';
                                if(context.parsed.y <= 5) niveau = ' - Calme 😌';
                                else if(context.parsed.y <= 10) niveau = ' - Léger stress 😐';
                                else niveau = ' - Tension élevée 😰';
                                
                                return 'Score: ' + context.parsed.y + ' points' + niveau;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 20,
                        ticks: {
                            stepSize: 5,
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return value + ' pts';
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Score Émotionnel',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: 10
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Date des tests',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: 10
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    } catch(error) {
        canvas.parentElement.innerHTML = 
            '<div class="alert alert-danger">Erreur lors de la création du graphique</div>';
    }
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer_frontoffice.php'; ?>