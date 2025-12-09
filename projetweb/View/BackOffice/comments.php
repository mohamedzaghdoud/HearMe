<?php
require_once '../../controller/PostController.php';
require_once '../../controller/CommentController.php';

$postC = new PostController();
$commentC = new CommentController();

// Récupérer tous les posts avec leurs commentaires
$postsResult = $postC->postList();
$posts = $postsResult->fetchAll(); // Convertir en tableau

$allComments = [];
$commentsCount = [];

if (is_array($posts)) {
    foreach ($posts as $post) {
        $count = $commentC->countCommentsByPost($post['id']);
        $commentsCount[$post['id']] = $count;
        
        // Récupérer les commentaires de ce post
        $postComments = $commentC->getCommentsByPost($post['id']);
        $allComments[$post['id']] = $postComments;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commentaires - HearMe Forum Admin</title>
    <link rel="stylesheet" href="../FrontOffice/../../assets/css/style.css">
    <link rel="stylesheet" href="css/backoffice.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cogs"></i> Admin Panel</h2>
                <p>HearMe Forum</p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="postList.php" class="nav-item">
                    <i class="fas fa-newspaper"></i> Gestion des Posts
                </a>
                <a href="comments.php" class="nav-item active">
                    <i class="fas fa-comments"></i> Commentaires
                    <span class="badge"><?php echo is_array($posts) ? array_sum($commentsCount) : 0; ?></span>
                </a>
                <a href="addpost.php" class="nav-item">
                    <i class="fas fa-plus-circle"></i> Nouveau Post
                </a>
                <a href="badWords.php" class="nav-item">
                    <i class="fas fa-shield-alt"></i> Gros Mots
                </a>
                <div class="nav-divider"></div>
                <a href="../FrontOffice/postList.php" class="nav-item">
                    <i class="fas fa-eye"></i> Voir le FrontOffice
                </a>
                <a href="../FrontOffice/postList.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Retour au site
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="admin-info">
                    <div class="admin-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <p class="admin-name">Administrateur</p>
                        <p class="admin-role">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <h1><i class="fas fa-comments"></i> Gestion des Commentaires</h1>
                    <p>Visualisez et gérez tous les commentaires du forum</p>
                </div>
                <div class="header-right">
                    <div class="stats-card">
                        <i class="fas fa-comment-dots"></i>
                        <div>
                            <h3><?php echo is_array($posts) ? array_sum($commentsCount) : 0; ?></h3>
                            <p>Commentaires totaux</p>
                        </div>
                    </div>
                    <div class="stats-card">
                        <i class="fas fa-newspaper"></i>
                        <div>
                            <h3><?php echo is_array($posts) ? count($posts) : 0; ?></h3>
                            <p>Posts publiés</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                <div class="content-header">
                    <h2><i class="fas fa-list"></i> Liste des Posts et leurs Commentaires</h2>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Rechercher un commentaire...">
                    </div>
                </div>

                <?php if (is_array($posts) && !empty($posts)): ?>
                    <div class="posts-accordion">
                        <?php foreach ($posts as $post): 
                            $postComments = $allComments[$post['id']] ?? [];
                            $hasComments = !empty($postComments);
                        ?>
                            <div class="post-accordion-item">
                                <div class="accordion-header" onclick="toggleAccordion(<?php echo $post['id']; ?>)">
                                    <div class="post-info">
                                        <h3>
                                            <span class="post-title">Post #<?php echo $post['id']; ?></span>
                                            <span class="post-content-preview">
                                                <?php echo htmlspecialchars(substr($post['content'], 0, 100)) . (strlen($post['content']) > 100 ? '...' : ''); ?>
                                            </span>
                                        </h3>
                                        <div class="post-meta">
                                            <span class="meta-item">
                                                <i class="far fa-calendar"></i>
                                                <?php echo htmlspecialchars($post['created_at']); ?>
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-heart"></i>
                                                <?php echo htmlspecialchars($post['likes']); ?> likes
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-comment"></i>
                                                <?php echo $commentsCount[$post['id']] ?? 0; ?> commentaires
                                            </span>
                                        </div>
                                    </div>
                                    <div class="accordion-icon">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                
                                <div class="accordion-content" id="content-<?php echo $post['id']; ?>" style="display: none;">
                                    <?php if ($hasComments): ?>
                                        <div class="comments-list">
                                            <?php foreach ($postComments as $comment): ?>
                                                <div class="comment-card">
                                                    <div class="comment-header">
                                                        <div class="comment-author">
                                                            <div class="comment-avatar">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                            <div>
                                                                <h4>Utilisateur #<?php echo $comment['id']; ?></h4>
                                                                <p class="comment-date">
                                                                    <i class="far fa-clock"></i>
                                                                    <?php echo htmlspecialchars($comment['created_at']); ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="comment-id">
                                                            <span class="badge">ID: <?php echo $comment['id']; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="comment-body">
                                                        <p><?php echo nl2br(htmlspecialchars($comment['text'])); ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="no-comments">
                                            <i class="far fa-comment-slash"></i>
                                            <p>Aucun commentaire pour ce post</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h2>Aucun post trouvé</h2>
                        <p>Créez votre premier post pour commencer</p>
                        <a href="addpost.php" class="btn-primary">
                            <i class="fas fa-plus-circle"></i> Créer un post
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function toggleAccordion(postId) {
            const content = document.getElementById('content-' + postId);
            const icon = content.previousElementSibling.querySelector('.accordion-icon i');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                content.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        // Ouvrir le premier post avec commentaires
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (is_array($posts) && !empty($posts)): ?>
                const firstPost = <?php echo $posts[0]['id']; ?>;
                toggleAccordion(firstPost);
            <?php endif; ?>
        });
    </script>
</body>
</html>