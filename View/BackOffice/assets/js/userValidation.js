/**
 * Validation JavaScript - HearMe
 * Emplacement : MON PROJET/View/BackOffice/assets/js/userValidation.js
 */

// ====================================
// VALIDATION EMAIL
// ====================================

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showEmailError(input, message) {
    const formGroup = input.closest('.form-group');
    let errorElement = formGroup.querySelector('.error-message');
    
    if (!errorElement) {
        errorElement = document.createElement('small');
        errorElement.className = 'error-message';
        errorElement.style.color = '#D32F2F';
        errorElement.style.display = 'block';
        errorElement.style.marginTop = '5px';
        formGroup.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
    input.style.borderColor = '#D32F2F';
}

function clearEmailError(input) {
    const formGroup = input.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');
    
    if (errorElement) {
        errorElement.remove();
    }
    
    input.style.borderColor = '#E0E0E0';
}

// ====================================
// VALIDATION MOT DE PASSE
// ====================================

function validatePassword(password) {
    const errors = [];
    
    if (password.length < 8) {
        errors.push('Au moins 8 caractères');
    }
    
    if (!/[A-Z]/.test(password)) {
        errors.push('Au moins une majuscule');
    }
    
    if (!/[a-z]/.test(password)) {
        errors.push('Au moins une minuscule');
    }
    
    if (!/[0-9]/.test(password)) {
        errors.push('Au moins un chiffre');
    }
    
    if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        errors.push('Au moins un caractère spécial');
    }
    
    return {
        isValid: errors.length === 0,
        errors: errors
    };
}

function showPasswordStrength(input, password) {
    const formGroup = input.closest('.form-group');
    let strengthElement = formGroup.querySelector('.password-strength');
    
    if (!strengthElement) {
        strengthElement = document.createElement('div');
        strengthElement.className = 'password-strength';
        strengthElement.style.marginTop = '8px';
        formGroup.appendChild(strengthElement);
    }
    
    const validation = validatePassword(password);
    
    if (password.length === 0) {
        strengthElement.innerHTML = '';
        return;
    }
    
    if (validation.isValid) {
        strengthElement.innerHTML = '<span style="color: #2E7D32;">✅ Mot de passe fort</span>';
    } else {
        strengthElement.innerHTML = `
            <span style="color: #D32F2F;">❌ Requis :</span>
            <ul style="margin: 5px 0 0 20px; font-size: 12px; color: #D32F2F;">
                ${validation.errors.map(err => `<li>${err}</li>`).join('')}
            </ul>
        `;
    }
}

// ====================================
// VALIDATION FORMULAIRE UTILISATEUR
// ====================================

function validateUserForm(formId) {
    const form = document.getElementById(formId);
    
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validation Email
        const emailInput = form.querySelector('[name="email"]');
        if (emailInput) {
            const email = emailInput.value.trim();
            
            if (email === '') {
                showEmailError(emailInput, 'L\'email est requis');
                isValid = false;
            } else if (!validateEmail(email)) {
                showEmailError(emailInput, 'Email invalide');
                isValid = false;
            } else {
                clearEmailError(emailInput);
            }
        }
        
        // Validation Mot de passe
        const passwordInput = form.querySelector('[name="password"]');
        if (passwordInput && passwordInput.value.trim() !== '') {
            const password = passwordInput.value;
            const validation = validatePassword(password);
            
            if (!validation.isValid) {
                e.preventDefault();
                alert('Le mot de passe ne respecte pas les critères requis:\n' + validation.errors.join('\n'));
                isValid = false;
            }
        }
        
        // Vérification des mots de passe correspondants
        const confirmPasswordInput = form.querySelector('[name="confirm_password"]');
        if (confirmPasswordInput && passwordInput) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Validation en temps réel pour l'email
    const emailInput = form.querySelector('[name="email"]');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            
            if (email === '') {
                showEmailError(this, 'L\'email est requis');
            } else if (!validateEmail(email)) {
                showEmailError(this, 'Email invalide');
            } else {
                clearEmailError(this);
            }
        });
        
        emailInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                clearEmailError(this);
            }
        });
    }
    
    // Validation en temps réel pour le mot de passe
    const passwordInput = form.querySelector('[name="password"]');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            showPasswordStrength(this, this.value);
        });
    }
}

// ====================================
// VALIDATION PROFIL
// ====================================

