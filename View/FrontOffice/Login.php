<?php
/**
 * Page de connexion - HearMe
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('../BackOffice/dashboard.html');
    } else {
        redirect('Home.php');
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();
    $result = $controller->login();
    
    if (isset($result) && !$result['success']) {
        $error = $result['message'];
    }
}

if (isset($_GET['success'])) {
    if ($_GET['success'] === 'registered') {
        $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    } elseif ($_GET['success'] === 'logout') {
        $success = "Déconnexion réussie.";
    }
}

require_once __DIR__ . '/../../oauth_config.php';
$googleAuthUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    'client_id' => GOOGLE_OAUTH_CLIENT_ID,
    'redirect_uri' => GOOGLE_OAUTH_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'email profile',
    'access_type' => 'online',
    'prompt' => 'select_account',
]);

require_once __DIR__ . '/../../Model/CaptchaService.php';
$captcha = CaptchaService::generateEmojiMath();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - HearMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/auth.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
            </div>
            <h1>HearMe</h1>
            <p>Connectez-vous à votre espace</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="votreemail@exemple.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="Votre mot de passe">
            </div>

            <div class="form-group">
                <div class="captcha-box">
                    <div class="captcha-label">Vérification Anti-Robot</div>
                    <div class="emoji-math">
                        <div class="emoji-question"><?= htmlspecialchars($captcha['question'] ?? '') ?></div>
                        <div class="emoji-legend">
                            <?php if (isset($captcha['legend']) && is_array($captcha['legend'])): ?>
                                <?php foreach ($captcha['legend'] as $emoji => $value): ?>
                                    <div class="emoji-item"><?= htmlspecialchars($emoji) ?> = <?= htmlspecialchars($value) ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="hidden" name="captcha_answer" value="<?= htmlspecialchars($captcha['answer'] ?? '') ?>">
                    <input type="number" name="captcha" placeholder="Votre réponse" class="form-control captcha-input" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Se connecter
            </button>
        </form>

        <div class="divider">OU</div>

        <a href="<?= $googleAuthUrl ?>" class="btn btn-google">
            <svg class="icon-google" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continuer avec Google
        </a>

        <a href="/hearme_user/View/FrontOffice/face-login.php" class="btn btn-face">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Se connecter par Face ID
        </a>

        <div class="links">
            <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
            <p style="margin-top: 10px;"><a href="forgot-password.php">Mot de passe oublié ?</a></p>
        </div>
    </div>
</body>
</html>
