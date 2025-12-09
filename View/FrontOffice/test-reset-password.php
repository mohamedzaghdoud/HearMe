<?php
/**
 * 🧪 PAGE DE TEST - Reset Password avec affichage du lien
 * Emplacement : MON PROJET/View/FrontOffice/test-reset-password.php
 * ⚠️ À SUPPRIMER EN PRODUCTION !
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';
require_once __DIR__ . '/../../Model/EmailService.php';

// Sécurité : accessible uniquement en local
if (!in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1'])) {
    die('Page accessible uniquement en développement local');
}

secureSession();

$message = '';
$success = false;
$resetLink = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $message = 'Veuillez entrer votre email.';
    } else {
        try {
            $userModel = new User();
            $db = $userModel->getDb();
            
            // Vérifier si l'email existe
            $sql = "SELECT id_user, email FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $message = '❌ Cet email n\'existe pas dans la base de données.';
            } else {
                // Générer un token
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Supprimer les anciens tokens
                $sql = "DELETE FROM password_resets WHERE user_id = :user_id";
                $stmt = $db->prepare($sql);
                $stmt->execute([':user_id' => $user['id_user']]);
                
                // Insérer le nouveau token
                $sql = "INSERT INTO password_resets (user_id, reset_token, expires_at) 
                        VALUES (:user_id, :token, :expires_at)";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':user_id' => $user['id_user'],
                    ':token' => $token,
                    ':expires_at' => $expiresAt
                ]);
                
                // Envoyer l'email (sera loggé)
                $emailService = new EmailService();
                $emailService->sendPasswordResetEmail($email, $token);
                
                // ✅ CONSTRUIRE LE LIEN pour l'afficher directement
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];
                $resetLink = $protocol . '://' . $host . '/hearme/View/FrontOffice/reset-password.php?token=' . $token;
                
                $success = true;
                $message = '✅ Token créé avec succès !';
            }
            
        } catch (PDOException $e) {
            error_log("Erreur test-reset: " . $e->getMessage());
            $message = '❌ Erreur : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Reset Password - HearMe DEV</title>
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
        .link-box {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px solid #00ccff;
        }
        .link-box h3 {
            color: #00ccff;
            margin-bottom: 15px;
        }
        .link-display {
            background: #000;
            padding: 15px;
            border-radius: 8px;
            word-break: break-all;
            color: #00ccff;
            font-family: monospace;
            margin-bottom: 15px;
            border: 1px dashed #00ccff;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .copy-btn, .test-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .copy-btn {
            background: #00ccff;
            color: #000;
        }
        .test-btn {
            background: #ff9900;
            color: #000;
        }
        .copy-btn:hover {
            background: #00aadd;
            transform: translateY(-2px);
        }
        .test-btn:hover {
            background: #ff8800;
            transform: translateY(-2px);
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
    </style>
</head>
<body>
    <div class="container">
        <a href="Login.php" class="back-btn">← Retour au Login</a>
        
        <h1>🧪 Test Reset Password</h1>
        
        <div class="warning">
            ⚠️ PAGE DE TEST - DÉVELOPPEMENT UNIQUEMENT
        </div>

        <div class="info-box">
            <h3>📋 Comment utiliser cette page ?</h3>
            <p>Cette page génère un lien de réinitialisation et l'affiche directement.</p>
            <p>En local, les emails ne sont PAS envoyés - ils sont loggés dans <code>logs/</code></p>
            <p>Utilisez cette page pour tester le reset password sans chercher dans les logs.</p>
        </div>

        <form method="POST" action="" id="testForm">
            <div class="form-group">
                <label for="email">📧 Email de l'utilisateur</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="utilisateur@exemple.com"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required
                >
            </div>

            <button type="submit" class="btn">🚀 Générer le lien de reset</button>
        </form>

        <?php if ($message): ?>
            <div class="result <?php echo $success ? 'success' : 'error'; ?>">
                <strong><?php echo $message; ?></strong>
            </div>
        <?php endif; ?>

        <?php if ($success && $resetLink): ?>
            <div class="link-box">
                <h3>🔗 Lien de réinitialisation généré</h3>
                
                <div class="link-display">
                    <?php echo htmlspecialchars($resetLink); ?>
                </div>

                <p style="color: #ccc; margin-bottom: 15px;">
                    ✅ Ce lien est valide pendant <strong>1 heure</strong>
                </p>

                <div class="action-buttons">
                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($resetLink, ENT_QUOTES); ?>')">
                        📋 Copier le lien
                    </button>
                    <button class="test-btn" onclick="window.open('<?php echo htmlspecialchars($resetLink, ENT_QUOTES); ?>', '_blank')">
                        🔗 Tester maintenant
                    </button>
                </div>
            </div>

            <div class="link-box" style="border-color: #ff9900;">
                <h3 style="color: #ff9900;">📝 Où est l'email ?</h3>
                <p style="color: #ccc;">
                    L'email a été <strong>loggé</strong> (pas envoyé) dans :<br>
                    <code style="color: #ff9900;">logs/emails_<?php echo date('Y-m-d'); ?>.log</code>
                </p>
                <p style="color: #999; margin-top: 10px; font-size: 13px;">
                    En production, l'email sera réellement envoyé par mail()
                </p>
            </div>
        <?php endif; ?>

        <div class="steps">
            <h3 style="color: #00ccff; margin-bottom: 15px;">📖 Prochaines étapes :</h3>
            <ol>
                <li>Entrez l'email d'un utilisateur existant</li>
                <li>Cliquez sur "Générer le lien"</li>
                <li>Cliquez sur "Tester maintenant" pour ouvrir la page de reset</li>
                <li>Entrez un nouveau mot de passe</li>
                <li>Testez la connexion avec le nouveau mot de passe</li>
            </ol>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Lien copié dans le presse-papier !');
            }).catch(err => {
                console.error('Erreur copie:', err);
                // Fallback pour anciens navigateurs
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('✅ Lien copié !');
            });
        }

        // Auto-submit sur Enter
        document.getElementById('testForm')?.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            if (!email) {
                e.preventDefault();
                alert('Veuillez entrer un email.');
                return false;
            }
        });
    </script>
</body>
</html>