<?php
/**
 * Page de modification d'utilisateur - HearMe (Dark Theme)
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

if (!isset($_GET['id'])) {
    redirect('ListerUsers.php?error=' . urlencode('Aucun utilisateur spécifié.'));
}

$id = (int)$_GET['id'];
$controller = new UserController();
$user = $controller->getUser($id);

if (!$user) {
    redirect('ListerUsers.php?error=' . urlencode('Utilisateur introuvable.'));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->updateUser($id);
    
    if ($result['success']) {
        redirect('ListerUsers.php?success=updated');
    } else {
        $error = $result['message'];
    }
}

$pageTitle = "Modifier un utilisateur - HearMe Admin";
include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
    <h1>Modifier un utilisateur</h1>
    <p>Modifier les informations de l'utilisateur</p>
</div>

<div class="form-dark">
    <?php if ($error): ?>
        <div class="alert-danger-dark"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="info-box">
        <p><strong>ℹ️ Information :</strong> Laissez le champ mot de passe vide si vous ne souhaitez pas le modifier.</p>
    </div>

    <form method="POST" action="" id="updateUserForm">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="mb-4">
            <label class="form-label">Email <span class="required-indicator">*</span></label>
            <input type="email" id="email" name="email" class="form-control" placeholder="utilisateur@exemple.com"
                   value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <div class="mb-4">
            <label class="form-label">Nouveau mot de passe (optionnel)</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas modifier">
        </div>

        <div class="mb-4">
            <label class="form-label">Rôle <span class="required-indicator">*</span></label>
            <select id="role" name="role" class="form-select">
                <option value="user" <?= ($user['role'] === 'user') ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn-gradient">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Enregistrer les modifications
            </button>
            <a href="/hearme_user/View/BackOffice/ListerUsers.php" class="btn-dark">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
