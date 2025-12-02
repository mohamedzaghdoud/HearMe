<?php
/**
 * Ajout d'utilisateur (Admin) - HearMe
 * Emplacement : MON PROJET/View/BackOffice/AjoutUser.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

// Vérifier si l'utilisateur est admin
if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$controller = new UserController();
$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['action'] = 'create';
    $result = $controller->createUser();
    
    if ($result['success']) {
        $success = $result['message'];
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
    <title>Ajouter un Utilisateur - HearMe Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/userValidation.js" defer></script>
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
                    <a href="ListerUsers.php" class="menu-item active">
                        <span class="icon">👥</span>
                        <span class="text">Utilisateurs</span>
                    </a>
                    <a href="ListerProfil.php" class="menu-item">
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
                    <h1>➕ Ajouter un Utilisateur</h1>
                    <p>Créer un nouveau compte utilisateur</p>
                </div>

                <div class="form-container">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            ❌ <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            ✅ <?php echo htmlspecialchars($success); ?>
                            <br>
                            <a href="ListerUsers.php" style="color: #2E7D32; font-weight: 600;">
                                → Retour à la liste des utilisateurs
                            </a>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="addUserForm">
                        <div class="form-group">
                            <label for="email">
                                Email <span class="required">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="exemple@hearme.com"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            >
                            <small>L'email sera utilisé pour la connexion</small>
                        </div>

                        <div class="form-group">
                            <label for="password">
                                Mot de passe <span class="required">*</span>
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Mot de passe sécurisé"
                            >
                            <small>Minimum 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial</small>
                        </div>

                        <div class="form-group">
                            <label for="role">
                                Rôle <span class="required">*</span>
                            </label>
                            <select id="role" name="role">
                                <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : ''; ?>>
                                    Utilisateur
                                </option>
                                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>
                                    Administrateur
                                </option>
                            </select>
                            <small>Les administrateurs ont accès au BackOffice</small>
                        </div>

                        <div style="background: #F5F5F5; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                            <h4 style="color: #5BA8C8; margin-bottom: 10px; font-size: 14px;">
                                ℹ️ Informations importantes
                            </h4>
                            <ul style="margin-left: 20px; font-size: 13px; color: #7A7A7A;">
                                <li>L'utilisateur recevra ses identifiants par email (fonctionnalité à implémenter)</li>
                                <li>Il pourra modifier son mot de passe après sa première connexion</li>
                                <li>Les rôles peuvent être modifiés ultérieurement</li>
                            </ul>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                ✅ Créer l'utilisateur
                            </button>
                            <a href="ListerUsers.php" class="btn btn-secondary">
                                ❌ Annuler
                            </a>
                        </div>
                    </form>
                </div>

                <div style="background: white; border-radius: 15px; padding: 25px; margin-top: 30px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);">
                    <h3 style="color: #5BA8C8; margin-bottom: 15px;">💡 Conseils pour un mot de passe sécurisé</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div>
                            <h4 style="color: #2E7D32; font-size: 14px; margin-bottom: 8px;">✅ À faire</h4>
                            <ul style="margin-left: 20px; font-size: 13px; color: #7A7A7A;">
                                <li>Utiliser au moins 12 caractères</li>
                                <li>Mélanger majuscules et minuscules</li>
                                <li>Inclure des chiffres et symboles</li>
                                <li>Utiliser une phrase secrète</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: #D32F2F; font-size: 14px; margin-bottom: 8px;">❌ À éviter</h4>
                            <ul style="margin-left: 20px; font-size: 13px; color: #7A7A7A;">
                                <li>Informations personnelles</li>
                                <li>Mots du dictionnaire</li>
                                <li>Suites de caractères (123456)</li>
                                <li>Réutiliser d'anciens mots de passe</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>