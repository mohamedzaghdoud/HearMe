<?php
require_once __DIR__ . '/../../Controller/UserController.php';
$controller = new UserController();
$users = $controller->getAllUsers();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<h1>Liste des utilisateurs</h1>

<a href="addUser.php" class="btn btn-add">+ Ajouter un utilisateur</a>

<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['role'] ?></td>
        <td>
            <a href="updateUser.php?id=<?= $u['id'] ?>" class="btn btn-edit">Modifier</a>
            <a href="deleteUserAction.php?id=<?= $u['id'] ?>"
               onclick="return confirm('Supprimer cet utilisateur ?');"
               class="btn btn-delete">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
