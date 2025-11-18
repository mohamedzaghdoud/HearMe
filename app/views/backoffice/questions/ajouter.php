<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h2>Ajouter une Question Émotionnelle</h2>
                    <a href="?action=liste" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="?action=ajouter" id="formQuestion">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Texte de la question *</label>
                                        <input type="text" name="texte_question" 
                                               class="form-control"
                                               placeholder="Ex: Comment te sens-tu aujourd'hui ?">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Catégorie *</label>
                                        <select name="categorie" class="form-control">
                                            <option value="">-- Choisir --</option>
                                            <option value="stress">Stress</option>
                                            <option value="anxiété">Anxiété</option>
                                            <option value="fatigue">Fatigue</option>
                                            <option value="joie">Joie</option>
                                            <option value="général">Général</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Ordre *</label>
                                        <input type="text" name="ordre" class="form-control" value="1">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h4>Options de réponse</h4>
                            <div id="optionsContainer">
                                <div class="option-item card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label>Texte de l'option</label>
                                                <input type="text" name="options[0][texte]" 
                                                       class="form-control"
                                                       placeholder="Ex: Je me sens très bien">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Score (poids)</label>
                                                <input type="text" name="options[0][score]" 
                                                       class="form-control"
                                                       value="1">
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
                            </div>

                            <button type="button" class="btn btn-success mb-3" id="btnAjouterOption">
                                <i class="fas fa-plus"></i> Ajouter une option
                            </button>

                            <hr>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Enregistrer la question
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🔹 Script pour ajouter/supprimer les options dynamiquement -->
<script>
let optionIndex = 1;

document.getElementById('btnAjouterOption').addEventListener('click', function() {
    const container = document.getElementById('optionsContainer');
    const newOption = document.createElement('div');
    newOption.className = 'option-item card mb-3';
    newOption.innerHTML = `
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <label>Texte de l'option</label>
                    <input type="text" name="options[${optionIndex}][texte]" 
                           class="form-control"
                           placeholder="Ex: Je me sens stressé">
                </div>
                <div class="col-md-3">
                    <label>Score (poids)</label>
                    <input type="text" name="options[${optionIndex}][score]" 
                           class="form-control" value="2">
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
            alert('⚠️ Vous devez avoir au moins une option !');
        }
    }
});
</script>

<!-- 🔹 Script de validation JavaScript -->
<script src="../../assets/js/validation.js"></script>

<?php include __DIR__ . '/../layout/footer_backoffice.php'; ?>
