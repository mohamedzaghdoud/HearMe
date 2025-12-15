<?php
/**
 * Controller Test Émotionnel - Intégré au module User
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Quiz.php';
require_once __DIR__ . '/../Model/Question.php';

class TestController {
    private $db;

    public function __construct() {
        secureSession();
        $this->db = getDB();
    }

    // Récupérer tous les quiz actifs
    public function getActiveQuiz() {
        $query = "SELECT q.*, 
                  (SELECT COUNT(*) FROM question WHERE id_quiz = q.id_quiz) as nb_questions
                  FROM quiz q
                  WHERE q.actif = 1
                  ORDER BY q.date_creation DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Récupérer un quiz avec ses questions
    public function getQuizWithQuestions($id_quiz) {
        $query = "SELECT * FROM quiz WHERE id_quiz = :id AND actif = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id_quiz, PDO::PARAM_INT);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quiz) return null;

        $query = "SELECT * FROM question WHERE id_quiz = :id ORDER BY ordre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id_quiz, PDO::PARAM_INT);
        $stmt->execute();

        $questions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['options'] = [];
            for ($i = 1; $i <= 4; $i++) {
                if (!empty($row["option{$i}_texte"])) {
                    $row['options'][] = [
                        'texte' => $row["option{$i}_texte"],
                        'score' => $row["option{$i}_score"]
                    ];
                }
            }
            $questions[] = $row;
        }

        return ['quiz' => $quiz, 'questions' => $questions];
    }

    // Enregistrer un résultat
    public function saveResult($id_quiz, $score, $niveau, $conseil) {
        $query = "INSERT INTO resultat_utilisateur
                  (id_quiz, user_id, score_total, niveau, conseil_genere, date_test)
                  VALUES (:id_quiz, :user_id, :score, :niveau, :conseil, NOW())";

        $stmt = $this->db->prepare($query);
        $user_id = $_SESSION['user_id'] ?? 1;

        $stmt->bindParam(':id_quiz', $id_quiz);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':score', $score);
        $stmt->bindParam(':niveau', $niveau);
        $stmt->bindParam(':conseil', $conseil);

        return $stmt->execute();
    }

    // Récupérer l'historique d'un utilisateur
    public function getUserHistory($user_id = null) {
        $user_id = $user_id ?? ($_SESSION['user_id'] ?? 1);

        $query = "SELECT r.*, q.titre as quiz_titre
                  FROM resultat_utilisateur r
                  LEFT JOIN quiz q ON r.id_quiz = q.id_quiz
                  WHERE r.user_id = :user_id
                  ORDER BY r.date_test DESC LIMIT 20";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Page d'accueil - Liste des quiz
    public function accueil() {
        $quizList = $this->getActiveQuiz();
        include __DIR__ . '/../View/FrontOffice/test/accueil.php';
    }

    // Afficher un test
    public function afficherTest($id_quiz) {
        $data = $this->getQuizWithQuestions($id_quiz);

        if (!$data) {
            header("Location: TestController.php?error=quiz_not_found");
            exit();
        }

        $quiz = $data['quiz'];
        $questions = $data['questions'];

        include __DIR__ . '/../View/FrontOffice/test/test_emotionnel.php';
    }

    // Enregistrer et calculer le résultat
    public function enregistrerResultat() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: TestController.php");
            exit();
        }

        $id_quiz = intval($_POST['id_quiz'] ?? 0);
        $reponses = $_POST['reponses'] ?? [];

        $score_total = 0;
        foreach ($reponses as $valeur) {
            $score_total += intval($valeur);
        }

        $nb_questions = count($reponses);
        $score_max = $nb_questions * 4;
        $pourcentage = ($score_total / $score_max) * 100;

        if ($pourcentage <= 25) {
            $niveau = "Excellent";
            $conseil = "Tu es en excellente forme émotionnelle ! 🌟 Continue à prendre soin de toi.";
            $couleur = "success";
        } elseif ($pourcentage <= 50) {
            $niveau = "Bien";
            $conseil = "Ton état émotionnel est stable 🌿. Quelques moments de stress légers, mais tu gères bien.";
            $couleur = "info";
        } elseif ($pourcentage <= 75) {
            $niveau = "Moyen";
            $conseil = "Tu accumules un peu de stress 💆. Prends du temps pour toi : méditation, sport, ou parle à un ami.";
            $couleur = "warning";
        } else {
            $niveau = "Attention";
            $conseil = "Tu traverses une période difficile 😢. N'hésite pas à en parler à quelqu'un de confiance.";
            $couleur = "danger";
        }

        $this->saveResult($id_quiz, $score_total, $niveau, $conseil);

        $_SESSION['resultat'] = [
            'id_quiz' => $id_quiz,
            'score' => $score_total,
            'score_max' => $score_max,
            'pourcentage' => round($pourcentage),
            'niveau' => $niveau,
            'conseil' => $conseil,
            'couleur' => $couleur
        ];

        header("Location: TestController.php?action=resultat");
        exit();
    }

    // Afficher le résultat
    public function afficherResultat() {
        if (!isset($_SESSION['resultat'])) {
            header("Location: TestController.php");
            exit();
        }
        $resultat = $_SESSION['resultat'];
        include __DIR__ . '/../View/FrontOffice/test/resultat.php';
    }

    // Afficher l'historique
    public function afficherHistorique() {
        $stmt = $this->getUserHistory();
        include __DIR__ . '/../View/FrontOffice/test/historique.php';
    }
}

// ROUTEUR
$controller = new TestController();
$action = $_GET['action'] ?? 'accueil';

switch ($action) {
    case 'test':
        $id_quiz = intval($_GET['id'] ?? 0);
        if ($id_quiz > 0) {
            $controller->afficherTest($id_quiz);
        } else {
            $controller->accueil();
        }
        break;
    case 'enregistrer':
        $controller->enregistrerResultat();
        break;
    case 'resultat':
        $controller->afficherResultat();
        break;
    case 'historique':
        $controller->afficherHistorique();
        break;
    default:
        $controller->accueil();
        break;
}
?>
