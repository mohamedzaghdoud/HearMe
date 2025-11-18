<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HearMe - Auto-évaluation Émotionnelle</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/frontoffice.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-heart-pulse"></i> <strong>HearMe</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="TestController.php">
                            <i class="fas fa-clipboard-check"></i> Test Émotionnel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="TestController.php?action=historique">
                            <i class="fas fa-history"></i> Mon Historique
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../profil.php">
                            <i class="fas fa-user"></i> Mon Profil
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>