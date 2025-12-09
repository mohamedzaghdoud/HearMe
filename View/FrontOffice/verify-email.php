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
    <style>
        body { background: #f5f5f5; font-family: Arial, sans-serif; }
        .container { max-width: 500px; margin: 100px auto; background: white; padding: 30px; border-radius: 15px; text-align: center; }
        .success { color: green; }
        .error { color: red; }
        .btn { background: #5BA8C8; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($result): ?>
            <?php if ($result['success']): ?>
                <h2 class="success">✅ Email vérifié !</h2>
                <p><?php echo htmlspecialchars($result['message']); ?></p>
                <a href="Login.php" class="btn">Se connecter</a>
            <?php else: ?>
                <h2 class="error">❌ Échec de vérification</h2>
                <p><?php echo htmlspecialchars($result['message']); ?></p>
                <a href="Login.php" class="btn">Retour à la connexion</a>
            <?php endif; ?>
        <?php else: ?>
            <h2 class="error">Lien invalide</h2>
            <p>Aucun token fourni.</p>
            <a href="Login.php" class="btn">Retour à la connexion</a>
        <?php endif; ?>
    </div>
</body>
</html>