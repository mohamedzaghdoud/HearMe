<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/QuestionEmotionnelle.php';

class TestController {
    private $db;
    private $question;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->question = new QuestionEmotionnelle($this->db);
    }

    public function accueil() {
        include __DIR__ . '/../views/frontoffice/test/accueil.php';
    }

    public function afficherTest() {
        $stmt = $this->question->lireAvecOptions();
        $questions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_q = $row['id_question'];
            if (!isset($questions[$id_q])) {
                $questions[$id_q] = [
                    'id_question' => $row['id_question'],
                    'texte_question' => $row['texte_question'],
                    'categorie' => $row['categorie'],
                    'options' => []
                ];
            }
            if ($row['id_option']) {
                $questions[$id_q]['options'][] = [
                    'id_option' => $row['id_option'],
                    'texte_option' => $row['texte_option'],
                    'valeur_score' => $row['valeur_score']
                ];
            }
        }
        include __DIR__ . '/../views/frontoffice/test/test_emotionnel.php';
    }

    public function enregistrerResultat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reponses = $_POST['reponses'] ?? [];
            $score_total = 0;
            foreach ($reponses as $valeur) {
                $score_total += intval($valeur);
            }

            if ($score_total <= 5) {
                $niveau = "Calme";
                $conseil = "Tu sembles calme et équilibré. Continue à prendre soin de toi !";
                $couleur = "success";
            } elseif ($score_total <= 10) {
                $niveau = "Léger stress";
                $conseil = "Léger stress détecté. Prends un moment pour respirer profondément.";
                $couleur = "warning";
            } else {
                $niveau = "Tension élevée";
                $conseil = "Tu sembles tendu. Voici quelques ressources pour t'aider à te détendre.";
                $couleur = "danger";
            }

            $user_id = $_SESSION['user_id'] ?? 1;
            $query = "INSERT INTO resultat_utilisateur SET user_id=:user_id, score_total=:score, niveau=:niveau, conseil_genere=:conseil, date_test=NOW()";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":score", $score_total);
            $stmt->bindParam(":niveau", $niveau);
            $stmt->bindParam(":conseil", $conseil);
            $stmt->execute();

            $_SESSION['resultat'] = [
                'score' => $score_total,
                'niveau' => $niveau,
                'conseil' => $conseil,
                'couleur' => $couleur
            ];

            header("Location: TestController.php?action=resultat");
            exit();
        }
    }

    public function afficherResultat() {
        if (!isset($_SESSION['resultat'])) {
            header("Location: TestController.php?action=test");
            exit();
        }
        $resultat = $_SESSION['resultat'];
        include __DIR__ . '/../views/frontoffice/test/resultat.php';
    }

    public function afficherHistorique() {
        $user_id = $_SESSION['user_id'] ?? 1;
        $query = "SELECT * FROM resultat_utilisateur WHERE user_id = ? ORDER BY date_test DESC LIMIT 10";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        include __DIR__ . '/../views/frontoffice/test/historique.php';
    }
}

// === ROUTEUR ===
$controller = new TestController();
$action = $_GET['action'] ?? 'accueil';

switch ($action) {
    case 'test':
        $controller->afficherTest();
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