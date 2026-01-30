<?php
/**
 * Face ID Login - HearMe
 */
$pageTitle = "Connexion Face ID - HearMe";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/face-login.css">
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface"></script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                    Face ID
                </h1>
                <p>Connexion par reconnaissance faciale</p>
            </div>
            
            <div class="card-body">
                <!-- Status -->
                <div id="status" class="status-box status-info hidden">
                    <div id="statusIcon"></div>
                    <span id="statusText"></span>
                </div>
                
                <!-- Menu Section -->
                <div id="menuSection">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    
                    <div class="instructions">
                        <h3>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A7C7E7" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            Instructions
                        </h3>
                        <ul>
                            <li><span>1.</span> Autorisez l'accès à votre caméra</li>
                            <li><span>2.</span> Positionnez-vous à 50cm de l'écran</li>
                            <li><span>3.</span> Regardez directement la caméra</li>
                            <li><span>4.</span> Assurez un bon éclairage</li>
                        </ul>
                    </div>
                    
                    <div class="btn-grid">
                        <button id="registerBtn" class="btn btn-register">
                            <span class="btn-icon">👤</span>
                            <span class="btn-text">Enregistrer</span>
                            <span class="btn-subtext">Nouveau visage</span>
                        </button>
                        <button id="loginBtn" class="btn btn-login">
                            <span class="btn-icon">🔓</span>
                            <span class="btn-text">Se connecter</span>
                            <span class="btn-subtext">Face ID</span>
                        </button>
                    </div>
                    
                    <a href="/hearme_user/View/FrontOffice/Login.php" class="btn btn-back">
                        ← Retour à la connexion classique
                    </a>
                </div>
                
                <!-- Camera Section -->
                <div id="cameraSection" class="hidden">
                    <div class="video-container">
                        <video id="videoElement" autoplay playsinline muted></video>
                        <canvas id="canvasElement"></canvas>
                        <div class="quality-badge">
                            <div>Qualité: <span id="qualityText">--</span></div>
                        </div>
                    </div>
                    
                    <div class="btn-flex">
                        <button id="actionBtn" class="btn btn-login btn-action" disabled>
                            ✅ Action
                        </button>
                        <button id="backBtn" class="btn btn-back btn-action">
                            ← Retour
                        </button>
                    </div>
                </div>
                
                <!-- Success Section -->
                <div id="successSection" class="hidden text-center">
                    <div class="success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <h2 style="color: #2C3E50; margin-bottom: 10px;">Connexion réussie! 🎉</h2>
                    <p id="successMessage" style="color: #666; margin-bottom: 20px;">Redirection en cours...</p>
                    <button id="backToMenuBtn" class="btn btn-login">Retour au menu</button>
                </div>
            </div>
            
            <div class="footer">
                🔒 Système de reconnaissance faciale sécurisé - HearMe
            </div>
        </div>
    </div>

    <script src="/hearme_user/View/FrontOffice/assets/js/face-login.js"></script>
</body>
</html>
