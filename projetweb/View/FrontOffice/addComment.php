<?php
require_once '../../controller/CommentController.php';
require_once __DIR__ . '/../../Model/Comment.php';

$commentC = new CommentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;
    $text = $_POST['text'] ?? "";

    if ($post_id && !empty($text)) {
        $comment = new Comment(
            null,
            $post_id,
            $text,
            new DateTime()
        );

        $commentC->addComment($comment);
    }
    
    header("Location: postList.php");
    exit;
}

// Si accès direct, rediriger
header("Location: postList.php");
exit;
?>