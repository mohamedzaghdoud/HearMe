<?php
require_once __DIR__ . '/../../../config.php';
secureSession();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'HearMe - Bien-être Mental' ?></title>
    
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/templatemo-softy-pinko.css">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/custom.css">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/footer.css">
    
    <?php if (isset($additionalCss)): ?>
        <?= $additionalCss ?>
    <?php endif; ?>
</head>
<body>

    <!-- Preloader -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <!-- Header -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- Logo -->
                        <a href="/hearme_user/View/FrontOffice/Home.php" class="logo">
                            <img src="/hearme_user/View/FrontOffice/assets/images/logo.png" 
                                 alt="HearMe" 
                                 style="max-height: 60px; margin-top: -15px; margin-left: 20px;">
                        </a>

                        <!-- Menu -->
                        <ul class="nav">
                            <li><a href="/hearme_user/View/FrontOffice/Home.php">Accueil</a></li>
                            
                            <?php if (isLoggedIn()): ?>
                                <li><a href="/hearme_user/View/FrontOffice/Profilfront.php">Mon Profil</a></li>
                            <?php endif; ?>
                            
                            <li><a href="/hearme_user/Controller/TestController.php">Tests Émotionnels</a></li>
                            
                            <?php if (isLoggedIn()): ?>
                                <li><a href="/hearme_user/View/BackOffice/logout.php" class="btn-logout">Déconnexion</a></li>
                            <?php else: ?>
                                <li><a href="/hearme_user/View/FrontOffice/Login.php" class="btn-cta">Connexion</a></li>
                            <?php endif; ?>
                        </ul>
                        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Start -->
    <main>
