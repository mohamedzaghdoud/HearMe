<?php
require_once '../../controller/PostController.php';
require_once __DIR__ . '/../../Model/Post.php';

$postC = new PostController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? "";
    $imageName = null;

    // Gestion upload image
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../../uploads/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadPath = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
    }

    $post = new Post(
        null,
        $content,
        $imageName,
        0,
        new DateTime()
    );

    $postC->addpost($post);
    header("Location: postList.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Post - HearMe Forum</title>
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
                    <a href="addPost.php" class="nav-link active"><i class="fas fa-plus-circle"></i> Nouveau Post</a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="form-wrapper">
                <div class="form-card">
                    <div class="form-header">
                        <h2><i class="fas fa-pen"></i> Créer un nouveau post</h2>
                        <p>Partagez vos pensées avec la communauté</p>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data" id="postForm" novalidate onsubmit="return validateAddPost()">
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
                            ></textarea>
                            <div id="errorContent" class="error-message"></div>
                            <div class="char-counter">
                                <span id="charCount">0</span> / 500 caractères
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="image">
                                <i class="fas fa-image"></i> Ajouter une image (optionnel)
                            </label>
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
                                    <span id="fileName">Choisir une image</span>
                                </label>
                            </div>
                            <div id="errorImage" class="error-message"></div>
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="image-preview" style="display: none;">
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
                                <i class="fas fa-paper-plane"></i> Publier
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tips Card -->
                <div class="tips-card">
                    <h3><i class="fas fa-lightbulb"></i> Conseils</h3>
                    <ul>
                        <li>Soyez respectueux envers les autres membres</li>
                        <li>Partagez du contenu pertinent et intéressant</li>
                        <li>Utilisez des images pour rendre votre post plus attractif</li>
                        <li>Limitez votre message à 500 caractères</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>