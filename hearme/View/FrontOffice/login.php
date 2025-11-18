<?php
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: ../BackOffice/user-dashboard.php'); exit;
}
$errors = [];
if (!empty($_GET['errors'])) $errors = json_decode(urldecode($_GET['errors']), true) ?: [];
?>
<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>Connexion</title>
<link rel="stylesheet" href="assets/css/style.css"></head>
<body>
  <div class="container formbox">
    <h2>Connexion</h2>
    <?php if ($errors): ?><div class="errors"><?php foreach($errors as $m) echo '<div>'.htmlspecialchars($m).'</div>'; ?></div><?php endif; ?>
    <form action="../../actions/login-action.php" method="post" novalidate id="loginForm">
      <label>Email:<input type="text" name="email" id="email"></label>
      <label>Mot de passe:<input type="password" name="password" id="password"></label>
      <button type="submit">Se connecter</button>
    </form>
    <p><a href="register.php">S'inscrire</a> | <a href="forgot-password.php">Mot de passe oublié</a></p>
  </div>
  <script src="assets/js/main.js"></script>
</body>
</html>
