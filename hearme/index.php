<?php
/**
 * Page d'accueil principale - HearMe
 * Emplacement : MON PROJET/index.php (à la racine du projet)
 * 
 * Ce fichier redirige automatiquement vers la page appropriée
 * selon l'état de connexion de l'utilisateur
 */

require_once __DIR__ . '/config.php';

secureSession();

// Vérifier si l'utilisateur est connecté
if (isLoggedIn()) {
    // Si admin -> Dashboard BackOffice
    if (isAdmin()) {
        redirect('View/BackOffice/dashboard.html');
    } 
    // Si utilisateur normal -> Page d'accueil FrontOffice
    else {
        redirect('View/FrontOffice/Home.html');
    }
} 
// Si non connecté -> Page de connexion
else {
    redirect('View/FrontOffice/Login.php');
}
?>