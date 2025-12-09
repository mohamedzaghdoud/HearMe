/**
 * HearMe Forum - BackOffice JavaScript
 * Gestion interactive du panneau d'administration
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeBackOffice();
});

/**
 * Initialise toutes les fonctionnalités du BackOffice
 */
function initializeBackOffice() {
    // Initialiser les tooltips
    initTooltips();
    
    // Initialiser les confirmations de suppression
    initDeleteConfirmations();
    
    // Initialiser les formulaires
    initForms();
    
    // Initialiser les tableaux triables
    initSortableTables();
    
    // Initialiser la recherche
    initSearch();
    
    // Initialiser les notifications
    initNotifications();
    
    // Initialiser les animations
    initAnimations();
    
    // Mettre à jour les statistiques en temps réel
    updateLiveStats();
}

/**
 * Initialise les tooltips
 */
function initTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = tooltipText;
            tooltip.style.position = 'absolute';
            tooltip.style.background = 'rgba(0, 0, 0, 0.8)';
            tooltip.style.color = 'white';
            tooltip.style.padding = '5px 10px';
            tooltip.style.borderRadius = '4px';
            tooltip.style.fontSize = '12px';
            tooltip.style.zIndex = '10000';
            tooltip.style.whiteSpace = 'nowrap';
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
            
            this.tooltipElement = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this.tooltipElement) {
                this.tooltipElement.remove();
                this.tooltipElement = null;
            }
        });
    });
}

/**
 * Initialise les confirmations de suppression
 */
function initDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('.delete-btn, .delete-item, [onclick*="delete"]');
    
    deleteButtons.forEach(button => {
        if (!button.hasAttribute('data-confirmed')) {
            button.addEventListener('click', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ? Cette action est irréversible.')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
            button.setAttribute('data-confirmed', 'true');
        }
    });
}

/**
 * Initialise les formulaires
 */
function initForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Validation en temps réel
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
        
        // Soumission avec feedback
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showNotification('Veuillez corriger les erreurs dans le formulaire.', 'error');
            } else {
                showNotification('Traitement en cours...', 'info');
                // Ajouter un indicateur de chargement
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> En cours...';
                    submitBtn.disabled = true;
                }
            }
        });
    });
}

/**
 * Valide un champ de formulaire
 */
function validateField(field) {
    const value = field.value.trim();
    const errorElement = field.nextElementSibling?.classList.contains('error-message') 
        ? field.nextElementSibling 
        : document.createElement('div');
    
    if (!errorElement.classList.contains('error-message')) {
        errorElement.className = 'error-message';
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }
    
    errorElement.textContent = '';
    
    if (field.hasAttribute('required') && !value) {
        errorElement.textContent = 'Ce champ est obligatoire.';
        field.classList.add('error');
        return false;
    }
    
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            errorElement.textContent = 'Veuillez entrer une adresse email valide.';
            field.classList.add('error');
            return false;
        }
    }
    
    if (field.hasAttribute('minlength')) {
        const minLength = parseInt(field.getAttribute('minlength'));
        if (value.length < minLength) {
            errorElement.textContent = `Minimum ${minLength} caractères requis.`;
            field.classList.add('error');
            return false;
        }
    }
    
    if (field.hasAttribute('maxlength')) {
        const maxLength = parseInt(field.getAttribute('maxlength'));
        if (value.length > maxLength) {
            errorElement.textContent = `Maximum ${maxLength} caractères autorisés.`;
            field.classList.add('error');
            return false;
        }
    }
    
    field.classList.remove('error');
    return true;
}

/**
 * Efface l'erreur d'un champ
 */
function clearFieldError(field) {
    field.classList.remove('error');
    const errorElement = field.nextElementSibling;
    if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.textContent = '';
    }
}

/**
 * Valide un formulaire complet
 */
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Initialise les tableaux triables
 */
