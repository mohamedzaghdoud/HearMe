<?php
session_start();
if (empty($_SESSION['user'])) header('Location: ../FrontOffice/login.php');
require_once __DIR__ . '/../../Controller/UserController.php';
$ctrl = new UserController();
$user = $ctrl->getUser($_SESSION['user']['id']);
?>
<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>Profil</title>
<link rel="stylesheet" href="assets/css/admin.css"></head>
<body>
  <div class="wrap formbox">
    <h2>Profil</h2>
    <p>Nom: <?= htmlspecialchars($user['username']) ?></p>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Rôle: <?= htmlspecialchars($user['role']) ?></p>
    <p><a href="user-dashboard.php">Retour</a></p>
  </div>
</body>
</html>
