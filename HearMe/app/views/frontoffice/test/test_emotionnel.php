<?php include __DIR__ . '/../layout/header.php'; ?>

<section class="test-section py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <br><br><br>
                <!-- Info Quiz -->
                <div class="text-center mb-4">
                    <span class="badge bg-primary fs-6 mb-2">
                        <i class="fas fa-tag"></i> <?= htmlspecialchars($quiz['categorie']) ?>
                    </span>
                    <h2><?= htmlspecialchars($quiz['titre']) ?></h2>
                    <p class="text-muted"><?= htmlspecialchars($quiz['description'] ?? '') ?></p>
                </div>
                
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient text-white text-center py-4" 
                         style="background: linear-gradient(135deg, #a854edff 0%, #B4E7B0 100%);">
                        <h4 class="mb-0">
                            <i class="fas fa-heart-pulse"></i> Réponds honnêtement
                        </h4>
                        <small>Tes réponses sont confidentielles</small>
                    </div>

                    <div class="card-body p-4">
                        <!-- Barre de progression -->
                        <div class="progress mb-4" style="height: 30px; border-radius: 15px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                 id="progressBar" role="progressbar" style="width: 0%">
                                <span id="progressText" class="fw-bold">
                                    Question 0 sur <?= count($questions) ?>
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="TestController.php?action=enregistrer" id="formTest">
                            <input type="hidden" name="id_quiz" value="<?= $quiz['id_quiz'] ?>">
                            
                            <?php 
                            $questionNumber = 1;
                            foreach($questions as $q): 
                            ?>
                            <div class="question-block mb-4 p-4 rounded" 
                                 style="background: #fff; border: 2px solid #e0e0e0;">
                                <h5 class="mb-4">
                                    <span class="badge bg-primary me-2">Q<?= $questionNumber ?></span>
                                    <?= htmlspecialchars($q['texte_question']) ?>
                                </h5>

                                <div class="options-container">
                                    <?php 
                                    $optionNum = 1;
                                    foreach($q['options'] as $opt): 
                                    ?>
                                    <div class="form-check mb-3 p-3 option-item rounded" 
                                         style="border: 2px solid #e0e0e0; cursor: pointer; transition: all 0.3s;">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="reponses[<?= $q['id_question'] ?>]" 
                                               id="q<?= $q['id_question'] ?>_opt<?= $optionNum ?>"
                                               value="<?= $opt['score'] ?>">
                                        <label class="form-check-label w-100" 
                                               for="q<?= $q['id_question'] ?>_opt<?= $optionNum ?>"
                                               style="cursor: pointer;">
                                            <?= htmlspecialchars($opt['texte']) ?>
                                        </label>
                                    </div>
                                    <?php 
                                    $optionNum++;
                                    endforeach; 
                                    ?>
                                </div>
                            </div>
                            <?php 
                            $questionNumber++;
                            endforeach; 
                            ?>

                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                    <i class="fas fa-check-circle"></i> Voir mon résultat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Bouton retour -->
                <div class="text-center mt-4">
                    <a href="TestController.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Choisir un autre test
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.option-item:hover {
    border-color: #000000ff !important;
    background: #667eea !important;
    transform: translateX(5px);
}

.option-item:has(input:checked) {
    border-color: #667eea !important;
    background: linear-gradient(135deg, #e8f0ff 0%, #f0f4ff 100%) !important;
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.2);
}

.option-item input[type="radio"] {
    width: 20px;
    height: 20px;
    accent-color: #667eea;
}

.question-block {
    animation: fadeInUp 0.5s ease-out;
}

.question-block.border-danger {
    border-color: #dc3545 !important;
    animation: shake 0.5s;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>

<!-- Validation JS -->
<script src="../../assets/template/js/validation.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>