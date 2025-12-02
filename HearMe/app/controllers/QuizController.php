<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Quiz.php';

class QuizController {
    private $db;
    private $table = "quiz";

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // OPÉRATIONS CRUD

    //CREATE - Créer un nouveau quiz
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (titre, description, categorie, image, duree_estimee, actif, date_creation) 
                  VALUES (:titre, :description, :categorie, :image, :duree, :actif, NOW())";
        
        $stmt = $this->db->prepare($query);
        
        $titre = htmlspecialchars($data['titre']);
        $description = htmlspecialchars($data['description'] ?? '');
        $categorie = htmlspecialchars($data['categorie']);
        $image = htmlspecialchars($data['image'] ?? 'default-quiz.png');
        $duree = intval($data['duree_estimee'] ?? 5);
        $actif = intval($data['actif'] ?? 1);
        
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':duree', $duree);
        $stmt->bindParam(':actif', $actif);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    //READ - Lire tous les quiz
    public function readAll() {
        $query = "SELECT q.*, 
                  (SELECT COUNT(*) FROM question WHERE id_quiz = q.id_quiz) as nb_questions
                  FROM " . $this->table . " q 
                  ORDER BY q.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    //READ - Lire les quiz actifs uniquement
    public function readActive() {
        $query = "SELECT q.*, 
                  (SELECT COUNT(*) FROM question WHERE id_quiz = q.id_quiz) as nb_questions
                  FROM " . $this->table . " q 
                  WHERE q.actif = 1 
                  ORDER BY q.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    //READ - Lire un quiz par ID
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_quiz = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Quiz($row);
        }
        return null;
    }
    
    //READ - Lire un quiz avec ses questions
    public function readWithQuestions($id) {
        // Récupérer le quiz
        $quiz = $this->readOne($id);
        if (!$quiz) return null;
        
        // Récupérer les questions
        $query = "SELECT * FROM question WHERE id_quiz = :id ORDER BY ordre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $questions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $questions[] = $row;
        }
        
        return [
            'quiz' => $quiz,
            'questions' => $questions
        ];
    }
    
    //UPDATE - Mettre à jour un quiz
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET titre = :titre, 
                      description = :description, 
                      categorie = :categorie,
                      image = :image,
                      duree_estimee = :duree,
                      actif = :actif
                  WHERE id_quiz = :id";
        
        $stmt = $this->db->prepare($query);
        
        $titre = htmlspecialchars($data['titre']);
        $description = htmlspecialchars($data['description'] ?? '');
        $categorie = htmlspecialchars($data['categorie']);
        $image = htmlspecialchars($data['image'] ?? 'default-quiz.png');
        $duree = intval($data['duree_estimee'] ?? 5);
        $actif = intval($data['actif'] ?? 1);
        
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':duree', $duree);
        $stmt->bindParam(':actif', $actif);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    //DELETE - Supprimer un quiz (et ses questions en cascade)
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_quiz = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // ACTIONS DU CONTROLLER (Routes)

    //Afficher la liste des quiz
    public function liste() {
        $stmt = $this->readAll();
        include __DIR__ . '/../views/backoffice/quiz/liste.php';
    }
    
    //Afficher le formulaire d'ajout
    public function afficherAjouter() {
        include __DIR__ . '/../views/backoffice/quiz/ajouter.php';
    }
    
    //Traiter l'ajout d'un quiz
    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->create($_POST);
            if ($id) {
                header("Location: QuizController.php?action=liste&success=1");
                exit();
            }
        }
    }
    
    //Afficher le formulaire de modification
    public function afficherModifier($id) {
        $quiz = $this->readOne($id);
        if (!$quiz) {
            header("Location: QuizController.php?action=liste&error=1");
            exit();
        }
        include __DIR__ . '/../views/backoffice/quiz/modifier.php';
    }
    
    //Traiter la modification d'un quiz
    public function modifier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_quiz']);
            if ($this->update($id, $_POST)) {
                header("Location: QuizController.php?action=liste&success=2");
                exit();
            }
        }
    }
    
    //Supprimer un quiz
    public function supprimer($id) {
        if ($this->delete($id)) {
            header("Location: QuizController.php?action=liste&success=3");
            exit();
        }
    }
}

// ROUTEUR
$controller = new QuizController();
$action = $_GET['action'] ?? 'liste';

switch ($action) {
    case 'ajouter':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->ajouter();
        } else {
            $controller->afficherAjouter();
        }
        break;

    case 'modifier':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->modifier();
        } else {
            $id = intval($_GET['id'] ?? 0);
            $controller->afficherModifier($id);
        }
        break;

    case 'supprimer':
        $id = intval($_GET['id'] ?? 0);
        $controller->supprimer($id);
        break;

    default:
        $controller->liste();
        break;
}
?>