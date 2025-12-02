<?php
/**
 * Suppression d'utilisateur (Admin) - HearMe
 * Emplacement : MON PROJET/View/BackOffice/DeleteUser.php
 */

// Aucune sortie avant cette ligne
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    header("Location: ../FrontOffice/Login.php");
    exit();
}

// Vérifier si l'ID est présent
if (!isset($_GET['id'])) {
    header("Location: ListerUsers.php?error=" . urlencode("ID manquant"));
    exit();
}

$controller = new UserController();
$id = intval($_GET['id']);

// Empêcher la suppression de son propre compte
if ($id == $_SESSION['user_id']) {
    header("Location: ListerUsers.php?error=" . urlencode("Vous ne pouvez pas supprimer votre propre compte"));
    exit();
}

// Suppression
$result = $controller->deleteUser($id);

if ($result['success']) {
    header("Location: ListerUsers.php?success=deleted");
    exit();
} else {
    header("Location: ListerUsers.php?error=" . urlencode($result['message']));
    exit();
}
?>