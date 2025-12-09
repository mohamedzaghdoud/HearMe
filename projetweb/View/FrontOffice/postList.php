<?php
require_once '../../controller/PostController.php';
require_once '../../controller/CommentController.php';

$postC = new PostController();
$commentC = new CommentController();
$list = $postC->postList();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum HearMe - Fil d'actualité</title>
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
                    <a href="postList.php" class="nav-link active"><i class="fas fa-home"></i> Accueil</a>
                    <a href="addPost.php" class="nav-link"><i class="fas fa-plus-circle"></i> Nouveau Post</a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="posts-wrapper">
                <!-- Create Post Card (Quick Access) -->
                <div class="create-post-card">
                    <div class="create-post-content">
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                        <a href="addPost.php" class="create-post-input">
                            Quoi de neuf ? Partagez vos pensées...
                        </a>
                    </div>
                    <div class="create-post-actions">
                        <a href="addPost.php" class="action-btn">
                            <i class="fas fa-image"></i> Photo
                        </a>
                    </div>
                </div>

                <!-- Posts Feed -->
                <div class="posts-feed">
                    <?php 
                    if (!empty($list)) {
                        foreach ($list as $post) {
                            $commentCount = $commentC->countCommentsByPost($post['id']);
                            $comments = $commentC->getCommentsByPost($post['id']);
                    ?>
                        <article class="post-card" id="post-<?= $post['id'] ?>">
                            <div class="post-header">
                                <div class="post-user">
                                    <div class="user-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="user-info">
                                        <h3 class="user-name">Utilisateur</h3>
                                        <p class="post-date">
                                            <i class="far fa-clock"></i> 
                                            <?= htmlspecialchars($post['created_at']) ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="post-actions-menu">
                                    <button class="menu-btn" onclick="toggleMenu(<?= $post['id'] ?>)">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu" id="menu-<?= $post['id'] ?>">
                                        <a href="showPost.php?id=<?= $post['id'] ?>" class="dropdown-item">
                                            <i class="fas fa-eye"></i> Voir détails
                                        </a>
                                        <a href="updatePost.php?id=<?= $post['id'] ?>" class="dropdown-item">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="deletePost.php?id=<?= $post['id'] ?>" 
                                           class="dropdown-item delete-item"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="post-content">
                                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                                
                                <?php if (!empty($post['image'])) { ?>
                                    <div class="post-image">
                                        <img src="../../uploads/<?= htmlspecialchars($post['image']) ?>" 
                                             alt="Post Image"
                                             onclick="openImageModal('../../uploads/<?= htmlspecialchars($post['image']) ?>')">
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="post-stats">
                                <span class="stat-item">
                                    <i class="fas fa-heart"></i> 
                                    <span id="likes-<?= $post['id'] ?>"><?= htmlspecialchars($post['likes']) ?></span> likes
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-comment"></i> 
                                    <span id="comment-count-<?= $post['id'] ?>"><?= $commentCount ?></span> commentaires
                                </span>
                            </div>

                            <div class="post-interactions">
                                <button class="interaction-btn" onclick="likePost(<?= $post['id'] ?>)">
                                    <i class="far fa-heart"></i> J'aime
                                </button>
                                <button class="interaction-btn" onclick="toggleComments(<?= $post['id'] ?>)">
                                    <i class="far fa-comment"></i> Commenter
                                </button>
                                <button class="interaction-btn">
                                    <i class="far fa-share-square"></i> Partager
                                </button>
                            </div>

                            <!-- Comments Section -->
                            <div class="comments-section" id="comments-<?= $post['id'] ?>" style="display: none;">
                                <!-- Add Comment Form -->
                                <div class="add-comment-form">
                                    <form action="addComment.php" method="POST" novalidate onsubmit="return validateComment(<?= $post['id'] ?>)">
                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                        <div class="comment-input-wrapper">
                                            <textarea 
                                                id="comment-text-<?= $post['id'] ?>" 
                                                name="text" 
                                                placeholder="Écrivez un commentaire..." 
                                                class="comment-input"
                                                rows="2"
                                            ></textarea>
                                            <div id="error-comment-<?= $post['id'] ?>" class="error-message"></div>
                                        </div>
                                        <button type="submit" class="btn-comment">
                                            <i class="fas fa-paper-plane"></i> Commenter
                                        </button>
                                    </form>
                                </div>

                                <!-- Comments List -->
                                <div class="comments-list">
                                    <?php if (!empty($comments)) { 
                                        foreach ($comments as $comment) {
                                    ?>
                                        <div class="comment-item" id="comment-item-<?= $comment['id'] ?>">
                                            <div class="comment-avatar">
                                                <i class="fas fa-user-circle"></i>
                                            </div>
                                            <div class="comment-content">
                                                <div class="comment-header">
                                                    <span class="comment-author">Utilisateur</span>
                                                    <span class="comment-date"><?= htmlspecialchars($comment['created_at']) ?></span>
                                                </div>
                                                <p class="comment-text" id="comment-text-display-<?= $comment['id'] ?>">
                                                    <?= nl2br(htmlspecialchars($comment['text'])) ?>
                                                </p>
                                                
                                                <!-- Edit Form (hidden by default) -->
                                                <div class="comment-edit-form" id="edit-form-<?= $comment['id'] ?>" style="display: none;">
                                                    <textarea 
                                                        id="edit-text-<?= $comment['id'] ?>" 
                                                        class="comment-input"
                                                        rows="2"
                                                    ><?= htmlspecialchars($comment['text']) ?></textarea>
                                                    <div class="comment-edit-actions">
                                                        <button onclick="saveComment(<?= $comment['id'] ?>)" class="btn-save-comment">
                                                            <i class="fas fa-check"></i> Enregistrer
                                                        </button>
                                                        <button onclick="cancelEditComment(<?= $comment['id'] ?>)" class="btn-cancel-comment">
                                                            <i class="fas fa-times"></i> Annuler
                                                        </button>
                                                    </div>
                                                    <div id="error-edit-<?= $comment['id'] ?>" class="error-message"></div>
                                                </div>

                                                <div class="comment-actions">
                                                    <button onclick="editComment(<?= $comment['id'] ?>)" class="comment-action-btn">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </button>
                                                    <button onclick="deleteComment(<?= $comment['id'] ?>, <?= $post['id'] ?>)" class="comment-action-btn delete">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                        }
                                    } else { ?>
                                        <p class="no-comments">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </article>
                    <?php 
                        } 
                    } else {
                    ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h2>Aucun post pour le moment</h2>
                            <p>Soyez le premier à partager quelque chose !</p>
                            <a href="addPost.php" class="btn-primary">Créer un post</a>
                        </div>
                    <?php
                    }
                    ?>
                </div>
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