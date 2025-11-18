<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/QuestionEmotionnelle.php';
require_once __DIR__ . '/../models/OptionReponse.php';

class QuestionController {
    private $db;
    private $question;
    private $option;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->question = new QuestionEmotionnelle($this->db);
        $this->option = new OptionReponse($this->db);
    }

    // Afficher la liste
    public function liste() {
        $stmt = $this->question->lire();
        include __DIR__ . '/../views/backoffice/questions/liste.php';
    }

    // Afficher formulaire ajout
    public function afficherAjouter() {
        include __DIR__ . '/../views/backoffice/questions/ajouter.php';
    }

    // Ajouter
    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->question->texte_question = htmlspecialchars($_POST['texte_question']);
            $this->question->categorie = htmlspecialchars($_POST['categorie']);
            $this->question->ordre = intval($_POST['ordre']);

            if ($this->question->creer()) {
                $id_question = $this->db->lastInsertId();

                if (isset($_POST['options']) && is_array($_POST['options'])) {
                    foreach ($_POST['options'] as $index => $opt) {
                        $this->option->id_question = $id_question;
                        $this->option->texte_option = htmlspecialchars($opt['texte']);
                        $this->option->valeur_score = intval($opt['score']);
                        $this->option->ordre = $index + 1;
                        $this->option->creer();
                    }
                }
                header("Location: QuestionController.php?action=liste&success=1");
                exit();
            }
        }
    }

    // Afficher formulaire modification
    public function afficherModifier($id) {
        $this->question->id_question = $id;
        $this->question->lireUn();
        $this->option->id_question = $id;
        $options = $this->option->lireParQuestion();
        include __DIR__ . '/../views/backoffice/questions/modifier.php';
    }

    // Modifier
    public function modifier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->question->id_question = intval($_POST['id_question']);
            $this->question->texte_question = htmlspecialchars($_POST['texte_question']);
            $this->question->categorie = htmlspecialchars($_POST['categorie']);
            $this->question->ordre = intval($_POST['ordre']);

            if ($this->question->modifier()) {
                $this->option->id_question = $this->question->id_question;
                $this->option->supprimerParQuestion();

                if (isset($_POST['options']) && is_array($_POST['options'])) {
                    foreach ($_POST['options'] as $index => $opt) {
                        $this->option->id_question = $this->question->id_question;
                        $this->option->texte_option = htmlspecialchars($opt['texte']);
                        $this->option->valeur_score = intval($opt['score']);
                        $this->option->ordre = $index + 1;
                        $this->option->creer();
                    }
                }
                header("Location: QuestionController.php?action=liste&success=2");
                exit();
            }
        }
    }

    // Supprimer
    public function supprimer($id) {
        $this->option->id_question = $id;
        $this->option->supprimerParQuestion();
        $this->question->id_question = $id;
        if ($this->question->supprimer()) {
            header("Location: QuestionController.php?action=liste&success=3");
            exit();
        }
    }
    public function resultats() {
    $stmt = $this->db->query("SELECT * FROM resultat_utilisateur ORDER BY date_test DESC");
    include __DIR__ . '/../views/backoffice/questions/resultats.php';
}
}

// === ROUTEUR ===
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
            $id = $_GET['id'] ?? 0;
            $controller->afficherModifier($id);
        }
        break;

    case 'supprimer':
        $id = $_GET['id'] ?? 0;
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