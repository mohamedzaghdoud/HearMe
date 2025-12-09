<?php
require_once '../../controller/CommentController.php';

// Traitement AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $commentId = (int)($_POST['id'] ?? 0);

    if ($commentId === 0) {
        echo json_encode(['success' => false, 'message' => 'ID manquant.']);
        exit;
    }

    try {
        $commentC = new CommentController();
        $commentC->deleteComment($commentId);
        echo json_encode(['success' => true, 'message' => 'Commentaire supprimé.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
    }

    exit;
}

header('Location: postList.php');
exit;
?>