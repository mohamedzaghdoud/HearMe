<?php
$errors = $_GET['errors'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<h1>Ajouter un utilisateur</h1>

<form action="createUser.php" method="POST">

    <label>Nom d'utilisateur :</label>
    <input type="text" name="username">
    <div class="error"><?= $errors['username'] ?? '' ?></div>

    <label>Email :</label>
    <input type="text" name="email">
    <div class="error"><?= $errors['email'] ?? '' ?></div>

    <label>Mot de passe :</label>
    <input type="password" name="password">
    <div class="error"><?= $errors['password'] ?? '' ?></div>

    <label>Rôle :</label>
    <select name="role">
        <option value="user">Utilisateur</option>
        <option value="admin">Admin</option>
    </select>

    <button type="submit" class="btn btn-add">Créer</button>
</form>

</body>
</html>
