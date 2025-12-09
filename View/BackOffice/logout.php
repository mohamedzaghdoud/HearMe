<?php
/**
 * Page de déconnexion - HearMe
 * Emplacement : MON_PROJET/View/BackOffice/logout.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

// Créer une instance du contrôleur
$controller = new UserController();

// Appeler la méthode de déconnexion
$controller->logout();

// La redirection se fait automatiquement dans la méthode logout()
?>