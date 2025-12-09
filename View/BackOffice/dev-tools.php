<?php
/**
 * Outils de développement - Tokens et Emails (DÉVELOPPEMENT UNIQUEMENT)
 * Emplacement : MON PROJET/View/BackOffice/dev-tools.php
 * 
 * ⚠️ À SUPPRIMER EN PRODUCTION !
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';

// SÉCURITÉ : Désactiver en production
if (!in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1'])) {
    die('Cette page n\'est accessible qu\'en développement local.');
}

// SÉCURITÉ : Accessible uniquement aux admins
secureSession();
if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$userModel = new User();
$db = $userModel->getDb();

// Récupérer tous les tokens de vérification
$sqlVerif = "SELECT id_user, email, verification_token, verification_expires, email_verified 
             FROM users 
             WHERE verification_token IS NOT NULL 
             ORDER BY id_user DESC 
             LIMIT 20";
$stmtVerif = $db->query($sqlVerif);
$verificationTokens = $stmtVerif->fetchAll();

// Récupérer tous les tokens de reset password
$sqlReset = "SELECT pr.id, pr.reset_token, pr.expires_at, pr.used, u.email 
             FROM password_resets pr
             JOIN users u ON pr.user_id = u.id_user
             ORDER BY pr.created_at DESC 
             LIMIT 20";
$stmtReset = $db->query($sqlReset);
$resetTokens = $stmtReset->fetchAll();

// Lire les derniers emails du log
$logFile = __DIR__ . '/../../logs/emails_' . date('Y-m-d') . '.log';
$emailLogs = file_exists($logFile) ? file_get_contents($logFile) : 'Aucun email envoyé aujourd\'hui';

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛠️ Dev Tools - HearMe</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #1a1a1a; color: #00ff00; }
        .dev-container { max-width: 1400px; margin: 20px auto; padding: 20px; }
        .warning { background: #ff4444; color: white; padding: 15px; border-radius: 8px; text-align: center; margin-bottom: 30px; font-weight: bold; }
        .section { background: #2a2a2a; padding: 30px; border-radius: 15px; margin-bottom: 30px; border: 2px solid #00ff00; }
        h1 { color: #00ff00; text-align: center; font-size: 36px; margin-bottom: 10px; text-shadow: 0 0 10px #00ff00; }
        h2 { color: #00ccff; border-bottom: 2px solid #00ccff; padding-bottom: 10px; margin-bottom: 20px; }
        .token-card { background: #333; border: 2px solid #00ff00; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
        .token-card:hover { box-shadow: 0 5px 20px rgba(0, 255, 0, 0.5); }
        .email { color: #ffaa00; font-weight: bold; margin-bottom: 10px; }
        .token { background: #1a1a1a; padding: 10px; border-radius: 5px; word-break: break-all; margin: 10px 0; font-size: 12px; }
        .link { background: #004400; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .link a { color: #00ccff; text-decoration: none; word-break: break-all; }
        .copy-btn { background: #00ff00; color: #000; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .copy-btn:hover { background: #00cc00; }
        .status { display: inline-block; padding: 5px 12px; border-radius: 15px; font-size: 12px; font-weight: bold; margin: 5px 0; }
        .status.active { background: #00ff00; color: #000; }
        .status.expired { background: #ff4444; color: white; }
        .status.verified { background: #44ff44; color: #000; }
        .log-viewer { background: #1a1a1a; padding: 20px; border-radius: 10px; max-height: 400px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.6; white-space: pre-wrap; }
        .refresh-btn { position: fixed; top: 20px; right: 20px; background: #00ccff; color: #000; border: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 204, 255, 0.5); }
        .back-btn { background: #5BA8C8; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <button class="refresh-btn" onclick="location.reload()">🔄 Actualiser</button>

    <div class="dev-container">
        <a href="dashboard.html" class="back-btn">← Retour au Dashboard</a>
        
        <h1>🛠️ OUTILS DE DÉVELOPPEMENT</h1>
        
        <div class="warning">
            ⚠️ PAGE ACCESSIBLE UNIQUEMENT EN DÉVELOPPEMENT LOCAL - ADMINS SEULEMENT ⚠️
        </div>

        <!-- LOG DES EMAILS -->
        <div class="section">
            <h2>📧 Log des emails du jour</h2>
            <div class="log-viewer"><?php echo htmlspecialchars($emailLogs); ?></div>
        </div>

        <!-- TOKENS DE VÉRIFICATION EMAIL -->
        <div class="section">
            <h2>🔐 Tokens de vérification email</h2>
            
            <?php if (count($verificationTokens) > 0): ?>
                <?php foreach ($verificationTokens as $item): ?>
                    <?php 
                    $isExpired = strtotime($item['verification_expires']) < time();
                    $isVerified = $item['email_verified'];
                    ?>
                    <div class="token-card">
                        <div class="email">👤 <?php echo htmlspecialchars($item['email']); ?></div>
                        
                        <?php if ($isVerified): ?>
                            <span class="status verified">✅ Email déjà vérifié</span>
                        <?php elseif ($isExpired): ?>
                            <span class="status expired">⏰ Expiré</span>
                        <?php else: ?>
                            <span class="status active">✓ Actif</span>
                        <?php endif; ?>
                        
                        <div class="token">
                            <strong>Token:</strong><br>
                            <?php echo htmlspecialchars($item['verification_token']); ?>
                        </div>
                        
                        <?php if (!$isVerified && !$isExpired): ?>
                            <div class="link">
                                <strong>🔗 Lien de vérification:</strong><br>
                                <a href="<?php echo $baseUrl; ?>/View/FrontOffice/verify-email.php?token=<?php echo $item['verification_token']; ?>" target="_blank">
                                    <?php echo $baseUrl; ?>/View/FrontOffice/verify-email.php?token=<?php echo $item['verification_token']; ?>
                                </a>
                            </div>
                            <button class="copy-btn" onclick="copyToClipboard('<?php echo $baseUrl; ?>/View/FrontOffice/verify-email.php?token=<?php echo $item['verification_token']; ?>')">
                                📋 Copier le lien
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #888;">Aucun token en attente</p>
            <?php endif; ?>
        </div>

        <!-- TOKENS DE RESET PASSWORD -->
        <div class="section">
            <h2>🔑 Tokens de réinitialisation mot de passe</h2>
            
            <?php if (count($resetTokens) > 0): ?>
                <?php foreach ($resetTokens as $item): ?>
                    <?php 
                    $isExpired = strtotime($item['expires_at']) < time();
                    $isUsed = $item['used'];
                    ?>
                    <div class="token-card">
                        <div class="email">👤 <?php echo htmlspecialchars($item['email']); ?></div>
                        
                        <?php if ($isUsed): ?>
                            <span class="status verified">✓ Utilisé</span>
                        <?php elseif ($isExpired): ?>
                            <span class="status expired">⏰ Expiré</span>
                        <?php else: ?>
                            <span class="status active">✓ Actif</span>
                        <?php endif; ?>
                        
                        <div class="token">
                            <strong>Token:</strong><br>
                            <?php echo htmlspecialchars($item['reset_token']); ?>
                        </div>
                        
                        <?php if (!$isUsed && !$isExpired): ?>
                            <div class="link">
                                <strong>🔗 Lien de réinitialisation:</strong><br>
                                <a href="<?php echo $baseUrl; ?>/View/FrontOffice/reset-password.php?token=<?php echo $item['reset_token']; ?>" target="_blank">
                                    <?php echo $baseUrl; ?>/View/FrontOffice/reset-password.php?token=<?php echo $item['reset_token']; ?>
                                </a>
                            </div>
                            <button class="copy-btn" onclick="copyToClipboard('<?php echo $baseUrl; ?>/View/FrontOffice/reset-password.php?token=<?php echo $item['reset_token']; ?>')">
                                📋 Copier le lien
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #888;">Aucun token en attente</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✅ Lien copié !');
            });
        }
        setTimeout(() => location.reload(), 30000); // Auto-refresh 30s
    </script>
</body>
</html>