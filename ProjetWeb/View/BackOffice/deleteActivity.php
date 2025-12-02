<?php
require_once '../../Controller/ActivityController.php';

if (isset($_GET['id'])) {
    $controller = new ActivityController();
    $controller->deleteActivity($_GET['id']);
    
    header('Location: listActivities.php?message=deleted');
    exit;
} else {
    header('Location: listActivities.php?error=no_id');
    exit;
}
?>