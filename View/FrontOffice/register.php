<?php
/**
 * Page d'inscription AVEC CAPTCHA CRÉATIF - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/register.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';
require_once __DIR__ . '/../../Model/CaptchaService.php';

secureSession();

if (isLoggedIn()) {
    redirect(isAdmin() ? '../BackOffice/dashboard.html' : 'Home.html');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captchaType = $_SESSION['captcha_type'] ?? 'emoji';
    $captchaAnswer = trim($_POST['captcha'] ?? '');
    
    if (empty($captchaAnswer)) {
        $error = "⚠️ Veuillez compléter la vérification anti-robot.";
    } elseif (!CaptchaService::verifyCaptcha($captchaAnswer, $captchaType)) {
        $error = "❌ Vérification anti-robot échouée. Réessayez.";
    } else {
        $controller = new UserController();
        $result = $controller->register();
        if (isset($result) && !$result['success']) {
            $error = $result['message'];
        }
    }
}

// Générer un CAPTCHA Emoji Math
$captcha = CaptchaService::generateEmojiMath();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - HearMe</title>
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
            max-width: 550px;
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

        .form-group small {
            display: block;
            color: #999;
            font-size: 12px;
            margin-top: 5px;
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

        .password-requirements {
            background-color: #F5F5F5;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .password-requirements h4 {
            color: #5BA8C8;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .password-requirements ul {
            margin-left: 20px;
            font-size: 12px;
            color: #7A7A7A;
        }

        .password-requirements li {
            margin-bottom: 5px;
        }

        /* CAPTCHA EMOJI MATH */
        .captcha-box {
            background: linear-gradient(135deg, #F8F9FA 0%, #E9ECEF 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 3px solid #5BA8C8;
            box-shadow: 0 5px 20px rgba(91, 168, 200, 0.2);
        }

        .captcha-label {
            text-align: center;
            display: block;
            margin-bottom: 20px;
            color: #5BA8C8;
            font-weight: 700;
            font-size: 18px;
        }

        .emoji-math {
            text-align: center;
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 2px dashed #5BA8C8;
            margin-bottom: 15px;
        }

        .emoji-question {
            font-size: 48px;
            margin: 15px 0;
            font-family: 'Arial', sans-serif;
        }

        .emoji-legend {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .emoji-item {
            background: #F5F5F5;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 20px;
        }

        .captcha-input {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #2E7D32;
            padding: 15px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
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

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>🎧 HearMe</h1>
            <p>Créez votre compte</p>
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

        <form method="POST" action="" id="registerForm">
            <div class="form-group">
                <label for="email">Email <span style="color: #D32F2F;">*</span></label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="votreemail@exemple.com"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Mot de passe <span style="color: #D32F2F;">*</span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Créez un mot de passe fort"
                    required
                >
                <div class="password-requirements">
                    <h4>Le mot de passe doit contenir :</h4>
                    <ul>
                        <li>Au moins 8 caractères</li>
                        <li>Au moins une majuscule (A-Z)</li>
                        <li>Au moins une minuscule (a-z)</li>
                        <li>Au moins un chiffre (0-9)</li>
                        <li>Au moins un caractère spécial (!@#$%...)</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe <span style="color: #D32F2F;">*</span></label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Répétez votre mot de passe"
                    required
                >
            </div>

            <!-- CAPTCHA EMOJI MATH -->
            <div class="captcha-box">
                <label class="captcha-label">
                    🤖 Vérification Anti-Robot
                </label>
                
                <div class="emoji-math">
                    <div class="emoji-question">
                        <?php echo $captcha['question']; ?>
                    </div>
                    
                    <div class="emoji-legend">
                        <?php foreach ($captcha['legend'] as $emoji => $value): ?>
                            <div class="emoji-item">
                                <?php echo $emoji; ?> = <?php echo $value; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <input 
                    type="number" 
                    id="captcha" 
                    name="captcha" 
                    placeholder="Tapez la réponse"
                    class="captcha-input"
                    style="width: 100%; border: 2px solid #5BA8C8; border-radius: 10px;"
                    required
                    autocomplete="off"
                >
                
                <small style="display: block; text-align: center; color: #666; margin-top: 12px;">
                    💡 Résolvez ce calcul amusant pour continuer
                </small>
            </div>

            <button type="submit" class="btn">S'inscrire</button>
        </form>

        <div class="divider">───── OU ─────</div>

        <div class="links">
            <p>Vous avez déjà un compte ?</p>
            <a href="Login.php">Se connecter</a>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const captcha = document.getElementById('captcha').value.trim();

            if (!email || !password || !confirmPassword || !captcha) {
                e.preventDefault();
                alert('⚠️ Veuillez remplir tous les champs.');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Veuillez entrer un email valide.');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 8 caractères.');
                return false;
            }

            if (!/[A-Z]/.test(password)) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins une majuscule.');
                return false;
            }

            if (!/[a-z]/.test(password)) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins une minuscule.');
                return false;
            }

            if (!/[0-9]/.test(password)) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins un chiffre.');
                return false;
            }

            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins un caractère spécial.');
                return false;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return false;
            }

            return true;
        });

        // Indicateur de force du mot de passe en temps réel
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const requirements = document.querySelectorAll('.password-requirements li');

            requirements[0].style.color = password.length >= 8 ? '#2E7D32' : '#7A7A7A';
            requirements[1].style.color = /[A-Z]/.test(password) ? '#2E7D32' : '#7A7A7A';
            requirements[2].style.color = /[a-z]/.test(password) ? '#2E7D32' : '#7A7A7A';
            requirements[3].style.color = /[0-9]/.test(password) ? '#2E7D32' : '#7A7A7A';
            requirements[4].style.color = /[!@#$%^&*(),.?":{}|<>]/.test(password) ? '#2E7D32' : '#7A7A7A';
        });
    </script>
</body>
</html>