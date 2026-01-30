<?php
/**
 * Controller Quiz - BackOffice pour gérer les quiz
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Quiz.php';

class QuizController {
    private $db;

    public function __construct() {
        secureSession();
        if (!isAdmin()) {
            redirect('../View/FrontOffice/Login.php');
        }
        $this->db = getDB();
    }

    // Liste tous les quiz
    public function index() {
        $query = "SELECT q.*, (SELECT COUNT(*) FROM question WHERE id_quiz = q.id_quiz) as nb_questions 
                  FROM quiz q ORDER BY q.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $quizList = $stmt;
        include __DIR__ . '/../View/BackOffice/quiz/liste.php';
    }

    // Formulaire d'ajout
    public function create() {
        include __DIR__ . '/../View/BackOffice/quiz/ajouter.php';
    }

    // Enregistrer un nouveau quiz
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('QuizController.php');
        }

        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');
        $duree_estimee = intval($_POST['duree_estimee'] ?? 5);
        $actif = isset($_POST['actif']) ? 1 : 0;

        // Gestion de l'image
        $image = 'default-quiz.png';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../View/FrontOffice/assets/images/';
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = 'quiz_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
        }

        $query = "INSERT INTO quiz (titre, description, categorie, image, duree_estimee, actif) 
                  VALUES (:titre, :description, :categorie, :image, :duree, :actif)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':categorie' => $categorie,
            ':image' => $image,
            ':duree' => $duree_estimee,
            ':actif' => $actif
        ]);

        $_SESSION['success'] = "Quiz ajouté avec succès!";
        redirect('QuizController.php');
    }

    // Formulaire de modification
    public function edit($id) {
        $query = "SELECT * FROM quiz WHERE id_quiz = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quiz) {
            $_SESSION['error'] = "Quiz non trouvé";
            redirect('QuizController.php');
        }

        include __DIR__ . '/../View/BackOffice/quiz/modifier.php';
    }

    // Mettre à jour un quiz
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('QuizController.php');
        }

        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');
        $duree_estimee = intval($_POST['duree_estimee'] ?? 5);
        $actif = isset($_POST['actif']) ? 1 : 0;

        $query = "UPDATE quiz SET titre = :titre, description = :description, 
                  categorie = :categorie, duree_estimee = :duree, actif = :actif 
                  WHERE id_quiz = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':categorie' => $categorie,
            ':duree' => $duree_estimee,
            ':actif' => $actif,
            ':id' => $id
        ]);

        $_SESSION['success'] = "Quiz modifié avec succès!";
        redirect('QuizController.php');
    }

    // Supprimer un quiz
    public function delete($id) {
        $query = "DELETE FROM quiz WHERE id_quiz = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        $_SESSION['success'] = "Quiz supprimé avec succès!";
        redirect('QuizController.php');
    }
}

// ROUTEUR
$controller = new QuizController();
$action = $_GET['action'] ?? 'index';
$id = intval($_GET['id'] ?? 0);

switch ($action) {
    case 'create': $controller->create(); break;
    case 'store': $controller->store(); break;
    case 'edit': $controller->edit($id); break;
    case 'update': $controller->update($id); break;
    case 'delete': $controller->delete($id); break;
    default: $controller->index(); break;
}
?>
