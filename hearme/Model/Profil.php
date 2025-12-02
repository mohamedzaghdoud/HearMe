<?php
/**
 * Modèle Profil - Définition de la classe et validations
 * Emplacement : MON PROJET/Model/Profil.php
 */

require_once __DIR__ . '/../config.php';

class Profil {
    private $db;
    private $id_profil;
    private $id_user;
    private $bio;
    private $competences;
    private $formation;
    private $experience;
    private $reseaux_sociaux;
    private $disponibilite;
    private $localisation;
    private $preferences;
    private $autre_theme;
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

    public function getIdProfil() {
        return $this->id_profil;
    }

    public function setIdProfil($id_profil) {
        $this->id_profil = filter_var($id_profil, FILTER_VALIDATE_INT);
    }

    public function getIdUser() {
        return $this->id_user;
    }

    public function setIdUser($id_user) {
        $this->id_user = filter_var($id_user, FILTER_VALIDATE_INT);
    }

    public function getBio() {
        return $this->bio;
    }

    public function setBio($bio) {
        $this->bio = htmlspecialchars(trim($bio), ENT_QUOTES, 'UTF-8');
    }

    public function getCompetences() {
        return $this->competences;
    }

    public function setCompetences($competences) {
        $this->competences = htmlspecialchars(trim($competences), ENT_QUOTES, 'UTF-8');
    }

    public function getFormation() {
        return $this->formation;
    }

    public function setFormation($formation) {
        $this->formation = htmlspecialchars(trim($formation), ENT_QUOTES, 'UTF-8');
    }

    public function getExperience() {
        return $this->experience;
    }

    public function setExperience($experience) {
        $this->experience = htmlspecialchars(trim($experience), ENT_QUOTES, 'UTF-8');
    }

    public function getReseauxSociaux() {
        return $this->reseaux_sociaux;
    }

    public function setReseauxSociaux($reseaux_sociaux) {
        $this->reseaux_sociaux = filter_var(trim($reseaux_sociaux), FILTER_SANITIZE_URL);
    }

    public function getDisponibilite() {
        return $this->disponibilite;
    }

    public function setDisponibilite($disponibilite) {
        $allowedValues = ['disponible', 'occupé', 'indisponible'];
        $this->disponibilite = in_array($disponibilite, $allowedValues) ? $disponibilite : 'disponible';
    }

    public function getLocalisation() {
        return $this->localisation;
    }

    public function setLocalisation($localisation) {
        $this->localisation = htmlspecialchars(trim($localisation), ENT_QUOTES, 'UTF-8');
    }

    public function getPreferences() {
        return $this->preferences;
    }

    public function setPreferences($preferences) {
        $this->preferences = htmlspecialchars(trim($preferences), ENT_QUOTES, 'UTF-8');
    }

    public function getAutreTheme() {
        return $this->autre_theme;
    }

    public function setAutreTheme($autre_theme) {
        $this->autre_theme = htmlspecialchars(trim($autre_theme), ENT_QUOTES, 'UTF-8');
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
     * Valide la bio
     */
    public function validateBio($bio) {
        if (strlen($bio) > 1000) {
            return "La bio ne peut pas dépasser 1000 caractères.";
        }
        return true;
    }

    /**
     * Valide les compétences
     */
    public function validateCompetences($competences) {
        if (strlen($competences) > 500) {
            return "Les compétences ne peuvent pas dépasser 500 caractères.";
        }
        return true;
    }

    /**
     * Valide la formation
     */
    public function validateFormation($formation) {
        if (strlen($formation) > 255) {
            return "La formation ne peut pas dépasser 255 caractères.";
        }
        return true;
    }

    /**
     * Valide l'expérience
     */
    public function validateExperience($experience) {
        if (strlen($experience) > 1000) {
            return "L'expérience ne peut pas dépasser 1000 caractères.";
        }
        return true;
    }

    /**
     * Valide les réseaux sociaux
     */
    public function validateReseauxSociaux($reseaux_sociaux) {
        if (!empty($reseaux_sociaux) && strlen($reseaux_sociaux) > 255) {
            return "Les réseaux sociaux ne peuvent pas dépasser 255 caractères.";
        }
        return true;
    }

    /**
     * Valide la disponibilité
     */
    public function validateDisponibilite($disponibilite) {
        $allowedValues = ['disponible', 'occupé', 'indisponible'];
        if (!in_array($disponibilite, $allowedValues)) {
            return "La disponibilité doit être 'disponible', 'occupé' ou 'indisponible'.";
        }
        return true;
    }

    /**
     * Valide la localisation
     */
    public function validateLocalisation($localisation) {
        if (strlen($localisation) > 255) {
            return "La localisation ne peut pas dépasser 255 caractères.";
        }
        return true;
    }

    /**
     * Valide les préférences
     */
    public function validatePreferences($preferences) {
        if (strlen($preferences) > 500) {
            return "Les préférences ne peuvent pas dépasser 500 caractères.";
        }
        return true;
    }

    /**
     * Valide autre_theme
     */
    public function validateAutreTheme($autre_theme) {
        if (strlen($autre_theme) > 500) {
            return "Autre thème ne peut pas dépasser 500 caractères.";
        }
        return true;
    }
}
?>