<?php
require_once __DIR__ . '/../../Controller/UserController.php';

$controller = new UserController();
$user = $controller->getUser($_GET['id']);

$errors = $_GET['errors'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier utilisateur</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<h1>Modifier l’utilisateur</h1>

<form action="updateUserAction.php" method="POST">

    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <label>Nom :</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>">
    <div class="error"><?= $errors['username'] ?? '' ?></div>

    <label>Email :</label>
    <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>">
    <div class="error"><?= $errors['email'] ?? '' ?></div>

    <label>Rôle :</label>
    <select name="role">
        <option value="user" <?= $user['role']=='user'?'selected':'' ?>>Utilisateur</option>
        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
    </select>

    <label>Nouveau mot de passe (optionnel) :</label>
    <input type="password" name="password">

    <button type="submit" class="btn btn-edit">Mettre à jour</button>
</form>

</body>
</html>
