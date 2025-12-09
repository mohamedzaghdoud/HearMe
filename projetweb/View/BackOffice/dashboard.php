<?php
require_once '../../controller/PostController.php';
require_once '../../controller/CommentController.php';

$postC = new PostController();
$commentC = new CommentController();

// Récupérer les statistiques
$postsResult = $postC->postList();
$posts = $postsResult->fetchAll();
$totalPosts = is_array($posts) ? count($posts) : 0;

// Compter les commentaires totaux
$totalComments = 0;
$recentComments = [];
$postsWithMostComments = [];

if (is_array($posts)) {
    foreach ($posts as $post) {
        $count = $commentC->countCommentsByPost($post['id']);
        $totalComments += $count;
        
        // Récupérer les commentaires récents
        $postComments = $commentC->getCommentsByPost($post['id']);
        foreach ($postComments as $comment) {
            $recentComments[] = [
                'id' => $comment['id'],
                'text' => substr($comment['text'], 0, 50) . (strlen($comment['text']) > 50 ? '...' : ''),
                'post_id' => $post['id'],
                'created_at' => $comment['created_at']
            ];
        }
        
        $postsWithMostComments[] = [
            'id' => $post['id'],
            'content' => substr($post['content'], 0, 30) . (strlen($post['content']) > 30 ? '...' : ''),
            'comments' => $count
        ];
    }
}

// Trier par nombre de commentaires (décroissant)
usort($postsWithMostComments, function($a, $b) {
    return $b['comments'] - $a['comments'];
});

// Garder les 5 premiers
$postsWithMostComments = array_slice($postsWithMostComments, 0, 5);

