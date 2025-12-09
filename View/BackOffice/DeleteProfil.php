<?php
/**
 * Suppression de profil (Admin) - HearMe
 * Emplacement : MON PROJET/View/BackOffice/DeleteProfil.php
 */

// Aucune sortie avant cette ligne
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/ProfilController.php';

secureSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    header("Location: ../FrontOffice/Login.php");
    exit();
}

// Vérifier si l'ID est présent
if (!isset($_GET['id'])) {
    header("Location: ListerProfil.php?error=" . urlencode("ID manquant"));
    exit();
}

$controller = new ProfilController();
$id = intval($_GET['id']);

// Suppression
$result = $controller->deleteProfil($id);

if ($result['success']) {
    header("Location: ListerProfil.php?success=deleted");
    exit();
} else {
    header("Location: ListerProfil.php?error=" . urlencode($result['message']));
    exit();
}
?>