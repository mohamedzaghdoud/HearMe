/**
 * JavaScript pour le footer
 * HearMe FrontOffice
 */

document.addEventListener('DOMContentLoaded', function() {
    initScrollTopButton();
});

/**
 * Initialise le bouton retour en haut
 */
function initScrollTopButton() {
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    
    if (!scrollTopBtn) return;
    
    // Afficher/masquer le bouton selon le scroll
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    });
    
    // Action au clic
    scrollTopBtn.addEventListener('click', function() {
        window.scrollTo({ 
            top: 0, 
            behavior: 'smooth' 
        });
    });
}
