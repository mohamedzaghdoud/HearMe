<?php
/**
 * Modification de profil (Admin) - HearMe
 * Emplacement : MON PROJET/View/BackOffice/ModifierProfil.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/ProfilController.php';

secureSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$controller = new ProfilController();
$error = '';
$success = '';

// Récupérer l'ID du profil
if (!isset($_GET['id'])) {
    redirect('ListerProfil.php');
}

$id = $_GET['id'];
$profil = $controller->getProfil($id);

if (!$profil) {
    redirect('ListerProfil.php?error=Profil non trouvé');
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['id'] = $id;
    $_POST['action'] = 'update';
    $result = $controller->updateProfil($id);
    
    if ($result['success']) {
        $success = $result['message'];
        $profil = $controller->getProfil($id);
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil - HearMe Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-3 sidebar">
                <div class="sidebar-header">
                    <h2>🎧 HearMe</h2>
                    <p>Administration</p>
                </div>
                <nav class="sidebar-menu">
                    <a href="dashboard.html" class="menu-item">
                        <span class="icon">📊</span>
                        <span class="text">Dashboard</span>
                    </a>
                    <a href="ListerUsers.php" class="menu-item">
                        <span class="icon">👥</span>
                        <span class="text">Utilisateurs</span>
                    </a>
                    <a href="ListerProfil.php" class="menu-item active">
                        <span class="icon">👤</span>
                        <span class="text">Profils</span>
                    </a>
                    <a href="logout.php" class="menu-item">
                        <span class="icon">🚪</span>
                        <span class="text">Déconnexion</span>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="col-md-9 main-content">
                <div class="page-header">
                    <h1>Modifier le Profil</h1>
                    <p>Modification du profil de <?php echo htmlspecialchars($profil['email']); ?></p>
                </div>

                <div class="form-container">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="bio">Biographie</label>
                            <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($profil['bio']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="competences">Compétences</label>
                            <textarea id="competences" name="competences" rows="3"><?php echo htmlspecialchars($profil['competences']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="formation">Formation</label>
                            <input type="text" id="formation" name="formation" value="<?php echo htmlspecialchars($profil['formation']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="experience">Expérience</label>
                            <textarea id="experience" name="experience" rows="4"><?php echo htmlspecialchars($profil['experience']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="reseaux_sociaux">Réseaux Sociaux</label>
                            <input type="url" id="reseaux_sociaux" name="reseaux_sociaux" value="<?php echo htmlspecialchars($profil['reseaux_sociaux']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="disponibilite">Disponibilité</label>
                            <select id="disponibilite" name="disponibilite">
                                <option value="disponible" <?php echo $profil['disponibilite'] === 'disponible' ? 'selected' : ''; ?>>Disponible</option>
                                <option value="occupé" <?php echo $profil['disponibilite'] === 'occupé' ? 'selected' : ''; ?>>Occupé</option>
                                <option value="indisponible" <?php echo $profil['disponibilite'] === 'indisponible' ? 'selected' : ''; ?>>Indisponible</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="localisation">Localisation</label>
                            <input type="text" id="localisation" name="localisation" value="<?php echo htmlspecialchars($profil['localisation']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="preferences">Préférences</label>
                            <textarea id="preferences" name="preferences" rows="3"><?php echo htmlspecialchars($profil['preferences']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="autre_theme">Autre Thème</label>
                            <textarea id="autre_theme" name="autre_theme" rows="3"><?php echo htmlspecialchars($profil['autre_theme']); ?></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <a href="ListerProfil.php" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>