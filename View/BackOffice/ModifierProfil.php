<?php
/**
 * Modification de profil (Admin) - HearMe
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/ProfilController.php';

secureSession();

if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$controller = new ProfilController();
$error = '';
$success = '';

if (!isset($_GET['id'])) {
    redirect('ListerProfil.php');
}

$id = $_GET['id'];
$profil = $controller->getProfil($id);

if (!$profil) {
    redirect('ListerProfil.php?error=Profil non trouvé');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['id'] = $id;
    $_POST['action'] = 'update';
    $result = $controller->updateProfil($id);
    
    if ($result['success']) {
        $success = $result['message'];
        $profil = $controller->getProfil($id);
    } else {
        $error = $result['message'];
    }
}

$pageTitle = "Modifier Profil - HearMe Admin";
include __DIR__ . '/layout/header.php';
?>

<div class="page-header">
    <h1>Modifier le Profil</h1>
    <p>Modification du profil de <?= htmlspecialchars($profil['email']) ?></p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-dark full-width">
            <?php if ($error): ?>
                <div class="alert-danger-dark"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert-success-dark"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Formation</label>
                        <input type="text" name="formation" class="form-control" value="<?= htmlspecialchars($profil['formation']) ?>">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Localisation</label>
                        <input type="text" name="localisation" class="form-control" value="<?= htmlspecialchars($profil['localisation']) ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Biographie</label>
                    <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($profil['bio']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Compétences</label>
                    <textarea name="competences" class="form-control" rows="2"><?= htmlspecialchars($profil['competences']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Expérience</label>
                    <textarea name="experience" class="form-control" rows="3"><?= htmlspecialchars($profil['experience']) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Réseaux Sociaux</label>
                        <input type="url" name="reseaux_sociaux" class="form-control" value="<?= htmlspecialchars($profil['reseaux_sociaux']) ?>">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Disponibilité</label>
                        <select name="disponibilite" class="form-select">
                            <option value="disponible" <?= $profil['disponibilite'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                            <option value="occupé" <?= $profil['disponibilite'] === 'occupé' ? 'selected' : '' ?>>Occupé</option>
                            <option value="indisponible" <?= $profil['disponibilite'] === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Préférences</label>
                    <textarea name="preferences" class="form-control" rows="2"><?= htmlspecialchars($profil['preferences']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Autre Thème</label>
                    <textarea name="autre_theme" class="form-control" rows="2"><?= htmlspecialchars($profil['autre_theme']) ?></textarea>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-gradient">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Mettre à jour
                    </button>
                    <a href="/hearme_user/View/BackOffice/ListerProfil.php" class="btn-dark">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
