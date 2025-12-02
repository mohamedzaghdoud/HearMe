<?php
/**
 * Controller User - Gestion complète des utilisateurs avec CRUD
 * Emplacement : MON PROJET/Controller/UserController.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/User.php';

class UserController {
    private $userModel;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->userModel = new User();
    }

    // ====================================
    // AUTHENTIFICATION
    // ====================================

    /**
     * Connexion utilisateur
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $result = $this->loginUser($email, $password);

            if ($result['success']) {
                // Redirection selon le rôle
                if ($result['role'] === 'admin') {
                    redirect('../BackOffice/dashboard.html');
                } else {
                    redirect('Home.html');
                }
            } else {
                return $result;
            }
        }
    }

    /**
     * Logique de connexion
     */
    private function loginUser($email, $password) {
        try {
            $user = $this->getUserByEmail($email);

            if (!$user) {
                return ['success' => false, 'message' => "Email ou mot de passe incorrect."];
            }

            if (password_verify($password, $user['password'])) {
                secureSession();
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                return [
                    'success' => true, 
                    'message' => "Connexion réussie.",
                    'role' => $user['role'],
                    'user_id' => $user['id_user']
                ];
            } else {
                return ['success' => false, 'message' => "Email ou mot de passe incorrect."];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => "Erreur : " . $e->getMessage()];
        }
    }

    /**
     * Déconnexion
     */
    public function logout() {
        secureSession();
        session_unset();
        session_destroy();
        redirect('../FrontOffice/Login.php');
    }

    /**
     * Inscription
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Vérification des mots de passe
            if ($password !== $confirmPassword) {
                return ['success' => false, 'message' => "Les mots de passe ne correspondent pas."];
            }

            // Création du compte (par défaut en tant que 'user')
            $result = $this->createUser($email, $password, 'user');
            
            if ($result['success']) {
                // Connexion automatique après inscription
                $this->loginUser($email, $password);
                redirect('Home.html');
            }

            return $result;
        }
    }

    // ====================================
    // CRUD - CREATE
    // ====================================

    /**
     * Crée un nouvel utilisateur
     */
    public function createUser($email = null, $password = null, $role = 'user') {
        try {
            // Si appelé depuis un formulaire
            if ($email === null && $_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isAdmin()) {
                    redirect('../FrontOffice/Login.php');
                }
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? 'user';
            }

            // Validation
            $emailValid = $this->userModel->validateEmail($email);
            if ($emailValid !== true) {
                return ['success' => false, 'message' => $emailValid];
            }

            $passwordValid = $this->userModel->validatePassword($password);
            if ($passwordValid !== true) {
                return ['success' => false, 'message' => $passwordValid];
            }

            $roleValid = $this->userModel->validateRole($role);
            if ($roleValid !== true) {
                return ['success' => false, 'message' => $roleValid];
            }

            // Vérifier si l'email existe déjà
            if ($this->emailExists($email)) {
                return ['success' => false, 'message' => "Cet email est déjà utilisé."];
            }

            // Hashage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insertion
            $db = $this->userModel->getDb();
            $sql = "INSERT INTO users (email, password, role) VALUES (:email, :password, :role)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                return [
                    'success' => true, 
                    'message' => "Utilisateur créé avec succès.",
                    'id' => $db->lastInsertId()
                ];
            } else {
                return ['success' => false, 'message' => "Erreur lors de la création."];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => "Erreur : " . $e->getMessage()];
        }
    }

    // ====================================
    // CRUD - READ
    // ====================================

    /**
     * Liste tous les utilisateurs
     */
    public function listUsers() {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }
        return $this->getAllUsers();
    }

    /**
     * Récupère tous les utilisateurs
     */
    private function getAllUsers() {
        try {
            $db = $this->userModel->getDb();
            $sql = "SELECT id_user, email, role, created_at, updated_at FROM users ORDER BY created_at DESC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Récupère un utilisateur par ID
     */
    public function getUser($id) {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }
        return $this->getUserById($id);
    }

    /**
     * Récupère un utilisateur par ID
     */
    private function getUserById($id) {
        try {
            $db = $this->userModel->getDb();
            $sql = "SELECT id_user, email, password, role, created_at, updated_at FROM users WHERE id_user = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Récupère un utilisateur par email
     */
    private function getUserByEmail($email) {
        try {
            $db = $this->userModel->getDb();
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
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
     * Met à jour un utilisateur
     */
    public function updateUser($id = null) {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $id ?? $_POST['id'];
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            return $this->updateUserData($id, $email, $role, $password);
        }
    }

    /**
     * Logique de mise à jour
     */
    private function updateUserData($id, $email, $role, $password = null) {
        try {
            // Validation
            $emailValid = $this->userModel->validateEmail($email);
            if ($emailValid !== true) {
                return ['success' => false, 'message' => $emailValid];
            }

            $roleValid = $this->userModel->validateRole($role);
            if ($roleValid !== true) {
                return ['success' => false, 'message' => $roleValid];
            }

            // Vérifier si l'email existe déjà pour un autre utilisateur
            $existingUser = $this->getUserByEmail($email);
            if ($existingUser && $existingUser['id_user'] != $id) {
                return ['success' => false, 'message' => "Cet email est déjà utilisé."];
            }

            $db = $this->userModel->getDb();

            // Mise à jour avec ou sans mot de passe
            if ($password !== null && !empty($password)) {
                $passwordValid = $this->userModel->validatePassword($password);
                if ($passwordValid !== true) {
                    return ['success' => false, 'message' => $passwordValid];
                }
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET email = :email, role = :role, password = :password WHERE id_user = :id";
            } else {
                $sql = "UPDATE users SET email = :email, role = :role WHERE id_user = :id";
            }

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($password !== null && !empty($password)) {
                $stmt->bindParam(':password', $hashedPassword);
            }

            if ($stmt->execute()) {
                return ['success' => true, 'message' => "Utilisateur mis à jour avec succès."];
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
     * Supprime un utilisateur
     */
    public function deleteUser($id) {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }

        // Empêcher la suppression de son propre compte
        secureSession();
        if ($id == $_SESSION['user_id']) {
            return ['success' => false, 'message' => "Vous ne pouvez pas supprimer votre propre compte."];
        }

        try {
            $db = $this->userModel->getDb();
            $sql = "DELETE FROM users WHERE id_user = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => "Utilisateur supprimé avec succès."];
            } else {
                return ['success' => false, 'message' => "Erreur lors de la suppression."];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => "Erreur : " . $e->getMessage()];
        }
    }

    // ====================================
    // STATISTIQUES
    // ====================================

    /**
     * Récupère des statistiques sur les utilisateurs
     */
    public function getStats() {
        if (!isAdmin()) {
            redirect('../FrontOffice/Login.php');
        }

        return [
            'total' => $this->countUsers(),
            'admins' => $this->countUsersByRole('admin'),
            'users' => $this->countUsersByRole('user')
        ];
    }

    /**
     * Compte le nombre total d'utilisateurs
     */
    private function countUsers() {
        try {
            $db = $this->userModel->getDb();
            $sql = "SELECT COUNT(*) as total FROM users";
            $stmt = $db->query($sql);
            $result = $stmt->fetch();
            return $result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Compte les utilisateurs par rôle
     */
    private function countUsersByRole($role) {
        try {
            $db = $this->userModel->getDb();
            $sql = "SELECT COUNT(*) as total FROM users WHERE role = :role";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    // ====================================
    // MÉTHODES UTILITAIRES
    // ====================================

    /**
     * Vérifie si un email existe déjà
     */
    private function emailExists($email) {
        $user = $this->getUserByEmail($email);
        return $user !== false && $user !== null;
    }

    /**
     * Vérifie si un email existe (pour validation AJAX)
     */
    public function checkEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $exists = $this->emailExists($email);
            
            header('Content-Type: application/json');
            echo json_encode(['exists' => $exists]);
            exit;
        }
    }

    // ====================================
    // GESTION DU PROFIL UTILISATEUR
    // ====================================

    /**
     * Affiche le profil de l'utilisateur connecté
     */
    public function viewProfile() {
        if (!isLoggedIn()) {
            redirect('../FrontOffice/Login.php');
        }

        secureSession();
        return $this->getUserById($_SESSION['user_id']);
    }

    /**
     * Met à jour le profil de l'utilisateur connecté
     */
    public function updateProfile() {
        if (!isLoggedIn()) {
            redirect('../FrontOffice/Login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            secureSession();
            $id = $_SESSION['user_id'];
            $email = $_POST['email'] ?? '';
            $currentRole = $_SESSION['role'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            $result = $this->updateUserData($id, $email, $currentRole, $password);
            
            if ($result['success']) {
                $_SESSION['user_email'] = $email;
            }

            return $result;
        }
    }

    /**
     * Change le mot de passe de l'utilisateur connecté
     */
    public function changePassword() {
        if (!isLoggedIn()) {
            redirect('../FrontOffice/Login.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            secureSession();
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Vérifier le mot de passe actuel
            $user = $this->getUserById($_SESSION['user_id']);
            if (!password_verify($currentPassword, $user['password'])) {
                return ['success' => false, 'message' => "Le mot de passe actuel est incorrect."];
            }

            // Vérifier la correspondance des nouveaux mots de passe
            if ($newPassword !== $confirmPassword) {
                return ['success' => false, 'message' => "Les nouveaux mots de passe ne correspondent pas."];
            }

            // Mettre à jour le mot de passe
            $result = $this->updateUserData(
                $_SESSION['user_id'],
                $_SESSION['user_email'],
                $_SESSION['role'],
                $newPassword
            );

            return $result;
        }
    }
}

// ====================================
// GESTION DES ACTIONS
// ====================================

// Instanciation du controller
$controller = new UserController();

// Gestion des actions via GET
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'logout':
            $controller->logout();
            break;

        case 'list':
            $users = $controller->listUsers();
            break;

        case 'get':
            if (isset($_GET['id'])) {
                $user = $controller->getUser($_GET['id']);
            }
            break;

        case 'delete':
            if (isset($_GET['id'])) {
                $result = $controller->deleteUser($_GET['id']);
                if ($result['success']) {
                    redirect('../BackOffice/ListerUsers.php?success=deleted');
                } else {
                    redirect('../BackOffice/ListerUsers.php?error=' . urlencode($result['message']));
                }
            }
            break;

        case 'stats':
            $stats = $controller->getStats();
            break;

        case 'checkEmail':
            $controller->checkEmail();
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
            case 'login':
                $result = $controller->login();
                break;

            case 'register':
                $result = $controller->register();
                break;

            case 'create':
                $result = $controller->createUser();
                if ($result['success']) {
                    redirect('../BackOffice/ListerUsers.php?success=created');
                }
                break;

            case 'update':
                if (isset($_POST['id'])) {
                    $result = $controller->updateUser($_POST['id']);
                    if ($result['success']) {
                        redirect('../BackOffice/ListerUsers.php?success=updated');
                    }
                }
                break;

            case 'updateProfile':
                $result = $controller->updateProfile();
                break;

            case 'changePassword':
                $result = $controller->changePassword();
                break;

            default:
                break;
        }
    }
}
?>