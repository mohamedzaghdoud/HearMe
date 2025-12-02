<?php
require_once '../../config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Activité - HearMe</title>
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
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
            transform: translateY(-2px);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background: linear-gradient(135deg, #4CAF50, #45a049);
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
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(76, 175, 80, 0.3);
        }

        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
            font-weight: 500;
        }

        .success {
            color: #4CAF50;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
            font-weight: 500;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
            color: #666;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #4CAF50;
        }

        .form-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .form-header p {
            color: #666;
            font-size: 1.1rem;
            margin-top: 0.5rem;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h1>➕ Ajouter une Activité</h1>
            <p>Créez une nouvelle activité de bien-être pour la communauté HearMe</p>
        </div>
        
        <form action="verification.php" method="POST" id="activityForm">
            <div class="form-group">
                <label for="title">📝 Titre de l'activité</label>
                <input type="text" id="title" name="title" placeholder="Ex: Yoga du matin au parc, Méditation guidée..." required>
                <span id="titleError" class="error"></span>
            </div>
            
            <div class="form-group">
                <label for="activity_type">🎯 Type d'activité</label>
                <select id="activity_type" name="activity_type" required>
                    <option value="">Choisir un type d'activité</option>
                    <option value="Yoga">🧘‍♀️ Yoga</option>
                    <option value="Meditation">😌 Méditation</option>
                    <option value="Randonnée">🥾 Randonnée</option>
                    <option value="Sport_doux">💪 Sport doux</option>
                    <option value="Atelier_respiration">🌬️ Atelier respiration</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">📄 Description</label>
                <textarea id="description" name="description" placeholder="Décrivez l'activité, ses bienfaits, ce que les participants vont découvrir..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="location">📍 Lieu</label>
                <input type="text" id="location" name="location" placeholder="Ex: Parc central, Salle de yoga, Forêt de..." required>
            </div>
            
            <div class="form-group">
                <label for="date_time">📅 Date et heure</label>
                <input type="datetime-local" id="date_time" name="date_time" required>
            </div>
            
            <div class="form-group">
                <label for="max_participants">👥 Nombre maximum de participants</label>
                <input type="number" id="max_participants" name="max_participants" min="1" placeholder="Ex: 15" required>
                <span id="participantsError" class="error"></span>
            </div>
            
            <div class="form-group">
                <label for="difficulty_level">⚡ Niveau de difficulté</label>
                <select id="difficulty_level" name="difficulty_level" required>
                    <option value="">Choisir un niveau</option>
                    <option value="Débutant">🌟 Débutant</option>
                    <option value="Intermédiaire">⭐⭐ Intermédiaire</option>
                    <option value="Avancé">⭐⭐⭐ Avancé</option>
                </select>
            </div>
            
            <button type="submit">🚀 Créer l'activité</button>
        </form>
        
        <a href="listActivities.php" class="back-link">
            ← Retour à la liste des activités
        </a>
    </div>

    <script src="addActivity.js"></script>
</body>
</html>