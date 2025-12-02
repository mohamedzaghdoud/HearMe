<?php
/**
 * Modèle User - Définition de la classe et validations
 * Emplacement : MON PROJET/Model/User.php
 */

require_once __DIR__ . '/../config.php';

class User {
    private $db;
    private $id_user;
    private $email;
    private $password;
    private $role;
    private $created_at;
    private $updated_at;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->db = getDB();
    }

    // ====================================
    // GETTERS ET SETTERS
    // ====================================

    public function getIdUser() {
        return $this->id_user;
    }

    public function setIdUser($id_user) {
        $this->id_user = filter_var($id_user, FILTER_VALIDATE_INT);
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $allowedRoles = ['user', 'admin'];
        $this->role = in_array($role, $allowedRoles) ? $role : 'user';
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

    public function getDb() {
        return $this->db;
    }

    // ====================================
    // VALIDATION
    // ====================================

    /**
     * Valide l'email
     */
    public function validateEmail($email) {
        if (empty($email)) {
            return "L'email est requis.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "L'email n'est pas valide.";
        }
        if (strlen($email) > 100) {
            return "L'email ne peut pas dépasser 100 caractères.";
        }
        return true;
    }

    /**
     * Valide le mot de passe
     */
    public function validatePassword($password) {
        if (empty($password)) {
            return "Le mot de passe est requis.";
        }
        if (strlen($password) < 8) {
            return "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return "Le mot de passe doit contenir au moins une majuscule.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            return "Le mot de passe doit contenir au moins une minuscule.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            return "Le mot de passe doit contenir au moins un chiffre.";
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return "Le mot de passe doit contenir au moins un caractère spécial.";
        }
        return true;
    }

    /**
     * Valide le rôle
     */
    public function validateRole($role) {
        $allowedRoles = ['user', 'admin'];
        if (!in_array($role, $allowedRoles)) {
            return "Le rôle doit être 'user' ou 'admin'.";
        }
        return true;
    }
}
?>