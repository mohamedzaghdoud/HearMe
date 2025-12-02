<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="resultat-section py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 80vh;">
    <br><br><br><br><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-center py-4 bg-<?= $resultat['couleur'] ?> text-white">
                        <i class="fas fa-chart-pie fa-3x mb-3"></i>
                        <h2 class="mb-0">Ton Résultat</h2>
                    </div>

                    <div class="card-body p-5 text-center">
                        <!-- Score -->
                        <div class="score-circle mx-auto mb-4" 
                             style="width: 150px; height: 150px; border-radius: 50%; 
                                    background: linear-gradient(135deg, #00ecadff 0%, #fb68fbff 100%);
                                    display: flex; flex-direction: column; 
                                    align-items: center; justify-content: center;
                                    box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                            <h1 class="text-white mb-0" style="font-size: 3rem;">
                                <?= $resultat['score'] ?>
                            </h1>
                            <p class="text-white mb-0">points</p>
                        </div>

                        <!-- Niveau -->
                        <h3 class="mb-4">
                            <span class="badge badge-<?= $resultat['couleur'] ?> p-3" 
                                  style="font-size: 1.3rem;">
                                <?= htmlspecialchars($resultat['niveau']) ?>
                            </span>
                        </h3>

                        <!-- Conseil -->
                        <div class="alert alert-<?= $resultat['couleur'] ?> p-4 mb-4" 
                             style="border-radius: 15px; font-size: 1.1rem;">
                            <p class="mb-0">
                                <i class="fas fa-comment-dots"></i>
                                <?= htmlspecialchars($resultat['conseil']) ?>
                            </p>
                        </div>

                        <!-- Suggestions intelligentes -->
                        <div class="suggestions-box mt-5">
                            <h4 class="mb-4">
                                <i class="fas fa-lightbulb text-warning"></i> 
                                Ressources Recommandées
                            </h4>

                            <?php if($resultat['score'] > 10): ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <i class="fas fa-video fa-2x text-primary mb-3"></i>
                                            <h5>Méditation Guidée</h5>
                                            <p class="text-muted">
                                                Une séance de 10 min pour te détendre
                                            </p>
                                            <a href="https://youtube.com/watch?v=relaxation" 
                                               target="_blank" class="btn btn-primary btn-sm">
                                                Regarder <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border-success">
                                        <div class="card-body">
                                            <h5>Cercle d'Écoute</h5>
                                            <p class="text-muted">
                                                Rejoins le cercle "Gestion du stress"
                                            </p>
                                            <a href="../module4/cercles.php" 
                                               class="btn btn-success btn-sm">
                                                Rejoindre
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info">
                                <strong>Bravo !</strong> Continue à partager ta paix intérieure 
                                sur le Mur de Bienveillance 
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Actions -->
                        <div class="action-buttons mt-5">
                            <a href="?action=historique" class="btn btn-outline-primary btn-lg mx-2">
                                Voir mon historique
                            </a>
                            <a href="?action=test" class="btn btn-primary btn-lg mx-2">
                                 Refaire le test
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Citation motivante -->
                <div class="text-center mt-4">
                    <blockquote class="blockquote">
                        <p class="mb-0" style="font-style: italic; color: #666;">
                            "La santé mentale est aussi importante que la santé physique. 
                            Prendre soin de soi, c'est prendre soin de son avenir."
                        </p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>