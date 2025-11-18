<?php
// Controller/UserController.php
require_once __DIR__ . '/../Model/User.php';

class UserController {

    // ---- validations serveur ----
    private function validateUsername($username) {
        $username = trim($username);
        if ($username === '') return 'Le nom est requis.';
        if (mb_strlen($username) < 3 || mb_strlen($username) > 50) return 'Le nom doit contenir entre 3 et 50 caractères.';
        if (!preg_match('/^[A-Za-z0-9_\- ]+$/u', $username)) return 'Caractères invalides dans le nom.';
        return true;
    }

    private function validateEmail($email) {
        if (trim($email) === '') return 'Email requis.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return 'Email invalide.';
        return true;
    }

    private function validatePassword($pwd) {
        if ($pwd === '') return 'Le mot de passe est requis.';
        if (strlen($pwd) < 8) return 'Le mot de passe doit faire au moins 8 caractères.';
        if (!preg_match('/[A-Z]/', $pwd)) return 'Le mot de passe doit contenir au moins une majuscule.';
        if (!preg_match('/[0-9]/', $pwd)) return 'Le mot de passe doit contenir au moins un chiffre.';
        return true;
    }

    // ----- Front actions -----
    public function register(array $input) {
        $errors = [];

        $u = $input['username'] ?? '';
        $e = $input['email'] ?? '';
        $p = $input['password'] ?? '';
        $pc = $input['password_confirm'] ?? '';

        $v = $this->validateUsername($u); if ($v !== true) $errors['username'] = $v;
        $v = $this->validateEmail($e);    if ($v !== true) $errors['email'] = $v;
        $v = $this->validatePassword($p); if ($v !== true) $errors['password'] = $v;
        if ($p !== $pc) $errors['password_confirm'] = 'La confirmation ne correspond pas.';

        if (!empty($errors)) return ['success'=>false, 'errors'=>$errors];

        if (User::findByEmail($e)) {
            return ['success'=>false, 'errors'=>['email'=>'Email déjà utilisé.']];
        }

        $user = new User(htmlspecialchars($u), htmlspecialchars($e), $p, 'user');
        $id = User::insertUser($user);
        if ($id) return ['success'=>true, 'id'=>$id];
        return ['success'=>false, 'errors'=>['general'=>'Erreur à la création.']];
    }

    public function login(array $input) {
        $errors = [];
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if ($this->validateEmail($email) !== true) $errors['email']='Email invalide';
        if (trim($password) === '') $errors['password']='Mot de passe requis';

        if (!empty($errors)) return ['success'=>false, 'errors'=>$errors];

        $user = User::login($email, $password);
        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            return ['success'=>true, 'user'=>$user];
        } else {
            return ['success'=>false, 'errors'=>['general'=>'Identifiants incorrects']];
        }
    }

    // ----- Admin CRUD -----
    public function getAllUsers() {
    return User::getAllUsers();
}

public function getUser($id) {
    return User::getUserById($id);
}

public function adminAddUser($data) {
    $errors = [];

    if (($v = $this->validateUsername($data['username'])) !== true) 
        $errors['username'] = $v;
    if (($v = $this->validateEmail($data['email'])) !== true) 
        $errors['email'] = $v;
    if (($v = $this->validatePassword($data['password'])) !== true) 
        $errors['password'] = $v;

    if (!empty($errors)) return ['success'=>false, 'errors'=>$errors];

    $user = new User($data['username'], $data['email'], $data['password'], $data['role']);
    $id = User::insertUser($user);

    return ['success'=> (bool)$id ];
}

public function adminUpdateUser($data) {
    $errors = [];

    if (($v = $this->validateUsername($data['username'])) !== true) 
        $errors['username'] = $v;
    if (($v = $this->validateEmail($data['email'])) !== true) 
        $errors['email'] = $v;

    if (!empty($errors)) return ['success'=>false, 'errors'=>$errors];

    $updateData = [
        'username'=>$data['username'],
        'email'=>$data['email'],
        'role'=>$data['role']
    ];

    if (!empty($data['password'])) {
        if (($v = $this->validatePassword($data['password'])) !== true) 
            return ['success'=>false, 'errors'=>['password'=>$v]];
        $updateData['password'] = $data['password'];
    }

    User::updateUser($data['id'], $updateData);
    return ['success'=>true];
}

public function delete($id) {
    return User::deleteUser($id);
}
}