<?php
require_once '../../controller/BadWordController.php';
require_once __DIR__ . '/../../Model/BadWord.php';

$badWordC = new BadWordController();
$message = "";
$messageType = "";

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $word = trim($_POST['word'] ?? "");
            if (!empty($word)) {
                $badWord = new BadWord(null, $word);
                if ($badWordC->addBadWord($badWord)) {
                    $message = "Gros mot ajouté avec succès.";
                    $messageType = "success";
                } else {
                    $message = "Erreur lors de l'ajout.";
                    $messageType = "error";
                }
            } else {
                $message = "Le mot ne peut pas être vide.";
                $messageType = "error";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                if ($badWordC->deleteBadWord($id)) {
                    $message = "Gros mot supprimé avec succès.";
                    $messageType = "success";
                } else {
                    $message = "Erreur lors de la suppression.";
                    $messageType = "error";
                }
            }
        }
    }
}

$list = $badWordC->badWordList();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Gros Mots - HearMe Forum Admin</title>
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
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                </a>
                <a href="postList.php" class="nav-item">
                    <i class="fas fa-newspaper"></i> Gestion des Posts
                </a>
                <a href="comments.php" class="nav-item">
                    <i class="fas fa-comments"></i> Commentaires
                </a>
                <a href="badWords.php" class="nav-item active">
                    <i class="fas fa-shield-alt"></i> Gros Mots
                </a>
                <a href="addpost.php" class="nav-item">
                    <i class="fas fa-plus-circle"></i> Nouveau Post
                </a>
                <div class="nav-divider"></div>
                <a href="../FrontOffice/postList.php" class="nav-item">
                    <i class="fas fa-eye"></i> Voir le FrontOffice
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
            <header class="admin-header">
                <div class="header-left">
                    <h1><i class="fas fa-shield-alt"></i> Gestion des Gros Mots</h1>
                    <p>Gérez la liste des mots interdits</p>
                </div>
            </header>

            <div class="admin-content">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?>" style="padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; background: <?php echo $messageType === 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $messageType === 'success' ? '#155724' : '#721c24'; ?>; border: 1px solid <?php echo $messageType === 'success' ? '#c3e6cb' : '#f5c6cb'; ?>;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <!-- Add Bad Word Form -->
                <div class="dashboard-card" style="margin-bottom: 2rem;">
                    <div class="card-header">
                        <h3><i class="fas fa-plus-circle"></i> Ajouter un gros mot</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" novalidate onsubmit="return validateBadWord()">
                            <input type="hidden" name="action" value="add">
                            <div style="display: flex; gap: 1rem; align-items: flex-end;">
                                <div style="flex: 1;">
                                    <label for="word" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Mot à ajouter :</label>
                                    <input 
                                        type="text" 
                                        id="word" 
                                        name="word" 
                                        placeholder="Entrez le mot à filtrer"
                                        style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem;"
                                    >
                                    <div id="errorWord" style="color: red; font-size: 0.875rem; margin-top: 0.25rem;"></div>
                                </div>
                                <button type="submit" style="padding: 0.75rem 1.5rem; background: #4a6bdf; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem; font-weight: 500;">
                                    <i class="fas fa-plus"></i> Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bad Words List -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-list"></i> Liste des gros mots (<?php echo $list->rowCount(); ?>)</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($list->rowCount() > 0): ?>
                            <div style="display: grid; gap: 0.75rem;">
                                <?php foreach ($list as $badWord): ?>
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                                        <span style="font-weight: 500; font-size: 1.1rem;"><?php echo htmlspecialchars($badWord['word']); ?></span>
                                        <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce mot ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $badWord['id']; ?>">
                                            <button type="submit" style="padding: 0.5rem 1rem; background: #d63031; color: white; border: none; border-radius: 6px; cursor: pointer;">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="text-align: center; padding: 2rem; color: #636e72;">Aucun gros mot dans la liste.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function validateBadWord() {
            const word = document.getElementById('word').value.trim();
            const errorElement = document.getElementById('errorWord');
            
            errorElement.textContent = '';
            
            if (word === '') {
                errorElement.textContent = 'Le mot ne peut pas être vide.';
                return false;
            }
            
            if (word.length < 2) {
                errorElement.textContent = 'Le mot doit contenir au moins 2 caractères.';
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>

