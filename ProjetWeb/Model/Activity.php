<?php
class Activity {
    private $activity_id;
    private $title;
    private $activity_type;
    private $description;
    private $location;
    private $date_time;
    private $max_participants;
    private $current_participants;
    private $difficulty_level;
    private $status;

    public function __construct($title, $activity_type, $description, $location, $date_time, $max_participants, $difficulty_level, $status = true) {
        $this->title = $title;
        $this->activity_type = $activity_type;
        $this->description = $description;
        $this->location = $location;
        $this->date_time = $date_time;
        $this->max_participants = $max_participants;
        $this->difficulty_level = $difficulty_level;
        $this->status = $status;
    }

    // Getters
    public function getId() { return $this->activity_id; }
    public function getTitle() { return $this->title; }
    public function getType() { return $this->activity_type; }
    public function getDescription() { return $this->description; }
    public function getLocation() { return $this->location; }
    public function getDateTime() { return $this->date_time; }
    public function getMaxParticipants() { return $this->max_participants; }
    public function getCurrentParticipants() { return $this->current_participants; }
    public function getDifficulty() { return $this->difficulty_level; }
    public function getStatus() { return $this->status; }

    // Méthode show()
    public function show() {
        echo "<table border='1'>";
        echo "<tr><th>Titre</th><td>" . $this->title . "</td></tr>";
        echo "<tr><th>Type</th><td>" . $this->activity_type . "</td></tr>";
        echo "<tr><th>Description</th><td>" . $this->description . "</td></tr>";
        echo "<tr><th>Lieu</th><td>" . $this->location . "</td></tr>";
        echo "<tr><th>Date/Heure</th><td>" . $this->date_time . "</td></tr>";
        echo "<tr><th>Participants max</th><td>" . $this->max_participants . "</td></tr>";
        echo "<tr><th>Niveau</th><td>" . $this->difficulty_level . "</td></tr>";
        echo "</table>";
    }
}
?>