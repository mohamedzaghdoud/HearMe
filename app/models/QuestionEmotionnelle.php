<?php
class QuestionEmotionnelle {
    private $conn;
    private $table = "question_emotionnelle";

    public $id_question;
    public $texte_question;
    public $categorie;
    public $ordre;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les questions
    public function lire() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY ordre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lire une question par ID
    public function lireUn() {
        $query = "SELECT * FROM " . $this->table . " WHERE id_question = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_question);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->texte_question = $row['texte_question'];
            $this->categorie = $row['categorie'];
            $this->ordre = $row['ordre'];
            $this->date_creation = $row['date_creation'];
            return true;
        }
        return false;
    }

    // Créer une question
    public function creer() {
        $query = "INSERT INTO " . $this->table . " 
                  SET texte_question=:texte, categorie=:categorie, 
                      ordre=:ordre, date_creation=NOW()";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":texte", $this->texte_question);
        $stmt->bindParam(":categorie", $this->categorie);
        $stmt->bindParam(":ordre", $this->ordre);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mettre à jour une question
    public function modifier() {
        $query = "UPDATE " . $this->table . " 
                  SET texte_question=:texte, categorie=:categorie, ordre=:ordre
                  WHERE id_question=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":texte", $this->texte_question);
        $stmt->bindParam(":categorie", $this->categorie);
        $stmt->bindParam(":ordre", $this->ordre);
        $stmt->bindParam(":id", $this->id_question);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer une question
    public function supprimer() {
        $query = "DELETE FROM " . $this->table . " WHERE id_question = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_question);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lire questions avec leurs options
    public function lireAvecOptions() {
        $query = "SELECT q.*, o.id_option, o.texte_option, o.valeur_score, o.ordre as ordre_option
                  FROM " . $this->table . " q
                  LEFT JOIN option_reponse o ON q.id_question = o.id_question
                  ORDER BY q.ordre, o.ordre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>