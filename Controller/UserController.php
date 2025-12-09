<?php
/**
 * ContrÃ´leur User COMPLET - HearMe
 * Emplacement : MON PROJET/Controller/UserController.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/User.php';
require_once __DIR__ . '/../Model/CaptchaService.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // ====================================
    // INSCRIPTION
    // ====================================

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'MÃ©thode non autorisÃ©e.'];
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $captchaAnswer = trim($_POST['captcha'] ?? '');

        // âœ… CORRECTION : Validation CAPTCHA simplifiÃ©e
        if (!CaptchaService::verifyCaptcha($captchaAnswer)) {
            return ['success' => false, 'message' => 'VÃ©rification CAPTCHA Ã©chouÃ©e.'];
        }

        // Validation EMAIL
        $emailValidation = $this->userModel->validateEmail($email);
        if ($emailValidation !== true) {
            return ['success' => false, 'message' => $emailValidation];
        }

        // Validation MOT DE PASSE
        $passwordValidation = $this->userModel->validatePassword($password);
        if ($passwordValidation !== true) {
            return ['success' => false, 'message' => $passwordValidation];
        }

        // VÃ©rifier correspondance
        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Les mots de passe ne correspondent pas.'];
        }

        try {
            $db = $this->userModel->getDb();
            
            // VÃ©rifier si email existe dÃ©jÃ 
            $sql = "SELECT id_user FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Cet email est dÃ©jÃ  utilisÃ©.'];
            }

            // CrÃ©er l'utilisateur
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password, role, email_verified) VALUES (:email, :password, 'user', FALSE)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':email' => $email,
                ':password' => $hashedPassword
            ]);

            $userId = $db->lastInsertId();

            // Envoyer email de vÃ©rification
            $emailSent = $this->userModel->sendVerificationEmail($userId, $email);

            if ($emailSent) {
                // âœ… CORRECTION : Redirection immÃ©diate
                header('Location: email-sent.php?email=' . urlencode($email));
                exit;
            } else {
                return ['success' => false, 'message' => 'Compte crÃ©Ã© mais erreur d\'envoi d\'email.'];
            }

        } catch (PDOException $e) {
            error_log("Erreur register: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de l\'inscription.'];
        }
    }

    // ====================================
    // CONNEXION
    // ====================================

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'MÃ©thode non autorisÃ©e.'];
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $captchaResponse = trim($_POST['captcha'] ?? '');

        if (empty($email) || empty($password) || empty($captchaResponse)) {
            return ['success' => false, 'message' => 'Tous les champs sont requis.'];
        }

        // âœ… CORRECTION : VÃ©rification CAPTCHA simplifiÃ©e
        if (!CaptchaService::verifyCaptcha($captchaResponse)) {
            return ['success' => false, 'message' => 'VÃ©rification de sÃ©curitÃ© Ã©chouÃ©e.'];
        }

        try {
            $db = $this->userModel->getDb();
            $sql = "SELECT id_user, email, password, role, email_verified FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if (!$user) {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
            }

            if (!password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
            }

            if (!$user['email_verified']) {
                return ['success' => false, 'message' => 'Veuillez vÃ©rifier votre email avant de vous connecter.'];
            }

            // CrÃ©er session
            secureSession();
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirection
            if ($user['role'] === 'admin') {
                header('Location: ../BackOffice/dashboard.php');
            } else {
                header('Location: Home.html');
            }
            exit;

        } catch (PDOException $e) {
            error_log("Erreur login: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la connexion.'];
        }
    }

    // ====================================
    // CONNEXION SOCIALE (OAuth)
    // ====================================

    public function socialLogin() {
        secureSession();

        $code = $_GET['code'] ?? '';

        if (empty($code)) {
            redirect('Login.php?error=oauth_failed');
            exit;
        }

        try {
            $tokenData = $this->exchangeGoogleCode($code);
            
            if (!isset($tokenData['access_token'])) {
                redirect('Login.php?error=oauth_token_failed');
                exit;
            }
            
            $userInfo = $this->getGoogleUserInfo($tokenData['access_token']);

            if (!isset($userInfo['email'])) {
                redirect('Login.php?error=oauth_email_failed');
                exit;
            }

            $email = $userInfo['email'];
            $db = $this->userModel->getDb();

            $sql = "SELECT id_user, email, role FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if (!$user) {
                // CrÃ©er nouvel utilisateur
                $randomPassword = bin2hex(random_bytes(16));
                $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO users (email, password, role, email_verified) VALUES (:email, :password, 'user', TRUE)";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':email' => $email,
                    ':password' => $hashedPassword
                ]);

                $userId = $db->lastInsertId();
                $role = 'user';
            } else {
                $userId = $user['id_user'];
                $role = $user['role'];

                // VÃ©rifier l'email automatiquement
                $sql = "UPDATE users SET email_verified = TRUE WHERE id_user = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute([':id' => $userId]);
            }

            $_SESSION['user_id'] = $userId;
            $_SESSION['user_email'] = $email;
            $_SESSION['role'] = $role;

            if ($role === 'admin') {
                redirect('../BackOffice/dashboard.php');
            } else {
                redirect('Home.html');
            }
            exit;

        } catch (Exception $e) {
            error_log("Erreur socialLogin: " . $e->getMessage());
            redirect('Login.php?error=oauth_exception');
            exit;
        }
    }

    private function exchangeGoogleCode($code) {
        require_once __DIR__ . '/../oauth_config.php';
        
        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $postData = [
            'code' => $code,
            'client_id' => GOOGLE_OAUTH_CLIENT_ID,
            'client_secret' => GOOGLE_OAUTH_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_OAUTH_REDIRECT_URI,
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function getGoogleUserInfo($accessToken) {
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo';
        
        $ch = curl_init($userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $accessToken"]);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

// ====================================
// RECONNAISSANCE FACIALE
// ====================================

public function getFaces() {
    header('Content-Type: application/json');

    try {
        $db = $this->userModel->getDb();
        $sql = "SELECT id_user, email, face_encoding FROM users WHERE face_encoding IS NOT NULL";
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll();

        $faces = [];
        foreach ($users as $user) {
            $faces[] = [
                'id' => $user['id_user'],
                'email' => $user['email'],
                'faceData' => json_decode($user['face_encoding'], true)
            ];
        }

        echo json_encode([
            'success' => true,
            'faces' => $faces
        ]);
        exit;

    } catch (PDOException $e) {
        error_log("Erreur getFaces: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Erreur lors de la récupération des visages.'
        ]);
        exit;
    }
}

public function registerFace() {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $faceData = $_POST['face_data'] ?? '';

    if (empty($email) || empty($faceData)) {
        echo json_encode(['success' => false, 'message' => 'Email ou données faciales manquants.']);
        exit;
    }

    $emailValidation = $this->userModel->validateEmail($email);
    if ($emailValidation !== true) {
        echo json_encode(['success' => false, 'message' => $emailValidation]);
        exit;
    }

    try {
        $db = $this->userModel->getDb();

        $sql = "SELECT id_user, face_encoding FROM users WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Cet email n\'existe pas.']);
            exit;
        }

        if (!empty($user['face_encoding'])) {
            echo json_encode(['success' => false, 'message' => 'Un visage est déjà enregistré pour cet email.']);
            exit;
        }

        $decodedFaceData = json_decode($faceData, true);
        if (!$decodedFaceData || !isset($decodedFaceData['landmarks'])) {
            echo json_encode(['success' => false, 'message' => 'Données faciales invalides.']);
            exit;
        }

        // Normaliser les landmarks avant de sauvegarder
        $normalizedData = $this->normalizeFaceData($decodedFaceData);

        $sql = "UPDATE users SET face_encoding = :encoding WHERE id_user = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':encoding' => json_encode($normalizedData),
            ':id' => $user['id_user']
        ]);

        echo json_encode([
            'success' => true, 
            'message' => 'Visage enregistré avec succès !',
            'landmarks_count' => count($normalizedData['landmarks'])
        ]);
        exit;

    } catch (PDOException $e) {
        error_log("Erreur registerFace: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement.']);
        exit;
    }
}

public function faceLogin() {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
        exit;
    }

    $faceData = $_POST['face_data'] ?? '';

    if (empty($faceData)) {
        echo json_encode(['success' => false, 'message' => 'Données faciales manquantes.']);
        exit;
    }

    try {
        $db = $this->userModel->getDb();

        $sql = "SELECT id_user, email, role, face_encoding 
                FROM users 
                WHERE face_encoding IS NOT NULL AND email_verified = TRUE";
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll();

        if (empty($users)) {
            echo json_encode([
                'success' => false, 
                'message' => 'Aucun visage enregistré dans le système.'
            ]);
            exit;
        }

        $inputFaceData = json_decode($faceData, true);
        if (!$inputFaceData || !isset($inputFaceData['landmarks'])) {
            echo json_encode(['success' => false, 'message' => 'Données faciales invalides.']);
            exit;
        }

        // Normaliser les données d'entrée
        $normalizedInput = $this->normalizeFaceData($inputFaceData);

        error_log("=== FACE LOGIN ===");
        error_log("Input landmarks: " . count($normalizedInput['landmarks']));
        error_log("Registered users: " . count($users));

        $bestMatch = null;
        $bestSimilarity = 0;
        $threshold = 0.75; // Seuil ajusté pour méthode améliorée

        foreach ($users as $user) {
            $storedFaceData = json_decode($user['face_encoding'], true);
            
            if (!$storedFaceData || !isset($storedFaceData['landmarks'])) {
                continue;
            }

            $similarity = $this->calculateImprovedSimilarity($normalizedInput, $storedFaceData);
            
            error_log(sprintf(
                "User: %s | Similarity: %.3f", 
                $user['email'], 
                $similarity
            ));

            if ($similarity > $bestSimilarity) {
                $bestSimilarity = $similarity;
                $bestMatch = $user;
            }
        }

        error_log("Best match: " . ($bestMatch ? $bestMatch['email'] : 'none'));
        error_log("Best similarity: " . round($bestSimilarity, 3));
        error_log("==================");

        if ($bestSimilarity >= $threshold && $bestMatch) {
            secureSession();
            $_SESSION['user_id'] = $bestMatch['id_user'];
            $_SESSION['user_email'] = $bestMatch['email'];
            $_SESSION['role'] = $bestMatch['role'];

            echo json_encode([
                'success' => true,
                'message' => 'Connexion réussie !',
                'email' => $bestMatch['email'],
                'similarity' => round($bestSimilarity, 3),
                'redirect' => $bestMatch['role'] === 'admin'
                    ? '../BackOffice/dashboard.php'
                    : 'Home.html'
            ]);
            exit;
        }

        echo json_encode([
            'success' => false, 
            'message' => 'Visage non reconnu. Similarité: ' . round($bestSimilarity * 100, 1) . '%',
            'best_similarity' => round($bestSimilarity, 3),
            'threshold' => $threshold
        ]);
        exit;

    } catch (PDOException $e) {
        error_log("Erreur faceLogin: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la connexion faciale.']);
        exit;
    }
}

/**
 * NOUVELLE MÉTHODE: Normalise les données faciales
 */
