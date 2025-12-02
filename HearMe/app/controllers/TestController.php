<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Quiz.php';
require_once __DIR__ . '/../models/Question.php';

class TestController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // MÉTHODES DE LECTURE

    //Récupérer tous les quiz actifs
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
    
    //Récupérer un quiz avec ses questions
    public function getQuizWithQuestions($id_quiz) {
        // Récupérer le quiz
        $query = "SELECT * FROM quiz WHERE id_quiz = :id AND actif = 1 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id_quiz, PDO::PARAM_INT);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$quiz) return null;
        
        // Récupérer les questions
        $query = "SELECT * FROM question WHERE id_quiz = :id ORDER BY ordre ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id_quiz, PDO::PARAM_INT);
        $stmt->execute();
        
        $questions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formater les options
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
        
        return [
            'quiz' => $quiz,
            'questions' => $questions
        ];
    }
    
    //Enregistrer un résultat
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
    
    //Récupérer l'historique d'un utilisateur
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
    
    // ACTIONS (Routes)

    //Page d'accueil - Liste des quiz disponibles
    public function accueil() {
        $quizList = $this->getActiveQuiz();
        include __DIR__ . '/../views/frontoffice/test/accueil.php';
    }
    
    //Afficher un test spécifique
    public function afficherTest($id_quiz) {
        $data = $this->getQuizWithQuestions($id_quiz);
        
        if (!$data) {
            header("Location: TestController.php?error=quiz_not_found");
            exit();
        }
        
        $quiz = $data['quiz'];
        $questions = $data['questions'];
        
        include __DIR__ . '/../views/frontoffice/test/test_emotionnel.php';
    }
    
    //Enregistrer et calculer le résultat
    public function enregistrerResultat() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: TestController.php");
            exit();
        }
        
        $id_quiz = intval($_POST['id_quiz'] ?? 0);
        $reponses = $_POST['reponses'] ?? [];
        
        // Calculer le score total
        $score_total = 0;
        foreach ($reponses as $valeur) {
            $score_total += intval($valeur);
        }
        
        // Nombre de questions
        $nb_questions = count($reponses);
        $score_max = $nb_questions * 4; // Max 4 points par question
        
        // Calculer le pourcentage pour déterminer le niveau
        $pourcentage = ($score_total / $score_max) * 100;
        
        // Déterminer le niveau et le conseil
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
            $conseil = "Tu traverses une période difficile 😔. N'hésite pas à en parler à quelqu'un de confiance.";
            $couleur = "danger";
        }
        
        // Enregistrer en base
        $this->saveResult($id_quiz, $score_total, $niveau, $conseil);
        
        // Stocker en session pour affichage
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
    
    //Afficher le résultat
    public function afficherResultat() {
        if (!isset($_SESSION['resultat'])) {
            header("Location: TestController.php");
            exit();
        }
        
        $resultat = $_SESSION['resultat'];
        include __DIR__ . '/../views/frontoffice/test/resultat.php';
    }
    
    //Afficher l'historique
    public function afficherHistorique() {
        $stmt = $this->getUserHistory();
        include __DIR__ . '/../views/frontoffice/test/historique.php';
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