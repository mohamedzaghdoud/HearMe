<?php
/**
 * Renvoyer l'email de vérification - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/resend-verification.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';

$email = $_GET['email'] ?? '';
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($email)) {
    $email = $_POST['email'] ?? $email;
    
    if ($email) {
        $userModel = new User();
        $db = $userModel->getDb();
        
        try {
            // Vérifier si l'email existe et n'est pas vérifié
            $sql = "SELECT id_user, email_verified FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $message = "Cet email n'est pas enregistré.";
            } elseif ($user['email_verified']) {
                $message = "Cet email est déjà vérifié !";
                $success = true;
            } else {
                // Renvoyer l'email
                $userModel->sendVerificationEmail($user['id_user'], $email);
                $message = "Email de vérification renvoyé ! Vérifiez votre boîte de réception.";
                $success = true;
            }
        } catch (PDOException $e) {
            error_log("Erreur resend: " . $e->getMessage());
            $message = "Erreur lors de l'envoi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renvoyer la vérification - HearMe</title>
    <link rel="stylesheet" href="assets/css/frontoffice.css">
</head>
<body>
    <div class="container" style="max-width: 500px; margin: 50px auto; background: white; padding: 40px; border-radius: 20px;">
        <h1 style="text-align: center; color: #5BA8C8;">📧 Renvoyer la vérification</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $success ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!$success): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Votre email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Renvoyer l'email</button>
            </form>
        <?php else: ?>
            <a href="Login.php" class="btn btn-primary" style="width: 100%; text-align: center; display: block; margin-top: 20px;">Retour à la connexion</a>
        <?php endif; ?>
    </div>
</body>
</html>