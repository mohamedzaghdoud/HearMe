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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #E8F4F8 0%, #B8E6F0 50%, #D4E8F0 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 50px;
            max-width: 550px;
            width: 100%;
            text-align: center;
        }

        .icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }

        h1 {
            color: #5BA8C8;
            font-size: 32px;
            margin-bottom: 15px;
        }

        .email-highlight {
            color: #2E7D32;
            font-weight: 600;
            background: #E5F7E5;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
            margin: 15px 0;
        }

        p {
            color: #555;
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .instructions {
            background: #F5F5F5;
            padding: 20px;
            border-radius: 12px;
            margin: 25px 0;
            text-align: left;
        }

        .instructions h3 {
            color: #5BA8C8;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .instructions ol {
            margin-left: 20px;
            color: #555;
        }

        .instructions li {
            margin-bottom: 8px;
        }

        .btn {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 168, 200, 0.3);
        }

        .help-text {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E0E0E0;
            font-size: 14px;
            color: #999;
        }
    </style>
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

        <a href="Login.php" class="btn">Retour à la connexion</a>

        <div class="help-text">
            <p>Vous n'avez pas reçu l'email ?</p>
            <p>Vérifiez vos spams ou <a href="resend-verification.php?email=<?php echo urlencode($email); ?>" style="color: #5BA8C8; font-weight: 600;">renvoyez l'email</a></p>
        </div>
    </div>
</body>
</html>