<?php include __DIR__ . '/../layout/header_frontoffice.php'; ?>

<section class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 100px 0; color: white;">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <i class="fas fa-heart fa-4x mb-4" style="opacity: 0.9;"></i>
                <h1 class="display-4 mb-4">Auto-évaluation Émotionnelle</h1>
                <p class="lead mb-5">
                    Prends quelques minutes pour évaluer ton état émotionnel. 
                    Reçois des conseils personnalisés et suis ton évolution dans le temps.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="features-section py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="feature-card p-4">
                    <div class="icon-circle mx-auto mb-3" 
                         style="width: 80px; height: 80px; background: #667eea; border-radius: 50%; 
                                display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clipboard-check fa-2x text-white"></i>
                    </div>
                    <h4>Test Rapide</h4>
                    <p class="text-muted">
                        Quelques questions simples pour évaluer ton état émotionnel
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="feature-card p-4">
                    <div class="icon-circle mx-auto mb-3" 
                         style="width: 80px; height: 80px; background: #764ba2; border-radius: 50%; 
                                display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-lightbulb fa-2x text-white"></i>
                    </div>
                    <h4>Conseils Personnalisés</h4>
                    <p class="text-muted">
                        Reçois des suggestions adaptées à ton résultat
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="feature-card p-4">
                    <div class="icon-circle mx-auto mb-3" 
                         style="width: 80px; height: 80px; background: #f093fb; border-radius: 50%; 
                                display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chart-line fa-2x text-white"></i>
                    </div>
                    <h4>Suivi dans le temps</h4>
                    <p class="text-muted">
                        Visualise ton évolution émotionnelle
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="TestController.php?action=test" 
                    class="btn btn-outline-primary">
                    Commencer le test
                </a>
                <a href="TestController.php?action=historique" class="btn btn-outline-primary">
                    Voir mon historique
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,106.7C1248,96,1344,96,1392,96L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
    background-size: cover;
    opacity: 0.3;
}

.feature-card {
    transition: transform 0.3s ease;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.btn-light:hover {
    transform: scale(1.05);
    transition: all 0.3s ease;
}
</style>

<?php include __DIR__ . '/../layout/footer_frontoffice.php'; ?>