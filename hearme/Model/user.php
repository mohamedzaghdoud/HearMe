<?php
// Model/User.php
require_once __DIR__ . '/../config.php';

class User {
    public $id;
    public $username;
    public $email;
    public $password; // hashed
    public $role;
    public $created_at;

    public function __construct($username, $email, $password, $role = 'user') {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->created_at = date('Y-m-d H:i:s');
    }

    public static function insertUser(User $u) {
        $db = Config::connect();
        // unique email check
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$u->email]);
        if ($stmt->fetchColumn() > 0) return false;

        $stmt = $db->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, ?)");
        $hashed = password_hash($u->password, PASSWORD_DEFAULT);
        $res = $stmt->execute([$u->username, $u->email, $hashed, $u->role, $u->created_at]);
        if ($res) return $db->lastInsertId();
        return false;
    }

    public static function getAllUsers() {
        $db = Config::connect();
        $stmt = $db->query("SELECT id, username, email, role, created_at FROM users ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public static function getUserById($id) {
        $db = Config::connect();
        $stmt = $db->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findByEmail($email) {
        $db = Config::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function updateUser($id, $data) {
        $db = Config::connect();
        $fields = [];
        $values = [];
        if (isset($data['username'])) { $fields[] = 'username = ?'; $values[] = $data['username']; }
        if (isset($data['email']))    { $fields[] = 'email = ?';    $values[] = $data['email']; }
        if (isset($data['role']))     { $fields[] = 'role = ?';     $values[] = $data['role']; }
        if (isset($data['password'])) { $fields[] = 'password = ?'; $values[] = password_hash($data['password'], PASSWORD_DEFAULT); }

        if (count($fields) === 0) return false;

        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute($values);
    }

    public static function deleteUser($id) {
        $db = Config::connect();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function login($email, $password) {
        $db = Config::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }
}
