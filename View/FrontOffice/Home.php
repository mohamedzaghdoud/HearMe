<?php 
$pageTitle = "HearMe - Accueil";
include __DIR__ . '/layout/header.php'; 
?>
<link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/home.css">

<!-- ***** Welcome Area Start ***** -->
<div class="welcome-area hero-section" id="welcome">
    <div class="header-text">
        <div class="container">
            <div class="row">
                <div class="offset-xl-3 col-xl-6 offset-lg-2 col-lg-8 col-md-12 col-sm-12">
                </div>
            </div>
        </div>
    </div>
    <!-- Wave - White -->
    <svg class="wave wave2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 200" preserveAspectRatio="none">
        <path fill="#ffffff" d="M0,120 C240,20 480,180 720,100 C960,20 1200,180 1440,80 L1440,200 L0,200 Z"></path>
    </svg>
</div>
<!-- ***** Welcome Area End ***** -->

<!-- ***** Features Start ***** -->
<section class="section home-feature" id="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <br><br><br><br><br><br>
                    <h2>Ce que nous offrons</h2>
                    <br>
                    <p>Des outils pour prendre soin de votre bien-être mental</p>
                    <br><br>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Feature 1 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="features-item text-center p-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <path d="M12 8v4"></path>
                            <path d="M12 16h.01"></path>
                        </svg>
                    </div>
                    <h4>Anonymat garanti</h4>
                    <p>Partagez vos pensées en toute confidentialité.</p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="features-item text-center p-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </div>
                    <h4>Écoute bienveillante</h4>
                    <p>Recevez du soutien d'une communauté empathique.</p>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="features-item text-center p-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            <path d="M8 10h.01"></path>
                            <path d="M12 10h.01"></path>
                            <path d="M16 10h.01"></path>
                        </svg>
                    </div>
                    <h4>Cercles d'écoute</h4>
                    <p>Rejoignez des groupes de personnes similaires.</p>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="features-item text-center p-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <h4>Messages d'encouragement</h4>
                    <p>Lisez et envoyez des messages bienveillants.</p>
                </div>
            </div>
            
            <!-- Feature 5 - Tests Émotionnels -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="features-item text-center p-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                    </div>
                    <h4>Tests Émotionnels</h4>
                    <p>Évalue ton bien-être et reçois des conseils personnalisés.</p>
                </div>
            </div>
            
            <!-- Feature 6 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="features-item text-center p-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                    </div>
                    <h4>Suivi de progression</h4>
                    <p>Observe ton évolution émotionnelle dans le temps.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Features End ***** -->

<?php include __DIR__ . '/layout/footer.php'; ?>
