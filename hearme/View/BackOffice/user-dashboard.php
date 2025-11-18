<?php
session_start();
if (empty($_SESSION['user'])) header('Location: ../FrontOffice/login.php');
?>
<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>Mon espace</title>
<link rel="stylesheet" href="assets/css/admin.css"></head>
<body>
  <div class="wrap">
    <h1>Bienvenue <?= htmlspecialchars($_SESSION['user']['username']) ?></h1>
    <p><a href="user-profile.php">Mon profil</a> | <a href="logout.php">Se déconnecter</a></p>
  </div>
</body>
</html>
