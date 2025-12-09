<?php
/**
 * Liste des profils (Admin) - HearMe
 * Emplacement : MON PROJET/View/BackOffice/ListerProfil.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/ProfilController.php';

secureSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$controller = new ProfilController();
$profils = $controller->listProfils();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Profils - HearMe Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .success-message {
            background-color: #E5F7E5;
            color: #2E7D32;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #B3E5B3;
        }

        .error-message {
            background-color: #FFE5E5;
            color: #D32F2F;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
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

        .badge-disponible {
            background-color: #E5F7E5;
            color: #2E7D32;
        }

        .badge-occupe {
            background-color: #FFF8E1;
            color: #F57C00;
        }

        .badge-indisponible {
            background-color: #FFE5E5;
            color: #D32F2F;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(91, 168, 200, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 100%);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #7A7A7A;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
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
                    <a href="ListerUsers.php" class="menu-item">
                        <span class="icon">👥</span>
                        <span class="text">Utilisateurs</span>
                    </a>
                    <a href="ListerProfil.php" class="menu-item active">
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
                    <h1>Gestion des Profils</h1>
                    <p>Liste de tous les profils utilisateurs</p>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="success-message">
                        <?php
                        switch ($_GET['success']) {
                            case 'deleted':
                                echo '✅ Profil supprimé avec succès.';
                                break;
                            case 'updated':
                                echo '✅ Profil mis à jour avec succès.';
                                break;
                            case 'created':
                                echo '✅ Profil créé avec succès.';
                                break;
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message">
                        ❌ Erreur : <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="table-container">
                    <?php if (count($profils) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Formation</th>
                                    <th>Disponibilité</th>
                                    <th>Localisation</th>
                                    <th>Date Création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($profils as $profil): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($profil['id_profil']); ?></td>
                                        <td><?php echo htmlspecialchars($profil['email']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($profil['formation'], 0, 30)) . (strlen($profil['formation']) > 30 ? '...' : ''); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo htmlspecialchars($profil['disponibilite']); ?>">
                                                <?php echo htmlspecialchars($profil['disponibilite']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($profil['localisation']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($profil['created_at'])); ?></td>
                                        <td>
                                            <div class="actions">
                                                <a href="ModifierProfil.php?id=<?php echo $profil['id_profil']; ?>" class="btn-edit">
                                                    Modifier
                                                </a>
                                                <a href="DeleteProfil.php?id=<?php echo $profil['id_profil']; ?>" 
                                                   class="btn-delete" 
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce profil ?');">
                                                    Supprimer
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <i>🔭</i>
                            <p>Aucun profil trouvé</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>