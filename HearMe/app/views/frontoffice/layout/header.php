<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HearMe - Plateforme de Bien-être Mental</title>
    
    <!-- Softy Pinko Template CSS -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/template/css/font-awesome.css">
    <link rel="stylesheet" href="../../assets/template/css/templatemo-softy-pinko.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    
    <!-- Custom CSS pour HearMe -->
    <link rel="stylesheet" href="../../assets/template/css/custom.css">
    
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="../../index.php" class="logo">
                            <img src="../../assets/template/images/logo.png" 
                                 alt="HearMe" 
                                 style="max-height: 60px; margin-top: -15px; margin-left: 20px;">
                        </a>
                        <!-- ***** Logo End ***** -->
                        
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li><a href="../../index.php" class="active">Accueil</a></li>
                            <li><a href="TestController.php">Tests Émotionnels</a></li>
                            <li><a href="TestController.php?action=historique">Mon Historique</a></li>
                            
                            <?php if(isset($_GET['admin']) || strpos($_SERVER['REQUEST_URI'], 'QuizController') !== false || strpos($_SERVER['REQUEST_URI'], 'QuestionController') !== false): ?>
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i> Admin
                                </a>
                                <div class="dropdown-menu" aria-labelledby="adminDropdown">
                                    <a class="dropdown-item" href="QuizController.php">Gérer les Quiz</a>
                                    <a class="dropdown-item" href="QuestionController.php">Gérer les Questions</a>
                                    <a class="dropdown-item" href="QuestionController.php?action=resultats">Résultats</a>
                                </div>
                            </li>
                            <?php endif; ?>
                            
                            <li><a href="TestController.php?action=test" class="btn-cta">Commencer un Test</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->