private function normalizeFaceData($faceData) {
    if (!isset($faceData['landmarks']) || count($faceData['landmarks']) < 6) {
        return $faceData;
    }

    $landmarks = $faceData['landmarks'];
    
    // Calculer le centre du visage (moyenne de tous les points)
    $centerX = 0;
    $centerY = 0;
    foreach ($landmarks as $point) {
        $centerX += $point[0];
        $centerY += $point[1];
    }
    $centerX /= count($landmarks);
    $centerY /= count($landmarks);

    // Calculer l'échelle (distance entre les yeux)
    $eyeDistance = $this->euclideanDistance($landmarks[0], $landmarks[1]);
    $scale = $eyeDistance > 0 ? 100 / $eyeDistance : 1; // Normaliser à 100 pixels

    // Normaliser chaque landmark
    $normalizedLandmarks = [];
    foreach ($landmarks as $point) {
        $normalizedLandmarks[] = [
            ($point[0] - $centerX) * $scale,
            ($point[1] - $centerY) * $scale
        ];
    }

    return [
        'landmarks' => $normalizedLandmarks,
        'probability' => $faceData['probability'] ?? 1,
        'normalized' => true,
        'original_scale' => $eyeDistance
    ];
}

/**
 * NOUVELLE MÉTHODE: Calcul de similarité amélioré
 */
