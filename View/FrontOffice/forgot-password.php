<?php
/**
 * Page de demande de réinitialisation de mot de passe - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/forgot-password.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';
require_once __DIR__ . '/../../Model/EmailService.php';

secureSession();

$message = '';
$success = false;

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
            
            if ($user) {
                // Générer un token
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Supprimer les anciens tokens de cet utilisateur
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
                
                // Envoyer l'email
                $emailService = new EmailService();
                $emailService->sendPasswordResetEmail($email, $token);
                
                $success = true;
                $message = "Un email de réinitialisation a été envoyé à votre adresse.";
            } else {
                // Pour la sécurité, on affiche le même message
                $success = true;
                $message = "Si cet email existe, un lien de réinitialisation a été envoyé.";
            }
            
        } catch (PDOException $e) {
            error_log("Erreur forgot-password: " . $e->getMessage());
            $message = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - HearMe</title>
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
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #5BA8C8;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .logo p {
            color: #7A7A7A;
            font-size: 14px;
        }

        .icon {
            text-align: center;
            font-size: 60px;
            margin-bottom: 20px;
        }

        .description {
            text-align: center;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #5BA8C8;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px;
            border: 2px solid #E0E0E0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #5BA8C8;
            box-shadow: 0 0 0 3px rgba(91, 168, 200, 0.1);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #FFE5E5;
            color: #D32F2F;
            border: 1px solid #FFB3B3;
        }

        .alert-success {
            background-color: #E5F7E5;
            color: #2E7D32;
            border: 1px solid #B3E5B3;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 168, 200, 0.3);
        }

        .links {
            text-align: center;
            margin-top: 25px;
        }

        .links a {
            color: #5BA8C8;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>🎧 HearMe</h1>
        </div>

        <div class="icon">🔑</div>

        <div class="description">
            <h2 style="color: #5BA8C8; margin-bottom: 15px;">Mot de passe oublié ?</h2>
            <p>Entrez votre email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $success ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST" action="" id="forgotForm">
                <div class="form-group">
                    <label for="email">Votre email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="votreemail@exemple.com"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    📧 Envoyer le lien de réinitialisation
                </button>
            </form>
        <?php else: ?>
            <a href="Login.php" class="btn btn-primary">
                ← Retour à la connexion
            </a>
        <?php endif; ?>

        <div class="links">
            <a href="Login.php">← Retour à la connexion</a>
        </div>
    </div>

    <script>
        document.getElementById('forgotForm')?.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();

            if (!email) {
                e.preventDefault();
                alert('Veuillez entrer votre email.');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Veuillez entrer un email valide.');
                return false;
            }

            return true;
        });
    </script>
</body>
</html>