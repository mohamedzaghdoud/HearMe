<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>

<div class="container-fluid py-4">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list"></i> Gestion des Quiz</h2>
        <a href="QuizController.php?action=ajouter" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Quiz
        </a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php 
            if($_GET['success'] == 1) echo "✅ Quiz ajouté avec succès !";
            elseif($_GET['success'] == 2) echo "✅ Quiz modifié avec succès !";
            elseif($_GET['success'] == 3) echo "✅ Quiz supprimé avec succès !";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped table-hover" id="tableQuiz">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Questions</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>Date création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['id_quiz'] ?></td>
                        <td><strong><?= htmlspecialchars($row['titre']) ?></strong></td>
                        <td>
                            <span class="badge bg-info">
                                <?= htmlspecialchars($row['categorie']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                <?= $row['nb_questions'] ?> questions
                            </span>
                        </td>
                        <td><?= $row['duree_estimee'] ?> min</td>
                        <td>
                            <?php if($row['actif']): ?>
                                <span class="badge bg-success">Actif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($row['date_creation'])) ?></td>
                        <td>
                            <a href="QuizController.php?action=modifier&id=<?= $row['id_quiz'] ?>" 
                               class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="QuestionController.php?quiz_id=<?= $row['id_quiz'] ?>" 
                               class="btn btn-sm btn-info" title="Voir les questions">
                                <i class="fas fa-list"></i>
                            </a>
                            <a href="QuizController.php?action=supprimer&id=<?= $row['id_quiz'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('⚠️ Supprimer ce quiz et toutes ses questions ?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableQuiz').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        order: [[0, 'desc']]
    });
});
</script>

<?php include __DIR__ . '/../layout/footer_backoffice.php'; ?>