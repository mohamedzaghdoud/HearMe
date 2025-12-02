<?php
require_once __DIR__ . '/../Model/Activity.php';
require_once __DIR__ . '/../config.php';

class ActivityController {
    
    // Afficher une activité
    public function showActivity($activity) {
        $activity->show();
    }
    
    // Lister toutes les activités
    public function listActivities() {
        $pdo = config::getConnexion();
        $sql = "SELECT * FROM activities";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Ajouter une activité
    public function addActivity($activity) {
        $pdo = config::getConnexion();
        $sql = "INSERT INTO activities (title, activity_type, description, location, date_time, max_participants, difficulty_level, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $activity->getTitle(),
            $activity->getType(),
            $activity->getDescription(),
            $activity->getLocation(),
            $activity->getDateTime(),
            $activity->getMaxParticipants(),
            $activity->getDifficulty(),
            $activity->getStatus()
        ]);
    }
    
    // Récupérer une activité par ID
    public function getActivityById($id) {
        $pdo = config::getConnexion();
        $sql = "SELECT * FROM activities WHERE activity_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Mettre à jour une activité
    public function updateActivity($id, $activity) {
        $pdo = config::getConnexion();
        $sql = "UPDATE activities SET title=?, activity_type=?, description=?, location=?, date_time=?, max_participants=?, difficulty_level=?, status=? WHERE activity_id=?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $activity->getTitle(),
            $activity->getType(),
            $activity->getDescription(),
            $activity->getLocation(),
            $activity->getDateTime(),
            $activity->getMaxParticipants(),
            $activity->getDifficulty(),
            $activity->getStatus(),
            $id
        ]);
    }

    // Supprimer une activité
    public function deleteActivity($id) {
        $pdo = config::getConnexion();
        $sql = "DELETE FROM activities WHERE activity_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    // Compter le nombre total d'activités
    public function countActivities() {
        $pdo = config::getConnexion();
        $sql = "SELECT COUNT(*) as total FROM activities";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }
}
?>