<?php
/**
 * Page de confirmation d'envoi d'email - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/email-sent.php
 */

$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérifiez votre email - HearMe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/email-pages.css">
</head>
<body>
    <div class="container">
        <div class="icon">📧</div>
        <h1>Vérifiez votre email</h1>
        
        <p>Un email de vérification a été envoyé à :</p>
        <div class="email-highlight"><?php echo htmlspecialchars($email); ?></div>

        <div class="instructions">
            <h3>📋 Prochaines étapes :</h3>
            <ol>
                <li>Ouvrez votre boîte de réception</li>
                <li>Recherchez l'email de <strong>HearMe Platform</strong></li>
                <li>Cliquez sur le bouton de vérification</li>
                <li>Connectez-vous à votre compte</li>
            </ol>
        </div>

        <p>🔒 Le lien est valide pendant <strong>24 heures</strong>.</p>

        <a href="/hearme_user/View/FrontOffice/Login.php" class="btn">Retour à la connexion</a>

        <div class="help-text">
            <p>Vous n'avez pas reçu l'email ?</p>
            <p>Vérifiez vos spams ou <a href="resend-verification.php?email=<?php echo urlencode($email); ?>">renvoyez l'email</a></p>
        </div>
    </div>
</body>
</html>
