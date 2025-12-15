<?php 
$pageTitle = "Tests Émotionnels - HearMe";
$additionalCss = '<link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/test-accueil.css">';
include __DIR__ . '/../layout/header.php'; 
?>

<!-- ***** Features Start ***** -->
<br><br><br><br><br><br><br><br>
<section class="section home-feature" id="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading">
                            <br><br><br><br><br><br><br><br>
                            <h2>Tests Émotionnels Disponibles</h2>
                            <br><br>
                            <p>Choisis un test adapté à tes besoins et commence ton parcours vers le bien-être</p>
                            <br><br><br><br><br><br>
                        </div>
                    </div>

                    <?php if($quizList->rowCount() > 0): ?>
                        <?php 
                        $count = 0;
                        while($quiz = $quizList->fetch(PDO::FETCH_ASSOC)): 
                            $count++;
                            $leftRight = ($count % 2 == 1) ? 'left' : 'right';
                            $quizImage = !empty($quiz['image']) ? $quiz['image'] : 'tests.png';
                            $imagePath = "/hearme_user/View/FrontOffice/assets/images/" . $quizImage;
                        ?>
                        <div class="col-lg-12">
                            <div class="features-item">
                                <div class="row align-items-center">
                                    <!-- Image à gauche pour les quiz impairs -->
                                    <?php if($leftRight == 'left'): ?>
                                    <div class="col-lg-6 order-lg-1 order-2">
                                        <div class="left-image">
                                            <img src="<?= $imagePath ?>" 
                                                 alt="<?= htmlspecialchars($quiz['titre']) ?>" 
                                                 class="img-fluid"
                                                 onerror="this.src='/hearme_user/View/FrontOffice/assets/images/tests.png'">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 order-lg-2 order-1">
                                    <?php else: ?>
                                    <!-- Texte à gauche pour les quiz pairs -->
                                    <div class="col-lg-6 order-lg-1 order-1">
                                    <?php endif; ?>
                                        <div class="<?= $leftRight ?>-text-content">
                                            <div class="section-heading text-start">
                                                <h4><?= htmlspecialchars($quiz['titre']) ?></h4>
                                            </div>
                                            <p><?= htmlspecialchars($quiz['description'] ?? 'Évalue ton état émotionnel.') ?></p>
                                            <div class="info-badges mb-3">
                                                <span class="badge bg-info me-2"><i class="fa fa-tag"></i> <?= htmlspecialchars($quiz['categorie']) ?></span>
                                                <span class="badge bg-secondary me-2"><i class="fa fa-clock"></i> <?= $quiz['duree_estimee'] ?> min</span>
                                                <span class="badge bg-success"><i class="fa fa-question"></i> <?= $quiz['nb_questions'] ?> questions</span>
                                            </div>
                                            <?php if($quiz['nb_questions'] > 0): ?>
                                                <a href="/hearme_user/Controller/TestController.php?action=test&id=<?= $quiz['id_quiz'] ?>" class="main-button">Commencer le Test</a>
                                            <?php else: ?>
                                                <button class="main-button" disabled>Aucune question disponible</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- Image à droite pour les quiz pairs -->
                                    <?php if($leftRight == 'right'): ?>
                                    <div class="col-lg-6 order-lg-2 order-2">
                                        <div class="right-image">
                                            <img src="<?= $imagePath ?>" 
                                                 alt="<?= htmlspecialchars($quiz['titre']) ?>" 
                                                 class="img-fluid"
                                                 onerror="this.src='/hearme_user/View/FrontOffice/assets/images/tests.png'">
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-lg-12 text-center">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle fa-3x mb-3"></i>
                                <h4>Aucun test disponible pour le moment</h4>
                                <p>De nouveaux tests seront bientôt ajoutés. Revenez nous voir !</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Features End ***** -->

<!-- ***** Historique Section Start ***** -->
<section class="section colored" id="historique">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="center-heading">
                    <h2 class="section-title">Suis Ton Évolution</h2>
                    <div class="evolution-image">
                        <img src="/hearme_user/View/FrontOffice/assets/images/tests.png" alt="Évolution">
                    </div>
                    <br>
                    <p>Consulte tes résultats précédents et observe ta progression émotionnelle dans le temps.</p>
                    <br><br>
                </div>
            </div>
            <div class="offset-lg-3 col-lg-6">
                <div class="center-text">
                    <a href="/hearme_user/Controller/TestController.php?action=historique" class="main-button">
                        <i class="fa fa-chart-line"></i> Voir Mon Historique
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Historique Section End ***** -->

<?php include __DIR__ . '/../layout/footer.php'; ?>
