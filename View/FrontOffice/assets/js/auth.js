/**
 * Auth Pages JS - HearMe
 * Login, Register, Forgot Password
 */

document.addEventListener('DOMContentLoaded', function() {
    // Password validation for register page
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const requirements = document.querySelectorAll('.password-requirements li');
            
            if (requirements.length >= 5) {
                requirements[0].style.color = password.length >= 8 ? '#2E7D32' : '#777';
                requirements[1].style.color = /[A-Z]/.test(password) ? '#2E7D32' : '#777';
                requirements[2].style.color = /[a-z]/.test(password) ? '#2E7D32' : '#777';
                requirements[3].style.color = /[0-9]/.test(password) ? '#2E7D32' : '#777';
                requirements[4].style.color = /[!@#$%^&*(),.?":{}|<>]/.test(password) ? '#2E7D32' : '#777';
            }
        });
    }

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const emailInput = form.querySelector('input[type="email"]');
            if (emailInput && emailInput.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value)) {
                    e.preventDefault();
                    alert('Veuillez entrer un email valide.');
                    return false;
                }
            }
        });
    });
});
