<?php
/**
 * Configuration de la base de données - Projet HearMe
 * Fichier à placer à la racine du projet
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'hearme_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Fuseau horaire
date_default_timezone_set('Africa/Tunis');

// Gestion des erreurs en développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Classe de connexion à la base de données
 */
class Database {
    private static $instance = null;
    private $connection;

    /**
     * Constructeur privé pour le pattern Singleton
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
        // Ensure there are usable test accounts in dev setups (fixes placeholder hashes in database.sql)
        try {
            $this->ensureDefaultAccounts();
        } catch (Exception $e) {
            // silently ignore seeding errors - not fatal for the app
        }
    }

    /**
     * Récupère l'instance unique de la classe
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Récupère la connexion PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Ensure default admin and user accounts exist and do not contain placeholder password hashes.
     * This helps development setups that import a SQL file containing placeholders.
     */
    private function ensureDefaultAccounts() {
        if (!$this->connection) return;

        $placeholder = '$2y$10$YourHashedPasswordHere';

        // Check if users table exists and how many rows
        try {
            $stmt = $this->connection->query("SELECT COUNT(*) as c FROM users");
            $count = (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            // table missing or other error — nothing to do
            return;
        }

        // Default credentials (development only)
        $defaults = [
            ['email' => 'admin@hearme.com', 'password' => 'Admin123!', 'role' => 'admin'],
            ['email' => 'user@hearme.com', 'password' => 'User123!', 'role' => 'user']
        ];

        // If DB empty, insert both
        if ($count === 0) {
            $ins = $this->connection->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
            foreach ($defaults as $d) {
                $ins->execute(['email' => $d['email'], 'password' => password_hash($d['password'], PASSWORD_DEFAULT), 'role' => $d['role']]);
            }
            return;
        }

        // Replace placeholder hashes if present
        $check = $this->connection->prepare("SELECT id_user, email FROM users WHERE password = :ph");
        $check->execute(['ph' => $placeholder]);
        $rows = $check->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            $upd = $this->connection->prepare("UPDATE users SET password = :password, updated_at = NOW() WHERE id_user = :id");
            foreach ($rows as $r) {
                $email = strtolower($r['email']);
                $pw = 'User123!';
                if ($email === 'admin@hearme.com') $pw = 'Admin123!';
                $hash = password_hash($pw, PASSWORD_DEFAULT);
                $upd->execute(['password' => $hash, 'id' => $r['id_user']]);
            }
        }
    }

    /**
     * Empêche le clonage de l'instance
     */
    private function __clone() {}

    /**
     * Empêche la désérialisation de l'instance
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Fonction helper pour récupérer la connexion
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

/**
 * Démarre une session sécurisée si elle n'est pas déjà démarrée
 */
function secureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Mettre à 1 en HTTPS
        session_start();
    }
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn() {
    secureSession();
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

/**
 * Vérifie si l'utilisateur est admin
 */
function isAdmin() {
    secureSession();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Redirige vers une page
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Échappe les données HTML
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Génère un token CSRF
 */
function generateCSRFToken() {
    secureSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF
 */
function verifyCSRFToken($token) {
    secureSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>