private function calculateImprovedSimilarity($face1, $face2) {
    if (!isset($face1['landmarks']) || !isset($face2['landmarks'])) {
        return 0;
    }

    $landmarks1 = $face1['landmarks'];
    $landmarks2 = $face2['landmarks'];

    if (count($landmarks1) !== count($landmarks2)) {
        return 0;
    }

    // 1. Similarité géométrique (distances entre landmarks)
    $geometricSimilarity = $this->calculateGeometricSimilarity($landmarks1, $landmarks2);

    // 2. Similarité de forme (angles entre points)
    $shapeSimilarity = $this->calculateShapeSimilarity($landmarks1, $landmarks2);

    // 3. Similarité de probabilité
    $probSimilarity = 1;
    if (isset($face1['probability']) && isset($face2['probability'])) {
        $probDiff = abs($face1['probability'] - $face2['probability']);
        $probSimilarity = max(0, 1 - $probDiff);
    }

    // Combiner les similarités avec pondération
    $finalSimilarity = (
        $geometricSimilarity * 0.50 +  // 50% sur la géométrie
        $shapeSimilarity * 0.40 +       // 40% sur la forme
        $probSimilarity * 0.10          // 10% sur la probabilité
    );

    return min(1, max(0, $finalSimilarity));
}

/**
 * Calcule la similarité géométrique
 */
