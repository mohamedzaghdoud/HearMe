<?php
/**
 * Liste des profils - Dark Theme - HearMe Admin
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/ProfilController.php';

secureSession();

if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$controller = new ProfilController();
$profils = $controller->listProfils();

$pageTitle = "Profils - HearMe Admin";
include __DIR__ . '/layout/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <h1>Profils</h1>
    <p>Gérer les profils utilisateurs</p>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success mb-4" style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--accent-green); color: var(--accent-green); padding: 15px; border-radius: 10px;">
    <?php
    switch ($_GET['success']) {
        case 'deleted': echo 'Profil supprimé avec succès.'; break;
        case 'updated': echo 'Profil mis à jour avec succès.'; break;
        case 'created': echo 'Profil créé avec succès.'; break;
    }
    ?>
</div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger mb-4" style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--accent-red); color: var(--accent-red); padding: 15px; border-radius: 10px;">
    Erreur : <?= htmlspecialchars($_GET['error']) ?>
</div>
<?php endif; ?>

<!-- Profils Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="color: #ffffff;">Liste des profils</h5>
        <span class="badge-dark badge-info"><?= count($profils) ?> profil(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (count($profils) > 0): ?>
        <table class="table-dark-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Formation</th>
                    <th>Disponibilité</th>
                    <th>Localisation</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profils as $profil): ?>
                <tr>
                    <td>#<?= htmlspecialchars($profil['id_profil']) ?></td>
                    <td><?= htmlspecialchars($profil['email']) ?></td>
                    <td><?= htmlspecialchars(substr($profil['formation'], 0, 25)) . (strlen($profil['formation']) > 25 ? '...' : '') ?></td>
                    <td>
                        <?php
                        $badgeClass = 'badge-info';
                        if ($profil['disponibilite'] === 'disponible') $badgeClass = 'badge-success';
                        elseif ($profil['disponibilite'] === 'occupé') $badgeClass = 'badge-warning';
                        elseif ($profil['disponibilite'] === 'indisponible') $badgeClass = 'badge-danger';
                        ?>
                        <span class="badge-dark <?= $badgeClass ?>">
                            <?= htmlspecialchars($profil['disponibilite']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($profil['localisation']) ?></td>
                    <td><?= date('d/m/Y', strtotime($profil['created_at'])) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="ModifierProfil.php?id=<?= $profil['id_profil'] ?>" class="btn-dark btn-sm text-decoration-none" style="padding: 5px 12px; font-size: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <a href="DeleteProfil.php?id=<?= $profil['id_profil'] ?>" onclick="return confirm('Supprimer ce profil ?')" class="btn-dark btn-sm text-decoration-none" style="padding: 5px 12px; font-size: 12px; border-color: var(--accent-red);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="text-center py-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <h5 class="mt-3 text-secondary">Aucun profil trouvé</h5>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
