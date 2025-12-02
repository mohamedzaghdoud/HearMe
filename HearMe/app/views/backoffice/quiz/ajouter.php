<?php include __DIR__ . '/../layout/header_backoffice.php'; ?>

<div class="container-fluid py-4">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle"></i> Ajouter un Quiz</h2>
        <a href="QuizController.php?action=liste" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="QuizController.php?action=ajouter" id="formQuiz">
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Titre du Quiz <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="titre" 
                                   class="form-control" 
                                   placeholder="Ex: Évaluation du Stress"
                                   data-validate="true">
                            <div class="invalid-feedback">Le titre est obligatoire.</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                Catégorie <span class="text-danger">*</span>
                            </label>
                            <select name="categorie" class="form-select" data-validate="true">
                                <option value="">-- Choisir une catégorie --</option>
                                <option value="stress">Stress</option>
                                <option value="anxiété">Anxiété</option>
                                <option value="fatigue">Fatigue</option>
                                <option value="joie">Joie</option>
                                <option value="général">Général</option>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner une catégorie.</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" 
                                      class="form-control" 
                                      rows="3"
                                      placeholder="Décrivez brièvement ce quiz..."></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                          <label class="form-label fw-bold">Image du Quiz</label>
                          <input type="text" 
                              name="image" 
                              class="form-control" 
                              placeholder="Ex: stress.jpg"
                              value="<?= isset($quiz) ? htmlspecialchars($quiz->getImage()) : '' ?>">
                          <small class="text-muted">Nom du fichier dans assets/images/quiz/</small>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Durée (min)</label>
                            <input type="number" 
                                   name="duree_estimee" 
                                   class="form-control" 
                                   value="5"
                                   min="1"
                                   max="60">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <select name="actif" class="form-select">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Enregistrer le Quiz
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Validation JS -->
<script src="../../assets/js/validation.js"></script>

<?php include __DIR__ . '/../layout/footer_backoffice.php'; ?>