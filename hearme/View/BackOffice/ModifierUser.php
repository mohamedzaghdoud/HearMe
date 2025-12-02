<?php
/**
 * Modification d'utilisateur (Admin) - HearMe
 * Emplacement : MON PROJET/View/BackOffice/ModifierUser.php
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

// Récupérer l'ID de l'utilisateur
if (!isset($_GET['id'])) {
    redirect('ListerUsers.php');
}

$id = $_GET['id'];
$user = $controller->getUser($id);

if (!$user) {
    redirect('ListerUsers.php?error=Utilisateur non trouvé');
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['id'] = $id;
    $_POST['action'] = 'update';
    $result = $controller->updateUser($id);
    
    if ($result['success']) {
        $success = $result['message'];
        $user = $controller->getUser($id);
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
    <title>Modifier Utilisateur - HearMe Admin</title>
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
                    <h1>✏️ Modifier l'Utilisateur</h1>
                    <p>Modification du compte : <?php echo htmlspecialchars($user['email']); ?></p>
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
                        </div>
                    <?php endif; ?>

                    <!-- Informations du compte -->
                    <div style="background: #E8F4F8; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
                        <h3 style="color: #5BA8C8; margin-bottom: 15px; font-size: 16px;">
                            📋 Informations du compte
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; font-size: 14px;">
                            <div>
                                <strong style="color: #5BA8C8;">ID :</strong>
                                <span style="color: #7A7A7A;"><?php echo htmlspecialchars($user['id_user']); ?></span>
                            </div>
                            <div>
                                <strong style="color: #5BA8C8;">Date de création :</strong>
                                <span style="color: #7A7A7A;"><?php echo date('d/m/Y à H:i', strtotime($user['created_at'])); ?></span>
                            </div>
                            <div>
                                <strong style="color: #5BA8C8;">Dernière modification :</strong>
                                <span style="color: #7A7A7A;"><?php echo date('d/m/Y à H:i', strtotime($user['updated_at'])); ?></span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="" id="editUserForm">
                        <div class="form-group">
                            <label for="email">
                                Email <span class="required">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                            >
                            <small>L'email sert d'identifiant de connexion</small>
                        </div>

                        <div class="form-group">
                            <label for="role">
                                Rôle <span class="required">*</span>
                            </label>
                            <select id="role" name="role">
                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>
                                    Utilisateur
                                </option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>
                                    Administrateur
                                </option>
                            </select>
                            <small>Changez le rôle si nécessaire</small>
                        </div>

                        <div style="background: #FFF8E1; padding: 20px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #F57C00;">
                            <h4 style="color: #F57C00; margin-bottom: 10px; font-size: 14px;">
                                ⚠️ Modification du mot de passe
                            </h4>
                            <p style="font-size: 13px; color: #7A7A7A; margin-bottom: 15px;">
                                Laissez vide si vous ne souhaitez pas modifier le mot de passe.
                            </p>
                            
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="password">
                                    Nouveau mot de passe (optionnel)
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Laissez vide pour ne pas modifier"
                                >
                                <small>Minimum 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                💾 Enregistrer les modifications
                            </button>
                            <a href="ListerUsers.php" class="btn btn-secondary">
                                ❌ Annuler
                            </a>
                        </div>
                    </form>

                    <!-- Actions supplémentaires -->
                    <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #E0E0E0;">
                        <h3 style="color: #D32F2F; margin-bottom: 15px; font-size: 16px;">
                            🗑️ Zone de danger
                        </h3>
                        <p style="font-size: 14px; color: #7A7A7A; margin-bottom: 15px;">
                            La suppression d'un utilisateur est irréversible. Toutes ses données seront perdues.
                        </p>
                        <a 
                            href="../../Controller/UserController.php?action=delete&id=<?php echo $user['id_user']; ?>" 
                            class="btn btn-danger"
                            onclick="return confirm('⚠️ ATTENTION !\n\nÊtes-vous absolument sûr de vouloir supprimer cet utilisateur ?\n\nCette action est IRRÉVERSIBLE et supprimera :\n- Le compte utilisateur\n- Son profil\n- Toutes ses données\n\nTapez OK pour confirmer.');"
                        >
                            🗑️ Supprimer cet utilisateur
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Afficher/Masquer le champ mot de passe
        const passwordInput = document.getElementById('password');
        const passwordLabel = passwordInput.previousElementSibling;

        passwordInput.addEventListener('focus', function() {
            this.type = 'text';
            this.placeholder = 'Entrez le nouveau mot de passe';
        });

        passwordInput.addEventListener('blur', function() {
            if (this.value === '') {
                this.type = 'password';
                this.placeholder = 'Laissez vide pour ne pas modifier';
            }
        });
    </script>
</body>
</html>