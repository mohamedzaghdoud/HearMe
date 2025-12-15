<?php
/**
 * Dashboard Admin - Dark Theme - HearMe
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
    $stmt = $db->query("SELECT COUNT(*) as total FROM users");
    $totalUsers = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE email_verified = TRUE");
    $verifiedUsers = $stmt->fetch()['total'];

    $unverifiedUsers = $totalUsers - $verifiedUsers;

    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $newUsers = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(DISTINCT id_user) as total FROM profil");
    $usersWithProfile = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
    $totalAdmins = $stmt->fetch()['total'];

    $stmt = $db->query("
        SELECT DATE(created_at) as date, COUNT(*) as count 
        FROM users 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date DESC
    ");
    $dailyRegistrations = $stmt->fetchAll();

    $stmt = $db->query("
        SELECT id_user, email, role, email_verified, created_at 
        FROM users 
        ORDER BY created_at DESC 
        LIMIT 10
    ");
    $recentUsers = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Erreur stats: " . $e->getMessage());
    $totalUsers = $verifiedUsers = $unverifiedUsers = $newUsers = $usersWithProfile = $totalAdmins = 0;
    $dailyRegistrations = [];
    $recentUsers = [];
}

$pageTitle = "Dashboard - HearMe Admin";
include __DIR__ . '/layout/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Vue d'ensemble de la plateforme HearMe</p>
</div>

<!-- Stats Grid -->
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label">Total Utilisateurs</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card green">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-value"><?= $verifiedUsers ?></div>
            <div class="stat-label">Emails Vérifiés</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card orange">
            <div class="stat-icon orange">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="stat-value"><?= $unverifiedUsers ?></div>
            <div class="stat-label">En Attente</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card cyan">
            <div class="stat-icon cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            </div>
            <div class="stat-value"><?= $newUsers ?></div>
            <div class="stat-label">Nouveaux (7j)</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card purple">
            <div class="stat-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="stat-value"><?= $usersWithProfile ?></div>
            <div class="stat-label">Avec Profil</div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card red">
            <div class="stat-icon red">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <div class="stat-value"><?= $totalAdmins ?></div>
            <div class="stat-label">Administrateurs</div>
        </div>
    </div>
</div>

<!-- Chart & Recent Users -->
<div class="row g-4">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #ffffff;">Inscriptions des 7 derniers jours</h5>
            </div>
            <div class="card-body">
                <canvas id="registrationChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color: #ffffff;">Actions rapides</h5>
            </div>
            <div class="card-body d-grid gap-3">
                <a href="ListerUsers.php" class="btn-gradient text-center text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                    Gérer les utilisateurs
                </a>
                <a href="AjoutUser.php" class="btn-dark text-center text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                    Ajouter un utilisateur
                </a>
                <a href="/hearme_user/Controller/QuizController.php?action=liste" class="btn-dark text-center text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    Gérer les Quiz
                </a>
                <a href="dev-tools.php" class="btn-dark text-center text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                    Dev Tools
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users Table -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #ffffff;">Utilisateurs récents</h5>
                <a href="ListerUsers.php" class="btn-gradient btn-sm">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <table class="table-dark-custom">
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
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Chart Configuration
const labels = <?= json_encode(array_reverse(array_column($dailyRegistrations, 'date'))) ?>;
const data = <?= json_encode(array_reverse(array_column($dailyRegistrations, 'count'))) ?>;

const ctx = document.getElementById('registrationChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Inscriptions',
            data: data,
            borderColor: '#6366f1',
            backgroundColor: gradient,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#6366f1',
            pointBorderColor: '#1a1a2e',
            pointBorderWidth: 3,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a1a2e',
                titleColor: '#fff',
                bodyColor: '#a1a1aa',
                borderColor: '#2d2d44',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#2d2d44' },
                ticks: { color: '#71717a' }
            },
            x: {
                grid: { color: '#2d2d44' },
                ticks: { color: '#71717a' }
            }
        }
    }
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>
