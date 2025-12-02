<?php
/**
 * Page de déconnexion - HearMe
 * Emplacement : MON PROJET/View/BackOffice/logout.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

// Déconnexion
$controller = new UserController();
$controller->logout();
?>