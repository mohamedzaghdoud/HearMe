<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h2>Modifier une Question Émotionnelle</h2>
                    <a href="?action=liste" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="?action=modifier" id="formQuestion">
                            <input type="hidden" name="id_question" value="<?= $this->question->id_question ?>">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label>Texte de la question *</label>
                                        <input type="text" name="texte_question" 
                                               class="form-control"
                                               value="<?= htmlspecialchars($this->question->texte_question) ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group mb-3">
                                        <label>Catégorie *</label>
                                        <select name="categorie" class="form-control">
                                            <option value="stress" <?= $this->question->categorie == 'stress' ? 'selected' : '' ?>>Stress</option>
                                            <option value="anxiété" <?= $this->question->categorie == 'anxiété' ? 'selected' : '' ?>>Anxiété</option>
                                            <option value="fatigue" <?= $this->question->categorie == 'fatigue' ? 'selected' : '' ?>>Fatigue</option>
                                            <option value="joie" <?= $this->question->categorie == 'joie' ? 'selected' : '' ?>>Joie</option>
                                            <option value="général" <?= $this->question->categorie == 'général' ? 'selected' : '' ?>>Général</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group mb-3">
                                        <label>Ordre *</label>
                                        <input type="number" name="ordre" 
                                               class="form-control" 
                                               value="<?= $this->question->ordre ?>">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h4>Options de réponse</h4>
                            <div id="optionsContainer">
                                <?php 
                                $index = 0;
                                while($opt = $options->fetch(PDO::FETCH_ASSOC)): 
                                ?>
                                <div class="option-item card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label>Texte de l'option</label>
                                                <input type="text" name="options[<?= $index ?>][texte]" 
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($opt['texte_option']) ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Score (poids)</label>
                                                <input type="number" name="options[<?= $index ?>][score]" 
                                                       class="form-control" 
                                                       value="<?= $opt['valeur_score'] ?>">
                                            </div>
                                            <div class="col-md-1">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-block btn-remove-option">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                $index++;
                                endwhile; 
                                ?>
                            </div>

                            <button type="button" class="btn btn-success mb-3" id="btnAjouterOption">
                                <i class="fas fa-plus"></i> Ajouter une option
                            </button>

                            <hr>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../assets/js/validation.js"></script>

<script>
let optionIndex = <?= $index ?>;

document.getElementById('btnAjouterOption').addEventListener('click', function() {
    const container = document.getElementById('optionsContainer');
    const newOption = document.createElement('div');
    newOption.className = 'option-item card mb-3';
    newOption.innerHTML = `
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <label>Texte de l'option</label>
                    <input type="text" name="options[${optionIndex}][texte]" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Score (poids)</label>
                    <input type="number" name="options[${optionIndex}][score]" class="form-control" value="1">
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-block btn-remove-option">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.appendChild(newOption);
    optionIndex++;
});

document.addEventListener('click', function(e) {
    if(e.target.closest('.btn-remove-option')) {
        if(document.querySelectorAll('.option-item').length > 1) {
            e.target.closest('.option-item').remove();
        } else {
            alert('Vous devez avoir au moins une option !');
        }
    }
});
</script>

<?php include __DIR__ . '/../layout/footer_backoffice.php'; ?>
