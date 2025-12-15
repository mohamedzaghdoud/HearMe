<?php
/**
 * Page de gestion de profil utilisateur - HearMe
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/ProfilController.php';

secureSession();

if (!isLoggedIn()) {
    redirect('Login.php');
}

$controller = new ProfilController();
$error = '';
$success = '';

$profil = $controller->getMyProfil();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'createOrUpdateMyProfil') {
    $result = $controller->createOrUpdateMyProfil();
    
    if ($result['success']) {
        $success = $result['message'];
        $profil = $controller->getMyProfil();
    } else {
        $error = $result['message'];
    }
}

$pageTitle = "Mon Profil - HearMe";
include __DIR__ . '/layout/header.php';
?>
<link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/profil.css">
<br><br><br><br><br>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <div class="page-icon mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h1 style="color: var(--hearme-primary);">Mon Profil</h1>
                    <p class="text-muted">Créez ou modifiez votre profil</p>
                </div>

                <div class="card shadow-lg border-0" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>

                        <form method="POST" action="" id="profilForm">
                            <input type="hidden" name="action" value="createOrUpdateMyProfil">

                            <div class="mb-3">
                                <label for="bio" class="form-label fw-bold">Biographie</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Parlez-nous de vous..."><?= $profil ? htmlspecialchars($profil['bio']) : '' ?></textarea>
                                <small class="text-muted">Maximum 1000 caractères</small>
                            </div>

                            <div class="mb-3">
                                <label for="competences" class="form-label fw-bold">Compétences</label>
                                <textarea class="form-control" id="competences" name="competences" rows="2" placeholder="Ex: Écoute active, Empathie..."><?= $profil ? htmlspecialchars($profil['competences']) : '' ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="formation" class="form-label fw-bold">Formation</label>
                                <input type="text" class="form-control" id="formation" name="formation" placeholder="Ex: Master en Psychologie" value="<?= $profil ? htmlspecialchars($profil['formation']) : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="experience" class="form-label fw-bold">Expérience Professionnelle</label>
                                <textarea class="form-control" id="experience" name="experience" rows="3" placeholder="Décrivez votre expérience..."><?= $profil ? htmlspecialchars($profil['experience']) : '' ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="reseaux_sociaux" class="form-label fw-bold">Réseaux Sociaux</label>
                                <input type="url" class="form-control" id="reseaux_sociaux" name="reseaux_sociaux" placeholder="https://linkedin.com/in/votreprofil" value="<?= $profil ? htmlspecialchars($profil['reseaux_sociaux']) : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="disponibilite" class="form-label fw-bold">Disponibilité</label>
                                <select class="form-select" id="disponibilite" name="disponibilite">
                                    <option value="disponible" <?= ($profil && $profil['disponibilite'] === 'disponible') ? 'selected' : '' ?>>Disponible</option>
                                    <option value="occupé" <?= ($profil && $profil['disponibilite'] === 'occupé') ? 'selected' : '' ?>>Occupé</option>
                                    <option value="indisponible" <?= ($profil && $profil['disponibilite'] === 'indisponible') ? 'selected' : '' ?>>Indisponible</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="localisation" class="form-label fw-bold">Localisation</label>
                                <input type="text" class="form-control" id="localisation" name="localisation" placeholder="Ex: Paris, France" value="<?= $profil ? htmlspecialchars($profil['localisation']) : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="preferences" class="form-label fw-bold">Préférences</label>
                                <textarea class="form-control" id="preferences" name="preferences" rows="2" placeholder="Vos préférences..."><?= $profil ? htmlspecialchars($profil['preferences']) : '' ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="autre_theme" class="form-label fw-bold">Informations Complémentaires</label>
                                <textarea class="form-control" id="autre_theme" name="autre_theme" rows="2" placeholder="Autres informations..."><?= $profil ? htmlspecialchars($profil['autre_theme']) : '' ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100" style="background: linear-gradient(135deg, var(--hearme-primary) 0%, var(--hearme-secondary) 100%); border: none;">
                                <?= $profil ? 'Mettre à jour mon profil' : 'Créer mon profil' ?>
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="/hearme_user/View/FrontOffice/Home.php" class="btn btn-outline-secondary">← Retour à l'accueil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/hearme_user/View/FrontOffice/assets/js/profil.js"></script>
<?php include __DIR__ . '/layout/footer.php'; ?>
