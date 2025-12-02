<?php
/**
 * Page de gestion de profil utilisateur - HearMe
 * Emplacement : MON PROJET/View/FrontOffice/Profilfront.php
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controller/ProfilController.php';

secureSession();

// Vérifier si l'utilisateur est connecté
if (!isLoggedIn()) {
    redirect('Login.php');
}

$controller = new ProfilController();
$error = '';
$success = '';

// Récupérer le profil existant
$profil = $controller->getMyProfil();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'createOrUpdateMyProfil') {
    $result = $controller->createOrUpdateMyProfil();
    
    if ($result['success']) {
        $success = $result['message'];
        // Recharger le profil
        $profil = $controller->getMyProfil();
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
    <title>Mon Profil - HearMe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #E8F4F8 0%, #B8E6F0 50%, #D4E8F0 100%);
            min-height: 100vh;
            padding-bottom: 50px;
        }

        /* Navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: #5BA8C8;
            text-decoration: none;
        }

        .navbar-menu {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar-menu a {
            color: #5BA8C8;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .navbar-menu a:hover {
            background-color: #E8F4F8;
        }

        /* Container */
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            color: #5BA8C8;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #7A7A7A;
            font-size: 16px;
        }

        /* Form */
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #FFE5E5;
            color: #D32F2F;
            border: 1px solid #FFB3B3;
        }

        .alert-success {
            background-color: #E5F7E5;
            color: #2E7D32;
            border: 1px solid #B3E5B3;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #5BA8C8;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group label .required {
            color: #FF6B6B;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #E0E0E0;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #5BA8C8;
            box-shadow: 0 0 0 3px rgba(91, 168, 200, 0.1);
        }

        .form-group small {
            display: block;
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #5BA8C8 0%, #7AC5E0 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 168, 200, 0.3);
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            color: #5BA8C8;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .navbar-menu {
                flex-direction: column;
                width: 100%;
            }

            .form-container {
                padding: 25px;
            }

            .page-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="Home.html" class="navbar-brand">🎧 HearMe</a>
        <div class="navbar-menu">
            <a href="Home.html">Accueil</a>
            <a href="Profilfront.php">Mon Profil</a>
            <a href="../BackOffice/logout.php">Se déconnecter</a>
        </div>
    </nav>

    <!-- Container -->
    <div class="container">
        <div class="page-header">
            <h1>👤 Mon Profil</h1>
            <p>Créez ou modifiez votre profil professionnel</p>
        </div>

        <div class="form-container">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="profilForm">
                <input type="hidden" name="action" value="createOrUpdateMyProfil">

                <!-- Bio -->
                <div class="form-group">
                    <label for="bio">Biographie</label>
                    <textarea 
                        id="bio" 
                        name="bio" 
                        placeholder="Parlez-nous de vous..."
                    ><?php echo $profil ? htmlspecialchars($profil['bio']) : ''; ?></textarea>
                    <small>Maximum 1000 caractères</small>
                </div>

                <!-- Compétences -->
                <div class="form-group">
                    <label for="competences">Compétences</label>
                    <textarea 
                        id="competences" 
                        name="competences" 
                        placeholder="Ex: Écoute active, Empathie, Communication..."
                    ><?php echo $profil ? htmlspecialchars($profil['competences']) : ''; ?></textarea>
                    <small>Séparez les compétences par des virgules (500 caractères max)</small>
                </div>

                <!-- Formation -->
                <div class="form-group">
                    <label for="formation">Formation</label>
                    <input 
                        type="text" 
                        id="formation" 
                        name="formation" 
                        placeholder="Ex: Master en Psychologie"
                        value="<?php echo $profil ? htmlspecialchars($profil['formation']) : ''; ?>"
                    >
                    <small>Maximum 255 caractères</small>
                </div>

                <!-- Expérience -->
                <div class="form-group">
                    <label for="experience">Expérience Professionnelle</label>
                    <textarea 
                        id="experience" 
                        name="experience" 
                        placeholder="Décrivez votre expérience..."
                    ><?php echo $profil ? htmlspecialchars($profil['experience']) : ''; ?></textarea>
                    <small>Maximum 1000 caractères</small>
                </div>

                <!-- Réseaux Sociaux -->
                <div class="form-group">
                    <label for="reseaux_sociaux">Réseaux Sociaux / Portfolio</label>
                    <input 
                        type="url" 
                        id="reseaux_sociaux" 
                        name="reseaux_sociaux" 
                        placeholder="https://linkedin.com/in/votreprofil"
                        value="<?php echo $profil ? htmlspecialchars($profil['reseaux_sociaux']) : ''; ?>"
                    >
                    <small>URL complète (ex: https://...)</small>
                </div>

                <!-- Disponibilité -->
                <div class="form-group">
                    <label for="disponibilite">Disponibilité</label>
                    <select id="disponibilite" name="disponibilite">
                        <option value="disponible" <?php echo ($profil && $profil['disponibilite'] === 'disponible') ? 'selected' : ''; ?>>
                            Disponible
                        </option>
                        <option value="occupé" <?php echo ($profil && $profil['disponibilite'] === 'occupé') ? 'selected' : ''; ?>>
                            Occupé
                        </option>
                        <option value="indisponible" <?php echo ($profil && $profil['disponibilite'] === 'indisponible') ? 'selected' : ''; ?>>
                            Indisponible
                        </option>
                    </select>
                </div>

                <!-- Localisation -->
                <div class="form-group">
                    <label for="localisation">Localisation</label>
                    <input 
                        type="text" 
                        id="localisation" 
                        name="localisation" 
                        placeholder="Ex: Paris, France"
                        value="<?php echo $profil ? htmlspecialchars($profil['localisation']) : ''; ?>"
                    >
                    <small>Ville, Pays (255 caractères max)</small>
                </div>

                <!-- Préférences -->
                <div class="form-group">
                    <label for="preferences">Préférences / Centres d'intérêt</label>
                    <textarea 
                        id="preferences" 
                        name="preferences" 
                        placeholder="Vos préférences, domaines d'intérêt..."
                    ><?php echo $profil ? htmlspecialchars($profil['preferences']) : ''; ?></textarea>
                    <small>Maximum 500 caractères</small>
                </div>

                <!-- Autre Thème -->
                <div class="form-group">
                    <label for="autre_theme">Informations Complémentaires</label>
                    <textarea 
                        id="autre_theme" 
                        name="autre_theme" 
                        placeholder="Autres informations que vous souhaitez partager..."
                    ><?php echo $profil ? htmlspecialchars($profil['autre_theme']) : ''; ?></textarea>
                    <small>Maximum 500 caractères</small>
                </div>

                <button type="submit" class="btn-submit">
                    <?php echo $profil ? 'Mettre à jour mon profil' : 'Créer mon profil'; ?>
                </button>
            </form>

            <a href="Home.html" class="btn-back">← Retour à l'accueil</a>
        </div>
    </div>

    <script>
        // Validation côté client
        document.getElementById('profilForm').addEventListener('submit', function(e) {
            const bio = document.getElementById('bio').value;
            const competences = document.getElementById('competences').value;
            const formation = document.getElementById('formation').value;
            const experience = document.getElementById('experience').value;
            const preferences = document.getElementById('preferences').value;
            const autre_theme = document.getElementById('autre_theme').value;

            // Validation des longueurs
            if (bio.length > 1000) {
                e.preventDefault();
                alert('La bio ne peut pas dépasser 1000 caractères.');
                return false;
            }

            if (competences.length > 500) {
                e.preventDefault();
                alert('Les compétences ne peuvent pas dépasser 500 caractères.');
                return false;
            }

            if (formation.length > 255) {
                e.preventDefault();
                alert('La formation ne peut pas dépasser 255 caractères.');
                return false;
            }

            if (experience.length > 1000) {
                e.preventDefault();
                alert('L\'expérience ne peut pas dépasser 1000 caractères.');
                return false;
            }

            if (preferences.length > 500) {
                e.preventDefault();
                alert('Les préférences ne peuvent pas dépasser 500 caractères.');
                return false;
            }

            if (autre_theme.length > 500) {
                e.preventDefault();
                alert('Les informations complémentaires ne peuvent pas dépasser 500 caractères.');
                return false;
            }

            return true;
        });

        // Compteurs de caractères (optionnel)
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            const maxLength = textarea.id === 'bio' || textarea.id === 'experience' ? 1000 : 500;
            
            textarea.addEventListener('input', function() {
                const remaining = maxLength - this.value.length;
                const small = this.nextElementSibling;
                
                if (remaining < 0) {
                    small.style.color = '#D32F2F';
                    small.textContent = `Vous avez dépassé de ${Math.abs(remaining)} caractères`;
                } else {
                    small.style.color = '#999';
                    small.textContent = `${remaining} caractères restants`;
                }
            });
        });
    </script>
    <script src="assets/js/frontoffice.js"></script>
</body>
</html>