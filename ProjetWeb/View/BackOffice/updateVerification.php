<?php
require_once '../../Model/Activity.php';
require_once '../../Controller/ActivityController.php';

if ($_POST) {
    // Récupération des données du formulaire
    $activity_id = $_POST['activity_id'];
    $title = $_POST['title'];
    $activity_type = $_POST['activity_type'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $date_time = $_POST['date_time'];
    $max_participants = $_POST['max_participants'];
    $difficulty_level = $_POST['difficulty_level'];
    $status = $_POST['status'];
    
    // Création de l'objet Activity
    $activity = new Activity($title, $activity_type, $description, $location, $date_time, $max_participants, $difficulty_level, $status);
    
    // Modification dans la base
    $controller = new ActivityController();
    $controller->updateActivity($activity_id, $activity);
    
    // Redirection avec message de succès
    header('Location: listActivities.php?message=updated&id=' . $activity_id);
    exit;
} else {
    header('Location: listActivities.php?error=no_data');
    exit;
}
?>