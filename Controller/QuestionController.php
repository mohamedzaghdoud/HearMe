<?php
/**
 * Controller Question - BackOffice pour gérer les questions
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Question.php';

class QuestionController {
    private $db;

    public function __construct() {
        secureSession();
        if (!isAdmin()) {
            redirect('../View/FrontOffice/Login.php');
        }
        $this->db = getDB();
    }

    // Liste les questions d'un quiz
    public function index($id_quiz) {
        $query = "SELECT * FROM quiz WHERE id_quiz = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id_quiz]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quiz) {
            $_SESSION['error'] = "Quiz non trouvé";
            redirect('QuizController.php');
        }

        $query = "SELECT * FROM question WHERE id_quiz = :id ORDER BY ordre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id_quiz]);
        $questions = $stmt;

        include __DIR__ . '/../View/BackOffice/questions/liste.php';
    }

    // Formulaire d'ajout
    public function create($id_quiz) {
        include __DIR__ . '/../View/BackOffice/questions/ajouter.php';
    }

    // Enregistrer une nouvelle question
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('QuizController.php');
        }

        $id_quiz = intval($_POST['id_quiz'] ?? 0);
        $texte = trim($_POST['texte_question'] ?? '');
        $ordre = intval($_POST['ordre'] ?? 1);

        $query = "INSERT INTO question (id_quiz, texte_question, ordre, 
                  option1_texte, option1_score, option2_texte, option2_score, 
                  option3_texte, option3_score, option4_texte, option4_score) 
                  VALUES (:id_quiz, :texte, :ordre, :o1t, :o1s, :o2t, :o2s, :o3t, :o3s, :o4t, :o4s)";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':id_quiz' => $id_quiz,
            ':texte' => $texte,
            ':ordre' => $ordre,
            ':o1t' => trim($_POST['option1_texte'] ?? ''),
            ':o1s' => intval($_POST['option1_score'] ?? 1),
            ':o2t' => trim($_POST['option2_texte'] ?? ''),
            ':o2s' => intval($_POST['option2_score'] ?? 2),
            ':o3t' => trim($_POST['option3_texte'] ?? ''),
            ':o3s' => intval($_POST['option3_score'] ?? 3),
            ':o4t' => trim($_POST['option4_texte'] ?? '') ?: null,
            ':o4s' => !empty($_POST['option4_texte']) ? intval($_POST['option4_score'] ?? 4) : null
        ]);

        $_SESSION['success'] = "Question ajoutée avec succès!";
        redirect("QuestionController.php?id_quiz=$id_quiz");
    }

    // Formulaire de modification
    public function edit($id) {
        $query = "SELECT * FROM question WHERE id_question = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$question) {
            $_SESSION['error'] = "Question non trouvée";
            redirect('QuizController.php');
        }

        $id_quiz = $question['id_quiz'];
        include __DIR__ . '/../View/BackOffice/questions/modifier.php';
    }

    // Mettre à jour une question
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('QuizController.php');
        }

        $id_quiz = intval($_POST['id_quiz'] ?? 0);
        $texte = trim($_POST['texte_question'] ?? '');
        $ordre = intval($_POST['ordre'] ?? 1);

        $query = "UPDATE question SET texte_question = :texte, ordre = :ordre,
                  option1_texte = :o1t, option1_score = :o1s,
                  option2_texte = :o2t, option2_score = :o2s,
                  option3_texte = :o3t, option3_score = :o3s,
                  option4_texte = :o4t, option4_score = :o4s
                  WHERE id_question = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':texte' => $texte,
            ':ordre' => $ordre,
            ':o1t' => trim($_POST['option1_texte'] ?? ''),
            ':o1s' => intval($_POST['option1_score'] ?? 1),
            ':o2t' => trim($_POST['option2_texte'] ?? ''),
            ':o2s' => intval($_POST['option2_score'] ?? 2),
            ':o3t' => trim($_POST['option3_texte'] ?? ''),
            ':o3s' => intval($_POST['option3_score'] ?? 3),
            ':o4t' => trim($_POST['option4_texte'] ?? '') ?: null,
            ':o4s' => !empty($_POST['option4_texte']) ? intval($_POST['option4_score'] ?? 4) : null,
            ':id' => $id
        ]);

        $_SESSION['success'] = "Question modifiée avec succès!";
        redirect("QuestionController.php?id_quiz=$id_quiz");
    }

    // Supprimer une question
    public function delete($id, $id_quiz) {
        $query = "DELETE FROM question WHERE id_question = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        $_SESSION['success'] = "Question supprimée avec succès!";
        redirect("QuestionController.php?id_quiz=$id_quiz");
    }
}

// ROUTEUR
$controller = new QuestionController();
$action = $_GET['action'] ?? 'index';
$id = intval($_GET['id'] ?? 0);
$id_quiz = intval($_GET['id_quiz'] ?? 0);

switch ($action) {
    case 'create': $controller->create($id_quiz); break;
    case 'store': $controller->store(); break;
    case 'edit': $controller->edit($id); break;
    case 'update': $controller->update($id); break;
    case 'delete': $controller->delete($id, $id_quiz); break;
    default: $controller->index($id_quiz); break;
}
?>
