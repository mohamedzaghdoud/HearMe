<?php 
require_once __DIR__ . '/../../../config.php';
secureSession();
if (!isAdmin()) redirect('../FrontOffice/Login.php');

$pageTitle = "Ajouter un Quiz - HearMe Admin";
include __DIR__ . '/../layout/header.php';
?>

<div class="page-header">
    <h1>Ajouter un Quiz</h1>
    <p>Créer un nouveau quiz pour les tests émotionnels</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-dark full-width">
            <form action="/hearme_user/Controller/QuizController.php?action=store" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label">Titre <span class="required-indicator">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Titre du quiz" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Description du quiz..."></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Catégorie <span class="required-indicator">*</span></label>
                        <select name="categorie" class="form-select" required>
                            <option value="stress">Stress</option>
                            <option value="anxiété">Anxiété</option>
                            <option value="dépression">Dépression</option>
                            <option value="général">Général</option>
                            <option value="bien-être">Bien-être</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Durée estimée (min)</label>
                        <input type="number" name="duree_estimee" class="form-control" value="5" min="1" max="60">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" name="actif" class="form-check-input" id="actif" checked>
                    <label class="form-check-label" for="actif">Quiz actif</label>
                </div>
                
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn-gradient">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Enregistrer
                    </button>
                    <a href="/hearme_user/Controller/QuizController.php?action=liste" class="btn-dark">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
