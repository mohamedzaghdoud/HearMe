<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Question.php';
require_once __DIR__ . '/../models/Quiz.php';

class QuestionController {
    private $db;
    private $table = "question";

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    // OPÉRATIONS CRUD

    //CREATE - Créer une nouvelle question
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (id_quiz, texte_question, ordre, 
                   option1_texte, option1_score,
                   option2_texte, option2_score,
                   option3_texte, option3_score,
                   option4_texte, option4_score,
                   date_creation) 
                  VALUES (:id_quiz, :texte, :ordre,
                          :opt1_texte, :opt1_score,
                          :opt2_texte, :opt2_score,
                          :opt3_texte, :opt3_score,
                          :opt4_texte, :opt4_score,
                          NOW())";
        
        $stmt = $this->db->prepare($query);
        
        $id_quiz = intval($data['id_quiz']);
        $texte = htmlspecialchars($data['texte_question']);
        $ordre = intval($data['ordre'] ?? 1);
        
        $opt1_texte = htmlspecialchars($data['option1_texte']);
        $opt1_score = intval($data['option1_score']);
        $opt2_texte = htmlspecialchars($data['option2_texte']);
        $opt2_score = intval($data['option2_score']);
        $opt3_texte = htmlspecialchars($data['option3_texte']);
        $opt3_score = intval($data['option3_score']);
        $opt4_texte = !empty($data['option4_texte']) ? htmlspecialchars($data['option4_texte']) : null;
        $opt4_score = !empty($data['option4_score']) ? intval($data['option4_score']) : null;
        
        $stmt->bindParam(':id_quiz', $id_quiz);
        $stmt->bindParam(':texte', $texte);
        $stmt->bindParam(':ordre', $ordre);
        $stmt->bindParam(':opt1_texte', $opt1_texte);
        $stmt->bindParam(':opt1_score', $opt1_score);
        $stmt->bindParam(':opt2_texte', $opt2_texte);
        $stmt->bindParam(':opt2_score', $opt2_score);
        $stmt->bindParam(':opt3_texte', $opt3_texte);
        $stmt->bindParam(':opt3_score', $opt3_score);
        $stmt->bindParam(':opt4_texte', $opt4_texte);
        $stmt->bindParam(':opt4_score', $opt4_score);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    //READ - Lire toutes les questions
    public function readAll() {
        $query = "SELECT q.*, qz.titre as quiz_titre 
                  FROM " . $this->table . " q
                  LEFT JOIN quiz qz ON q.id_quiz = qz.id_quiz
                  ORDER BY q.id_quiz, q.ordre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    //READ - Lire les questions d'un quiz
    public function readByQuiz($id_quiz) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id_quiz = :id_quiz ORDER BY ordre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_quiz', $id_quiz, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    
    //READ - Lire une question par ID
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_question = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Question($row);
        }
        return null;
    }
    
    //UPDATE - Mettre à jour une question
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET id_quiz = :id_quiz,
                      texte_question = :texte,
                      ordre = :ordre,
                      option1_texte = :opt1_texte,
                      option1_score = :opt1_score,
                      option2_texte = :opt2_texte,
                      option2_score = :opt2_score,
                      option3_texte = :opt3_texte,
                      option3_score = :opt3_score,
                      option4_texte = :opt4_texte,
                      option4_score = :opt4_score
                  WHERE id_question = :id";
        
        $stmt = $this->db->prepare($query);
        
        $id_quiz = intval($data['id_quiz']);
        $texte = htmlspecialchars($data['texte_question']);
        $ordre = intval($data['ordre'] ?? 1);
        
        $opt1_texte = htmlspecialchars($data['option1_texte']);
        $opt1_score = intval($data['option1_score']);
        $opt2_texte = htmlspecialchars($data['option2_texte']);
        $opt2_score = intval($data['option2_score']);
        $opt3_texte = htmlspecialchars($data['option3_texte']);
        $opt3_score = intval($data['option3_score']);
        $opt4_texte = !empty($data['option4_texte']) ? htmlspecialchars($data['option4_texte']) : null;
        $opt4_score = !empty($data['option4_score']) ? intval($data['option4_score']) : null;
        
        $stmt->bindParam(':id_quiz', $id_quiz);
        $stmt->bindParam(':texte', $texte);
        $stmt->bindParam(':ordre', $ordre);
        $stmt->bindParam(':opt1_texte', $opt1_texte);
        $stmt->bindParam(':opt1_score', $opt1_score);
        $stmt->bindParam(':opt2_texte', $opt2_texte);
        $stmt->bindParam(':opt2_score', $opt2_score);
        $stmt->bindParam(':opt3_texte', $opt3_texte);
        $stmt->bindParam(':opt3_score', $opt3_score);
        $stmt->bindParam(':opt4_texte', $opt4_texte);
        $stmt->bindParam(':opt4_score', $opt4_score);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    //DELETE - Supprimer une question
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_question = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    //Récupérer tous les quiz pour les selects
    public function getAllQuiz() {
        $query = "SELECT id_quiz, titre FROM quiz ORDER BY titre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ACTIONS DU CONTROLLER (Routes)

    //Afficher la liste des questions
    public function liste() {
        $stmt = $this->readAll();
        include __DIR__ . '/../views/backoffice/questions/liste.php';
    }
    
    //Afficher le formulaire d'ajout
    public function afficherAjouter() {
        $quizList = $this->getAllQuiz();
        include __DIR__ . '/../views/backoffice/questions/ajouter.php';
    }
    
    //Traiter l'ajout d'une question
    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->create($_POST);
            if ($id) {
                header("Location: QuestionController.php?action=liste&success=1");
                exit();
            }
        }
    }
    
    //Afficher le formulaire de modification
    public function afficherModifier($id) {
        $question = $this->readOne($id);
        if (!$question) {
            header("Location: QuestionController.php?action=liste&error=1");
            exit();
        }
        $quizList = $this->getAllQuiz();
        include __DIR__ . '/../views/backoffice/questions/modifier.php';
    }
    
    //Traiter la modification
    public function modifier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_question']);
            if ($this->update($id, $_POST)) {
                header("Location: QuestionController.php?action=liste&success=2");
                exit();
            }
        }
    }
    
    // Supprimer une question
    public function supprimer($id) {
        if ($this->delete($id)) {
            header("Location: QuestionController.php?action=liste&success=3");
            exit();
        }
    }
    
    //Afficher les résultats
    public function resultats() {
        $query = "SELECT r.*, q.titre as quiz_titre 
                  FROM resultat_utilisateur r
                  LEFT JOIN quiz q ON r.id_quiz = q.id_quiz
                  ORDER BY r.date_test DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    }
}

// ROUTEUR
$controller = new QuestionController();
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

    case 'resultats':
        $controller->resultats();
        break;

    default:
        $controller->liste();
        break;
}
?>