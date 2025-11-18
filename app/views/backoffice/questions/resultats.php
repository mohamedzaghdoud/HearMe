<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>

<div class="container mt-5">
    <h2>Résultats des Utilisateurs</h2>

    <?php
    // Requête SANS table users (100% fonctionnelle)
    $stmt = $this->db->query("
        SELECT * FROM resultat_utilisateur 
        ORDER BY date_test DESC
    ");
    ?>

    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Score</th>
                    <th>Niveau</th>
                    <th>Conseil</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id_resultat'] ?></td>
                    <td><em>Anonyme</em></td>
                    <td>
                        <span class="badge 
                            <?= $row['score_total'] <= 5 ? 'bg-success' : 
                               ($row['score_total'] <= 10 ? 'bg-warning' : 'bg-danger') ?>">
                            <?= $row['score_total'] ?> pts
                        </span>
                    </td>
                    <td><strong><?= htmlspecialchars($row['niveau']) ?></strong></td>
                    <td><small><?= htmlspecialchars($row['conseil_genere']) ?></small></td>
                    <td><?= date('d/m/Y H:i', strtotime($row['date_test'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="QuestionController.php" class="btn btn-secondary">
            Retour aux questions
        </a>
    </div>
</div>
<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>