private function calculateGeometricSimilarity($landmarks1, $landmarks2) {
    $totalDistance = 0;
    $numLandmarks = count($landmarks1);

    for ($i = 0; $i < $numLandmarks; $i++) {
        $distance = $this->euclideanDistance($landmarks1[$i], $landmarks2[$i]);
        $totalDistance += $distance;
    }

    $avgDistance = $totalDistance / $numLandmarks;
    
    // Distance moyenne normalisée (0 = parfait, 20+ = très différent)
    $maxExpectedDistance = 15;
    $similarity = max(0, 1 - ($avgDistance / $maxExpectedDistance));
    
    return $similarity;
}

/**
 * Calcule la similarité de forme (angles entre points)
 */
private function calculateShapeSimilarity($landmarks1, $landmarks2) {
    $numLandmarks = count($landmarks1);
    if ($numLandmarks < 3) {
        return 1;
    }

    $totalAngleDiff = 0;
    $numAngles = 0;

    // Comparer les angles entre triplets de points consécutifs
    for ($i = 0; $i < $numLandmarks - 2; $i++) {
        $angle1 = $this->calculateAngle(
            $landmarks1[$i], 
            $landmarks1[$i + 1], 
            $landmarks1[$i + 2]
        );
        
        $angle2 = $this->calculateAngle(
            $landmarks2[$i], 
            $landmarks2[$i + 1], 
            $landmarks2[$i + 2]
        );

        $angleDiff = abs($angle1 - $angle2);
        // Normaliser à [0, 180]
        if ($angleDiff > 180) {
            $angleDiff = 360 - $angleDiff;
        }
        
        $totalAngleDiff += $angleDiff;
        $numAngles++;
    }

    $avgAngleDiff = $totalAngleDiff / $numAngles;
    
    // Différence d'angle moyenne (0 = identique, 30+ = très différent)
    $maxExpectedAngleDiff = 30;
    $similarity = max(0, 1 - ($avgAngleDiff / $maxExpectedAngleDiff));
    
    return $similarity;
}

/**
 * Calcule l'angle entre trois points
 */
