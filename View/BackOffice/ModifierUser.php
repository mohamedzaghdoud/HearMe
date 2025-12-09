<?php
/**
 * Page de modification d'utilisateur - HearMe
 * Emplacement : MON PROJET/View/BackOffice/ModifierUser.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

if (!isset($_GET['id'])) {
    redirect('ListerUsers.php?error=' . urlencode('Aucun utilisateur spécifié.'));
}

$id = (int)$_GET['id'];
$controller = new UserController();
$user = $controller->getUser($id);

if (!$user) {
    redirect('ListerUsers.php?error=' . urlencode('Utilisateur introuvable.'));
}

$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->updateUser($id);
    
    if ($result['success']) {
        redirect('ListerUsers.php?success=updated');
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
    <title>Modifier un utilisateur - HearMe Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #5BA8C8;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #E0E0E0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #5BA8C8;
            box-shadow: 0 0 0 3px rgba(91, 168, 200, 0.1);
        }

        .alert {
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #FFE5E5;
            color: #D32F2F;
            border: 1px solid #FFB3B3;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
        }

        .btn-secondary {
            background: #E0E0E0;
            color: #555;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .info-box {
            background: #E8F4F8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #5BA8C8;
        }

        .info-box p {
            margin: 0;
            color: #555;
            font-size: 13px;
        }
    </style>
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
                    <h1>Modifier un utilisateur</h1>
                    <p>Modifier les informations de l'utilisateur</p>
                </div>

                <div class="form-container">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <div class="info-box">
                        <p><strong>ℹ️ Information :</strong> Laissez le champ mot de passe vide si vous ne souhaitez pas le modifier.</p>
                    </div>

                    <form method="POST" action="" id="updateUserForm">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">

                        <div class="form-group">
                            <label for="email">Email <span style="color: #D32F2F;">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="utilisateur@exemple.com"
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Nouveau mot de passe (optionnel)</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Laisser vide pour ne pas modifier"
                            >
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle <span style="color: #D32F2F;">*</span></label>
                            <select id="role" name="role">
                                <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>
                                    Utilisateur
                                </option>
                                <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>
                                    Administrateur
                                </option>
                            </select>
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
                </div>
            </main>
        </div>
    </div>

    <script>
        document.getElementById('updateUserForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email) {
                e.preventDefault();
                alert('L\'email est requis.');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Veuillez entrer un email valide.');
                return false;
            }

            // Validation mot de passe uniquement s'il est rempli
            if (password && password.length > 0) {
                if (password.length < 8) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins 8 caractères.');
                    return false;
                }

                if (!/[A-Z]/.test(password)) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins une majuscule.');
                    return false;
                }

                if (!/[a-z]/.test(password)) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins une minuscule.');
                    return false;
                }

                if (!/[0-9]/.test(password)) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins un chiffre.');
                    return false;
                }

                if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins un caractère spécial.');
                    return false;
                }
            }

            return true;
        });
    </script>
</body>
</html>