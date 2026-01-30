<?php
/**
 * Page d'ajout d'utilisateur - HearMe (Dark Theme)
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();
    $result = $controller->createUser();
    
    if ($result['success']) {
        redirect('ListerUsers.php?success=created');
    } else {
        $error = $result['message'];
    }
}

$pageTitle = "Ajouter un utilisateur - HearMe Admin";
include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
    <h1>Ajouter un utilisateur</h1>
    <p>Créer un nouveau compte utilisateur</p>
</div>

<div class="form-dark">
    <?php if ($error): ?>
        <div class="alert-danger-dark"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" id="createUserForm">
        <div class="mb-4">
            <label class="form-label">Email <span class="required-indicator">*</span></label>
            <input type="email" id="email" name="email" class="form-control" placeholder="utilisateur@exemple.com"
                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>

        <div class="mb-4">
            <label class="form-label">Mot de passe <span class="required-indicator">*</span></label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe sécurisé">
            <div class="password-requirements">
                <strong>Exigences du mot de passe :</strong>
                <ul>
                    <li>Au moins 8 caractères</li>
                    <li>Au moins une majuscule (A-Z)</li>
                    <li>Au moins une minuscule (a-z)</li>
                    <li>Au moins un chiffre (0-9)</li>
                    <li>Au moins un caractère spécial (!@#$%...)</li>
                </ul>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Rôle <span class="required-indicator">*</span></label>
            <select id="role" name="role" class="form-select">
                <option value="user" <?= (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn-gradient">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                Créer l'utilisateur
            </button>
            <a href="/hearme_user/View/BackOffice/ListerUsers.php" class="btn-dark">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
