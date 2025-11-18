<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../FrontOffice/login.php'); exit;
}
?>
<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>Admin Dashboard</title>
<link rel="stylesheet" href="assets/css/admin.css"></head>
<body>
  <div class="wrap">
    <h1>Admin Dashboard</h1>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
    <p><a href="admin-users.php" class="btn">Gérer utilisateurs</a> | <a href="logout.php">Se déconnecter</a></p>
  </div>
</body>
</html>
