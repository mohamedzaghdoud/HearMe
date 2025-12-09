<?php
/**
 * Page de suppression d'utilisateur - HearMe
 * Emplacement : MON PROJET/View/BackOffice/DeleteUser.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

// Vérifier si un ID est fourni
if (!isset($_GET['id'])) {
    redirect('ListerUsers.php?error=' . urlencode('Aucun utilisateur spécifié.'));
}

$id = (int)$_GET['id'];

// Instanciation du controller
$controller = new UserController();

// Suppression de l'utilisateur
$result = $controller->deleteUser($id);

// Redirection avec message
if ($result['success']) {
    redirect('ListerUsers.php?success=deleted');
} else {
    redirect('ListerUsers.php?error=' . urlencode($result['message']));
}
?>