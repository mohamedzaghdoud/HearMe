<?php 
require_once __DIR__ . '/../../../config.php';
secureSession();
if (!isAdmin()) redirect('../FrontOffice/Login.php');

$pageTitle = "Questions - " . e($quiz['titre']);
include __DIR__ . '/../layout/header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <a href="/hearme_user/Controller/QuizController.php?action=liste" class="btn-dark mb-3" style="display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Retour aux Quiz
        </a>
        <h1>Questions: <?= e($quiz['titre']) ?></h1>
        <p>Gérer les questions de ce quiz</p>
    </div>
    <a href="/hearme_user/Controller/QuestionController.php?action=create&id_quiz=<?= $quiz['id_quiz'] ?>" class="btn-gradient" style="display: inline-flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Nouvelle Question
    </a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--accent-green); color: var(--accent-green); border-radius: 10px; padding: 15px; margin-bottom: 20px;">
        <?= e($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-dark-custom">
                <thead>
                    <tr>
                        <th>Ordre</th>
                        <th>Question</th>
                        <th>Options</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($q = $questions->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><span class="badge-dark badge-info"><?= $q['ordre'] ?></span></td>
                        <td><?= e(substr($q['texte_question'], 0, 80)) ?>...</td>
                        <td>
                            <small style="color: var(--text-secondary);">
                                1: <?= e(substr($q['option1_texte'], 0, 20)) ?>...<br>
                                2: <?= e(substr($q['option2_texte'], 0, 20)) ?>...<br>
                                3: <?= e(substr($q['option3_texte'], 0, 20)) ?>...
                                <?php if ($q['option4_texte']): ?><br>4: <?= e(substr($q['option4_texte'], 0, 20)) ?>...<?php endif; ?>
                            </small>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="/hearme_user/Controller/QuestionController.php?action=edit&id=<?= $q['id_question'] ?>" class="btn-dark" style="padding: 8px 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <a href="/hearme_user/Controller/QuestionController.php?action=delete&id=<?= $q['id_question'] ?>&id_quiz=<?= $quiz['id_quiz'] ?>" 
                                   class="btn-dark" style="padding: 8px 12px; border-color: var(--accent-red);" onclick="return confirm('Supprimer cette question ?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent-red)" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