function initSortableTables() {
    const tables = document.querySelectorAll('table.sortable');
    
    tables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const columnIndex = Array.from(this.parentNode.children).indexOf(this);
                const sortDirection = this.classList.contains('sort-asc') ? 'desc' : 'asc';
                
                // Réinitialiser les autres headers
                headers.forEach(h => {
                    h.classList.remove('sort-asc', 'sort-desc');
                });
                
                // Appliquer la direction actuelle
                this.classList.add('sort-' + sortDirection);
                
                // Trier le tableau
                sortTable(table, columnIndex, sortDirection);
            });
        });
    });
}

/**
 * Trie un tableau
 */
function sortTable(table, columnIndex, direction) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aText = a.children[columnIndex].textContent.trim();
        const bText = b.children[columnIndex].textContent.trim();
        
        // Essayer de comparer comme des nombres
        const aNum = parseFloat(aText);
        const bNum = parseFloat(bText);
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }
        
        // Sinon comparer comme du texte
        return direction === 'asc' 
            ? aText.localeCompare(bText)
            : bText.localeCompare(aText);
    });
    
    // Réorganiser les lignes
    rows.forEach(row => tbody.appendChild(row));
}

/**
 * Initialise la fonctionnalité de recherche
 */
function initSearch() {
    const searchInputs = document.querySelectorAll('.search-box input');
    
    searchInputs.forEach(input => {
        const searchBox = input.closest('.search-box');
        const clearBtn = document.createElement('button');
        clearBtn.className = 'search-clear';
        clearBtn.innerHTML = '<i class="fas fa-times"></i>';
        clearBtn.style.display = 'none';
        
        searchBox.appendChild(clearBtn);
        
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            clearBtn.style.display = searchTerm ? 'block' : 'none';
            
            // Recherche dans le tableau ou la liste
            const container = this.closest('.admin-content');
            if (container) {
                performSearch(container, searchTerm);
            }
        });
        
        clearBtn.addEventListener('click', function() {
            input.value = '';
            this.style.display = 'none';
            input.dispatchEvent(new Event('input'));
            input.focus();
        });
    });
}

/**
 * Effectue une recherche
 */
