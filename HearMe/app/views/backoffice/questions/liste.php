<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>

<div class="container-fluid py-4">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-question-circle"></i> Gestion des Questions</h2>
        <div>
            <a href="QuizController.php" class="btn btn-outline-secondary me-2">
                <i class="fas fa-clipboard-list"></i> Voir les Quiz
            </a>
            <a href="QuestionController.php?action=ajouter" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle Question
            </a>
        </div>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php 
            if($_GET['success'] == 1) echo "✅ Question ajoutée avec succès !";
            elseif($_GET['success'] == 2) echo "✅ Question modifiée avec succès !";
            elseif($_GET['success'] == 3) echo "✅ Question supprimée avec succès !";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped table-hover" id="tableQuestions">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Quiz</th>
                        <th>Question</th>
                        <th>Options</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['id_question'] ?></td>
                        <td>
                            <span class="badge bg-primary">
                                <?= htmlspecialchars($row['quiz_titre'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars(substr($row['texte_question'], 0, 60)) ?>...</strong>
                        </td>
                        <td>
                            <small class="text-muted">
                                1. <?= htmlspecialchars(substr($row['option1_texte'], 0, 20)) ?>...<br>
                                2. <?= htmlspecialchars(substr($row['option2_texte'], 0, 20)) ?>...<br>
                                3. <?= htmlspecialchars(substr($row['option3_texte'], 0, 20)) ?>...
                                <?php if($row['option4_texte']): ?>
                                <br>4. <?= htmlspecialchars(substr($row['option4_texte'], 0, 20)) ?>...
                                <?php endif; ?>
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= $row['ordre'] ?></span>
                        </td>
                        <td>
                            <a href="QuestionController.php?action=modifier&id=<?= $row['id_question'] ?>" 
                               class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="QuestionController.php?action=supprimer&id=<?= $row['id_question'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('⚠️ Supprimer cette question ?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Lien vers les résultats -->
    <div class="text-center mt-4">
        <a href="QuestionController.php?action=resultats" class="btn btn-info btn-lg">
            <i class="fas fa-chart-bar"></i> Voir les Résultats des Tests
        </a>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableQuestions').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        order: [[1, 'asc'], [4, 'asc']]
    });
});
</script>

<?php include __DIR__ . '/../layout/footer_backoffice.php'; ?>