<?php 
$pageTitle = "Mon Historique - HearMe";
include __DIR__ . '/../layout/header.php';

if (!isLoggedIn()) {
    redirect('/hearme_user/View/FrontOffice/Login.php');
}
?>
<br><br><br>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-history"></i> Mon Historique de Tests</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Test</th>
                                        <th>Score</th>
                                        <th>Niveau</th>
                                        <th>Conseil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                    <?php
                                        $couleur = 'secondary';
                                        if ($row['niveau'] == 'Excellent') $couleur = 'success';
                                        elseif ($row['niveau'] == 'Bien') $couleur = 'info';
                                        elseif ($row['niveau'] == 'Moyen') $couleur = 'warning';
                                        elseif ($row['niveau'] == 'Attention') $couleur = 'danger';
                                    ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($row['date_test'])) ?></td>
                                        <td><?= e($row['quiz_titre'] ?? 'Test supprimé') ?></td>
                                        <td><?= $row['score_total'] ?></td>
                                        <td><span class="badge bg-<?= $couleur ?>"><?= e($row['niveau']) ?></span></td>
                                        <td><small><?= e($row['conseil_genere']) ?></small></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="/hearme_user/Controller/TestController.php" class="btn btn-primary">
                                <i class="fas fa-play"></i> Faire un nouveau test
                            </a>
                            <a href="/hearme_user/View/FrontOffice/Home.php" class="btn btn-outline-secondary">
                                <i class="fas fa-home"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