private function calculateAngle($p1, $p2, $p3) {
    $v1x = $p1[0] - $p2[0];
    $v1y = $p1[1] - $p2[1];
    $v2x = $p3[0] - $p2[0];
    $v2y = $p3[1] - $p2[1];
    
    $dot = $v1x * $v2x + $v1y * $v2y;
    $mag1 = sqrt($v1x * $v1x + $v1y * $v1y);
    $mag2 = sqrt($v2x * $v2x + $v2y * $v2y);
    
    if ($mag1 == 0 || $mag2 == 0) {
        return 0;
    }
    
    $cosAngle = $dot / ($mag1 * $mag2);
    $cosAngle = max(-1, min(1, $cosAngle)); // Clamp to [-1, 1]
    
    return rad2deg(acos($cosAngle));
}

/**
 * Calcule la distance euclidienne entre deux points
 */
private function euclideanDistance($point1, $point2) {
    if (!isset($point1[0]) || !isset($point1[1]) || !isset($point2[0]) || !isset($point2[1])) {
        return 100;
    }
    
    $dx = $point1[0] - $point2[0];
    $dy = $point1[1] - $point2[1];
    return sqrt($dx * $dx + $dy * $dy);
}

/**
 * Fonction de débogage améliorée
 */
public function debugFaces() {
    header('Content-Type: application/json');

    $faceData = $_POST['face_data'] ?? '';
    $inputFaceData = json_decode($faceData, true);

    if (!$inputFaceData || !isset($inputFaceData['landmarks'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Données faciales invalides'
        ]);
        exit;
    }

    try {
        $db = $this->userModel->getDb();
        $sql = "SELECT id_user, email, face_encoding FROM users WHERE face_encoding IS NOT NULL";
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll();

        $normalizedInput = $this->normalizeFaceData($inputFaceData);
        $comparisons = [];

        foreach ($users as $user) {
            $storedFaceData = json_decode($user['face_encoding'], true);
            if (!$storedFaceData || !isset($storedFaceData['landmarks'])) {
                continue;
            }

            $similarity = $this->calculateImprovedSimilarity($normalizedInput, $storedFaceData);
            $geometricSim = $this->calculateGeometricSimilarity(
                $normalizedInput['landmarks'], 
                $storedFaceData['landmarks']
            );
            $shapeSim = $this->calculateShapeSimilarity(
                $normalizedInput['landmarks'], 
                $storedFaceData['landmarks']
            );
            
            $comparisons[] = [
                'email' => $user['email'],
                'similarity' => round($similarity, 3),
                'geometric' => round($geometricSim, 3),
                'shape' => round($shapeSim, 3),
                'landmarks_match' => count($normalizedInput['landmarks']) === count($storedFaceData['landmarks'])
            ];
        }

        usort($comparisons, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        echo json_encode([
            'success' => true,
            'input_landmarks' => count($normalizedInput['landmarks']),
            'comparisons' => $comparisons,
            'threshold' => 0.75,
            'best_match' => !empty($comparisons) ? $comparisons[0] : null,
            'recommendation' => $this->getRecommendation($comparisons)
        ]);
        exit;

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur debug: ' . $e->getMessage()
        ]);
        exit;
    }
}

/**
 * Donne des recommandations basées sur les résultats
 */
