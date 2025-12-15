<?php
/**
 * Page de réinitialisation de mot de passe - HearMe
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
            
            $sql = "SELECT id_user, email FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $sql = "DELETE FROM password_resets WHERE user_id = :user_id";
                $stmt = $db->prepare($sql);
                $stmt->execute([':user_id' => $user['id_user']]);
                
                $sql = "INSERT INTO password_resets (user_id, reset_token, expires_at) VALUES (:user_id, :token, :expires_at)";
                $stmt = $db->prepare($sql);
                $stmt->execute([':user_id' => $user['id_user'], ':token' => $token, ':expires_at' => $expiresAt]);
                
                $emailService = new EmailService();
                $emailService->sendPasswordResetEmail($email, $token);
                
                $success = true;
                $message = "Un email de réinitialisation a été envoyé à votre adresse.";
            } else {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/auth.css">
</head>
<body>
    <div class="forgot-container">
        <div class="logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
            </svg>
        </div>

        <h1>Mot de passe oublié ?</h1>
        <p class="description">Entrez votre email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

        <?php if ($message): ?>
            <div class="alert alert-<?= $success ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Votre email</label>
                    <input type="email" name="email" placeholder="votreemail@exemple.com" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Envoyer le lien
                </button>
            </form>
        <?php else: ?>
            <a href="Login.php" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Retour à la connexion
            </a>
        <?php endif; ?>

        <div class="links">
            <a href="Login.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Retour à la connexion
            </a>
        </div>
    </div>
</body>
</html>
