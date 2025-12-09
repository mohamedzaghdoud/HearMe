<?php
require_once '../../controller/CommentController.php';
require_once __DIR__ . '/../../Model/Comment.php';

$commentC = new CommentController();

// Traitement AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $commentId = (int)($_POST['id'] ?? 0);
    $newText = trim($_POST['text'] ?? '');

    if ($commentId === 0 || empty($newText)) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
        exit;
    }

    try {
        $existingComment = $commentC->showComment($commentId);

        if (!$existingComment) {
            echo json_encode(['success' => false, 'message' => 'Commentaire introuvable.']);
            exit;
        }

        $existingComment->setText($newText);
        $commentC->updateComment($existingComment, $commentId);

        echo json_encode(['success' => true, 'message' => 'Commentaire mis à jour.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
    }

    exit;
}

header('Location: postList.php');
exit;
?>