function validateProfilForm(formId) {
    const form = document.getElementById(formId);
    
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validation longueur bio
        const bioInput = form.querySelector('[name="bio"]');
        if (bioInput && bioInput.value.length > 1000) {
            alert('La bio ne peut pas dépasser 1000 caractères');
            isValid = false;
        }
        
        // Validation longueur compétences
        const competencesInput = form.querySelector('[name="competences"]');
        if (competencesInput && competencesInput.value.length > 500) {
            alert('Les compétences ne peuvent pas dépasser 500 caractères');
            isValid = false;
        }
        
        // Validation longueur formation
        const formationInput = form.querySelector('[name="formation"]');
        if (formationInput && formationInput.value.length > 255) {
            alert('La formation ne peut pas dépasser 255 caractères');
            isValid = false;
        }
        
        // Validation longueur expérience
        const experienceInput = form.querySelector('[name="experience"]');
        if (experienceInput && experienceInput.value.length > 1000) {
            alert('L\'expérience ne peut pas dépasser 1000 caractères');
            isValid = false;
        }
        
        // Validation URL réseaux sociaux
        const reseauxInput = form.querySelector('[name="reseaux_sociaux"]');
        if (reseauxInput && reseauxInput.value.trim() !== '') {
            const url = reseauxInput.value;
            try {
                new URL(url);
            } catch {
                alert('L\'URL des réseaux sociaux n\'est pas valide');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
}

// ====================================
// COMPTEUR DE CARACTÈRES
// ====================================

function addCharacterCounter(textareaId, maxLength) {
    const textarea = document.getElementById(textareaId);
    
    if (!textarea) return;
    
    const formGroup = textarea.closest('.form-group');
    let counterElement = formGroup.querySelector('.character-counter');
    
    if (!counterElement) {
        counterElement = document.createElement('small');
        counterElement.className = 'character-counter';
        counterElement.style.display = 'block';
        counterElement.style.marginTop = '5px';
        counterElement.style.color = '#999';
        formGroup.appendChild(counterElement);
    }
    
    function updateCounter() {
        const remaining = maxLength - textarea.value.length;
        
        if (remaining < 0) {
            counterElement.style.color = '#D32F2F';
            counterElement.textContent = `Vous avez dépassé de ${Math.abs(remaining)} caractères`;
        } else {
            counterElement.style.color = '#999';
            counterElement.textContent = `${remaining} caractères restants`;
        }
    }
    
    textarea.addEventListener('input', updateCounter);
    updateCounter();
}

// ====================================
// CONFIRMATION DE SUPPRESSION
// ====================================

function confirmDelete(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
    return confirm(message);
}

function addDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('.btn-delete, [data-action="delete"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const customMessage = this.getAttribute('data-confirm-message');
            const message = customMessage || 'Êtes-vous sûr de vouloir supprimer cet élément ?';
            
            if (!confirmDelete(message)) {
                e.preventDefault();
            }
        });
    });
}

// ====================================
// INITIALISATION
// ====================================

document.addEventListener('DOMContentLoaded', function() {
    // Validation des formulaires utilisateur
    validateUserForm('userForm');
    validateUserForm('addUserForm');
    validateUserForm('editUserForm');
    validateUserForm('loginForm');
    validateUserForm('registerForm');
    
    // Validation des formulaires profil
    validateProfilForm('profilForm');
    validateProfilForm('editProfilForm');
    
    // Compteurs de caractères
    addCharacterCounter('bio', 1000);
    addCharacterCounter('competences', 500);
    addCharacterCounter('experience', 1000);
    addCharacterCounter('preferences', 500);
    addCharacterCounter('autre_theme', 500);
    
    // Confirmation de suppression
    addDeleteConfirmation();
});

// ====================================
// UTILITAIRES
// ====================================

function showLoadingButton(button, loadingText = 'Chargement...') {
    const originalText = button.textContent;
    button.textContent = loadingText;
    button.disabled = true;
    button.classList.add('loading');
    
    return function() {
        button.textContent = originalText;
        button.disabled = false;
        button.classList.remove('loading');
    };
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.style.animation = 'fadeIn 0.5s ease';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'fadeOut 0.5s ease';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

// ====================================
// EXPORT DES FONCTIONS
// ====================================

window.HearMeValidation = {
    validateEmail,
    validatePassword,
    validateUserForm,
    validateProfilForm,
    confirmDelete,
    showNotification,
    showLoadingButton,
    addCharacterCounter
};