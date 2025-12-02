document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('activityForm');
    const titleInput = document.getElementById('title');
    const maxParticipantsInput = document.getElementById('max_participants');
    const titleError = document.getElementById('titleError');
    const participantsError = document.getElementById('participantsError');

    // Validation en temps réel du titre
    titleInput.addEventListener('input', function() {
        validateTitle();
    });

    // Validation en temps réel des participants
    maxParticipantsInput.addEventListener('input', function() {
        validateParticipants();
    });

    // Validation du formulaire à la soumission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isTitleValid = validateTitle();
        const isParticipantsValid = validateParticipants();
        
        if (isTitleValid && isParticipantsValid) {
            // Afficher un message de confirmation
            if (confirm('Êtes-vous sûr de vouloir créer cette activité ?')) {
                this.submit();
            }
        } else {
            // Afficher un message d'erreur général
            alert('Veuillez corriger les erreurs dans le formulaire avant de soumettre.');
        }
    });

    function validateTitle() {
        const title = titleInput.value.trim();
        
        if (title.length < 3) {
            showError(titleError, 'Le titre doit contenir au moins 3 caractères');
            return false;
        } else if (title.length > 255) {
            showError(titleError, 'Le titre ne peut pas dépasser 255 caractères');
            return false;
        } else {
            showSuccess(titleError, '✓ Titre valide');
            return true;
        }
    }

    function validateParticipants() {
        const participants = parseInt(maxParticipantsInput.value);
        
        if (isNaN(participants) || participants < 1) {
            showError(participantsError, 'Le nombre de participants doit être au moins 1');
            return false;
        } else if (participants > 1000) {
            showError(participantsError, 'Le nombre de participants ne peut pas dépasser 1000');
            return false;
        } else {
            showSuccess(participantsError, '✓ Nombre valide');
            return true;
        }
    }

    function showError(element, message) {
        element.textContent = message;
        element.style.color = '#e74c3c';
        element.style.fontWeight = '600';
    }

    function showSuccess(element, message) {
        element.textContent = message;
        element.style.color = '#4CAF50';
        element.style.fontWeight = '600';
    }

    // Validation de la date (doit être dans le futur)
    const dateTimeInput = document.getElementById('date_time');
    dateTimeInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const now = new Date();
        
        if (selectedDate <= now) {
            alert('⚠️ La date doit être dans le futur !');
            this.value = '';
        }
    });

    // Animation des champs au focus
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
            this.style.boxShadow = '0 0 0 3px rgba(76, 175, 80, 0.1)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });
});