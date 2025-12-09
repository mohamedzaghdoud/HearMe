<?php
/**
 * Callback OAuth (Google/Facebook) - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/oauth-callback.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

// Récupérer le provider et le code
$provider = $_GET['provider'] ?? $_GET['state'] ?? 'google';
$code = $_GET['code'] ?? '';
$error = $_GET['error'] ?? '';

// Vérifier si erreur OAuth
if ($error) {
    redirect('Login.php?error=oauth_denied');
    exit;
}

// Vérifier si code présent
if (empty($code)) {
    redirect('Login.php?error=oauth_failed');
    exit;
}

// Traiter la connexion sociale
$controller = new UserController();
$result = $controller->socialLogin();

// Si échec, rediriger vers login
if (isset($result) && !$result['success']) {
    redirect('Login.php?error=' . urlencode($result['message']));
    exit; // ✅ CORRECTION OBLIGATOIRE
}
?>
