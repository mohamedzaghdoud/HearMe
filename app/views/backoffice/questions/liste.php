<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h2>Gestion des Questions Émotionnelles</h2>
                    <a href="?action=ajouter" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une question
                    </a>
                </div>

                <?php if(isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php 
                        if($_GET['success'] == 1) echo "Question ajoutée avec succès !";
                        elseif($_GET['success'] == 2) echo "Question modifiée avec succès !";
                        elseif($_GET['success'] == 3) echo "Question supprimée avec succès !";
                        ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="tableQuestions">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Question</th>
                                    <th>Catégorie</th>
                                    <th>Ordre</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?= $row['id_question'] ?></td>
                                    <td><?= htmlspecialchars($row['texte_question']) ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?= htmlspecialchars($row['categorie']) ?>
                                        </span>
                                    </td>
                                    <td><?= $row['ordre'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['date_creation'])) ?></td>
                                    <td>
                                        <a href="?action=modifier&id=<?= $row['id_question'] ?>" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?action=supprimer&id=<?= $row['id_question'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')">
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
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableQuestions').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        }
    });
});
</script>

<?php include '../views/backoffice/layout/footer_backoffice.php'; ?>