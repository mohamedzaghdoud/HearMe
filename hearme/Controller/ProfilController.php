<?php
/**
 * Controller Profil - Gestion complète des profils avec CRUD
 * Emplacement : MON PROJET/Controller/ProfilController.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Profil.php';

class ProfilController {
    private $profilModel;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->profilModel = new Profil();
    }

    // ====================================
    // CRUD - CREATE
    // ====================================

    /**
     * Crée un nouveau profil (Admin)
     */
    public function createProfil() {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_user' => $_POST['id_user'] ?? 0,
                'bio' => $_POST['bio'] ?? '',
                'competences' => $_POST['competences'] ?? '',
                'formation' => $_POST['formation'] ?? '',
                'experience' => $_POST['experience'] ?? '',
                'reseaux_sociaux' => $_POST['reseaux_sociaux'] ?? '',
                'disponibilite' => $_POST['disponibilite'] ?? 'disponible',
                'localisation' => $_POST['localisation'] ?? '',
                'preferences' => $_POST['preferences'] ?? '',
                'autre_theme' => $_POST['autre_theme'] ?? ''
            ];

            return $this->createProfilData($data);
        }
    }

    /**
     * Logique de création d'un profil
     */
    private function createProfilData($data) {
        try {
            // Validation de tous les champs
            $validations = [
                'bio' => $this->profilModel->validateBio($data['bio'] ?? ''),
                'competences' => $this->profilModel->validateCompetences($data['competences'] ?? ''),
                'formation' => $this->profilModel->validateFormation($data['formation'] ?? ''),
                'experience' => $this->profilModel->validateExperience($data['experience'] ?? ''),
                'reseaux_sociaux' => $this->profilModel->validateReseauxSociaux($data['reseaux_sociaux'] ?? ''),
                'disponibilite' => $this->profilModel->validateDisponibilite($data['disponibilite'] ?? 'disponible'),
                'localisation' => $this->profilModel->validateLocalisation($data['localisation'] ?? ''),
                'preferences' => $this->profilModel->validatePreferences($data['preferences'] ?? ''),
                'autre_theme' => $this->profilModel->validateAutreTheme($data['autre_theme'] ?? '')
            ];

            foreach ($validations as $field => $result) {
                if ($result !== true) {
                    return ['success' => false, 'message' => $result];
                }
            }

            // Vérifier si l'utilisateur a déjà un profil
            if ($this->profilExists($data['id_user'])) {
                return ['success' => false, 'message' => "Cet utilisateur a déjà un profil."];
            }

            // Insertion
            $db = $this->profilModel->getDb();
            $sql = "INSERT INTO profil (
                id_user, bio, competences, formation, experience, 
                reseaux_sociaux, disponibilite, localisation, 
                preferences, autre_theme
            ) VALUES (
                :id_user, :bio, :competences, :formation, :experience, 
                :reseaux_sociaux, :disponibilite, :localisation, 
                :preferences, :autre_theme
            )";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_user', $data['id_user'], PDO::PARAM_INT);
            $stmt->bindParam(':bio', $data['bio']);
            $stmt->bindParam(':competences', $data['competences']);
            $stmt->bindParam(':formation', $data['formation']);
            $stmt->bindParam(':experience', $data['experience']);
            $stmt->bindParam(':reseaux_sociaux', $data['reseaux_sociaux']);
            $stmt->bindParam(':disponibilite', $data['disponibilite']);
            $stmt->bindParam(':localisation', $data['localisation']);
            $stmt->bindParam(':preferences', $data['preferences']);
            $stmt->bindParam(':autre_theme', $data['autre_theme']);

            if ($stmt->execute()) {
                return [
                    'success' => true, 
                    'message' => "Profil créé avec succès.",
                    'id' => $db->lastInsertId()
                ];
            } else {
                return ['success' => false, 'message' => "Erreur lors de la création du profil."];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => "Erreur : " . $e->getMessage()];
        }
    }

    // ====================================
    // CRUD - READ
    // ====================================

    /**
     * Liste tous les profils
     */
    public function listProfils() {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }
        return $this->getAllProfils();
    }

    /**
     * Récupère tous les profils
     */
    private function getAllProfils() {
        try {
            $db = $this->profilModel->getDb();
            $sql = "SELECT p.*, u.email, u.role 
                    FROM profil p 
                    INNER JOIN users u ON p.id_user = u.id_user 
                    ORDER BY p.created_at DESC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Récupère un profil par ID
     */
    public function getProfil($id) {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }
        return $this->getProfilById($id);
    }

    /**
     * Récupère un profil par ID
     */
    private function getProfilById($id) {
        try {
            $db = $this->profilModel->getDb();
            $sql = "SELECT p.*, u.email, u.role 
                    FROM profil p 
                    INNER JOIN users u ON p.id_user = u.id_user 
                    WHERE p.id_profil = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Récupère un profil par ID utilisateur
     */
    private function getProfilByUserId($userId) {
        try {
            $db = $this->profilModel->getDb();
            $sql = "SELECT * FROM profil WHERE id_user = :id_user";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    // ====================================
    // CRUD - UPDATE
    // ====================================

    /**
     * Met à jour un profil (Admin)
     */
    public function updateProfil($id) {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'bio' => $_POST['bio'] ?? '',
                'competences' => $_POST['competences'] ?? '',
                'formation' => $_POST['formation'] ?? '',
                'experience' => $_POST['experience'] ?? '',
                'reseaux_sociaux' => $_POST['reseaux_sociaux'] ?? '',
                'disponibilite' => $_POST['disponibilite'] ?? 'disponible',
                'localisation' => $_POST['localisation'] ?? '',
                'preferences' => $_POST['preferences'] ?? '',
                'autre_theme' => $_POST['autre_theme'] ?? ''
            ];

            return $this->updateProfilData($id, $data);
        }
    }

    /**
     * Logique de mise à jour d'un profil
     */
    private function updateProfilData($id, $data) {
        try {
            // Validation
            $validations = [
                'bio' => $this->profilModel->validateBio($data['bio'] ?? ''),
                'competences' => $this->profilModel->validateCompetences($data['competences'] ?? ''),
                'formation' => $this->profilModel->validateFormation($data['formation'] ?? ''),
                'experience' => $this->profilModel->validateExperience($data['experience'] ?? ''),
                'reseaux_sociaux' => $this->profilModel->validateReseauxSociaux($data['reseaux_sociaux'] ?? ''),
                'disponibilite' => $this->profilModel->validateDisponibilite($data['disponibilite'] ?? 'disponible'),
                'localisation' => $this->profilModel->validateLocalisation($data['localisation'] ?? ''),
                'preferences' => $this->profilModel->validatePreferences($data['preferences'] ?? ''),
                'autre_theme' => $this->profilModel->validateAutreTheme($data['autre_theme'] ?? '')
            ];

            foreach ($validations as $field => $result) {
                if ($result !== true) {
                    return ['success' => false, 'message' => $result];
                }
            }

            // Mise à jour
            $db = $this->profilModel->getDb();
            $sql = "UPDATE profil SET 
                bio = :bio,
                competences = :competences,
                formation = :formation,
                experience = :experience,
                reseaux_sociaux = :reseaux_sociaux,
                disponibilite = :disponibilite,
                localisation = :localisation,
                preferences = :preferences,
                autre_theme = :autre_theme
                WHERE id_profil = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':bio', $data['bio']);
            $stmt->bindParam(':competences', $data['competences']);
            $stmt->bindParam(':formation', $data['formation']);
            $stmt->bindParam(':experience', $data['experience']);
            $stmt->bindParam(':reseaux_sociaux', $data['reseaux_sociaux']);
            $stmt->bindParam(':disponibilite', $data['disponibilite']);
            $stmt->bindParam(':localisation', $data['localisation']);
            $stmt->bindParam(':preferences', $data['preferences']);
            $stmt->bindParam(':autre_theme', $data['autre_theme']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => "Profil mis à jour avec succès."];
            } else {
                return ['success' => false, 'message' => "Erreur lors de la mise à jour."];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => "Erreur : " . $e->getMessage()];
        }
    }

    // ====================================
    // CRUD - DELETE
    // ====================================

    /**
     * Supprime un profil (Admin)
     */
    public function deleteProfil($id) {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }

        try {
            $db = $this->profilModel->getDb();
            $sql = "DELETE FROM profil WHERE id_profil = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => "Profil supprimé avec succès."];
            } else {
                return ['success' => false, 'message' => "Erreur lors de la suppression."];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => "Erreur : " . $e->getMessage()];
        }
    }

    // ====================================
    // GESTION PROFIL UTILISATEUR
    // ====================================

    /**
     * Récupère le profil de l'utilisateur connecté
     */
    public function getMyProfil() {
        if (!isLoggedIn()) {
            redirect('../FrontOffice/Login.php');
        }

        secureSession();
        return $this->getProfilByUserId($_SESSION['user_id']);
    }

    /**
     * Crée ou met à jour le profil de l'utilisateur connecté
     */
    public function createOrUpdateMyProfil() {
        if (!isLoggedIn()) {
            redirect('../FrontOffice/Login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            secureSession();
            
            $data = [
                'bio' => $_POST['bio'] ?? '',
                'competences' => $_POST['competences'] ?? '',
                'formation' => $_POST['formation'] ?? '',
                'experience' => $_POST['experience'] ?? '',
                'reseaux_sociaux' => $_POST['reseaux_sociaux'] ?? '',
                'disponibilite' => $_POST['disponibilite'] ?? 'disponible',
                'localisation' => $_POST['localisation'] ?? '',
                'preferences' => $_POST['preferences'] ?? '',
                'autre_theme' => $_POST['autre_theme'] ?? ''
            ];

            return $this->createOrUpdateProfil($_SESSION['user_id'], $data);
        }
    }

    /**
     * Crée ou met à jour un profil utilisateur
     */
    private function createOrUpdateProfil($userId, $data) {
        $existingProfil = $this->getProfilByUserId($userId);
        
        if ($existingProfil) {
            // Mise à jour
            return $this->updateProfilData($existingProfil['id_profil'], $data);
        } else {
            // Création
            $data['id_user'] = $userId;
            return $this->createProfilData($data);
        }
    }

    /**
     * Supprime le profil de l'utilisateur connecté
     */
    public function deleteMyProfil() {
        if (!isLoggedIn()) {
            redirect('../FrontOffice/Login.php');
        }

        secureSession();
        $profil = $this->getProfilByUserId($_SESSION['user_id']);

        if ($profil) {
            return $this->deleteProfil($profil['id_profil']);
        } else {
            return ['success' => false, 'message' => "Aucun profil à supprimer."];
        }
    }

    // ====================================
    // RECHERCHE ET FILTRES
    // ====================================

    /**
     * Recherche des profils par disponibilité
     */
    public function searchByDisponibilite($disponibilite) {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }
        return $this->getByDisponibilite($disponibilite);
    }

    /**
     * Récupère les profils par disponibilité
     */
    private function getByDisponibilite($disponibilite) {
        try {
            $db = $this->profilModel->getDb();
            $sql = "SELECT p.*, u.email, u.role 
                    FROM profil p 
                    INNER JOIN users u ON p.id_user = u.id_user 
                    WHERE p.disponibilite = :disponibilite 
                    ORDER BY p.created_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':disponibilite', $disponibilite);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    // ====================================
    // STATISTIQUES
    // ====================================

    /**
     * Compte le nombre total de profils
     */
    public function countProfils() {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }
        return $this->countAllProfils();
    }

    /**
     * Compte tous les profils
     */
    private function countAllProfils() {
        try {
            $db = $this->profilModel->getDb();
            $sql = "SELECT COUNT(*) as total FROM profil";
            $stmt = $db->query($sql);
            $result = $stmt->fetch();
            return $result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Récupère des statistiques sur les profils
     */
    public function getStats() {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }

        $disponibles = count($this->getByDisponibilite('disponible'));
        $occupes = count($this->getByDisponibilite('occupé'));
        $indisponibles = count($this->getByDisponibilite('indisponible'));

        return [
            'total' => $this->countAllProfils(),
            'disponibles' => $disponibles,
            'occupes' => $occupes,
            'indisponibles' => $indisponibles
        ];
    }

    // ====================================
    // MÉTHODES UTILITAIRES
    // ====================================

    /**
     * Vérifie si un profil existe pour un utilisateur
     */
    private function profilExists($userId) {
        $profil = $this->getProfilByUserId($userId);
        return $profil !== false && $profil !== null;
    }

    // ====================================
    // EXPORT (OPTIONNEL)
    // ====================================

    /**
     * Exporte les profils en CSV
     */
    public function exportCSV() {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }

        $profils = $this->getAllProfils();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=profils_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        
        // En-têtes
        fputcsv($output, [
            'ID', 'Email', 'Bio', 'Compétences', 'Formation', 
            'Expérience', 'Réseaux Sociaux', 'Disponibilité', 
            'Localisation', 'Préférences', 'Autre Thème', 
            'Date Création', 'Date Modification'
        ]);

        // Données
        foreach ($profils as $profil) {
            fputcsv($output, [
                $profil['id_profil'],
                $profil['email'],
                $profil['bio'],
                $profil['competences'],
                $profil['formation'],
                $profil['experience'],
                $profil['reseaux_sociaux'],
                $profil['disponibilite'],
                $profil['localisation'],
                $profil['preferences'],
                $profil['autre_theme'],
                $profil['created_at'],
                $profil['updated_at']
            ]);
        }

        fclose($output);
        exit;
    }
}

