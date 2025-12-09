/**
 * JavaScript Front Office - HearMe
 * Emplacement : View/FrontOffice/assets/js/frontoffice.js
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // ====================================
    // UTILITAIRES
    // ====================================
    const HearMe = {
        validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        },

        validatePassword(pwd) {
            const errors = [];
            if (pwd.length < 8) errors.push('8 caractères minimum');
            if (!/[A-Z]/.test(pwd)) errors.push('1 majuscule');
            if (!/[a-z]/.test(pwd)) errors.push('1 minuscule');
            if (!/[0-9]/.test(pwd)) errors.push('1 chiffre');
            if (!/[!@#$%^&*(),.?":{}|<>]/.test(pwd)) errors.push('1 caractère spécial');
            return { isValid: errors.length === 0, errors };
        },

        addCharacterCounter(id, max) {
            const field = document.getElementById(id);
            if (!field) return;

            let counter = field.parentNode.querySelector('.char-counter');
            if (!counter) {
                counter = document.createElement('small');
                counter.className = 'char-counter';
                field.parentNode.appendChild(counter);
            }

            const update = () => {
                const remaining = max - field.value.length;
                if (remaining < 0) {
                    counter.style.color = '#D32F2F';
                    counter.textContent = `Dépassé de ${-remaining} caractère(s)`;
                } else if (remaining <= 50) {
                    counter.style.color = '#FF9800';
                    counter.textContent = `${remaining} restants`;
                } else {
                    counter.style.color = '#666';
                    counter.textContent = `${remaining} caractères restants`;
                }
            };
            field.addEventListener('input', update);
            update();
        },

        notify(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type}`;
            toast.textContent = message;
            toast.style.cssText = `
                position: fixed; top: 20px; right: 20px; z-index: 9999;
                min-width: 300px; animation: slideIn 0.4s ease;
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
    };

    // ====================================
    // VALIDATION FORMULAIRES
    // ====================================
    
    // Login Form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const email = this.querySelector('#email')?.value.trim();
            const password = this.querySelector('#password')?.value;
            const captcha = this.querySelector('#captcha')?.value.trim();

            if (!email || !password || !captcha) {
                e.preventDefault();
                HearMe.notify('Veuillez remplir tous les champs', 'error');
                return;
            }
            if (!HearMe.validateEmail(email)) {
                e.preventDefault();
                HearMe.notify('Email invalide', 'error');
                return;
            }
        });
    }

    // Register Form
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        const pwdInput = registerForm.querySelector('#password');

        if (pwdInput) {
            pwdInput.addEventListener('input', function () {
                const { isValid, errors } = HearMe.validatePassword(this.value);
                const requirements = document.querySelectorAll('.password-requirements li');
                
                if (this.value === '') {
                    requirements.forEach(li => li.style.color = '#7A7A7A');
                } else if (isValid) {
                    requirements.forEach(li => li.style.color = '#2E7D32');
                } else {
                    requirements[0].style.color = this.value.length >= 8 ? '#2E7D32' : '#7A7A7A';
                    requirements[1].style.color = /[A-Z]/.test(this.value) ? '#2E7D32' : '#7A7A7A';
                    requirements[2].style.color = /[a-z]/.test(this.value) ? '#2E7D32' : '#7A7A7A';
                    requirements[3].style.color = /[0-9]/.test(this.value) ? '#2E7D32' : '#7A7A7A';
                    requirements[4].style.color = /[!@#$%^&*(),.?":{}|<>]/.test(this.value) ? '#2E7D32' : '#7A7A7A';
                }
            });
        }

        registerForm.addEventListener('submit', function (e) {
            const email = this.querySelector('#email')?.value.trim();
            const pwd = pwdInput?.value;
            const confirm = this.querySelector('#confirm_password')?.value;
            const captcha = this.querySelector('#captcha')?.value.trim();

            if (!email || !pwd || !confirm || !captcha) {
                e.preventDefault();
                HearMe.notify('Tous les champs sont requis', 'error');
                return;
            }
            if (!HearMe.validateEmail(email)) {
                e.preventDefault();
                HearMe.notify('Email invalide', 'error');
                return;
            }
            if (!HearMe.validatePassword(pwd).isValid) {
                e.preventDefault();
                HearMe.notify('Mot de passe trop faible', 'error');
                return;
            }
            if (pwd !== confirm) {
                e.preventDefault();
                HearMe.notify('Les mots de passe ne correspondent pas', 'error');
                return;
            }
        });
    }

    // Profil Form
    const profilForm = document.getElementById('profilForm');
    if (profilForm) {
        HearMe.addCharacterCounter('bio', 1000);
        HearMe.addCharacterCounter('competences', 500);
        HearMe.addCharacterCounter('formation', 255);
        HearMe.addCharacterCounter('experience', 1000);
        HearMe.addCharacterCounter('preferences', 500);
        HearMe.addCharacterCounter('autre_theme', 500);
    }

    // Messages flash
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === '1') {
        HearMe.notify('Opération réussie !', 'success');
    }
    if (urlParams.get('error')) {
        HearMe.notify('Une erreur est survenue', 'error');
    }
});