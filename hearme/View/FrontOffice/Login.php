<?php
/**
 * Page de connexion - HearMe
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

// Messages de succès (inscription, etc.)
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'registered') {
        $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    }
}
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

        .btn:active {
            transform: translateY(0);
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

        @media (max-width: 500px) {
            .container {
                padding: 30px 20px;
            }

            .logo h1 {
                font-size: 28px;
            }
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

            <button type="submit" class="btn">Se connecter</button>
        </form>

        <div class="divider">───── OU ─────</div>

        <div class="links">
            <p>Pas encore de compte ?</p>
            <a href="../FrontOffice/register.php">S'inscrire maintenant</a>
        </div>

        <div class="links" style="margin-top: 15px;">
            <a href="forgot-password.php">Mot de passe oublié ?</a>
        </div>
    </div>

    <script>
        // Validation côté client
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
                return false;
            }

            // Validation email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Veuillez entrer un email valide.');
                return false;
            }

            return true;
        });
    </script>
    <script src="assets/js/frontoffice.js"></script>
</body>
</html>