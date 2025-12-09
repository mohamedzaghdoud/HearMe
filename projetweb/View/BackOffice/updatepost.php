<?php
// On inclut le contrôleur et le modèle
include '../../controller/PostController.php';
require_once __DIR__ . '/../../Model/Post.php';

$postC = new PostController();

// Nous traitons UNIQUEMENT la requête POST envoyée par AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Indiquer au navigateur que la réponse est au format JSON
    header('Content-Type: application/json');

    $postId = (int)($_POST['id'] ?? 0);
    $newContent = trim($_POST['content'] ?? '');

    // Vérification de l'ID et du contenu
    if ($postId === 0 || empty($newContent)) {
        echo json_encode(['success' => false, 'message' => 'L\'ID du post ou le contenu est manquant.']);
        exit;
    }

    try {
        // 1. Récupérer le post existant pour conserver l'image, les likes, etc.
        $existingPost = $postC->showpost($postId); // showpost() doit retourner un objet Post

        if (!$existingPost) {
            echo json_encode(['success' => false, 'message' => 'Post introuvable.']);
            exit;
        }

        // 2. Mettre à jour SEULEMENT le contenu de l'objet
        $existingPost->setContent($newContent);
        // Les autres champs (image, likes, created_at) restent inchangés dans l'objet

        // 3. Appeler la méthode d'update du contrôleur
        $postC->updatepost($existingPost, $postId);

        // Réponse JSON de succès
        echo json_encode(['success' => true, 'message' => 'Contenu du post mis à jour avec succès.']);
        
    } catch (Exception $e) {
        // Réponse JSON en cas d'erreur
        // (Décommentez la ligne $e->getMessage() pour le débogage si besoin)
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
    }

    exit;
}

// Si la page est accédée directement via GET, on redirige vers la liste des posts.
header('Location: postList.php');
exit;