<?php
class Quiz {
    // Private attributes (encapsulation)
    private $id_quiz;
    private $titre;
    private $description;
    private $image;
    private $categorie;
    private $duree_estimee;
    private $actif;
    private $date_creation;

    // Constructor - can initialize with data
    public function __construct($data = null) {
        if ($data) {
            $this->setIdQuiz($data['id_quiz'] ?? null);
            $this->setTitre($data['titre'] ?? '');
            $this->setDescription($data['description'] ?? '');
            $this->setImage($data['image'] ?? 'default-quiz.png');
            $this->setCategorie($data['categorie'] ?? '');
            $this->setDureeEstimee($data['duree_estimee'] ?? 5);
            $this->setActif($data['actif'] ?? 1);
            $this->setDateCreation($data['date_creation'] ?? null);
        }
    }

    // --- GETTERS ---
    public function getIdQuiz() {
        return $this->id_quiz;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getImage() {
        return $this->image;
    }

    public function getCategorie() {
        return $this->categorie;
    }

    public function getDureeEstimee() {
        return $this->duree_estimee;
    }

    public function getActif() {
        return $this->actif;
    }

    public function getDateCreation() {
        return $this->date_creation;
    }

    // --- SETTERS ---
    public function setIdQuiz($id) {
        $this->id_quiz = $id;
    }

    public function setTitre($titre) {
        $this->titre = trim($titre);
    }

    public function setDescription($description) {
        $this->description = trim($description);
    }

    public function setImage($image) {
        $this->image = trim($image);
    }

    public function setCategorie($categorie) {
        $this->categorie = trim($categorie);
    }

    public function setDureeEstimee($duree) {
        // Validation: minimum 1 minute, maximum 120 minutes
        $this->duree_estimee = max(1, min(120, (int)$duree));
    }

    public function setActif($actif) {
        // Convert to boolean integer (0 or 1)
        $this->actif = $actif ? 1 : 0;
    }

    public function setDateCreation($date) {
        $this->date_creation = $date;
    }

    // --- HELPER METHODS ---
    
    // Check if quiz is active
    public function isActif() {
        return $this->actif == 1;
    }

    // Convert object to array
    public function toArray() {
        return [
            'id_quiz' => $this->id_quiz,
            'titre' => $this->titre,
            'description' => $this->description,
            'image' => $this->image,
            'categorie' => $this->categorie,
            'duree_estimee' => $this->duree_estimee,
            'actif' => $this->actif,
            'date_creation' => $this->date_creation
        ];
    }
}
?>