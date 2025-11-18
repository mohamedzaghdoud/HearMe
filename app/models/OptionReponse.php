<?php
class OptionReponse {
    private $conn;
    private $table = "option_reponse";

    public $id_option;
    public $id_question;
    public $texte_option;
    public $valeur_score;
    public $ordre;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les options d'une question
    public function lireParQuestion() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id_question = ? ORDER BY ordre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_question);
        $stmt->execute();
        return $stmt;
    }

    // Créer une option
    public function creer() {
        $query = "INSERT INTO " . $this->table . " 
                  SET id_question=:id_question, texte_option=:texte, 
                      valeur_score=:score, ordre=:ordre";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id_question", $this->id_question);
        $stmt->bindParam(":texte", $this->texte_option);
        $stmt->bindParam(":score", $this->valeur_score);
        $stmt->bindParam(":ordre", $this->ordre);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Modifier une option
    public function modifier() {
        $query = "UPDATE " . $this->table . " 
                  SET texte_option=:texte, valeur_score=:score, ordre=:ordre
                  WHERE id_option=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":texte", $this->texte_option);
        $stmt->bindParam(":score", $this->valeur_score);
        $stmt->bindParam(":ordre", $this->ordre);
        $stmt->bindParam(":id", $this->id_option);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer une option
    public function supprimer() {
        $query = "DELETE FROM " . $this->table . " WHERE id_option = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_option);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer toutes les options d'une question
    public function supprimerParQuestion() {
        $query = "DELETE FROM " . $this->table . " WHERE id_question = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_question);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>