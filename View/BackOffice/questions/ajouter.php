<?php 
require_once __DIR__ . '/../../../config.php';
secureSession();
if (!isAdmin()) redirect('../FrontOffice/Login.php');

$pageTitle = "Ajouter une Question - HearMe Admin";
$additionalCss = '<link rel="stylesheet" href="/hearme_user/View/BackOffice/assets/css/admin-questions.css">';
include __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <a href="/hearme_user/Controller/QuestionController.php?action=liste&id_quiz=<?= $id_quiz ?>" class="btn-dark mb-3" style="display: inline-flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Retour aux Questions
    </a>
    <h1>Ajouter une Question</h1>
    <p>Créer une nouvelle question pour le quiz</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="form-dark full-width">
            <form action="/hearme_user/Controller/QuestionController.php?action=store" method="POST">
                <input type="hidden" name="id_quiz" value="<?= $id_quiz ?>">
                
                <div class="row mb-4">
                    <div class="col-md-9">
                        <label class="form-label">Question <span class="required-indicator">*</span></label>
                        <textarea name="texte_question" class="form-control" rows="2" placeholder="Entrez votre question..." required></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Ordre</label>
                        <input type="number" name="ordre" class="form-control" value="1" min="1">
                    </div>
                </div>

                <h5 class="section-title-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    Options de réponse
                </h5>
                
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="option-card">
                    <h6>Option <?= $i ?> <?= $i <= 3 ? '*' : '(optionnelle)' ?></h6>
                    <div class="row">
                        <div class="col-md-9">
                            <input type="text" name="option<?= $i ?>_texte" class="form-control" placeholder="Texte de l'option <?= $i ?>" <?= $i <= 3 ? 'required' : '' ?>>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="option<?= $i ?>_score" class="form-control" value="<?= $i ?>" min="1" max="10" placeholder="Score">
                        </div>
                    </div>
                </div>
                <?php endfor; ?>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-gradient">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Enregistrer
                    </button>
                    <a href="/hearme_user/Controller/QuestionController.php?action=liste&id_quiz=<?= $id_quiz ?>" class="btn-dark">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
