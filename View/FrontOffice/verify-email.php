<?php
/**
 * Page de vérification d'email - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/verify-email.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';

$token = $_GET['token'] ?? '';
$userModel = new User();
$result = null;

if ($token) {
    $result = $userModel->verifyEmailToken($token);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification email - HearMe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/email-pages.css">
</head>
<body>
    <div class="container verify-container">
        <?php if ($result): ?>
            <?php if ($result['success']): ?>
                <div class="icon">✅</div>
                <h2 class="success">Email vérifié !</h2>
                <p><?php echo htmlspecialchars($result['message']); ?></p>
                <a href="/hearme_user/View/FrontOffice/Login.php" class="btn">Se connecter</a>
            <?php else: ?>
                <div class="icon">❌</div>
                <h2 class="error">Échec de vérification</h2>
                <p><?php echo htmlspecialchars($result['message']); ?></p>
                <a href="/hearme_user/View/FrontOffice/Login.php" class="btn">Retour à la connexion</a>
            <?php endif; ?>
        <?php else: ?>
            <div class="icon">⚠️</div>
            <h2 class="error">Lien invalide</h2>
            <p>Aucun token fourni.</p>
            <a href="/hearme_user/View/FrontOffice/Login.php" class="btn">Retour à la connexion</a>
        <?php endif; ?>
    </div>
</body>
</html>
