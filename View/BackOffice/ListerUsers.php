<?php
/**
 * Liste des utilisateurs avec TRI ALPHABÉTIQUE et RECHERCHE - HearMe
 * Emplacement : MON PROJET/View/BackOffice/ListerUsers.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';

secureSession();

if (!isLoggedIn() || !isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$userModel = new User();
$db = $userModel->getDb();

// Paramètres de tri et recherche
$sortBy = $_GET['sort'] ?? 'email';
$sortOrder = $_GET['order'] ?? 'ASC';
$search = $_GET['search'] ?? '';
$filterRole = $_GET['role'] ?? '';
$filterVerified = $_GET['verified'] ?? '';

// Construire la requête SQL
$sql = "SELECT id_user, email, role, email_verified, created_at FROM users WHERE 1=1";
$params = [];

// Recherche
if (!empty($search)) {
    $sql .= " AND email LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

// Filtre par rôle
if ($filterRole !== '') {
    $sql .= " AND role = :role";
    $params[':role'] = $filterRole;
}

// Filtre par statut de vérification
if ($filterVerified !== '') {
    $sql .= " AND email_verified = :verified";
    $params[':verified'] = $filterVerified === '1' ? 1 : 0;
}

// Tri
$allowedSorts = ['email', 'role', 'created_at', 'email_verified'];
if (!in_array($sortBy, $allowedSorts)) {
    $sortBy = 'email';
}

$sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';
$sql .= " ORDER BY $sortBy $sortOrder";

try {
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Erreur ListerUsers: " . $e->getMessage());
    $users = [];
}

// Toggle l'ordre
$newOrder = $sortOrder === 'ASC' ? 'DESC' : 'ASC';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs - HearMe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: #5BA8C8;
        }

        .navbar-menu a {
            color: #5BA8C8;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 600;
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #2c3e50;
            font-size: 32px;
        }

        .btn-add {
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 168, 200, 0.3);
        }

        /* FILTRES ET RECHERCHE */
        .filters {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .filters-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            color: #5BA8C8;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-group input,
        .filter-group select {
            padding: 12px;
            border: 2px solid #E0E0E0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #5BA8C8;
            box-shadow: 0 0 0 3px rgba(91, 168, 200, 0.1);
        }

        .btn-filter {
            padding: 12px 24px;
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 168, 200, 0.3);
        }

        .btn-reset {
            padding: 12px 24px;
            background: #f8f9fa;
            color: #5BA8C8;
            border: 2px solid #5BA8C8;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        /* TABLE */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-header h2 {
            color: #2c3e50;
            font-size: 22px;
        }

        .result-count {
            color: #7f8c8d;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8f9fa;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: #5BA8C8;
            border-bottom: 2px solid #e9ecef;
            position: relative;
        }

        th a {
            color: #5BA8C8;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        th a:hover {
            color: #4A90A8;
        }

        .sort-icon {
            font-size: 12px;
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e9ecef;
            color: #2c3e50;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-admin {
            background: #cce5ff;
            color: #004085;
        }

        .badge-user {
            background: #d1ecf1;
            color: #0c5460;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #fff3cd;
            color: #856404;
        }

        .btn-edit:hover {
            background: #ffc107;
            color: white;
        }

        .btn-delete {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">🎧 HearMe Admin</div>
        <div class="navbar-menu">
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="ListerUsers.php">👥 Utilisateurs</a>
            <a href="dev-tools.php">🛠️ Dev Tools</a>
            <a href="logout.php">🚪 Déconnexion</a>
        </div>
    </nav>

    <div class="container">
        <!-- En-tête -->
        <div class="page-header">
            <h1>👥 Gestion des utilisateurs</h1>
            <a href="AjoutUser.php" class="btn-add">➕ Ajouter un utilisateur</a>
        </div>

        <!-- Filtres et recherche -->
        <div class="filters">
            <form method="GET" action="">
                <div class="filters-row">
                    <div class="filter-group">
                        <label for="search">🔍 Rechercher par email</label>
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            placeholder="Tapez un email..."
                            value="<?php echo htmlspecialchars($search); ?>"
                        >
                    </div>

                    <div class="filter-group">
                        <label for="role">Rôle</label>
                        <select id="role" name="role">
                            <option value="">Tous</option>
                            <option value="user" <?php echo $filterRole === 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo $filterRole === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="verified">Statut email</label>
                        <select id="verified" name="verified">
                            <option value="">Tous</option>
                            <option value="1" <?php echo $filterVerified === '1' ? 'selected' : ''; ?>>Vérifié</option>
                            <option value="0" <?php echo $filterVerified === '0' ? 'selected' : ''; ?>>Non vérifié</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <button type="submit" class="btn-filter">Filtrer</button>
                    </div>
                </div>
            </form>
            
            <?php if ($search || $filterRole || $filterVerified !== ''): ?>
                <div style="margin-top: 15px;">
                    <a href="ListerUsers.php" class="btn-reset">🔄 Réinitialiser les filtres</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Table des utilisateurs -->
        <div class="table-container">
            <div class="table-header">
                <h2>Liste des utilisateurs</h2>
                <span class="result-count"><?php echo count($users); ?> résultat(s)</span>
            </div>

            <?php if (count($users) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>
                                <a href="?sort=email&order=<?php echo $newOrder; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $filterRole; ?>&verified=<?php echo $filterVerified; ?>">
                                    Email 
                                    <?php if ($sortBy === 'email'): ?>
                                        <span class="sort-icon"><?php echo $sortOrder === 'ASC' ? '▲' : '▼'; ?></span>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=role&order=<?php echo $newOrder; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $filterRole; ?>&verified=<?php echo $filterVerified; ?>">
                                    Rôle
                                    <?php if ($sortBy === 'role'): ?>
                                        <span class="sort-icon"><?php echo $sortOrder === 'ASC' ? '▲' : '▼'; ?></span>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=email_verified&order=<?php echo $newOrder; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $filterRole; ?>&verified=<?php echo $filterVerified; ?>">
                                    Statut
                                    <?php if ($sortBy === 'email_verified'): ?>
                                        <span class="sort-icon"><?php echo $sortOrder === 'ASC' ? '▲' : '▼'; ?></span>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=created_at&order=<?php echo $newOrder; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $filterRole; ?>&verified=<?php echo $filterVerified; ?>">
                                    Inscription
                                    <?php if ($sortBy === 'created_at'): ?>
                                        <span class="sort-icon"><?php echo $sortOrder === 'ASC' ? '▲' : '▼'; ?></span>
                                    <?php endif; ?>
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id_user']; ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge <?php echo $user['role'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $user['email_verified'] ? 'badge-success' : 'badge-warning'; ?>">
                                        <?php echo $user['email_verified'] ? '✓ Vérifié' : '⏳ En attente'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="ModifierUser.php?id=<?php echo $user['id_user']; ?>" class="btn-action btn-edit">
                                            ✏️ Modifier
                                        </a>
                                        <a href="DeleteUser.php?id=<?php echo $user['id_user']; ?>" 
                                           class="btn-action btn-delete"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                            🗑️ Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🔍</div>
                    <h3>Aucun utilisateur trouvé</h3>
                    <p>Essayez de modifier vos critères de recherche</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>