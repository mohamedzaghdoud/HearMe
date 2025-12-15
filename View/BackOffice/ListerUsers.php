<?php
/**
 * Liste des utilisateurs - Dark Theme - HearMe
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';

secureSession();

if (!isLoggedIn() || !isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$userModel = new User();
$db = $userModel->getDb();

$sortBy = $_GET['sort'] ?? 'email';
$sortOrder = $_GET['order'] ?? 'ASC';
$search = $_GET['search'] ?? '';
$filterRole = $_GET['role'] ?? '';
$filterVerified = $_GET['verified'] ?? '';

$sql = "SELECT id_user, email, role, email_verified, created_at FROM users WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND email LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

if ($filterRole !== '') {
    $sql .= " AND role = :role";
    $params[':role'] = $filterRole;
}

if ($filterVerified !== '') {
    $sql .= " AND email_verified = :verified";
    $params[':verified'] = $filterVerified === '1' ? 1 : 0;
}

$allowedSorts = ['email', 'role', 'created_at', 'email_verified'];
if (!in_array($sortBy, $allowedSorts)) $sortBy = 'email';
$sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';
$sql .= " ORDER BY $sortBy $sortOrder";

try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
}

$newOrder = $sortOrder === 'ASC' ? 'DESC' : 'ASC';
$pageTitle = "Utilisateurs - HearMe Admin";
include __DIR__ . '/layout/header.php';
?>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Utilisateurs</h1>
        <p>Gérer les comptes utilisateurs</p>
    </div>
    <a href="AjoutUser.php" class="btn-gradient">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
        Ajouter
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-secondary small">Rechercher</label>
                    <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Email..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-secondary small">Rôle</label>
                    <select name="role" class="form-select bg-dark text-white border-secondary">
                        <option value="">Tous</option>
                        <option value="user" <?= $filterRole === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $filterRole === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-secondary small">Statut</label>
                    <select name="verified" class="form-select bg-dark text-white border-secondary">
                        <option value="">Tous</option>
                        <option value="1" <?= $filterVerified === '1' ? 'selected' : '' ?>>Vérifié</option>
                        <option value="0" <?= $filterVerified === '0' ? 'selected' : '' ?>>Non vérifié</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-gradient w-100">Filtrer</button>
                </div>
                <div class="col-md-2">
                    <a href="ListerUsers.php" class="btn-dark w-100 d-block text-center text-decoration-none">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="color: #ffffff;">Liste des utilisateurs</h5>
        <span class="badge-dark badge-info"><?= count($users) ?> résultat(s)</span>
    </div>
    <div class="card-body p-0">
        <?php if (count($users) > 0): ?>
        <table class="table-dark-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>
                        <a href="?sort=email&order=<?= $newOrder ?>&search=<?= urlencode($search) ?>&role=<?= $filterRole ?>&verified=<?= $filterVerified ?>" class="text-decoration-none" style="color: var(--text-secondary);">
                            Email <?= $sortBy === 'email' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                        </a>
                    </th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>
                        <a href="?sort=created_at&order=<?= $newOrder ?>&search=<?= urlencode($search) ?>&role=<?= $filterRole ?>&verified=<?= $filterVerified ?>" class="text-decoration-none" style="color: var(--text-secondary);">
                            Inscription <?= $sortBy === 'created_at' ? ($sortOrder === 'ASC' ? '↑' : '↓') : '' ?>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>#<?= $user['id_user'] ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="badge-dark <?= $user['role'] === 'admin' ? 'badge-purple' : 'badge-info' ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge-dark <?= $user['email_verified'] ? 'badge-success' : 'badge-warning' ?>">
                            <?= $user['email_verified'] ? 'Vérifié' : 'En attente' ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="ModifierUser.php?id=<?= $user['id_user'] ?>" class="btn-dark btn-sm text-decoration-none" style="padding: 5px 12px; font-size: 12px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <a href="DeleteUser.php?id=<?= $user['id_user'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')" class="btn-dark btn-sm text-decoration-none" style="padding: 5px 12px; font-size: 12px; border-color: var(--accent-red);">
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
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <h5 class="mt-3 text-secondary">Aucun utilisateur trouvé</h5>
            <p class="text-muted">Modifiez vos critères de recherche</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
