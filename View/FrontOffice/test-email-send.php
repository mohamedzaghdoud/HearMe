<?php
/**
 * 🧪 PAGE DE TEST - Envoi d'email réel
 * Emplacement : MON PROJET/View/FrontOffice/test-email-send.php
 * ⚠️ À SUPPRIMER EN PRODUCTION !
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/EmailService.php';

// Sécurité : accessible uniquement en local
if (!in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1'])) {
    die('Page accessible uniquement en développement local');
}

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailTo = trim($_POST['email'] ?? '');
    $testType = $_POST['test_type'] ?? 'reset';
    
    if (empty($emailTo)) {
        $message = 'Veuillez entrer un email.';
    } else {
        try {
            $emailService = new EmailService();
            $token = bin2hex(random_bytes(32));
            
            if ($testType === 'verification') {
                $result = $emailService->sendVerificationEmail($emailTo, $token);
                $emailType = "vérification d'email";
            } else {
                $result = $emailService->sendPasswordResetEmail($emailTo, $token);
                $emailType = "réinitialisation de mot de passe";
            }
            
            if ($result) {
                $success = true;
                $message = "✅ Email de $emailType envoyé avec succès à $emailTo !";
            } else {
                $message = "❌ Erreur lors de l'envoi de l'email.";
            }
            
        } catch (Exception $e) {
            $message = "❌ Erreur : " . $e->getMessage();
            error_log("Erreur test-email-send: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Envoi Email - HearMe DEV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #00ff00;
            padding: 40px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #2a2a2a;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 0 30px rgba(0, 255, 0, 0.3);
        }
        h1 {
            color: #00ff00;
            text-align: center;
            margin-bottom: 10px;
            text-shadow: 0 0 10px #00ff00;
        }
        .subtitle {
            text-align: center;
            color: #00ccff;
            margin-bottom: 30px;
            font-size: 18px;
        }
        .warning {
            background: #ff4444;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .info-box {
            background: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 2px solid #00ccff;
        }
        .info-box h3 {
            color: #00ccff;
            margin-bottom: 15px;
        }
        .info-box ol, .info-box ul {
            margin-left: 20px;
            color: #ccc;
        }
        .info-box li {
            margin-bottom: 8px;
            line-height: 1.5;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: #00ccff;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            background: #1a1a1a;
            border: 2px solid #00ff00;
            border-radius: 8px;
            color: #00ff00;
            font-size: 16px;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: #00ff00;
            color: #000;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: #00cc00;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 255, 0, 0.5);
        }
        .result {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid;
        }
        .result.success {
            background: #004400;
            border-color: #00ff00;
            color: #00ff00;
        }
        .result.error {
            background: #440000;
            border-color: #ff4444;
            color: #ff4444;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #5BA8C8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        code {
            background: #1a1a1a;
            padding: 2px 6px;
            border-radius: 4px;
            color: #ff9900;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="Login.php" class="back-btn">← Retour au Login</a>
        
        <h1>🧪 Test Envoi Email</h1>
        <p class="subtitle">Testez l'envoi réel d'emails SMTP</p>
        
        <div class="warning">
            ⚠️ PAGE DE TEST - DÉVELOPPEMENT UNIQUEMENT
        </div>

        <div class="info-box">
            <h3>📋 Configuration requise :</h3>
            <ol>
                <li>PHPMailer installé (<code>composer require phpmailer/phpmailer</code>)</li>
                <li>Compte Gmail avec validation 2 étapes activée</li>
                <li>Mot de passe d'application Gmail généré</li>
                <li>Configuration SMTP dans <code>EmailService.php</code></li>
            </ol>
        </div>

        <div class="info-box" style="border-color: #ff9900;">
            <h3 style="color: #ff9900;">🔧 Vérifiez votre configuration :</h3>
            <ul>
                <li><code>$smtpUsername</code> = Votre email Gmail</li>
                <li><code>$smtpPassword</code> = Mot de passe d'application (16 caractères)</li>
                <li><code>$smtpHost</code> = smtp.gmail.com</li>
                <li><code>$smtpPort</code> = 587</li>
            </ul>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">📧 Email destinataire</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="destinataire@exemple.com"
                    required
                >
                <small style="color: #999; display: block; margin-top: 5px;">
                    Entrez votre propre email pour recevoir le test
                </small>
            </div>

            <div class="form-group">
                <label for="test_type">📨 Type d'email à tester</label>
                <select id="test_type" name="test_type">
                    <option value="reset">🔐 Réinitialisation de mot de passe</option>
                    <option value="verification">✉️ Vérification d'email</option>
                </select>
            </div>

            <button type="submit" class="btn">
                🚀 Envoyer l'email de test
            </button>
        </form>

        <?php if ($message): ?>
            <div class="result <?php echo $success ? 'success' : 'error'; ?>">
                <strong><?php echo $message; ?></strong>
                
                <?php if ($success): ?>
                    <p style="margin-top: 15px;">
                        Vérifiez votre boîte de réception et le dossier spam.
                    </p>
                <?php else: ?>
                    <p style="margin-top: 15px;">
                        Vérifiez les logs dans <code>logs/emails_<?php echo date('Y-m-d'); ?>.log</code>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="info-box" style="border-color: #9900ff; margin-top: 30px;">
            <h3 style="color: #9900ff;">🐛 En cas d'erreur :</h3>
            <ol>
                <li>Vérifiez que PHPMailer est installé</li>
                <li>Vérifiez vos identifiants Gmail</li>
                <li>Vérifiez que la validation 2 étapes est activée</li>
                <li>Utilisez un mot de passe d'APPLICATION (pas votre mot de passe Gmail)</li>
                <li>Consultez les logs PHP : <code>logs/</code></li>
            </ol>
        </div>
    </div>
</body>
</html>