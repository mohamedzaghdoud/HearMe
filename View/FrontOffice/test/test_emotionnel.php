<?php 
$pageTitle = e($quiz['titre']) . " - HearMe";
$additionalCss = '<link rel="stylesheet" href="/hearme_user/View/FrontOffice/assets/css/test-emotionnel.css">';
$additionalJs = '<script src="/hearme_user/View/FrontOffice/assets/js/test-emotionnel.js"></script>';
include __DIR__ . '/../layout/header.php';
?>

<section class="test-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <br><br>
                <div class="text-center mb-4">
                    <span class="badge bg-primary fs-6 mb-2">
                        <i class="fas fa-tag"></i> <?= e($quiz['categorie']) ?>
                    </span>
                    <h2><?= e($quiz['titre']) ?></h2>
                    <p class="text-muted"><?= e($quiz['description'] ?? '') ?></p>
                </div>

                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient text-white text-center py-4"
                         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h4 class="mb-0"><i class="fas fa-heart-pulse"></i> Réponds honnêtement</h4>
                        <small>Tes réponses sont confidentielles</small>
                    </div>

                    <div class="card-body p-4">
                        <div class="progress mb-4" style="height: 30px; border-radius: 15px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                 id="progressBar" role="progressbar" style="width: 0%">
                                <span id="progressText" class="fw-bold">Question 0 sur <?= count($questions) ?></span>
                            </div>
                        </div>

                        <form method="POST" action="/hearme_user/Controller/TestController.php?action=enregistrer" id="formTest" data-total-questions="<?= count($questions) ?>">
                            <input type="hidden" name="id_quiz" value="<?= $quiz['id_quiz'] ?>">

                            <?php $questionNumber = 1; foreach($questions as $q): ?>
                            <div class="question-block mb-4 p-4 rounded">
                                <h5 class="mb-4">
                                    <span class="badge bg-primary me-2">Q<?= $questionNumber ?></span>
                                    <?= e($q['texte_question']) ?>
                                </h5>
                                <div class="options-container">
                                    <?php $optionNum = 1; foreach($q['options'] as $opt): ?>
                                    <div class="form-check mb-3 p-3 option-item rounded">
                                        <input class="form-check-input" type="radio"
                                               name="reponses[<?= $q['id_question'] ?>]"
                                               id="q<?= $q['id_question'] ?>_opt<?= $optionNum ?>"
                                               value="<?= $opt['score'] ?>" required>
                                        <label class="form-check-label w-100"
                                               for="q<?= $q['id_question'] ?>_opt<?= $optionNum ?>">
                                            <?= e($opt['texte']) ?>
                                        </label>
                                    </div>
                                    <?php $optionNum++; endforeach; ?>
                                </div>
                            </div>
                            <?php $questionNumber++; endforeach; ?>

                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                    <i class="fas fa-check-circle"></i> Voir mon résultat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="/hearme_user/Controller/TestController.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Choisir un autre test
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layout/footer.php'; ?>
