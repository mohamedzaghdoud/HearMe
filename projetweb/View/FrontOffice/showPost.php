<?php
require_once __DIR__ . "/../../Controller/postController.php";
require_once __DIR__ . "/../../Model/post.php";

$postC = new postController();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: postList.php");
    exit;
}

$post = $postC->showpost($id);
if (!$post) {
    header("Location: postList.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Post - HearMe Forum</title>
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
            <div class="show-post-wrapper">
                <div class="back-link">
                    <a href="postList.php">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>

                <article class="post-detail-card">
                    <div class="post-header">
                        <div class="post-user">
                            <div class="user-avatar-large">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="user-info">
                                <h2 class="user-name">Utilisateur</h2>
                                <p class="post-date">
                                    <i class="far fa-clock"></i> 
                                    <?= htmlspecialchars($post->getCreated_at()->format("d/m/Y H:i:s")) ?>
                                </p>
                            </div>
                        </div>
                        <div class="post-id">
                            <span class="badge">ID: <?= htmlspecialchars($post->getId()) ?></span>
                        </div>
                    </div>

                    <div class="post-content-detail">
                        <h3><i class="fas fa-quote-left"></i> Contenu</h3>
                        <p class="content-text"><?= nl2br(htmlspecialchars($post->getContent())) ?></p>
                    </div>

                    <?php if ($post->getImage()) { ?>
                        <div class="post-image-detail">
                            <h3><i class="fas fa-image"></i> Image</h3>
                            <img src="../../uploads/<?= htmlspecialchars($post->getImage()) ?>" 
                                 alt="Post Image"
                                 onclick="openImageModal('../../uploads/<?= htmlspecialchars($post->getImage()) ?>')">
                        </div>
                    <?php } else { ?>
                        <div class="no-image">
                            <i class="fas fa-image"></i>
                            <p>Aucune image</p>
                        </div>
                    <?php } ?>

                    <div class="post-stats-detail">
                        <div class="stat-box">
                            <i class="fas fa-heart"></i>
                            <div>
                                <span class="stat-number" id="likes"><?= htmlspecialchars($post->getLikes()) ?></span>
                                <span class="stat-label">Likes</span>
                            </div>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-comment"></i>
                            <div>
                                <span class="stat-number">0</span>
                                <span class="stat-label">Commentaires</span>
                            </div>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-share"></i>
                            <div>
                                <span class="stat-number">0</span>
                                <span class="stat-label">Partages</span>
                            </div>
                        </div>
                    </div>

                    <div class="post-interactions">
                        <button class="interaction-btn" onclick="likePost(<?= $post->getId() ?>)">
                            <i class="far fa-heart"></i> J'aime
                        </button>
                        <button class="interaction-btn">
                            <i class="far fa-comment"></i> Commenter
                        </button>
                        <button class="interaction-btn">
                            <i class="far fa-share-square"></i> Partager
                        </button>
                    </div>

                    <div class="post-actions-detail">
                        <a href="updatePost.php?id=<?= $post->getId() ?>" class="btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="deletePost.php?id=<?= $post->getId() ?>" 
                           class="btn-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </div>
                </article>
            </div>
        </main>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal" onclick="closeImageModal()">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script src="../../assets/js/script.js"></script>
</body>
</html>