<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HearMe - Espace Bien-être</title>
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
            color: #333;
            line-height: 1.6;
        }

        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #4CAF50;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .nav-links a:hover {
            color: #4CAF50;
            background: rgba(76, 175, 80, 0.1);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            text-align: center;
            color: white;
            padding: 6rem 2rem;
            background: rgba(0, 0, 0, 0.3);
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-weight: 700;
        }

        .hero p {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Activities Section */
        .activities {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
        }

        .activities h2 {
            text-align: center;
            color: white;
            font-size: 2.8rem;
            margin-bottom: 3rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
            font-weight: 600;
        }

        .activities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 2.5rem;
        }

        .activity-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .activity-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4CAF50, #45a049);
        }

        .activity-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }

        .activity-type {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .activity-card h3 {
            color: #2c3e50;
            margin-bottom: 1.2rem;
            font-size: 1.5rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .activity-card p {
            color: #555;
            margin-bottom: 0.8rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .activity-card strong {
            color: #2c3e50;
            font-weight: 600;
        }

        .available {
            color: #4CAF50;
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 1rem;
            padding: 0.8rem;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .full {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.1rem;
            margin-top: 1rem;
            padding: 0.8rem;
            background: rgba(231, 76, 60, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        /* No Activities Message */
        .no-activities {
            text-align: center;
            color: white;
            font-size: 1.3rem;
            background: rgba(255,255,255,0.1);
            padding: 3rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            grid-column: 1 / -1;
        }

        /* Footer */
        footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .activities-grid {
                grid-template-columns: 1fr;
            }

            .activity-card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <span>🧠</span>
                HearMe - Espace Bien-être
            </div>
            <div class="nav-links">
                <a href="index.php">🏠 Accueil</a>
                <a href="#activities">🎯 Activités</a>
                <a href="../BackOffice/addActivity.php">⚙️ Administration</a>
            </div>
        </nav>
    </header>

    <section class="hero">
        <h1>Bienvenue dans votre Espace Bien-être</h1>
        <p>Découvrez des activités de méditation, yoga, randonnée et bien plus encore pour votre équilibre mental et physique. Prenez soin de vous.</p>
    </section>

    <section id="activities" class="activities">
        <h2>🎯 Nos Activités de Bien-être</h2>
        <div class="activities-grid">
            <?php
            require_once '../../Controller/ActivityController.php';
            $controller = new ActivityController();
            $activities = $controller->listActivities();
            
            if (empty($activities)) {
                echo '<div class="no-activities">
                    <h3>📅 Aucune activité programmée pour le moment</h3>
                    <p>Revenez bientôt pour découvrir nos nouvelles activités bien-être !</p>
                </div>';
            } else {
                foreach ($activities as $activity) {
                    $statusClass = $activity['status'] ? 'available' : 'full';
                    $statusText = $activity['status'] ? '✅ Places disponibles' : '❌ Complet';
                    
                    echo '
                    <div class="activity-card">
                        <span class="activity-type">' . htmlspecialchars($activity['activity_type']) . '</span>
                        <h3>' . htmlspecialchars($activity['title']) . '</h3>
                        <p>📍 <strong>Lieu:</strong> ' . htmlspecialchars($activity['location']) . '</p>
                        <p>📅 <strong>Date:</strong> ' . date('d/m/Y à H:i', strtotime($activity['date_time'])) . '</p>
                        <p>⚡ <strong>Niveau:</strong> ' . htmlspecialchars($activity['difficulty_level']) . '</p>
                        <p>👥 <strong>Places:</strong> ' . $activity['current_participants'] . '/' . $activity['max_participants'] . '</p>
                        <p class="' . $statusClass . '">' . $statusText . '</p>
                    </div>';
                }
            }
            ?>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <p>© 2024 HearMe - Espace Bien-être. Tous droits réservés.</p>
            <p>Prenez soin de vous, chaque jour compte ❤️</p>
        </div>
    </footer>
</body>
</html>