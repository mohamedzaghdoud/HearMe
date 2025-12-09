<?php
/**
 * Page de réinitialisation de mot de passe - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/reset-password.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';

secureSession();

$token = $_GET['token'] ?? '';
$message = '';
$success = false;
$tokenValid = false;
$email = '';

// Vérifier le token
if ($token) {
    try {
        $userModel = new User();
        $db = $userModel->getDb();
        
        $sql = "SELECT pr.id, pr.user_id, pr.expires_at, pr.used, u.email 
                FROM password_resets pr
                JOIN users u ON pr.user_id = u.id_user
                WHERE pr.reset_token = :token";
        $stmt = $db->prepare($sql);
        $stmt->execute([':token' => $token]);
        $resetData = $stmt->fetch();
        
        if (!$resetData) {
            $message = "Lien de réinitialisation invalide.";
        } elseif ($resetData['used']) {
            $message = "Ce lien a déjà été utilisé.";
        } elseif (strtotime($resetData['expires_at']) < time()) {
            $message = "Ce lien a expiré. Veuillez refaire une demande.";
        } else {
            $tokenValid = true;
            $email = $resetData['email'];
        }
        
    } catch (PDOException $e) {
        error_log("Erreur vérification token: " . $e->getMessage());
        $message = "Une erreur est survenue.";
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValid) {
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($newPassword) || empty($confirmPassword)) {
        $message = "Tous les champs sont requis.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Validation du mot de passe
        $userModel = new User();
        $passwordValidation = $userModel->validatePassword($newPassword);
        
        if ($passwordValidation !== true) {
            $message = $passwordValidation;
        } else {
            try {
                $db = $userModel->getDb();
                
                // Mettre à jour le mot de passe
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = :password WHERE id_user = :user_id";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':password' => $hashedPassword,
                    ':user_id' => $resetData['user_id']
                ]);
                
                // Marquer le token comme utilisé
                $sql = "UPDATE password_resets SET used = TRUE WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute([':id' => $resetData['id']]);
                
                $success = true;
                $message = "Votre mot de passe a été réinitialisé avec succès !";
                
            } catch (PDOException $e) {
                error_log("Erreur reset password: " . $e->getMessage());
                $message = "Une erreur est survenue lors de la réinitialisation.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - HearMe</title>
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

        .email-highlight {
            background: #E5F7E5;
            color: #2E7D32;
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
            margin: 10px 0;
            font-weight: 600;
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

        .password-requirements {
            background: #F5F5F5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 13px;
        }

        .password-requirements ul {
            margin: 10px 0 0 20px;
            color: #666;
        }

        .password-requirements li {
            margin-bottom: 5px;
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

        <?php if (!$tokenValid && !empty($token)): ?>
            <!-- Token invalide ou expiré -->
            <div class="icon">❌</div>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <a href="forgot-password.php" class="btn btn-primary">
                Faire une nouvelle demande
            </a>
            <div class="links">
                <a href="Login.php">← Retour à la connexion</a>
            </div>

        <?php elseif ($success): ?>
            <!-- Succès -->
            <div class="icon">✅</div>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <a href="Login.php" class="btn btn-primary">
                Se connecter
            </a>

        <?php elseif ($tokenValid): ?>
            <!-- Formulaire de réinitialisation -->
            <div class="icon">🔐</div>
            <div class="description">
                <h2 style="color: #5BA8C8; margin-bottom: 15px;">Nouveau mot de passe</h2>
                <p>Créez un nouveau mot de passe pour votre compte :</p>
                <div class="email-highlight"><?php echo htmlspecialchars($email); ?></div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="resetForm">
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Votre nouveau mot de passe"
                        required
                    >
                    <div class="password-requirements">
                        <strong>Le mot de passe doit contenir :</strong>
                        <ul>
                            <li>Au moins 8 caractères</li>
                            <li>Au moins une majuscule</li>
                            <li>Au moins une minuscule</li>
                            <li>Au moins un chiffre</li>
                            <li>Au moins un caractère spécial (@$!%*?&)</li>
                        </ul>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Confirmez votre mot de passe"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    🔒 Réinitialiser le mot de passe
                </button>
            </form>

            <div class="links">
                <a href="Login.php">← Retour à la connexion</a>
            </div>

        <?php else: ?>
            <!-- Aucun token fourni -->
            <div class="icon">⚠️</div>
            <div class="alert alert-error">
                Lien de réinitialisation manquant.
            </div>
            <a href="forgot-password.php" class="btn btn-primary">
                Demander un nouveau lien
            </a>
            <div class="links">
                <a href="Login.php">← Retour à la connexion</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (!password || !confirmPassword) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
                return false;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return false;
            }

            // Validation du mot de passe
            if (password.length < 8) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 8 caractères.');
                return false;
            }

            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecialChar = /[@$!%*?&]/.test(password);

            if (!hasUpperCase || !hasLowerCase || !hasNumber || !hasSpecialChar) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.');
                return false;
            }

            return true;
        });
    </script>
</body>
</html>