// ====================================
// GESTION DES ACTIONS
// ====================================

// Instanciation du controller
$controller = new ProfilController();

// Gestion des actions via GET
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'list':
            $profils = $controller->listProfils();
            break;

        case 'get':
            if (isset($_GET['id'])) {
                $profil = $controller->getProfil($_GET['id']);
            }
            break;

        case 'delete':
            if (isset($_GET['id'])) {
                $result = $controller->deleteProfil($_GET['id']);
                if ($result['success']) {
                    redirect('../BackOffice/ListerProfil.php?success=deleted');
                } else {
                    redirect('../BackOffice/ListerProfil.php?error=' . urlencode($result['message']));
                }
            }
            break;

        case 'getMyProfil':
            $profil = $controller->getMyProfil();
            break;

        case 'deleteMyProfil':
            $result = $controller->deleteMyProfil();
            break;

        case 'stats':
            $stats = $controller->getStats();
            break;

        case 'exportCSV':
            $controller->exportCSV();
            break;

        case 'searchByDisponibilite':
            if (isset($_GET['disponibilite'])) {
                $profils = $controller->searchByDisponibilite($_GET['disponibilite']);
            }
            break;

        default:
            break;
    }
}

// Gestion des actions via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'create':
                $result = $controller->createProfil();
                if ($result['success']) {
                    redirect('../BackOffice/ListerProfil.php?success=created');
                }
                break;

            case 'update':
                if (isset($_POST['id'])) {
                    $result = $controller->updateProfil($_POST['id']);
                    if ($result['success']) {
                        redirect('../BackOffice/ListerProfil.php?success=updated');
                    }
                }
                break;

            case 'createOrUpdateMyProfil':
                $result = $controller->createOrUpdateMyProfil();
                break;

            default:
                break;
        }
    }
}
?>