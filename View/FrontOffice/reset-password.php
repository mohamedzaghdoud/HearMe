<?php
/**
 * Page de réinitialisation de mot de passe - HearMe
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';

secureSession();

$token = $_GET['token'] ?? '';
$message = '';
$success = false;
$tokenValid = false;
$email = '';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValid) {
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($newPassword) || empty($confirmPassword)) {
        $message = "Tous les champs sont requis.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $userModel = new User();
        $passwordValidation = $userModel->validatePassword($newPassword);
        
        if ($passwordValidation !== true) {
            $message = $passwordValidation;
        } else {
            try {
                $db = $userModel->getDb();
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $sql = "UPDATE users SET password = :password WHERE id_user = :user_id";
                $stmt = $db->prepare($sql);
                $stmt->execute([':password' => $hashedPassword, ':user_id' => $resetData['user_id']]);
                
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/auth.css">
</head>
<body>
    <div class="forgot-container">
        <?php if (!$tokenValid && !empty($token)): ?>
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            </div>
            <h1>Lien invalide</h1>
            <div class="alert alert-error"><?= htmlspecialchars($message) ?></div>
            <a href="forgot-password.php" class="btn btn-primary">Faire une nouvelle demande</a>
            <div class="links"><a href="Login.php">← Retour à la connexion</a></div>

        <?php elseif ($success): ?>
            <div class="logo-icon" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h1>Mot de passe réinitialisé</h1>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
            <a href="Login.php" class="btn btn-primary">Se connecter</a>

        <?php elseif ($tokenValid): ?>
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <h1>Nouveau mot de passe</h1>
            <p class="description">Créez un nouveau mot de passe pour : <strong><?= htmlspecialchars($email) ?></strong></p>

            <?php if ($message): ?>
                <div class="alert alert-error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" action="" id="resetForm">
                <div class="form-group" style="text-align: left;">
                    <label>Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Votre nouveau mot de passe" required>
                    <div class="password-requirements">
                        <strong>Le mot de passe doit contenir :</strong>
                        <ul>
                            <li>Au moins 8 caractères</li>
                            <li>Au moins une majuscule</li>
                            <li>Au moins une minuscule</li>
                            <li>Au moins un chiffre</li>
                            <li>Au moins un caractère spécial</li>
                        </ul>
                    </div>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
                </div>
                <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
            </form>
            <div class="links"><a href="Login.php">← Retour à la connexion</a></div>

        <?php else: ?>
            <div class="logo-icon" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <h1>Lien manquant</h1>
            <div class="alert alert-error">Lien de réinitialisation manquant.</div>
            <a href="forgot-password.php" class="btn btn-primary">Demander un nouveau lien</a>
            <div class="links"><a href="Login.php">← Retour à la connexion</a></div>
        <?php endif; ?>
    </div>

    <script src="/hearme_user/View/FrontOffice/assets/js/auth.js"></script>
</body>
</html>
