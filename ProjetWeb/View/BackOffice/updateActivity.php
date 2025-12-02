<?php
require_once '../../Controller/ActivityController.php';

$controller = new ActivityController();
$activity = null;

if (isset($_GET['id'])) {
    $activity = $controller->getActivityById($_GET['id']);
}

if (!$activity) {
    die("<script>alert('Activité non trouvée'); window.location.href='listActivities.php';</script>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Activité - HearMe</title>
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
            padding: 2rem;
            color: #333;
        }

        .container {
            max-width: 800px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 2.5rem;
            font-weight: 600;
        }

        .activity-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .activity-info h2 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 2rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #2196F3;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
            transform: translateY(-2px);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
            padding: 1.2rem 2.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(33, 150, 243, 0.3);
        }

        .btn-cancel {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-cancel:hover {
            box-shadow: 0 10px 25px rgba(149, 165, 166, 0.3);
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .actions .btn {
            flex: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .container {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 2rem;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Modifier l'Activité</h1>
        
        <div class="activity-info">
            <h2><?= htmlspecialchars($activity['title']) ?></h2>
            <p>Type: <?= $activity['activity_type'] ?> | Créée le: <?= date('d/m/Y', strtotime($activity['created_at'])) ?></p>
        </div>
        
        <form action="updateVerification.php" method="POST">
            <input type="hidden" name="activity_id" value="<?= $activity['activity_id'] ?>">
            
            <div class="form-group">
                <label for="title">📝 Titre de l'activité</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($activity['title']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="activity_type">🎯 Type d'activité</label>
                <select id="activity_type" name="activity_type" required>
                    <option value="Yoga" <?= $activity['activity_type'] == 'Yoga' ? 'selected' : '' ?>>🧘‍♀️ Yoga</option>
                    <option value="Meditation" <?= $activity['activity_type'] == 'Meditation' ? 'selected' : '' ?>>😌 Méditation</option>
                    <option value="Randonnée" <?= $activity['activity_type'] == 'Randonnée' ? 'selected' : '' ?>>🥾 Randonnée</option>
                    <option value="Sport_doux" <?= $activity['activity_type'] == 'Sport_doux' ? 'selected' : '' ?>>💪 Sport doux</option>
                    <option value="Atelier_respiration" <?= $activity['activity_type'] == 'Atelier_respiration' ? 'selected' : '' ?>>🌬️ Atelier respiration</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">📄 Description</label>
                <textarea id="description" name="description" rows="4"><?= htmlspecialchars($activity['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="location">📍 Lieu</label>
                <input type="text" id="location" name="location" value="<?= htmlspecialchars($activity['location']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="date_time">📅 Date et heure</label>
                <input type="datetime-local" id="date_time" name="date_time" value="<?= date('Y-m-d\TH:i', strtotime($activity['date_time'])) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="max_participants">👥 Nombre maximum de participants</label>
                <input type="number" id="max_participants" name="max_participants" min="1" value="<?= $activity['max_participants'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="difficulty_level">⚡ Niveau de difficulté</label>
                <select id="difficulty_level" name="difficulty_level" required>
                    <option value="Débutant" <?= $activity['difficulty_level'] == 'Débutant' ? 'selected' : '' ?>>🌟 Débutant</option>
                    <option value="Intermédiaire" <?= $activity['difficulty_level'] == 'Intermédiaire' ? 'selected' : '' ?>>⭐⭐ Intermédiaire</option>
                    <option value="Avancé" <?= $activity['difficulty_level'] == 'Avancé' ? 'selected' : '' ?>>⭐⭐⭐ Avancé</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="status">📊 Statut</label>
                <select id="status" name="status" required>
                    <option value="1" <?= $activity['status'] ? 'selected' : '' ?>>✅ Actif - Visible par tous</option>
                    <option value="0" <?= !$activity['status'] ? 'selected' : '' ?>>❌ Inactif - Masqué du public</option>
                </select>
            </div>
            
            <div class="actions">
                <button type="submit">💾 Enregistrer les modifications</button>
                <a href="listActivities.php" class="btn btn-cancel">← Annuler et retour</a>
            </div>
        </form>
    </div>
</body>
</html>