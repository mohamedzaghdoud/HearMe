<?php
/**
 * Outils de développement - Tokens et Emails (DÉVELOPPEMENT UNIQUEMENT)
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
try {
    $stmtReset = $db->query($sqlReset);
    $resetTokens = $stmtReset->fetchAll();
} catch (PDOException $e) {
    $resetTokens = [];
}

// Lire les derniers emails du log
$logFile = __DIR__ . '/../../logs/emails_' . date('Y-m-d') . '.log';
$emailLogs = file_exists($logFile) ? file_get_contents($logFile) : 'Aucun email envoyé aujourd\'hui';

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/hearme_user';

$pageTitle = "Dev Tools - HearMe Admin";
$additionalCss = '<link rel="stylesheet" href="/hearme_user/View/BackOffice/assets/css/dev-tools.css">';
include __DIR__ . '/layout/header.php';
?>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>🛠️ Outils de Développement</h1>
        <p>Tokens et logs pour le développement</p>
    </div>
    <button class="btn-gradient" onclick="location.reload()">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
        Actualiser
    </button>
</div>

<!-- Warning Banner -->
<div class="dev-warning mb-4">
    ⚠️ PAGE ACCESSIBLE UNIQUEMENT EN DÉVELOPPEMENT LOCAL - ADMINS SEULEMENT ⚠️
</div>

<!-- LOG DES EMAILS -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0" style="color: #ffffff;">📧 Log des emails du jour</h5>
    </div>
    <div class="card-body">
        <div class="log-viewer"><?= htmlspecialchars($emailLogs) ?></div>
    </div>
</div>

<!-- TOKENS DE VÉRIFICATION EMAIL -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="color: #ffffff;">🔐 Tokens de vérification email</h5>
        <span class="badge-dark badge-info"><?= count($verificationTokens) ?> token(s)</span>
    </div>
    <div class="card-body">
        <?php if (count($verificationTokens) > 0): ?>
            <div class="row">
                <?php foreach ($verificationTokens as $item): ?>
                    <?php 
                    $isExpired = strtotime($item['verification_expires']) < time();
                    $isVerified = $item['email_verified'];
                    ?>
                    <div class="col-lg-6 mb-3">
                        <div class="token-card">
                            <div class="token-email"><?= htmlspecialchars($item['email']) ?></div>
                            
                            <?php if ($isVerified): ?>
                                <span class="badge-dark badge-success">✅ Vérifié</span>
                            <?php elseif ($isExpired): ?>
                                <span class="badge-dark badge-danger">⏰ Expiré</span>
                            <?php else: ?>
                                <span class="badge-dark badge-success">✓ Actif</span>
                            <?php endif; ?>
                            
                            <div class="token-value"><?= htmlspecialchars($item['verification_token']) ?></div>
                            
                            <?php if (!$isVerified && !$isExpired): ?>
                                <div class="token-link">
                                    <a href="<?= $baseUrl ?>/View/FrontOffice/verify-email.php?token=<?= $item['verification_token'] ?>" target="_blank">
                                        <?= $baseUrl ?>/View/FrontOffice/verify-email.php?token=<?= $item['verification_token'] ?>
                                    </a>
                                </div>
                                <button class="btn-dark btn-sm mt-2" onclick="copyToClipboard('<?= $baseUrl ?>/View/FrontOffice/verify-email.php?token=<?= $item['verification_token'] ?>')">
                                    📋 Copier le lien
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <p class="text-muted">Aucun token en attente</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- TOKENS DE RESET PASSWORD -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="color: #ffffff;">🔑 Tokens de réinitialisation mot de passe</h5>
        <span class="badge-dark badge-info"><?= count($resetTokens) ?> token(s)</span>
    </div>
    <div class="card-body">
        <?php if (count($resetTokens) > 0): ?>
            <div class="row">
                <?php foreach ($resetTokens as $item): ?>
                    <?php 
                    $isExpired = strtotime($item['expires_at']) < time();
                    $isUsed = $item['used'];
                    ?>
                    <div class="col-lg-6 mb-3">
                        <div class="token-card">
                            <div class="token-email"><?= htmlspecialchars($item['email']) ?></div>
                            
                            <?php if ($isUsed): ?>
                                <span class="badge-dark badge-purple">✓ Utilisé</span>
                            <?php elseif ($isExpired): ?>
                                <span class="badge-dark badge-danger">⏰ Expiré</span>
                            <?php else: ?>
                                <span class="badge-dark badge-success">✓ Actif</span>
                            <?php endif; ?>
                            
                            <div class="token-value"><?= htmlspecialchars($item['reset_token']) ?></div>
                            
                            <?php if (!$isUsed && !$isExpired): ?>
                                <div class="token-link">
                                    <a href="<?= $baseUrl ?>/View/FrontOffice/reset-password.php?token=<?= $item['reset_token'] ?>" target="_blank">
                                        <?= $baseUrl ?>/View/FrontOffice/reset-password.php?token=<?= $item['reset_token'] ?>
                                    </a>
                                </div>
                                <button class="btn-dark btn-sm mt-2" onclick="copyToClipboard('<?= $baseUrl ?>/View/FrontOffice/reset-password.php?token=<?= $item['reset_token'] ?>')">
                                    📋 Copier le lien
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <p class="text-muted">Aucun token en attente</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('✅ Lien copié !');
    });
}
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
