<?php
require_once '../../controller/PostController.php';
require_once __DIR__ . '/../../Model/Post.php';

$postC = new PostController();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: postList.php");
    exit;
}

$existingPost = $postC->showpost($id);
if (!$existingPost) {
    header("Location: postList.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? "";
    $imageName = $existingPost->getImage();

    // Gestion upload nouvelle image
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../../uploads/';
        
        // Supprimer ancienne image si elle existe
        if ($imageName && file_exists($uploadDir . $imageName)) {
            unlink($uploadDir . $imageName);
        }
        
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadPath = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
    }

    $post = new Post(
        $id,
        $content,
        $imageName,
        $existingPost->getLikes(),
        $existingPost->getCreated_at()
    );

    $postC->updatepost($post, $id);
    header("Location: postList.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Post - HearMe Forum</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <h1 class="logo"><i class="fas fa-comments"></i> HearMe Forum</h1>
                <nav class="nav">
                    <a href="postList.php" class="nav-link"><i class="fas fa-home"></i> Accueil</a>
                    <a href="addPost.php" class="nav-link"><i class="fas fa-plus-circle"></i> Nouveau Post</a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="form-wrapper">
                <div class="form-card">
                    <div class="form-header">
                        <h2><i class="fas fa-edit"></i> Modifier le post</h2>
                        <p>Mettez à jour votre contenu</p>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data" id="updateForm" novalidate onsubmit="return validateUpdatePost()">
                        <div class="form-group">
                            <label for="content">
                                <i class="fas fa-comment-dots"></i> Votre message *
                            </label>
                            <textarea 
                                id="content" 
                                name="content" 
                                rows="6" 
                                placeholder="Quoi de neuf ? Partagez vos pensées..."
                                class="form-control"
                                oninput="updateCharCount()"
                            ><?= htmlspecialchars($existingPost->getContent()) ?></textarea>
                            <div id="errorContent" class="error-message"></div>
                            <div class="char-counter">
                                <span id="charCount"><?= strlen($existingPost->getContent()) ?></span> / 500 caractères
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image">
                                <i class="fas fa-image"></i> Modifier l'image (optionnel)
                            </label>
                            
                            <?php if ($existingPost->getImage()) { ?>
                                <div class="current-image">
                                    <p><strong>Image actuelle :</strong></p>
                                    <img src="../../uploads/<?= htmlspecialchars($existingPost->getImage()) ?>" alt="Current Image">
                                </div>
                            <?php } ?>
                            
                            <div class="file-input-wrapper">
                                <input 
                                    type="file" 
                                    id="image" 
                                    name="image" 
                                    accept="image/jpeg, image/jpg, image/png, image/gif"
                                    class="file-input"
                                    onchange="previewImage(event)"
                                >
                                <label for="image" class="file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span id="fileName">Changer l'image</span>
                                </label>
                            </div>
                            <div id="errorImage" class="error-message"></div>
                            
                            <!-- New Image Preview -->
                            <div id="imagePreview" class="image-preview" style="display: none;">
                                <p><strong>Nouvelle image :</strong></p>
                                <img id="preview" src="" alt="Aperçu">
                                <button type="button" class="remove-image" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="postList.php" class="btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>