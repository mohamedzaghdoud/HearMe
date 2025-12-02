<!-- ***** Footer Start ***** -->
<footer class="professional-footer">
    <div class="container">
        <!-- Section Principale -->
        <div class="row footer-content">
            <!-- Colonne 1: À propos -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-section">
                    <img src="../../assets/template/images/logo.png" alt="HearMe Logo" class="footer-logo mb-3">
                    <p class="footer-description">
                        HearMe est votre partenaire de confiance pour le bien-être mental. 
                        Évaluez votre état émotionnel et suivez votre évolution.
                    </p>
                    <div class="footer-stats">
                        <div class="stat-item">
                            <i class="fa fa-users"></i>
                            <span>+1000 utilisateurs</span>
                        </div>
                        <div class="stat-item">
                            <i class="fa fa-heart"></i>
                            <span>Bien-être garanti</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne 2: Liens rapides -->
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-section">
                    <h5 class="footer-title">Navigation</h5>
                    <ul class="footer-links">
                        <li><a href="../../index.php"><i class="fa fa-angle-right"></i> Accueil</a></li>
                        <li><a href="TestController.php"><i class="fa fa-angle-right"></i> Tests Émotionnels</a></li>
                        <li><a href="TestController.php?action=historique"><i class="fa fa-angle-right"></i> Mon Historique</a></li>
                        <li><a href="#about"><i class="fa fa-angle-right"></i> À propos</a></li>
                    </ul>
                </div>
            </div>

            <!-- Colonne 3: Ressources -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-section">
                    <h5 class="footer-title">Ressources</h5>
                    <ul class="footer-links">
                        <li><a href="#faq"><i class="fa fa-angle-right"></i> FAQ</a></li>
                        <li><a href="#privacy"><i class="fa fa-angle-right"></i> Politique de confidentialité</a></li>
                        <li><a href="#terms"><i class="fa fa-angle-right"></i> Conditions d'utilisation</a></li>
                        <li><a href="#support"><i class="fa fa-angle-right"></i> Support</a></li>
                    </ul>
                </div>
            </div>

            <!-- Colonne 4: Contact & Réseaux -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-section">
                    <h5 class="footer-title">Restons connectés</h5>
                    <div class="contact-info mb-3">
                        <p><i class="fa fa-envelope"></i> contact@hearme.com</p>
                        <p><i class="fa fa-phone"></i> +216 XX XXX XXX</p>
                        <p><i class="fa fa-map-marker"></i> Tunis, Tunisie</p>
                    </div>
                    
                    <!-- Réseaux Sociaux -->
                    <div class="social-networks">
                        <h6 class="social-title">Suivez-nous</h6>
                        <div class="social-icons">
                            <a href="#" class="social-icon facebook" aria-label="Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="#" class="social-icon twitter" aria-label="Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon instagram" aria-label="Instagram">
                                <i class="fa fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon linkedin" aria-label="LinkedIn">
                                <i class="fa fa-linkedin"></i>
                            </a>
                            <a href="#" class="social-icon youtube" aria-label="YouTube">
                                <i class="fa fa-youtube-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ligne de séparation -->
        <div class="footer-divider"></div>

        <!-- Copyright -->
        <div class="row">
            <div class="col-12">
                <div class="footer-bottom">
                    <p class="copyright-text">
                        &copy; 2024 <strong>HearMe</strong> - Tous droits réservés. 
                        Développé avec <i class="fa fa-heart pulse"></i> pour votre bien-être
                    </p>
                    <div class="footer-badges">
                        <span class="badge-item"><i class="fa fa-shield"></i> Sécurisé</span>
                        <span class="badge-item"><i class="fa fa-lock"></i> Confidentiel</span>
                        <span class="badge-item"><i class="fa fa-check-circle"></i> Certifié</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton retour en haut -->
    <button id="scrollTopBtn" class="scroll-top-btn" aria-label="Retour en haut">
        <i class="fa fa-chevron-up"></i>
    </button>
</footer>

<!-- Styles CSS pour le footer -->
<style>
.professional-footer {
    background: linear-gradient(135deg, #A7C7E7 0%, #764ba2 100%);
    color: #ffffff;
    padding: 80px 0 0 0;
    position: relative;
    overflow: hidden;
}

.professional-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #A7C7E7 0%, #B4E7B0 50%, #98D8C8 100%);
}

