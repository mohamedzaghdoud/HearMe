<?php
$errors = [];
if (!empty($_GET['errors'])) $errors = json_decode(urldecode($_GET['errors']), true) ?: [];
?>
<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>S'inscrire</title>
<link rel="stylesheet" href="assets/css/style.css"></head>
<body>
  <div class="container formbox">
    <h2>Inscription</h2>
    <?php if ($errors): ?><div class="errors"><?php foreach($errors as $m) echo '<div>'.htmlspecialchars($m).'</div>'; ?></div><?php endif; ?>
    <form action="../../actions/register-action.php" method="post" novalidate id="registerForm">
      <label>Nom d'utilisateur:<input type="text" name="username" id="username"></label>
      <label>Email:<input type="text" name="email" id="email"></label>
      <label>Mot de passe:<input type="password" name="password" id="password"></label>
      <label>Confirmer mot de passe:<input type="password" name="password_confirm" id="password_confirm"></label>
      <button type="submit">S'inscrire</button>
    </form>
    <p><a href="login.php">J'ai déjà un compte</a></p>
  </div>
  <script src="assets/js/main.js"></script>
</body>
</html>
