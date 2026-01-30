<?php 
require_once __DIR__ . '/../../../config.php';
secureSession();
if (!isAdmin()) redirect('../FrontOffice/Login.php');

$pageTitle = "Modifier Quiz - HearMe Admin";
include __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <h1>Modifier le Quiz</h1>
    <p>Modifier les informations du quiz</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-dark full-width">
            <form action="/hearme_user/Controller/QuizController.php?action=update&id=<?= $quiz['id_quiz'] ?>" method="POST">
                <div class="mb-4">
                    <label class="form-label">Titre <span class="required-indicator">*</span></label>
                    <input type="text" name="titre" class="form-control" value="<?= e($quiz['titre']) ?>" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= e($quiz['description']) ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Catégorie <span class="required-indicator">*</span></label>
                        <select name="categorie" class="form-select" required>
                            <option value="stress" <?= $quiz['categorie'] == 'stress' ? 'selected' : '' ?>>Stress</option>
                            <option value="anxiété" <?= $quiz['categorie'] == 'anxiété' ? 'selected' : '' ?>>Anxiété</option>
                            <option value="dépression" <?= $quiz['categorie'] == 'dépression' ? 'selected' : '' ?>>Dépression</option>
                            <option value="général" <?= $quiz['categorie'] == 'général' ? 'selected' : '' ?>>Général</option>
                            <option value="bien-être" <?= $quiz['categorie'] == 'bien-être' ? 'selected' : '' ?>>Bien-être</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Durée estimée (min)</label>
                        <input type="number" name="duree_estimee" class="form-control" value="<?= $quiz['duree_estimee'] ?>" min="1" max="60">
                    </div>
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" name="actif" class="form-check-input" id="actif" <?= $quiz['actif'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="actif">Quiz actif</label>
                </div>
                
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-gradient">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Modifier
                    </button>
                    <a href="/hearme_user/Controller/QuizController.php?action=liste" class="btn-dark">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
