<?php 
$pageTitle = "Résultat - HearMe";
$additionalCss = '<link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/test-resultat.css">';
include __DIR__ . '/../layout/header.php';
?>
<br><br><br><br>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card result-card shadow-lg border-0">
                    <div class="card-header bg-<?= $resultat['couleur'] ?> text-white text-center py-4">
                        <h3>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                            </svg>
                            Ton Résultat
                        </h3>
                    </div>
                    <div class="card-body text-center p-5">
                        <div class="score-circle bg-<?= $resultat['couleur'] ?> text-white mb-4">
                            <?= $resultat['pourcentage'] ?>%
                        </div>
                        
                        <h2 class="text-<?= $resultat['couleur'] ?> mb-3"><?= e($resultat['niveau']) ?></h2>
                        
                        <p class="lead mb-4"><?= e($resultat['conseil']) ?></p>
                        
                        <div class="bg-light rounded p-3 mb-4">
                            <small class="text-muted">
                                Score: <?= $resultat['score'] ?> / <?= $resultat['score_max'] ?>
                            </small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="/hearme_user/Controller/TestController.php" class="btn btn-primary btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                    <polyline points="23 4 23 10 17 10"></polyline>
                                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                                </svg>
                                Faire un autre test
                            </a>
                            <br><br>
                            <?php if (isLoggedIn()): ?>
                            <a href="/hearme_user/Controller/TestController.php?action=historique" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                Voir mon historique
                            </a>
                            <?php endif; ?>
                            <a href="/hearme_user/View/FrontOffice/Home.php" class="btn btn-outline-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section Ressources Dynamiques -->
            <div class="col-lg-10 mt-5">
                <div class="card shadow border-0" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px; color: #A7C7E7;">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            Ressources recommandées pour toi
                        </h4>
                        
                        <?php
                        // Ressources dynamiques selon le pourcentage
                        $pourcentage = $resultat['pourcentage'];
                        
                        if ($pourcentage >= 70) {
                            $ressources = [
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>',
                                    'titre' => 'Continue comme ça !',
                                    'description' => 'Maintiens tes bonnes habitudes : sommeil régulier, activité physique et relations sociales positives.'
                                ],
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>',
                                    'titre' => 'Partage ton énergie',
                                    'description' => 'Tu peux aider les autres ! Rejoins notre communauté comme écoutant bénévole.'
                                ],
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
                                    'titre' => 'Nouveaux défis',
                                    'description' => 'Explore de nouvelles activités : yoga, méditation avancée, ou développement personnel.'
                                ]
                            ];
                        } elseif ($pourcentage >= 40) {
                            $ressources = [
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#FF9800" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
                                    'titre' => 'Prends soin de toi',
                                    'description' => 'Accorde-toi des moments de pause. Essaie 10 minutes de respiration profonde chaque jour.'
                                ],
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#FF9800" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                                    'titre' => 'Parle à quelqu\'un',
                                    'description' => 'N\'hésite pas à partager ce que tu ressens avec un ami, un proche ou un professionnel.'
                                ],
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#FF9800" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                                    'titre' => 'Routine bien-être',
                                    'description' => 'Établis une routine : sommeil régulier, alimentation équilibrée et exercice modéré.'
                                ]
                            ];
                        } else {
                            $ressources = [
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#F44336" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
                                    'titre' => 'Ligne d\'écoute 3114',
                                    'description' => 'Appelle le 3114 (gratuit, 24h/24). Des professionnels sont là pour t\'écouter sans jugement.'
                                ],
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#F44336" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
                                    'titre' => 'Tu n\'es pas seul(e)',
                                    'description' => 'Ce que tu ressens est temporaire. Parle à un proche de confiance ou consulte un professionnel.'
                                ],
                                [
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#F44336" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M12 8v4"></path><path d="M12 16h.01"></path></svg>',
                                    'titre' => 'Aide professionnelle',
                                    'description' => 'Consulte un médecin ou psychologue. Ton bien-être mental est aussi important que ta santé physique.'
                                ]
                            ];
                        }
                        ?>
                        
                        <div class="row">
                            <?php foreach ($ressources as $ressource): ?>
                            <div class="col-md-4 mb-3">
                                <div class="resource-item p-3 text-center h-100">
                                    <div class="resource-icon mb-2">
                                        <?= $ressource['icon'] ?>
                                    </div>
                                    <h6><?= $ressource['titre'] ?></h6>
                                    <p class="small text-muted mb-0"><?= $ressource['description'] ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
