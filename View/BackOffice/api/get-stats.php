<?php
/**
 * API pour récupérer les statistiques du dashboard
 * Emplacement : MON PROJET/View/BackOffice/api/get-stats.php
 */

require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../Model/User.php';

secureSession();

// Vérifier l'authentification admin
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}

$userModel = new User();
$db = $userModel->getDb();

try {
    // 1. STATISTIQUES GÉNÉRALES
    $stats = [];
    
    // Total utilisateurs
    $stmt = $db->query("SELECT COUNT(*) as total FROM users");
    $stats['totalUsers'] = $stmt->fetch()['total'];
    
    // Total admins
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
    $stats['totalAdmins'] = $stmt->fetch()['total'];
    
    // Total users (non-admin)
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    $stats['totalRegularUsers'] = $stmt->fetch()['total'];
    
    // Emails vérifiés
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE email_verified = TRUE");
    $stats['verifiedEmails'] = $stmt->fetch()['total'];
    
    // Emails non vérifiés
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE email_verified = FALSE");
    $stats['unverifiedEmails'] = $stmt->fetch()['total'];
    
    // Taux de vérification
    $stats['verificationRate'] = $stats['totalUsers'] > 0 
        ? round(($stats['verifiedEmails'] / $stats['totalUsers']) * 100, 1) 
        : 0;
    
    // Total profils
    $stmt = $db->query("SELECT COUNT(*) as total FROM profil");
    $stats['totalProfils'] = $stmt->fetch()['total'];
    
    // Profils disponibles
    $stmt = $db->query("SELECT COUNT(*) as total FROM profil WHERE disponibilite = 'disponible'");
    $stats['disponibles'] = $stmt->fetch()['total'];
    
    
    // 2. INSCRIPTIONS PAR MOIS (12 derniers mois)
    $inscriptionsParMois = [];
    $stmt = $db->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as mois,
            COUNT(*) as total
        FROM users
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY mois ASC
    ");
    
    while ($row = $stmt->fetch()) {
        $inscriptionsParMois[] = [
            'mois' => $row['mois'],
            'total' => (int)$row['total']
        ];
    }
    
    
    // 3. INSCRIPTIONS PAR SEMAINE (4 dernières semaines)
    $inscriptionsParSemaine = [];
    $stmt = $db->query("
        SELECT 
            WEEK(created_at) as semaine,
            COUNT(*) as total
        FROM users
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 4 WEEK)
        GROUP BY WEEK(created_at)
        ORDER BY semaine ASC
    ");
    
    while ($row = $stmt->fetch()) {
        $inscriptionsParSemaine[] = [
            'semaine' => 'S' . $row['semaine'],
            'total' => (int)$row['total']
        ];
    }
    
    
    // 4. DERNIERS UTILISATEURS INSCRITS (5 derniers)
    $derniersInscrits = [];
    $stmt = $db->query("
        SELECT email, created_at, email_verified, role
        FROM users
        ORDER BY created_at DESC
        LIMIT 5
    ");
    
    while ($row = $stmt->fetch()) {
        $derniersInscrits[] = [
            'email' => $row['email'],
            'date' => date('d/m/Y H:i', strtotime($row['created_at'])),
            'verified' => (bool)$row['email_verified'],
            'role' => $row['role']
        ];
    }
    
    
    // 5. ACTIVITÉ RÉCENTE (basée sur updated_at)
    $activiteRecente = [];
    $stmt = $db->query("
        SELECT email, updated_at
        FROM users
        WHERE updated_at > created_at
        ORDER BY updated_at DESC
        LIMIT 5
    ");
    
    while ($row = $stmt->fetch()) {
        $activiteRecente[] = [
            'email' => $row['email'],
            'date' => date('d/m/Y H:i', strtotime($row['updated_at']))
        ];
    }
    
    
    // 6. RÉPARTITION PAR RÔLE
    $repartitionRoles = [
        'admins' => $stats['totalAdmins'],
        'users' => $stats['totalRegularUsers']
    ];
    
    
    // 7. STATISTIQUES DE VÉRIFICATION EMAIL
    $statsVerification = [
        'verified' => $stats['verifiedEmails'],
        'unverified' => $stats['unverifiedEmails']
    ];
    
    
    // RÉPONSE JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'inscriptionsParMois' => $inscriptionsParMois,
        'inscriptionsParSemaine' => $inscriptionsParSemaine,
        'derniersInscrits' => $derniersInscrits,
        'activiteRecente' => $activiteRecente,
        'repartitionRoles' => $repartitionRoles,
        'statsVerification' => $statsVerification
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors du chargement des statistiques',
        'message' => $e->getMessage()
    ]);
}
?>