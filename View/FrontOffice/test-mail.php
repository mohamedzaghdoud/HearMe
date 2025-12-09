<?php
/**
 * ✅ PAGE DE TEST AMÉLIORÉE - Envoi d'email de vérification
 * Emplacement : MON PROJET/View/FrontOffice/test-email.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/EmailService.php';

// Sécurité : accessible uniquement en local
if (!in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1'])) {
    die('Page accessible uniquement en développement local');
}

$message = '';
$success = false;
$verifyLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['test_email'] ?? '';
    
    if ($email) {
        $emailService = new EmailService();
        $token = EmailService::generateToken();
        
        // Tester l'envoi
        $result = $emailService->sendVerificationEmail($email, $token);
        
        if ($result) {
            $success = true;
            
            // ✅ CORRECTION : Construction correcte du lien
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $verifyLink = $protocol . '://' . $host . '/hearme/View/FrontOffice/verify-email.php?token=' . $token;
            
            $logFile = __DIR__ . '/../../logs/emails_' . date('Y-m-d') . '.log';
            
            $message = "✅ Email envoyé (logué) !<br><br>" .
                       "📂 Fichier log : <code>$logFile</code><br><br>" .
                       "🔗 <strong>Lien à tester :</strong><br>" .
                       "<a href='$verifyLink' target='_blank' style='color: #00ccff; word-break: break-all;'>$verifyLink</a>";
        } else {
            $message = "❌ Erreur lors de l'envoi";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Email - HearMe DEV</title>
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
            max-width: 700px;
            margin: 0 auto;
            background: #2a2a2a;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 0 30px rgba(0, 255, 0, 0.3);
        }
        h1 {
            color: #00ff00;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 0 10px #00ff00;
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
        .info-box p {
            color: #ccc;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .info-box code {
            background: #1a1a1a;
            padding: 2px 8px;
            border-radius: 4px;
            color: #00ff00;
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
        .form-group input {
            width: 100%;
            padding: 12px;
            background: #1a1a1a;
            border: 2px solid #00ff00;
            border-radius: 8px;
            color: #00ff00;
            font-size: 16px;
        }
        .form-group input:focus {
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
        .result a {
            color: #00ccff;
            word-break: break-all;
        }
        .steps {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            margin-top: 25px;
        }
        .steps ol {
            margin-left: 20px;
            color: #ccc;
        }
        .steps li {
            margin-bottom: 10px;
            line-height: 1.6;
        }
        .copy-btn {
            background: #00ccff;
            color: #000;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test d'envoi d'email</h1>
        
        <div class="warning">
            ⚠️ PAGE DE TEST - DÉVELOPPEMENT UNIQUEMENT
        </div>

        <div class="info-box">
            <h3>📋 Comment ça marche ?</h3>
            <p>En développement local, les emails ne sont <strong>pas envoyés réellement</strong>.</p>
            <p>Ils sont <strong>sauvegardés dans un fichier log</strong> pour que tu puisses tester.</p>
            <p>📂 Fichier log : <code>logs/emails_<?php echo date('Y-m-d'); ?>.log</code></p>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="test_email">📧 Email de test</label>
                <input 
                    type="email" 
                    id="test_email" 
                    name="test_email" 
                    placeholder="test@exemple.com"
                    value="<?php echo isset($_POST['test_email']) ? htmlspecialchars($_POST['test_email']) : 'test@hearme.com'; ?>"
                    required
                >
            </div>

            <button type="submit" class="btn">🚀 Envoyer un email de test</button>
        </form>

        <?php if ($message): ?>
            <div class="result <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
                
                <?php if ($success && $verifyLink): ?>
                    <button class="copy-btn" onclick="copyToClipboard('<?php echo $verifyLink; ?>')">
                        📋 Copier le lien
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="steps">
            <h3 style="color: #00ccff; margin-bottom: 15px;">📖 Étapes pour tester :</h3>
            <ol>
                <li>Clique sur "Envoyer un email de test"</li>
                <li><strong>COPIE le lien qui apparaît en bleu</strong></li>
                <li><strong>COLLE-le directement dans ton navigateur</strong></li>
                <li>Tu devrais voir la page "Email vérifié !"</li>
            </ol>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Lien copié ! Collez-le dans votre navigateur.');
            });
        }
    </script>
</body>
</html>