/* Logo et description */
.footer-logo {
    max-height: 50px;
    filter: brightness(0) invert(1);
}

.footer-description {
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.8;
    font-size: 14px;
    margin-bottom: 20px;
}

.footer-stats {
    display: flex;
    gap: 20px;
    margin-top: 15px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.9);
}

.stat-item i {
    color: #FFD54F;
    font-size: 16px;
}

/* Titres de section */
.footer-title {
    color: #ffffff;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 12px;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, #FFD54F 0%, #FFA726 100%);
    border-radius: 2px;
}

/* Liens du footer */
.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-links a i {
    font-size: 12px;
    transition: transform 0.3s ease;
}

.footer-links a:hover {
    color: #FFD54F;
    transform: translateX(5px);
}

.footer-links a:hover i {
    transform: translateX(3px);
}

/* Informations de contact */
.contact-info p {
    color: rgba(255, 255, 255, 0.85);
    font-size: 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.contact-info i {
    color: #FFD54F;
    width: 20px;
    font-size: 16px;
}

/* Réseaux sociaux */
.social-networks {
    margin-top: 25px;
}

.social-title {
    color: #ffffff;
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 15px;
}

.social-icons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.social-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    font-size: 18px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.social-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.05));
    transform: scale(0);
    transition: transform 0.3s ease;
    border-radius: 50%;
}

.social-icon:hover::before {
    transform: scale(1);
}

.social-icon:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

/* Couleurs spécifiques par réseau */
.social-icon.facebook:hover {
    background: #3b5998;
}

.social-icon.twitter:hover {
    background: #1da1f2;
}

.social-icon.instagram:hover {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
}

.social-icon.linkedin:hover {
    background: #0077b5;
}

.social-icon.youtube:hover {
    background: #ff0000;
}

/* Séparateur */
.footer-divider {
    height: 1px;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(255, 255, 255, 0.3) 50%, 
        transparent 100%);
    margin: 50px 0 30px 0;
}

/* Bas du footer */
.footer-bottom {
    text-align: center;
    padding: 30px 0;
}

.copyright-text {
    color: rgba(255, 255, 255, 0.85);
    font-size: 14px;
    margin-bottom: 15px;
}

.copyright-text strong {
    color: #FFD54F;
    font-weight: 700;
}

.copyright-text .fa-heart {
    color: #ff589e;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.footer-badges {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.badge-item {
    background: rgba(255, 255, 255, 0.15);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    gap: 6px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.badge-item:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

.badge-item i {
    color: #FFD54F;
}

/* Bouton retour en haut */
.scroll-top-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    z-index: 1000;
}

.scroll-top-btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
}

.scroll-top-btn.show {
    display: flex;
}

/* Responsive */
@media (max-width: 991px) {
    .professional-footer {
        padding: 60px 0 0 0;
    }
    
    .footer-section {
        margin-bottom: 40px;
    }
    
    .footer-stats {
        flex-direction: column;
        gap: 10px;
    }
    
    .footer-bottom {
        flex-direction: column;
        gap: 20px;
    }
}

@media (max-width: 767px) {
    .professional-footer {
        padding: 50px 0 0 0;
    }
    
    .footer-badges {
        flex-direction: column;
        align-items: center;
    }
    
    .social-icons {
        justify-content: center;
    }
    
    .scroll-top-btn {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
    }
}
</style>

<!-- Script pour le bouton retour en haut -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    
    // Afficher/masquer le bouton selon le scroll
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    });
    
    // Retour en haut au clic
    scrollTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

<!-- jQuery -->
<script src="../../assets/template/js/jquery-2.1.0.min.js"></script>

<!-- Bootstrap -->
<script src="../../assets/template/js/popper.js"></script>
<script src="../../assets/template/js/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="../../assets/template/js/scrollreveal.min.js"></script>
<script src="../../assets/template/js/waypoints.min.js"></script>
<script src="../../assets/template/js/jquery.counterup.min.js"></script>
<script src="../../assets/template/js/imgfix.min.js"></script> 

<!-- Template Main JS File -->
<script src="../../assets/template/js/custom.js"></script>

<!-- HearMe Validation JS -->
<script src="../../assets/js/validation.js"></script>

</body>
</html>