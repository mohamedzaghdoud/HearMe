<?php
require_once '../../controller/PostController.php';

$postC = new PostController();

// Gestion du filtrage par date
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

if (!empty($dateFrom) && !empty($dateTo)) {
    $listResult = $postC->postListByDate($dateFrom, $dateTo);
} else {
    $listResult = $postC->postList();
}

// Convertir le PDOStatement en tableau
$list = $listResult->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Posts - BackOffice</title>
    <link rel="stylesheet" href="../FrontOffice/../../assets/css/style.css">
    <link rel="stylesheet" href="css/backoffice.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles de la liste */
        body { font-family: sans-serif; }
        h1 { text-align: center; }
        table { border-collapse: collapse; width: 90%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
        img { max-width: 80px; max-height: 80px; object-fit: cover; }
        .action-link, button { 
            padding: 5px 10px; text-decoration: none; border: 1px solid #333; border-radius: 4px; 
            background-color: #eee; cursor: pointer; display: inline-block; margin: 2px;
            font-size: 14px;
        }
        .action-link:hover, button:hover { background-color: #ddd; }
        .add-link { display: block; width: 90%; margin: 10px auto; text-align: right; }
        
        /* Styles de la MODALE */
        .modal {
            display: none; /* Masqué par défaut */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4); /* Fond noir semi-transparent */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* Position légèrement plus haute */
            padding: 20px;
            border: 1px solid #888;
            width: 90%; 
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        #update-message { margin-top: 15px; }
    </style>
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
                <a href="postList.php" class="nav-item active">
                    <i class="fas fa-newspaper"></i> Gestion des Posts
                </a>
                <a href="comments.php" class="nav-item">
                    <i class="fas fa-comments"></i> Commentaires
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
                    <h1><i class="fas fa-newspaper"></i> Gestion des Posts</h1>
                    <p>Modifiez et gérez tous les posts du forum</p>
                </div>
                <div class="header-right">
                    <div class="stats-card">
                        <i class="fas fa-newspaper"></i>
                        <div>
                            <h3><?php echo is_array($list) ? count($list) : 0; ?></h3>
                            <p>Posts publiés</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Lien vers les commentaires -->
            <div class="admin-content" style="padding: 1rem 2rem;">
                <a href="comments.php" class="btn-primary" style="text-decoration: none;">
                    <i class="fas fa-comments"></i> Voir tous les commentaires
                </a>
            </div>

            <!-- Tableau des posts -->
            <div class="admin-content">
                <div class="content-header">
                    <h2><i class="fas fa-list"></i> Liste des Posts</h2>
                </div>

                <!-- Formulaire de filtrage par date -->
                <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    <form method="GET" action="" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label for="date_from" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="fas fa-calendar-alt"></i> Du :
                            </label>
                            <input 
                                type="date" 
                                id="date_from" 
                                name="date_from" 
                                value="<?php echo htmlspecialchars($dateFrom); ?>"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;"
                            >
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label for="date_to" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="fas fa-calendar-alt"></i> Au :
                            </label>
                            <input 
                                type="date" 
                                id="date_to" 
                                name="date_to" 
                                value="<?php echo htmlspecialchars($dateTo); ?>"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;"
                            >
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="submit" style="padding: 0.75rem 1.5rem; background: #4a6bdf; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem; font-weight: 500;">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                            <?php if (!empty($dateFrom) || !empty($dateTo)): ?>
                                <a href="postList.php" style="padding: 0.75rem 1.5rem; background: #636e72; color: white; border: none; border-radius: 6px; text-decoration: none; display: inline-block; font-size: 1rem; font-weight: 500;">
                                    <i class="fas fa-times"></i> Réinitialiser
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="add-link">
                    <a href="addpost.php" class="action-link" style="background-color: #d4edda; border-color: #c3e6cb;">➕ Ajouter un Post</a>
                </div>

                <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); padding: 20px; margin-top: 20px;">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Contenu</th>
                            <th>Image</th>
                            <th>Likes</th>
                            <th>Créé le</th>
                            <th colspan="2">Actions</th>
                        </tr>

                        <?php 
                        if (!empty($list) && is_array($list)) {
                            foreach ($list as $post) { 
                                $full_content = $post['content'];
                                $safe_content = addslashes(htmlspecialchars($full_content)); 
                                
                                $display_content = htmlspecialchars(substr($full_content, 0, 50)) . (strlen($full_content) > 50 ? '...' : '');
                        ?>
                                <tr>
                                    <td><?= htmlspecialchars($post['id']) ?></td>
                                    <td><?= $display_content ?></td>
                                    <td>
                                        <?php if (!empty($post['image'])) { ?>
                                            <img src="../../uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
                                        <?php } else { ?>
                                            Pas d'image
                                        <?php } ?>
                                    </td>
                                    <td><?= htmlspecialchars($post['likes']) ?></td>
                                    <td><?= htmlspecialchars($post['created_at']) ?></td>
                                    
                                    <td>
                                        <button 
                                            onclick="openEditModal(<?= $post['id'] ?>, '<?= $safe_content ?>')"
                                            style="background-color: #ffeeba; border-color: #ffc107;"
                                        >
                                            Modifier
                                        </button>
                                    </td>
                                    
                                    <td>
                                        <a href="deletepost.php?id=<?= $post['id'] ?>" class="action-link" style="background-color: #f8d7da; border-color: #f5c6cb;" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">Supprimer</a>
                                    </td>
                                </tr>
                        <?php 
                            } 
                        } else {
                        ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">Aucun post trouvé pour le moment.</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            <h2>Modifier le Contenu du Post</h2>
            
            <form id="updateContentForm">
                <input type="hidden" id="edit-post-id" name="id">
                
                <label for="edit-content">Contenu :</label><br>
                <textarea id="edit-content" name="content" rows="6" style="width: 100%; box-sizing: border-box;"></textarea>
                <p id="update-message"></p>
                <br>
                <button type="submit" style="background-color: #4CAF50; color: white; border: none;">Enregistrer les modifications</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('editModal');
        const postIdField = document.getElementById('edit-post-id');
        const contentField = document.getElementById('edit-content');
        const updateForm = document.getElementById('updateContentForm');
        const updateMessage = document.getElementById('update-message');

        // Ouvre la modale, pré-remplit le champ contenu et l'ID
        function openEditModal(id, content) {
            postIdField.value = id;
            // On rétablit le contenu HTML échappé pour le textarea
            contentField.value = content.replace(/&amp;/g, '&')
                                        .replace(/&lt;/g, '<')
                                        .replace(/&gt;/g, '>')
                                        .replace(/&quot;/g, '"')
                                        .replace(/&#039;/g, "'");
            updateMessage.innerText = ''; 
            modal.style.display = 'block';
        }

        // Soumission du formulaire via AJAX
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            if (contentField.value.trim() === '') {
                updateMessage.style.color = 'red';
                updateMessage.innerText = 'Le contenu est obligatoire.';
                return;
            }

            const formData = new FormData(updateForm);
            
            // Envoi des données à updatepost.php
            fetch('updatepost.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    updateMessage.style.color = 'green';
                    updateMessage.innerText = data.message + ' Rechargement en cours...';
                    
                    setTimeout(() => {
                        modal.style.display = 'none';
                        // Recharger la page pour voir le nouveau contenu
                        window.location.reload(); 
                    }, 1000);
                } else {
                    updateMessage.style.color = 'red';
                    updateMessage.innerText = 'Erreur: ' + data.message;
                }
            })
            .catch(error => {
                updateMessage.style.color = 'red';
                updateMessage.innerText = 'Erreur de connexion.';
                console.error('Fetch error:', error);
            });
        });

        // Fermer la modale si l'utilisateur clique en dehors
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>