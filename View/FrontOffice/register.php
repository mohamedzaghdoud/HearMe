<?php
/**
 * Page d'inscription - HearMe
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';
require_once __DIR__ . '/../../Model/CaptchaService.php';

secureSession();

if (isLoggedIn()) {
    redirect(isAdmin() ? '../BackOffice/dashboard.php' : 'Home.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captchaType = $_SESSION['captcha_type'] ?? 'emoji';
    $captchaAnswer = trim($_POST['captcha'] ?? '');
    
    if (empty($captchaAnswer)) {
        $error = "Veuillez compléter la vérification anti-robot.";
    } elseif (!CaptchaService::verifyCaptcha($captchaAnswer, $captchaType)) {
        $error = "Vérification anti-robot échouée. Réessayez.";
    } else {
        $controller = new UserController();
        $result = $controller->register();
        if (isset($result) && !$result['success']) {
            $error = $result['message'];
        }
    }
}

$captcha = CaptchaService::generateEmojiMath();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - HearMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/auth.css">
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>
            <h1>HearMe</h1>
            <p>Créez votre compte</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="registerForm">
            <div class="form-group">
                <label>Email <span style="color: #D32F2F;">*</span></label>
                <input type="email" name="email" placeholder="votreemail@exemple.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label>Mot de passe <span style="color: #D32F2F;">*</span></label>
                <input type="password" id="password" name="password" placeholder="Créez un mot de passe fort" required>
                <div class="password-requirements">
                    <strong>Le mot de passe doit contenir :</strong>
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
                <label>Confirmer le mot de passe <span style="color: #D32F2F;">*</span></label>
                <input type="password" name="confirm_password" placeholder="Répétez votre mot de passe" required>
            </div>

            <div class="captcha-box">
                <div class="captcha-label">Vérification Anti-Robot</div>
                <div class="emoji-math">
                    <div class="emoji-question"><?= $captcha['question'] ?></div>
                    <div class="emoji-legend">
                        <?php foreach ($captcha['legend'] as $emoji => $value): ?>
                            <div class="emoji-item"><?= $emoji ?> = <?= $value ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <input type="number" name="captcha" placeholder="Votre réponse" class="form-control captcha-input" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                S'inscrire
            </button>
        </form>

        <div class="divider">OU</div>

        <div class="links">
            <p>Vous avez déjà un compte ? <a href="Login.php">Se connecter</a></p>
        </div>
    </div>

    <script src="/hearme_user/View/FrontOffice/assets/js/auth.js"></script>
</body>
</html>
