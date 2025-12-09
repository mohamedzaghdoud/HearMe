<?php
/**
 * Dashboard Admin avec STATISTIQUES COMPLÈTES - HearMe
 * Emplacement : MON PROJET/View/BackOffice/dashboard.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/User.php';

secureSession();

if (!isLoggedIn() || !isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$userModel = new User();
$db = $userModel->getDb();

// STATISTIQUES
try {
    // Total utilisateurs
    $stmt = $db->query("SELECT COUNT(*) as total FROM users");
    $totalUsers = $stmt->fetch()['total'];

    // Utilisateurs vérifiés
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE email_verified = TRUE");
    $verifiedUsers = $stmt->fetch()['total'];

    // Utilisateurs non vérifiés
    $unverifiedUsers = $totalUsers - $verifiedUsers;

    // Nouveaux utilisateurs (7 derniers jours)
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $newUsers = $stmt->fetch()['total'];

    // Utilisateurs avec profil
    $stmt = $db->query("SELECT COUNT(DISTINCT id_user) as total FROM profil");
    $usersWithProfile = $stmt->fetch()['total'];

    // Admins
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
    $totalAdmins = $stmt->fetch()['total'];

    // Tokens de réinitialisation actifs
    $stmt = $db->query("SELECT COUNT(*) as total FROM password_resets WHERE used = FALSE AND expires_at > NOW()");
    $activeResetTokens = $stmt->fetch()['total'];

    // Inscriptions par jour (7 derniers jours)
    $stmt = $db->query("
        SELECT DATE(created_at) as date, COUNT(*) as count 
        FROM users 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date DESC
    ");
    $dailyRegistrations = $stmt->fetchAll();

    // Utilisateurs récents
    $stmt = $db->query("
        SELECT id_user, email, role, email_verified, created_at 
        FROM users 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $recentUsers = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Erreur stats: " . $e->getMessage());
    $totalUsers = $verifiedUsers = $unverifiedUsers = $newUsers = $usersWithProfile = $totalAdmins = $activeResetTokens = 0;
    $dailyRegistrations = [];
    $recentUsers = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - HearMe</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
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
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #7f8c8d;
            font-size: 16px;
        }

        /* CARTES STATISTIQUES */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-card.blue {
            border-color: #3498db;
        }

        .stat-card.green {
            border-color: #2ecc71;
        }

        .stat-card.orange {
            border-color: #e67e22;
        }

        .stat-card.purple {
            border-color: #9b59b6;
        }

        .stat-card.red {
            border-color: #e74c3c;
        }

        .stat-card.teal {
            border-color: #1abc9c;
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* GRAPHIQUE */
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .chart-container h2 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 20px;
        }

        canvas {
            max-height: 300px;
        }

        /* TABLE UTILISATEURS RÉCENTS */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table-container h2 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #5BA8C8;
            border-bottom: 2px solid #e9ecef;
        }

        td {
            padding: 12px;
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

        .quick-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-action-btn {
            flex: 1;
            padding: 15px;
            background: white;
            border: 2px solid #5BA8C8;
            border-radius: 12px;
            color: #5BA8C8;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            background: #5BA8C8;
            color: white;
            transform: translateY(-2px);
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
            <h1>📊 Tableau de bord</h1>
            <p>Vue d'ensemble de la plateforme HearMe</p>
        </div>

        <!-- Actions rapides -->
        <div class="quick-actions">
            <a href="ListerUsers.php" class="quick-action-btn">👥 Gérer les utilisateurs</a>
            <a href="dev-tools.php" class="quick-action-btn">🛠️ Outils de dev</a>
            <a href="AjoutUser.php" class="quick-action-btn">➕ Ajouter un utilisateur</a>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon">👥</div>
                <div class="stat-value"><?php echo $totalUsers; ?></div>
                <div class="stat-label">Total Utilisateurs</div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon">✅</div>
                <div class="stat-value"><?php echo $verifiedUsers; ?></div>
                <div class="stat-label">Emails Vérifiés</div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon">⏳</div>
                <div class="stat-value"><?php echo $unverifiedUsers; ?></div>
                <div class="stat-label">En Attente</div>
            </div>

            <div class="stat-card purple">
                <div class="stat-icon">🆕</div>
                <div class="stat-value"><?php echo $newUsers; ?></div>
                <div class="stat-label">Nouveaux (7j)</div>
            </div>

            <div class="stat-card teal">
                <div class="stat-icon">📝</div>
                <div class="stat-value"><?php echo $usersWithProfile; ?></div>
                <div class="stat-label">Avec Profil</div>
            </div>

            <div class="stat-card red">
                <div class="stat-icon">👑</div>
                <div class="stat-value"><?php echo $totalAdmins; ?></div>
                <div class="stat-label">Administrateurs</div>
            </div>
        </div>

        <!-- Graphique des inscriptions -->
        <div class="chart-container">
            <h2>📈 Inscriptions des 7 derniers jours</h2>
            <canvas id="registrationChart"></canvas>
        </div>

        <!-- Utilisateurs récents -->
        <div class="table-container">
            <h2>🕒 Utilisateurs récents</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentUsers as $user): ?>
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
                                    <?php echo $user['email_verified'] ? 'Vérifié' : 'En attente'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Données du graphique
        const labels = <?php echo json_encode(array_reverse(array_column($dailyRegistrations, 'date'))); ?>;
        const data = <?php echo json_encode(array_reverse(array_column($dailyRegistrations, 'count'))); ?>;

        // Configuration du graphique
        const ctx = document.getElementById('registrationChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Inscriptions',
                    data: data,
                    borderColor: '#5BA8C8',
                    backgroundColor: 'rgba(91, 168, 200, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#5BA8C8',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#2c3e50',
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>