<?php include __DIR__ . '/../layout/header.php'; ?>

<!-- ***** Welcome Area Start ***** -->
<div class="welcome-area hero-section" id="welcome">
    <div class="header-text">
        <div class="container">
            <div class="row">
                <div class="offset-xl-3 col-xl-6 offset-lg-2 col-lg-8 col-md-12 col-sm-12">
                    <img src="../../assets/template/images/logo.png" 
                          alt="HearMe" 
                          style="max-height: 70px; margin-left: 20px;">
                    <br>
                    <h1 class="text-white">Prends Soin de Ton Bien-être Mental</h1>
                    <br><br>
                    <a href="#features" class="main-button-slider">Découvrir</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ***** Welcome Area End ***** -->

<!-- ***** Features Start ***** -->
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
                            
                            // Définir l'image du quiz
                            $quizImage = !empty($quiz['image']) ? $quiz['image'] : 'default-quiz.png';
                            $imagePath = "../../assets/images/quiz/" . $quizImage;
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
                                                 style="max-width: 100%; height: auto; border-radius: 15px;">
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
                                                <span class="badge bg-info me-2">
                                                    <i class="fa fa-tag"></i> <?= htmlspecialchars($quiz['categorie']) ?>
                                                </span>
                                                <span class="badge bg-secondary me-2">
                                                    <i class="fa fa-clock"></i> <?= $quiz['duree_estimee'] ?> min
                                                </span>
                                                <span class="badge bg-success">
                                                    <i class="fa fa-question"></i> <?= $quiz['nb_questions'] ?> questions
                                                </span>
                                            </div>
                                            
                                            <?php if($quiz['nb_questions'] > 0): ?>
                                            <a href="TestController.php?action=test&id=<?= $quiz['id_quiz'] ?>" class="main-button">
                                                Commencer le Test
                                            </a>
                                            <?php else: ?>
                                            <button class="main-button" disabled style="opacity: 0.5; cursor: not-allowed;">
                                                Aucune question disponible
                                            </button>
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
                                                 style="max-width: 100%; height: auto; border-radius: 15px;">
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
<section class="section colored" id="historique" style="background-color: var(--hearme-light);">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="center-heading">
                    <h2 class="section-title">Suis Ton Évolution</h2>
                    <div class="evolution-image">
                         <img src="../../assets/images/quiz/evolution.png" alt="Évolution">
                    </div>
                    <br>
                    <p>Consulte tes résultats précédents et observe ta progression émotionnelle dans le temps.</p>
                    <br><br>
                </div>
            </div>
            <div class="offset-lg-3 col-lg-6">
                <div class="center-text">
                    <a href="TestController.php?action=historique" class="main-button">
                        <i class="fa fa-chart-line"></i> Voir Mon Historique
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Historique Section End ***** -->

<style>
/* Styles additionnels pour la page d'accueil */
.section-heading {
    text-align: center;
}

.section-heading.text-start {
    text-align: left !important;
}

.welcome-area {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.info-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.features-item {
    margin-bottom: 80px;
}

.quiz-preview-image {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
}

.quiz-preview-image img {
    max-width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: contain;
}

.left-image, .right-image {
    text-align: center;
    padding: 20px;
}

.left-image img, .right-image img {
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.left-image img:hover, .right-image img:hover {
    transform: scale(1.05);
}

.main-button {
    background: linear-gradient(135deg, var(--hearme-primary) 0%, var(--hearme-secondary) 100%);
    color: white;
    padding: 15px 40px;
    border-radius: 30px;
    font-weight: 600;
    display: inline-block;
    text-decoration: none;
    transition: all 0.3s ease;
}

.main-button:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(167, 199, 231, 0.4);
    color: white;
}

/* Responsive */
@media (max-width: 991px) {
    .left-image, .right-image {
        margin-bottom: 30px;
    }
}
.evolution-image {
    text-align: center;
    padding: 20px;
}

.evolution-image img {
    max-width: 100%;
    height: auto;
    max-height: 400px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.evolution-image img:hover {
    transform: scale(1.05);
}

</style>

<?php include __DIR__ . '/../layout/footer.php'; ?>