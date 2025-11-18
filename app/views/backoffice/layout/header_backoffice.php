<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - HearMe</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS BACKOFFICE -->
    <link rel="stylesheet" href="../../assets/css/backoffice.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="QuestionController.php">
                <img src="../../assets/images/logo.png" alt="HearMe Logo" class="me-2" style="height: 60px;">
                Admin HearMe
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a href="QuestionController.php" class="nav-link text-white">
                        <i class="fas fa-list"></i> Questions
                    </a>
                    <a href="QuestionController.php?action=resultats" class="nav-link text-white">
                        <i class="fas fa-chart-bar"></i> Résultats
                    </a>
                    <a href="../logout.php" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid py-4"></div>