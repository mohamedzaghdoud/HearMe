<?php
/**
 * Page de connexion COMPLÈTE - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/Login.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

// Redirection si déjà connecté
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('../BackOffice/dashboard.html');
    } else {
        redirect('Home.html');
    }
}

$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();
    $result = $controller->login();
    
    if (isset($result) && !$result['success']) {
        $error = $result['message'];
    }
}

// Messages de succès
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'registered') {
        $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    } elseif ($_GET['success'] === 'logout') {
        $success = "Déconnexion réussie.";
    }
}

// URL OAuth Google
require_once __DIR__ . '/../../oauth_config.php';
$googleAuthUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    'client_id' => GOOGLE_OAUTH_CLIENT_ID,
    'redirect_uri' => GOOGLE_OAUTH_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'email profile',
    'access_type' => 'online',
    'prompt' => 'select_account',
]);
// AJOUTER CETTE LIGNE - GÉNÉRER LE CAPTCHA
require_once __DIR__ . '/../../Model/CaptchaService.php';
$captcha = CaptchaService::generateEmojiMath();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - HearMe</title>
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
            max-width: 450px;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 168, 200, 0.3);
        }

        .btn-google {
            background: white;
            color: #333;
            border: 2px solid #E0E0E0;
            margin-top: 15px;
        }

        .btn-google:hover {
            background: #f8f8f8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-face {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-top: 10px;
        }

        .btn-face:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
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

        .divider {
            text-align: center;
            margin: 25px 0;
            color: #999;
            font-size: 14px;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #E0E0E0;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .icon-google {
            width: 20px;
            height: 20px;
        }
        
        .captcha-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            margin-top: 5px;
        }

        .captcha-label {
            display: block;
            color: #5BA8C8;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .emoji-math {
            background: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            border: 1px dashed #5BA8C8;
        }

        .emoji-question {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .emoji-legend {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #666;
        }

        .emoji-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .captcha-input {
            padding: 12px;
            font-size: 16px;
            margin-top: 10px;
            text-align: center;
            width: 100%;
            border: 2px solid #5BA8C8;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>🎧 HearMe</h1>
            <p>Connectez-vous à votre espace</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="votreemail@exemple.com"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                >
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Votre mot de passe"
                >
            </div>
            
            <div class="form-group">
                <label>Vérification de sécurité</label>
                <div class="captcha-box">
                    <label class="captcha-label">
                        🤖 Vérification Anti-Robot
                    </label>
                    
                    <div class="emoji-math">
                        <div class="emoji-question">
                            <?php echo htmlspecialchars($captcha['question'] ?? ''); ?>
                        </div>
                        
                        <div class="emoji-legend">
                            <?php if (isset($captcha['legend']) && is_array($captcha['legend'])): ?>
                                <?php foreach ($captcha['legend'] as $emoji => $value): ?>
                                    <div class="emoji-item">
                                        <?php echo htmlspecialchars($emoji); ?> = <?php echo htmlspecialchars($value); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <input type="hidden" name="captcha_answer" value="<?php echo htmlspecialchars($captcha['answer'] ?? ''); ?>">
                    
                    <input 
                        type="number" 
                        id="captcha" 
                        name="captcha" 
                        placeholder="Tapez la réponse"
                        class="captcha-input"
                        required
                        autocomplete="off"
                    >
                    
                    <small style="display: block; text-align: center; color: #666; margin-top: 12px;">
                        💡 Résolvez ce calcul amusant pour continuer
                    </small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                🔐 Se connecter
            </button>
        </form>

        <div class="divider">OU</div>

        <!-- Connexion Google OAuth -->
        <a href="<?php echo $googleAuthUrl; ?>" class="btn btn-google">
            <svg class="icon-google" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continuer avec Google
        </a>
<!-- Connexion par reconnaissance faciale -->
<button type="button" class="btn btn-face" onclick="window.location.href='face-id.html'">
    👤 Se connecter par Face ID 
</button>

        <div class="links">
            <p>Pas encore de compte ?</p>
            <a href="register.php">S'inscrire maintenant</a>
        </div>

        <div class="links" style="margin-top: 15px;">
            <a href="forgot-password.php">Mot de passe oublié ?</a>
        </div>
    </div>
     <div class="links" style="margin-top: 15px; font-size: 12px; color: #999;">
        <a href="../BackOffice/dev-tools.php" style="color: #999;">🛠️ Dev Tools</a>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const captcha = document.getElementById('captcha').value.trim();

            if (!email || !password || !captcha) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Veuillez entrer un email valide.');
                return false;
            }

            if (isNaN(captcha)) {
                e.preventDefault();
                alert('La réponse de vérification doit être un nombre.');
                return false;
            }

            return true;
        });
    </script>
</body>
</html>