<?php
/**
 * Liste des utilisateurs (Admin) - HearMe
 * Emplacement : MON PROJET/View/BackOffice/ListerUsers.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$controller = new UserController();
$users = $controller->listUsers();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - HearMe Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .page-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .search-box {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-box input {
            padding: 10px 15px;
            border: 2px solid #E0E0E0;
            border-radius: 10px;
            width: 300px;
            font-size: 14px;
        }

        .search-box input:focus {
            outline: none;
            border-color: #5BA8C8;
        }

        .btn-add {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7A7A7A;
        }

        .empty-state .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: #5BA8C8;
            margin-bottom: 10px;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #E5F7E5;
            color: #2E7D32;
            border: 1px solid #B3E5B3;
        }

        .alert-danger {
            background-color: #FFE5E5;
            color: #D32F2F;
            border: 1px solid #FFB3B3;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 15px;
            border-bottom: 1px solid #E0E0E0;
        }

        table tbody tr:hover {
            background-color: #F5F5F5;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-user {
            background-color: #E8F4F8;
            color: #5BA8C8;
        }

        .badge-admin {
            background-color: #FFF8E1;
            color: #F57C00;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(91, 168, 200, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3 sidebar">
                <div class="sidebar-header">
                    <h2>🎧 HearMe</h2>
                    <p>Administration</p>
                </div>
                <nav class="sidebar-menu">
                    <a href="dashboard.html" class="menu-item">
                        <span class="icon">📊</span>
                        <span class="text">Dashboard</span>
                    </a>
                    <a href="ListerUsers.php" class="menu-item active">
                        <span class="icon">👥</span>
                        <span class="text">Utilisateurs</span>
                    </a>
                    <a href="ListerProfil.php" class="menu-item">
                        <span class="icon">👤</span>
                        <span class="text">Profils</span>
                    </a>
                    <a href="logout.php" class="menu-item">
                        <span class="icon">🚪</span>
                        <span class="text">Déconnexion</span>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="col-md-9 main-content">
                <div class="page-header">
                    <h1>Gestion des Utilisateurs</h1>
                    <p>Liste de tous les utilisateurs de la plateforme</p>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <?php
                        switch ($_GET['success']) {
                            case 'created':
                                echo '✅ Utilisateur créé avec succès.';
                                break;
                            case 'updated':
                                echo '✅ Utilisateur mis à jour avec succès.';
                                break;
                            case 'deleted':
                                echo '✅ Utilisateur supprimé avec succès.';
                                break;
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        ❌ Erreur : <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="page-actions">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="🔍 Rechercher un utilisateur...">
                    </div>
                    <a href="AjoutUser.php" class="btn-add">➕ Ajouter un utilisateur</a>
                </div>

                <div class="table-container">
                    <?php if (count($users) > 0): ?>
                        <table id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id_user']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo htmlspecialchars($user['role']); ?>">
                                                <?php echo htmlspecialchars($user['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y à H:i', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div class="actions">
                                                <a href="ModifierUser.php?id=<?php echo $user['id_user']; ?>" class="btn btn-primary">
                                                    ✏️ Modifier
                                                </a>
                                                <a href="DeleteUser.php?id=<?php echo $user['id_user']; ?>" 
                                                   class="btn btn-danger"
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
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
                            <div class="icon">🔭</div>
                            <h3>Aucun utilisateur trouvé</h3>
                            <p>Commencez par ajouter votre premier utilisateur</p>
                            <a href="AjoutUser.php" class="btn btn-primary" style="margin-top: 20px;">
                                ➕ Ajouter un utilisateur
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Fonction de recherche en temps réel
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('usersTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const email = row.cells[1].textContent.toLowerCase();
                const role = row.cells[2].textContent.toLowerCase();

                if (email.includes(searchTerm) || role.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Animation d'apparition des lignes
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#usersTable tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.animation = `fadeIn 0.5s ease forwards ${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>