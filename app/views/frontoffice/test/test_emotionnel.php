<?php include __DIR__ . '/../layout/header_frontoffice.php'; ?>

<section class="test-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient text-white text-center py-4" 
                         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h3 class="mb-0">
                            <i class="fas fa-heart-pulse"></i> Test d'Évaluation Émotionnelle
                        </h3>
                        <p class="mb-0 mt-2">Réponds honnêtement à chaque question</p>
                    </div>

                    <div class="card-body p-5">
                        <!-- Barre de progression -->
                        <div class="progress mb-4" style="height: 25px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                 id="progressBar" role="progressbar" style="width: 0%">
                                <span id="progressText">Question 0 sur <?= count($questions) ?></span>
                            </div>
                        </div>

                        <form method="POST" action="?action=enregistrer" id="formTest">
                            <?php 
                            $questionNumber = 1;
                            foreach($questions as $question): 
                            ?>
                            <div class="question-block mb-5 p-4 border rounded" 
                                 style="background: #f8f9fa;">
                                <h5 class="mb-4">
                                    <span class="badge badge-primary">Q<?= $questionNumber ?></span>
                                    <?= htmlspecialchars($question['texte_question']) ?>
                                </h5>

                                <div class="options-container">
                                    <?php foreach($question['options'] as $option): ?>
                                    <div class="form-check mb-3 p-3 option-item" 
                                         style="border: 2px solid #e0e0e0; border-radius: 8px; 
                                                cursor: pointer; transition: all 0.3s;">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="reponses[<?= $question['id_question'] ?>]" 
                                               id="option_<?= $option['id_option'] ?>"
                                               value="<?= $option['valeur_score'] ?>"
                                               required>
                                        <label class="form-check-label w-100" 
                                               for="option_<?= $option['id_option'] ?>"
                                               style="cursor: pointer;">
                                            <?= htmlspecialchars($option['texte_option']) ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
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
            </div>
        </div>
    </div>
</section>

<style>
.option-item:hover {
    border-color: #667eea !important;
    background: #f0f4ff !important;
    transform: translateX(5px);
}

.option-item input:checked ~ label {
    font-weight: bold;
    color: #667eea;
}

.option-item:has(input:checked) {
    border-color: #667eea !important;
    background: #e8f0ff !important;
}

.question-block {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
// Mise à jour de la barre de progression
const form = document.getElementById('formTest');
const totalQuestions = <?= count($questions) ?>;
const progressBar = document.getElementById('progressBar');
const progressText = document.getElementById('progressText');

form.addEventListener('change', function() {
    const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
    const progress = (answeredQuestions / totalQuestions) * 100;
    
    progressBar.style.width = progress + '%';
    progressText.textContent = `Question ${answeredQuestions} sur ${totalQuestions}`;
    
    if(answeredQuestions === totalQuestions) {
        progressBar.classList.remove('bg-success');
        progressBar.classList.add('bg-primary');
    }
});

// Validation avant soumission
form.addEventListener('submit', function(e) {
    const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
    if(answeredQuestions < totalQuestions) {
        e.preventDefault();
        alert('Merci de répondre à toutes les questions !');
    }
});
</script>

<?php include __DIR__ . '/../layout/footer_frontoffice.php'; ?>