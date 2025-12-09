<?php
/**
 * Modèle User - CORRIGÉ - HearMe
 * Emplacement : MON PROJET/Model/User.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/EmailService.php';

class User {
    private $db;
    private $id_user;
    private $email;
    private $password;
    private $role;
    private $email_verified;
    private $verification_token;
    private $created_at;
    private $updated_at;

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

    public function getEmailVerified() {
        return $this->email_verified;
    }

    public function getDb() {
        return $this->db;
    }

    // ====================================
    // VALIDATION
    // ====================================

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

    public function validateRole($role) {
        $allowedRoles = ['user', 'admin'];
        if (!in_array($role, $allowedRoles)) {
            return "Le rôle doit être 'user' ou 'admin'.";
        }
        return true;
    }

    // ====================================
    // VÉRIFICATION EMAIL
    // ====================================

    /**
     * ✅ CORRECTION : Utilise la méthode statique
     */
    public function sendVerificationEmail($userId, $email) {
        try {
            $emailService = new EmailService();
            $token = bin2hex(random_bytes(32)); // ✅ CORRECTION
            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

            $sql = "UPDATE users SET verification_token = :token, verification_expires = :expires WHERE id_user = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':token' => $token,
                ':expires' => $expires,
                ':id' => $userId
            ]);

            return $emailService->sendVerificationEmail($email, $token);
        } catch (PDOException $e) {
            error_log("Erreur sendVerificationEmail: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie un token de vérification email
     */
    public function verifyEmailToken($token) {
        try {
            $sql = "SELECT id_user, email, verification_expires FROM users 
                    WHERE verification_token = :token AND email_verified = FALSE";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':token' => $token]);
            $user = $stmt->fetch();

            if (!$user) {
                return ['success' => false, 'message' => 'Token invalide ou déjà utilisé.'];
            }

            // Vérifier l'expiration
            if (strtotime($user['verification_expires']) < time()) {
                return ['success' => false, 'message' => 'Ce lien a expiré.'];
            }

            // Marquer comme vérifié
            $sql = "UPDATE users SET email_verified = TRUE, verification_token = NULL, verification_expires = NULL 
                    WHERE id_user = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $user['id_user']]);

            return ['success' => true, 'message' => 'Email vérifié avec succès !', 'email' => $user['email']];
        } catch (PDOException $e) {
            error_log("Erreur verifyEmailToken: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la vérification.'];
        }
    }

    // ====================================
    // RÉINITIALISATION MOT DE PASSE
    // ====================================

    public function sendPasswordResetEmail($email) {
        try {
            // Vérifier si l'email existe
            $sql = "SELECT id_user FROM users WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if (!$user) {
                // Pour la sécurité, on ne révèle pas si l'email existe
                return ['success' => true, 'message' => 'Si cet email existe, un lien de réinitialisation a été envoyé.'];
            }

            // Générer le token
            $token = bin2hex(random_bytes(32)); // ✅ CORRECTION
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Enregistrer dans password_resets
            $sql = "INSERT INTO password_resets (user_id, reset_token, expires_at) VALUES (:user_id, :token, :expires)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $user['id_user'],
                ':token' => $token,
                ':expires' => $expires
            ]);

            // Envoyer l'email
            $emailService = new EmailService();
            $emailService->sendPasswordResetEmail($email, $token);

            return ['success' => true, 'message' => 'Un email de réinitialisation a été envoyé.'];
        } catch (PDOException $e) {
            error_log("Erreur sendPasswordResetEmail: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de l\'envoi.'];
        }
    }

    public function verifyResetToken($token) {
        try {
            $sql = "SELECT pr.*, u.email FROM password_resets pr
                    JOIN users u ON pr.user_id = u.id_user
                    WHERE pr.reset_token = :token AND pr.used = FALSE AND pr.expires_at > NOW()";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':token' => $token]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur verifyResetToken: " . $e->getMessage());
            return null;
        }
    }

    public function resetPassword($token, $newPassword) {
        try {
            $resetData = $this->verifyResetToken($token);
            if (!$resetData) {
                return ['success' => false, 'message' => 'Token invalide ou expiré.'];
            }

            // Valider le nouveau mot de passe
            $valid = $this->validatePassword($newPassword);
            if ($valid !== true) {
                return ['success' => false, 'message' => $valid];
            }

            // Hasher le nouveau mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Mettre à jour le mot de passe
            $sql = "UPDATE users SET password = :password WHERE id_user = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':password' => $hashedPassword,
                ':id' => $resetData['user_id']
            ]);

            // Marquer le token comme utilisé
            $sql = "UPDATE password_resets SET used = TRUE WHERE reset_token = :token";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':token' => $token]);

            return ['success' => true, 'message' => 'Mot de passe réinitialisé avec succès !'];
        } catch (PDOException $e) {
            error_log("Erreur resetPassword: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la réinitialisation.'];
        }
    }
}
?>