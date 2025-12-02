<?php
require_once '../../Model/Activity.php';
require_once '../../Controller/ActivityController.php';

if ($_POST) {
    // Récupération des données du formulaire
    $title = $_POST['title'];
    $activity_type = $_POST['activity_type'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $date_time = $_POST['date_time'];
    $max_participants = $_POST['max_participants'];
    $difficulty_level = $_POST['difficulty_level'];
    
    // Création de l'objet Activity
    $activity = new Activity($title, $activity_type, $description, $location, $date_time, $max_participants, $difficulty_level);
    
    // Ajout dans la base
    $controller = new ActivityController();
    $controller->addActivity($activity);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Succès - HearMe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: #333;
        }

        .success-container {
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 4rem;
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .success-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            animation: bounce 1s infinite alternate;
        }

        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-20px); }
        }

        h1 {
            color: #4CAF50;
            margin-bottom: 1.5rem;
            font-size: 2.8rem;
            font-weight: 700;
        }

        .success-message {
            font-size: 1.3rem;
            color: #555;
            margin-bottom: 3rem;
            line-height: 1.8;
        }

        .activity-details {
            background: #f8f9fa;
            padding: 2.5rem;
            border-radius: 15px;
            margin: 2.5rem 0;
            text-align: left;
            border-left: 5px solid #4CAF50;
        }

        .activity-details h3 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-item {
            margin-bottom: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .detail-item strong {
            color: #2c3e50;
            display: inline-block;
            min-width: 200px;
        }

        .actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 3rem;
        }

        .btn {
            padding: 1.2rem 2.5rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.1rem;
            min-width: 200px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: #666;
            border: 2px solid #ddd;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .btn-outline:hover {
            border-color: #666;
            color: #333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .success-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            h1 {
                font-size: 2.2rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">🎉</div>
        <h1>Activité Créée avec Succès !</h1>
        
        <div class="success-message">
            Votre activité de bien-être a été ajoutée avec succès à la plateforme HearMe.
        </div>

        <div class="activity-details">
            <h3>📋 Détails de l'activité créée</h3>
            <div class="detail-item">
                <strong>📝 Titre:</strong> <?= htmlspecialchars($title) ?>
            </div>
            <div class="detail-item">
                <strong>🎯 Type:</strong> <?= htmlspecialchars($activity_type) ?>
            </div>
            <div class="detail-item">
                <strong>📍 Lieu:</strong> <?= htmlspecialchars($location) ?>
            </div>
            <div class="detail-item">
                <strong>📅 Date:</strong> <?= date('d/m/Y à H:i', strtotime($date_time)) ?>
            </div>
            <div class="detail-item">
                <strong>👥 Participants max:</strong> <?= $max_participants ?>
            </div>
            <div class="detail-item">
                <strong>⚡ Niveau:</strong> <?= htmlspecialchars($difficulty_level) ?>
            </div>
            <?php if (!empty($description)): ?>
            <div class="detail-item">
                <strong>📄 Description:</strong> <?= htmlspecialchars($description) ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="addActivity.php" class="btn btn-primary">
                ➕ Ajouter une autre activité
            </a>
            <a href="listActivities.php" class="btn btn-secondary">
                📋 Voir toutes les activités
            </a>
            <a href="../FrontOffice/index.php" class="btn btn-outline">
                👀 Voir le site public
            </a>
        </div>
    </div>
</body>
</html>
<?php
} else {
    header('Location: addActivity.php?error=no_data');
    exit;
}
?>