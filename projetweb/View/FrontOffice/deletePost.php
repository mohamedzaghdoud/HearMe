<?php
require_once '../../controller/postController.php';

$id = $_GET["id"] ?? null;

if ($id) {
    $postC = new postController();
    
    // Récupérer le post pour supprimer l'image associée
    $post = $postC->showpost($id);
    
    if ($post && $post->getImage()) {
        $imagePath = '../../uploads/' . $post->getImage();
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    $postC->deletepost($id);
}

header('Location: postList.php');
exit;
?>