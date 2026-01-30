<?php 
require_once __DIR__ . '/../../../config.php';
secureSession();
if (!isAdmin()) redirect('../FrontOffice/Login.php');

$pageTitle = "Quiz - HearMe Admin";
include __DIR__ . '/../layout/header.php';
?>

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Quiz</h1>
        <p>Gérer les quiz émotionnels</p>
    </div>
    <a href="/hearme_user/Controller/QuizController.php?action=create" class="btn-gradient">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Nouveau Quiz
    </a>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert mb-4" style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--accent-green); color: var(--accent-green); padding: 15px; border-radius: 10px;">
    <?= e($_SESSION['success']) ?>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert mb-4" style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--accent-red); color: var(--accent-red); padding: 15px; border-radius: 10px;">
    <?= e($_SESSION['error']) ?>
</div>
<?php unset($_SESSION['error']); endif; ?>

<!-- Quiz Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0" style="color: #ffffff;">Liste des Quiz</h5>
    </div>
    <div class="card-body p-0">
        <?php if (isset($quizList) && $quizList->rowCount() > 0): ?>
        <table class="table-dark-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Questions</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($quiz = $quizList->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td>#<?= $quiz['id_quiz'] ?></td>
                    <td><?= e($quiz['titre']) ?></td>
                    <td><span class="badge-dark badge-info"><?= e($quiz['categorie']) ?></span></td>
                    <td><?= $quiz['nb_questions'] ?></td>
                    <td><?= $quiz['duree_estimee'] ?> min</td>
                    <td>
                        <?php if ($quiz['actif']): ?>
                            <span class="badge-dark badge-success">Actif</span>
                        <?php else: ?>
                            <span class="badge-dark badge-warning">Inactif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="/hearme_user/Controller/QuestionController.php?id_quiz=<?= $quiz['id_quiz'] ?>" class="btn-dark btn-sm text-decoration-none" style="padding: 5px 12px; font-size: 12px;" title="Questions">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent-cyan)" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            </a>
                            <a href="/hearme_user/Controller/QuizController.php?action=edit&id=<?= $quiz['id_quiz'] ?>" class="btn-dark btn-sm text-decoration-none" style="padding: 5px 12px; font-size: 12px;" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <a href="/hearme_user/Controller/QuizController.php?action=delete&id=<?= $quiz['id_quiz'] ?>" onclick="return confirm('Supprimer ce quiz ?')" class="btn-dark btn-sm text-decoration-none" style="padding: 5px 12px; font-size: 12px; border-color: var(--accent-red);" title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="text-center py-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <h5 class="mt-3 text-secondary">Aucun quiz trouvé</h5>
            <p class="text-muted">Créez votre premier quiz</p>
            <a href="/hearme_user/Controller/QuizController.php?action=create" class="btn-gradient mt-3">Créer un quiz</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
