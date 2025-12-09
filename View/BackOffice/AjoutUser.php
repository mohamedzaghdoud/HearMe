<?php
/**
 * Page d'ajout d'utilisateur - HearMe
 * Emplacement : MON PROJET/View/BackOffice/AjoutUser.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/UserController.php';

secureSession();

if (!isAdmin()) {
    redirect('../FrontOffice/Login.php');
}

$error = '';
$success = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();
    $result = $controller->createUser();
    
    if ($result['success']) {
        redirect('ListerUsers.php?success=created');
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
    <title>Ajouter un utilisateur - HearMe Admin</title>
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

        .password-requirements {
            background: #F5F5F5;
            padding: 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-top: 8px;
            color: #666;
        }

        .password-requirements ul {
            margin: 8px 0 0 20px;
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
                    <h1>Ajouter un utilisateur</h1>
                    <p>Créer un nouveau compte utilisateur</p>
                </div>

                <div class="form-container">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="createUserForm">
                        <div class="form-group">
                            <label for="email">Email <span style="color: #D32F2F;">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="utilisateur@exemple.com"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe <span style="color: #D32F2F;">*</span></label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Mot de passe sécurisé"
                            >
                            <div class="password-requirements">
                                <strong>Exigences du mot de passe :</strong>
                                <ul>
                                    <li>Au moins 8 caractères</li>
                                    <li>Au moins une majuscule (A-Z)</li>
                                    <li>Au moins une minuscule (a-z)</li>
                                    <li>Au moins un chiffre (0-9)</li>
                                    <li>Au moins un caractère spécial (!@#$%...)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle <span style="color: #D32F2F;">*</span></label>
                            <select id="role" name="role">
                                <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] === 'user') ? 'selected' : ''; ?>>
                                    Utilisateur
                                </option>
                                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>
                                    Administrateur
                                </option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                ✅ Créer l'utilisateur
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
        // Validation côté client
        document.getElementById('createUserForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
                return false;
            }

            // Validation email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Veuillez entrer un email valide.');
                return false;
            }

            // Validation mot de passe
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

            return true;
        });
    </script>
</body>
</html>