function performSearch(container, searchTerm) {
    const tables = container.querySelectorAll('table');
    const lists = container.querySelectorAll('.list-item, .card, .accordion-item');
    
    // Recherche dans les tableaux
    tables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
    
    // Recherche dans les listes
    lists.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

/**
 * Initialise le système de notifications
 */
function initNotifications() {
    // Vérifier les notifications non lues
    checkUnreadNotifications();
    
    // Simuler des notifications système
    setInterval(() => {
        if (Math.random() > 0.7) {
            showRandomNotification();
        }
    }, 30000);
}

/**
 * Vérifie les notifications non lues
 */
function checkUnreadNotifications() {
    // Simuler une vérification AJAX
    setTimeout(() => {
        const hasUnread = Math.random() > 0.5;
        if (hasUnread) {
            const notificationBadge = document.querySelector('.notification-badge');
            if (notificationBadge) {
                notificationBadge.textContent = '1';
                notificationBadge.style.display = 'flex';
                
                // Afficher une notification toast
                showNotification('Nouvelle notification système', 'info');
            }
        }
    }, 2000);
}

/**
 * Affiche une notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-icon">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
        </div>
        <div class="notification-content">
            <p>${message}</p>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Styles pour la notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getNotificationColor(type)};
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        max-width: 400px;
    `;
    
    document.body.appendChild(notification);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

/**
 * Affiche une notification aléatoire (simulation)
 */
function showRandomNotification() {
    const notifications = [
        { message: 'Nouveau commentaire ajouté', type: 'success' },
        { message: 'Post modifié avec succès', type: 'success' },
        { message: 'Attention: activité suspecte détectée', type: 'warning' },
        { message: 'Backup automatique effectué', type: 'info' },
        { message: 'Mise à jour des statistiques', type: 'info' }
    ];
    
    const randomNotif = notifications[Math.floor(Math.random() * notifications.length)];
    showNotification(randomNotif.message, randomNotif.type);
}

/**
 * Retourne l'icône appropriée pour le type de notification
 */
function getNotificationIcon(type) {
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

/**
 * Retourne la couleur appropriée pour le type de notification
 */
function getNotificationColor(type) {
    const colors = {
        'success': '#00b894',
        'error': '#d63031',
        'warning': '#fdcb6e',
        'info': '#4a6bdf'
    };
    return colors[type] || '#4a6bdf';
}

/**
 * Initialise les animations
 */
function initAnimations() {
    // Animation des cartes au survol
    const cards = document.querySelectorAll('.card, .stat-card, .dashboard-card');
    cards.forEach(card => {
        card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
    
    // Animation des boutons
    const buttons = document.querySelectorAll('button, .btn, .nav-item');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Effet de ripple
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.7);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Ajouter l'animation ripple au CSS
    if (!document.querySelector('#ripple-animation')) {
        const style = document.createElement('style');
        style.id = 'ripple-animation';
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

/**
 * Met à jour les statistiques en temps réel
 */
function updateLiveStats() {
    // Simuler des mises à jour en temps réel
    setInterval(() => {
        const statsCards = document.querySelectorAll('.stat-card h3');
        statsCards.forEach(card => {
            const currentValue = parseInt(card.textContent);
            if (!isNaN(currentValue)) {
                const change = Math.random() > 0.5 ? 1 : -1;
                const newValue = Math.max(0, currentValue + change);
                card.textContent = newValue;
                
                // Mettre à jour l'animation de tendance
                const trendElement = card.closest('.stat-card').querySelector('.stat-trend');
                if (trendElement) {
                    trendElement.innerHTML = change > 0 
                        ? '<i class="fas fa-arrow-up"></i> +1' 
                        : '<i class="fas fa-arrow-down"></i> -1';
                    trendElement.className = change > 0 ? 'stat-trend up' : 'stat-trend down';
                }
            }
        });
    }, 10000); // Mettre à jour toutes les 10 secondes
}

/**
 * Exporte des données (simulation)
 */
function exportData(format = 'csv') {
    showNotification(`Export ${format} en cours...`, 'info');
    
    // Simuler un export
    setTimeout(() => {
        showNotification(`Export ${format} terminé avec succès`, 'success');
        
        // Créer un lien de téléchargement simulé
        const data = 'ID,Contenu,Likes,Commentaires\n1,Exemple de post,15,3\n2,Autre post,8,1';
        const blob = new Blob([data], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `forum-data-${new Date().toISOString().split('T')[0]}.${format}`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }, 2000);
}

/**
 * Bascule le mode sombre/clair
 */
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
    showNotification(`Mode ${isDarkMode ? 'sombre' : 'clair'} activé`, 'info');
}

/**
 * Vérifie et applique le mode préféré
 */
function checkDarkModePreference() {
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedMode = localStorage.getItem('darkMode');
    
    if (savedMode === 'true' || (savedMode === null && prefersDark)) {
        document.body.classList.add('dark-mode');
    }
}

// Initialiser le mode sombre au chargement
checkDarkModePreference();

/**
 * Rafraîchit les données de la page
 */
function refreshPageData() {
    const refreshBtn = document.querySelector('[onclick="refreshPageData()"]');
    if (refreshBtn) {
        const originalHTML = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        refreshBtn.disabled = true;
        
        showNotification('Actualisation des données...', 'info');
        
        setTimeout(() => {
            location.reload();
        }, 1500);
    }
}

/**
 * Affiche/masque le mot de passe
 */
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.type = input.type === 'password' ? 'text' : 'password';
    }
}

// Exposer les fonctions globales
window.showNotification = showNotification;
window.exportData = exportData;
window.toggleDarkMode = toggleDarkMode;
window.refreshPageData = refreshPageData;
window