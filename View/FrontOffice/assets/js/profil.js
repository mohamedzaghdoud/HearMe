/**
 * Profil Page JS - HearMe
 */

document.addEventListener('DOMContentLoaded', function() {
    const profilForm = document.getElementById('profilForm');
    
    if (profilForm) {
        profilForm.addEventListener('submit', function(e) {
            const bio = document.getElementById('bio').value;
            const competences = document.getElementById('competences').value;
            
            if (bio.length > 1000) {
                e.preventDefault();
                alert('La bio ne peut pas dépasser 1000 caractères.');
                return false;
            }
            if (competences.length > 500) {
                e.preventDefault();
                alert('Les compétences ne peuvent pas dépasser 500 caractères.');
                return false;
            }
            return true;
        });
    }
});
