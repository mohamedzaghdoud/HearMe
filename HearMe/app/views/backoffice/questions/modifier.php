<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>

<div class="container-fluid py-4">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Modifier la Question</h2>
        <a href="QuestionController.php?action=liste" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="QuestionController.php?action=modifier" id="formQuestion">
                <input type="hidden" name="id_question" value="<?= $question->getIdQuestion() ?>">
                
                <!-- Sélection du Quiz et Ordre -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Quiz associé <span class="text-danger">*</span>
                            </label>
                            <select name="id_quiz" class="form-select" data-validate="true">
                                <option value="">-- Sélectionner un Quiz --</option>
                                <?php foreach($quizList as $quiz): ?>
                                <option value="<?= $quiz['id_quiz'] ?>" 
                                    <?= $quiz['id_quiz'] == $question->getIdQuiz() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($quiz['titre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Ordre <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="ordre" 
                                   class="form-control" 
                                   value="<?= $question->getOrdre() ?>"
                                   min="1"
                                   data-validate="true">
                        </div>
                    </div>
                </div>

                <!-- Texte de la question -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Texte de la Question <span class="text-danger">*</span>
                    </label>
                    <textarea name="texte_question" 
                              class="form-control" 
                              rows="2"
                              data-validate="true"><?= htmlspecialchars($question->getTexteQuestion()) ?></textarea>
                </div>

                <hr>
                <h5 class="mb-3"><i class="fas fa-list-ol"></i> Options de Réponse</h5>
                
                <!-- Option 1 -->
                <div class="card mb-3 border-success">
                    <div class="card-header bg-success text-white">
                        Option 1 <span class="text-warning">*</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <input type="text" 
                                       name="option1_texte" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($question->getOption1Texte()) ?>"
                                       data-validate="true">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">Score</span>
                                    <input type="number" 
                                           name="option1_score" 
                                           class="form-control" 
                                           value="<?= $question->getOption1Score() ?>"
                                           min="1" max="10"
                                           data-validate="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Option 2 -->
                <div class="card mb-3 border-info">
                    <div class="card-header bg-info text-white">
                        Option 2 <span class="text-warning">*</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <input type="text" 
                                       name="option2_texte" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($question->getOption2Texte()) ?>"
                                       data-validate="true">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">Score</span>
                                    <input type="number" 
                                           name="option2_score" 
                                           class="form-control" 
                                           value="<?= $question->getOption2Score() ?>"
                                           min="1" max="10"
                                           data-validate="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Option 3 -->
                <div class="card mb-3 border-warning">
                    <div class="card-header bg-warning">
                        Option 3 <span class="text-danger">*</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <input type="text" 
                                       name="option3_texte" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($question->getOption3Texte()) ?>"
                                       data-validate="true">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">Score</span>
                                    <input type="number" 
                                           name="option3_score" 
                                           class="form-control" 
                                           value="<?= $question->getOption3Score() ?>"
                                           min="1" max="10"
                                           data-validate="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Option 4 (Facultative) -->
                <div class="card mb-3 border-danger">
                    <div class="card-header bg-danger text-white">
                        Option 4 (Facultative)
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <input type="text" 
                                       name="option4_texte" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($question->getOption4Texte() ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">Score</span>
                                    <input type="number" 
                                           name="option4_score" 
                                           class="form-control" 
                                           value="<?= $question->getOption4Score() ?? 4 ?>"
                                           min="1" max="10">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Validation JS -->
<script src="../../assets/js/validation.js"></script>

<?php include __DIR__ . '/../layout/footer_backoffice.php'; ?>