private function getRecommendation($comparisons) {
    if (empty($comparisons)) {
        return "Aucun visage enregistré pour comparaison.";
    }

    $best = $comparisons[0]['similarity'];

    if ($best >= 0.75) {
        return "✅ Excellente correspondance trouvée! Le login devrait fonctionner.";
    } elseif ($best >= 0.60) {
        return "⚠️ Correspondance moyenne. Essayez de vous rapprocher de la caméra et regardez directement.";
    } elseif ($best >= 0.40) {
        return "❌ Faible correspondance. Vérifiez l'éclairage et la position de votre visage.";
    } else {
        return "❌ Aucune correspondance trouvée. Assurez-vous d'avoir enregistré votre visage.";
    }
}

    // ====================================
    // DÃ‰CONNEXION
    // ====================================

    public function logout() {
        secureSession();
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        redirect('../FrontOffice/Login.php?success=logout');
    }

    // ====================================
    // CRUD ADMIN
    // ====================================

    public function createUser() {
        if (!isAdmin()) {
            return ['success' => false, 'message' => 'AccÃ¨s non autorisÃ©.'];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'MÃ©thode non autorisÃ©e.'];
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        $emailValidation = $this->userModel->validateEmail($email);
        if ($emailValidation !== true) {
            return ['success' => false, 'message' => $emailValidation];
        }

        $passwordValidation = $this->userModel->validatePassword($password);
        if ($passwordValidation !== true) {
            return ['success' => false, 'message' => $passwordValidation];
        }

        $roleValidation = $this->userModel->validateRole($role);
        if ($roleValidation !== true) {
            return ['success' => false, 'message' => $roleValidation];
        }

        try {
            $db = $this->userModel->getDb();
            
            $sql = "SELECT id_user FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Cet email est dÃ©jÃ  utilisÃ©.'];
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password, role, email_verified) VALUES (:email, :password, :role, TRUE)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':email' => $email,
                ':password' => $hashedPassword,
                ':role' => $role
            ]);

            return ['success' => true, 'message' => 'Utilisateur crÃ©Ã© avec succÃ¨s.'];

        } catch (PDOException $e) {
            error_log("Erreur createUser: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la crÃ©ation.'];
        }
    }

    public function getUser($id) {
        if (!isAdmin()) {
            return null;
        }

        try {
            $db = $this->userModel->getDb();
            $sql = "SELECT id_user, email, role, email_verified, created_at FROM users WHERE id_user = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur getUser: " . $e->getMessage());
            return null;
        }
    }

    public function updateUser($id) {
        if (!isAdmin()) {
            return ['success' => false, 'message' => 'AccÃ¨s non autorisÃ©.'];
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'MÃ©thode non autorisÃ©e.'];
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        $emailValidation = $this->userModel->validateEmail($email);
        if ($emailValidation !== true) {
            return ['success' => false, 'message' => $emailValidation];
        }

        if (!empty($password)) {
            $passwordValidation = $this->userModel->validatePassword($password);
            if ($passwordValidation !== true) {
                return ['success' => false, 'message' => $passwordValidation];
            }
        }

        $roleValidation = $this->userModel->validateRole($role);
        if ($roleValidation !== true) {
            return ['success' => false, 'message' => $roleValidation];
        }

        try {
            $db = $this->userModel->getDb();
            
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET email = :email, password = :password, role = :role WHERE id_user = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':email' => $email,
                    ':password' => $hashedPassword,
                    ':role' => $role,
                    ':id' => $id
                ]);
            } else {
                $sql = "UPDATE users SET email = :email, role = :role WHERE id_user = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':email' => $email,
                    ':role' => $role,
                    ':id' => $id
                ]);
            }

            return ['success' => true, 'message' => 'Utilisateur modifiÃ© avec succÃ¨s.'];

        } catch (PDOException $e) {
            error_log("Erreur updateUser: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la modification.'];
        }
    }

    public function deleteUser($id) {
        if (!isAdmin()) {
            return ['success' => false, 'message' => 'AccÃ¨s non autorisÃ©.'];
        }

        try {
            $db = $this->userModel->getDb();
            $sql = "DELETE FROM users WHERE id_user = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);

            return ['success' => true, 'message' => 'Utilisateur supprimÃ© avec succÃ¨s.'];

        } catch (PDOException $e) {
            error_log("Erreur deleteUser: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la suppression.'];
        }
    }
}

// ====================================
// GESTION DES ACTIONS
// ====================================

if (isset($_GET['action'])) {
    $controller = new UserController();
    
    switch ($_GET['action']) {
        case 'faceLogin':
            $controller->faceLogin();
            break;
        case 'getFaces':
            $controller->getFaces();
            break;
        case 'registerFace':
            $controller->registerFace();
            break;
        case 'debugFaces':
            $controller->debugFaces();
            break;
        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Action inconnue']);
            break;
    }
}
?>