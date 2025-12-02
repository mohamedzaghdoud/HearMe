<?php
require_once '../../Controller/ActivityController.php';
$controller = new ActivityController();
$activities = $controller->listActivities();
$totalActivities = $controller->countActivities();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Activités - HearMe</title>
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
            max-width: 1400px;
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
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
            font-weight: 600;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 3rem;
            font-size: 1.1rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        th {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 1.5rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        td {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #ecf0f1;
        }

        tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .btn-edit {
            background: #2196F3;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-edit:hover, .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .status-active {
            color: #4CAF50;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-inactive {
            color: #e74c3c;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 2rem 1rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Gestion des Activités</h1>
        <p class="subtitle">Administrez toutes les activités de bien-être HearMe</p>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= $totalActivities ?></div>
                <div>Activités totales</div>
            </div>
        </div>
        
        <div class="actions">
            <a href="addActivity.php" class="btn btn-primary">
                ➕ Ajouter une activité
            </a>
            <a href="../FrontOffice/index.php" class="btn btn-secondary">
                👀 Voir le site public
            </a>
            <a href="listActivities.php" class="btn btn-info">
                🔄 Actualiser
            </a>
        </div>
        
        <?php if (empty($activities)): ?>
            <div class="empty-state">
                <h3>📭 Aucune activité trouvée</h3>
                <p>Commencez par créer votre première activité de bien-être !</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Participants</th>
                        <th>Niveau</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><strong>#<?= $activity['activity_id'] ?></strong></td>
                        <td><?= htmlspecialchars($activity['title']) ?></td>
                        <td>
                            <span style="display: inline-block; padding: 0.3rem 0.8rem; background: #e3f2fd; color: #1976d2; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                <?= $activity['activity_type'] ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($activity['location']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($activity['date_time'])) ?></td>
                        <td><?= $activity['current_participants'] ?>/<?= $activity['max_participants'] ?></td>
                        <td>
                            <span style="display: inline-block; padding: 0.3rem 0.8rem; background: #fff3e0; color: #f57c00; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                <?= $activity['difficulty_level'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($activity['status']): ?>
                                <span class="status-active">✅ Actif</span>
                            <?php else: ?>
                                <span class="status-inactive">❌ Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="updateActivity.php?id=<?= $activity['activity_id'] ?>" class="btn-edit" title="Modifier">
                                ✏️ Modifier
                            </a>
                            <a href="deleteActivity.php?id=<?= $activity['activity_id'] ?>" class="btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?')">
                                🗑️ Supprimer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>