// Trier les commentaires récents par date (décroissant)
usort($recentComments, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

// Garder les 5 derniers commentaires
$recentComments = array_slice($recentComments, 0, 5);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - HearMe Forum Admin</title>
    <link rel="stylesheet" href="css/backoffice.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cogs"></i> Admin Panel</h2>
                <p>HearMe Forum</p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                </a>
                <a href="postList.php" class="nav-item">
                    <i class="fas fa-newspaper"></i> Gestion des Posts
                </a>
                <a href="comments.php" class="nav-item">
                    <i class="fas fa-comments"></i> Commentaires
                    <span class="badge"><?php echo $totalComments; ?></span>
                </a>
                <a href="addpost.php" class="nav-item">
                    <i class="fas fa-plus-circle"></i> Nouveau Post
                </a>
                <a href="badWords.php" class="nav-item">
                    <i class="fas fa-shield-alt"></i> Gros Mots
                </a>
                <div class="nav-divider"></div>
                <a href="../FrontOffice/postList.php" class="nav-item">
                    <i class="fas fa-eye"></i> Voir le FrontOffice
                </a>
                <a href="../FrontOffice/postList.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Retour au site
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="admin-info">
                    <div class="admin-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <p class="admin-name">Administrateur</p>
                        <p class="admin-role">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h1>
                    <p>Vue d'ensemble et statistiques du forum</p>
                </div>
                <div class="header-right">
                    <div class="header-date" id="currentDateTime">
                        <!-- Rempli par JavaScript -->
                    </div>
                </div>
            </header>

            <!-- Statistiques Principales -->
            <div class="admin-content">
                <div class="stats-grid">
                    <div class="stat-card primary">
                        <div class="stat-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $totalPosts; ?></h3>
                            <p>Posts Totaux</p>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> 12%
                        </div>
                    </div>
                    
                    <div class="stat-card success">
                        <div class="stat-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $totalComments; ?></h3>
                            <p>Commentaires</p>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> 8%
                        </div>
                    </div>
                    
                    <div class="stat-card warning">
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>
                                <?php 
                                    $totalLikes = 0;
                                    if (is_array($posts)) {
                                        foreach ($posts as $post) {
                                            $totalLikes += $post['likes'];
                                        }
                                    }
                                    echo $totalLikes;
                                ?>
                            </h3>
                            <p>Likes Totaux</p>
                        </div>
                        <div class="stat-trend up">
                            <i class="fas fa-arrow-up"></i> 15%
                        </div>
                    </div>
                    
                    <div class="stat-card danger">
                        <div class="stat-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="stat-info">
                            <h3>
                                <?php 
                                    $postsWithImages = 0;
                                    if (is_array($posts)) {
                                        foreach ($posts as $post) {
                                            if (!empty($post['image'])) {
                                                $postsWithImages++;
                                            }
                                        }
                                    }
                                    echo $postsWithImages;
                                ?>
                            </h3>
                            <p>Posts avec Images</p>
                        </div>
                        <div class="stat-trend down">
                            <i class="fas fa-arrow-down"></i> 3%
                        </div>
                    </div>
                </div>

                <!-- Graphiques et Tableaux -->
                <div class="dashboard-grid">
                    <!-- Graphique des commentaires -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><i class="fas fa-chart-line"></i> Activité des Commentaires</h3>
                            <select id="chartPeriod" onchange="updateChart()">
                                <option value="7">7 derniers jours</option>
                                <option value="30" selected>30 derniers jours</option>
                                <option value="90">90 derniers jours</option>
                            </select>
                        </div>
                        <div class="card-body">
                            <canvas id="commentsChart"></canvas>
                        </div>
                    </div>

                    <!-- Posts les plus commentés -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><i class="fas fa-fire"></i> Posts les plus commentés</h3>
                        </div>
                        <div class="card-body">
                            <div class="top-posts-list">
                                <?php if (!empty($postsWithMostComments)): ?>
                                    <?php foreach ($postsWithMostComments as $index => $post): ?>
                                        <div class="top-post-item">
                                            <div class="post-rank">
                                                <span class="rank-number">#<?php echo $index + 1; ?></span>
                                            </div>
                                            <div class="post-content">
                                                <h4>Post #<?php echo $post['id']; ?></h4>
                                                <p><?php echo htmlspecialchars($post['content']); ?></p>
                                            </div>
                                            <div class="post-stats">
                                                <span class="comments-count">
                                                    <i class="fas fa-comment"></i>
                                                    <?php echo $post['comments']; ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="no-data">Aucun commentaire pour le moment</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Commentaires récents -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><i class="fas fa-history"></i> Commentaires récents</h3>
                        </div>
                        <div class="card-body">
                            <div class="recent-comments">
                                <?php if (!empty($recentComments)): ?>
                                    <?php foreach ($recentComments as $comment): ?>
                                        <div class="recent-comment-item">
                                            <div class="comment-avatar-small">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="comment-content-small">
                                                <p class="comment-text"><?php echo htmlspecialchars($comment['text']); ?></p>
                                                <div class="comment-meta">
                                                    <span>Post #<?php echo $comment['post_id']; ?></span>
                                                    <span>•</span>
                                                    <span><?php echo htmlspecialchars($comment['created_at']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="no-data">Aucun commentaire récent</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3><i class="fas fa-bolt"></i> Actions rapides</h3>
                        </div>
                        <div class="card-body">
                            <div class="quick-actions">
                                <a href="addpost.php" class="quick-action-btn primary">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Créer un nouveau post</span>
                                </a>
                                <a href="comments.php" class="quick-action-btn success">
                                    <i class="fas fa-comments"></i>
                                    <span>Voir tous les commentaires</span>
                                </a>
                                <a href="postList.php" class="quick-action-btn warning">
                                    <i class="fas fa-edit"></i>
                                    <span>Modifier un post</span>
                                </a>
                                <button class="quick-action-btn info" onclick="refreshData()">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Actualiser les données</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification système -->
                <div class="system-notification" id="systemNotification">
                    <div class="notification-content">
                        <i class="fas fa-bell"></i>
                        <span>Système à jour. Toutes les fonctionnalités sont opérationnelles.</span>
                    </div>
                    <button class="notification-close" onclick="closeNotification()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="js/backoffice.js?v=<?php echo time(); ?>"></script>
    <script>
        // Initialiser le graphique
        const commentsChart = new Chart(document.getElementById('commentsChart'), {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    label: 'Commentaires',
                    data: [12, 19, 8, 15, 22, 18, 25],
                    borderColor: '#4a6bdf',
                    backgroundColor: 'rgba(74, 107, 223, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        function updateChart() {
            const period = document.getElementById('chartPeriod').value;
            // Ici, vous pourriez faire un appel AJAX pour récupérer les données réelles
            console.log('Période sélectionnée:', period + ' jours');
        }

        function refreshData() {
            const notification = document.getElementById('systemNotification');
            notification.querySelector('span').textContent = 'Données actualisées avec succès !';
            notification.style.display = 'flex';
            
            // Simuler un rechargement
            setTimeout(() => {
                location.reload();
            }, 1000);
        }

        function closeNotification() {
            document.getElementById('systemNotification').style.display = 'none';
        }

        // Mettre à jour la date et l'heure
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('currentDateTime').textContent = 
                now.toLocaleDateString('fr-FR', options);
        }

        // Mettre à jour toutes les secondes
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>
</html>