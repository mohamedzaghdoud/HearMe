# Script pour créer un historique Git réaliste sur 15+ jours
# Exécuter dans PowerShell depuis le dossier hearme_user

# Configuration
$env:GIT_AUTHOR_NAME = "Ton Nom"
$env:GIT_AUTHOR_EMAIL = "ton.email@example.com"
$env:GIT_COMMITTER_NAME = "Ton Nom"
$env:GIT_COMMITTER_EMAIL = "ton.email@example.com"

# Fonction pour commit avec date personnalisée
function Git-Commit-Date {
    param($message, $date, $files)
    
    $env:GIT_AUTHOR_DATE = $date
    $env:GIT_COMMITTER_DATE = $date
    
    foreach ($file in $files) {
        git add $file
    }
    git commit -m $message
}

# ===== JOUR 1 (il y a 18 jours) - Setup initial =====
$date1 = (Get-Date).AddDays(-18).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Initial commit - Structure du projet" $date1 @("config.php", "index.php")

# ===== JOUR 2 (il y a 17 jours) - Models =====
$date2 = (Get-Date).AddDays(-17).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout Model User" $date2 @("Model/User.php")

# ===== JOUR 3 (il y a 16 jours) =====
$date3 = (Get-Date).AddDays(-16).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout Model Profil" $date3 @("Model/Profil.php")

# ===== JOUR 4 (il y a 15 jours) =====
$date4 = (Get-Date).AddDays(-15).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout Controllers User et Profil" $date4 @("Controller/UserController.php", "Controller/ProfilController.php")

# ===== JOUR 5 (il y a 14 jours) =====
$date5 = (Get-Date).AddDays(-14).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout pages Login et Register FrontOffice" $date5 @("View/FrontOffice/Login.php", "View/FrontOffice/register.php")

# ===== JOUR 6 (il y a 13 jours) =====
$date6 = (Get-Date).AddDays(-13).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout page Home FrontOffice" $date6 @("View/FrontOffice/Home.php")

# ===== JOUR 7 (il y a 12 jours) =====
$date7 = (Get-Date).AddDays(-12).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout page Profil FrontOffice" $date7 @("View/FrontOffice/Profilfront.php")

# ===== JOUR 8 (il y a 11 jours) =====
$date8 = (Get-Date).AddDays(-11).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout système de récupération mot de passe" $date8 @("View/FrontOffice/forgot-password.php", "View/FrontOffice/reset-password.php")

# ===== JOUR 9 (il y a 10 jours) =====
$date9 = (Get-Date).AddDays(-10).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout vérification email" $date9 @("View/FrontOffice/email-sent.php", "View/FrontOffice/verify-email.php")

# ===== JOUR 10 (il y a 9 jours) - Integration Module Test =====
$date10 = (Get-Date).AddDays(-9).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Integration module test emotionnel - Models Quiz et Question" $date10 @("Model/Quiz.php", "Model/Question.php")

# ===== JOUR 11 (il y a 8 jours) =====
$date11 = (Get-Date).AddDays(-8).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout Controllers Quiz et Test" $date11 @("Controller/QuizController.php", "Controller/TestController.php", "Controller/QuestionController.php")

# ===== JOUR 12 (il y a 7 jours) =====
$date12 = (Get-Date).AddDays(-7).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout pages test emotionnel FrontOffice" $date12 @("View/FrontOffice/test/")

# ===== JOUR 13 (il y a 6 jours) =====
$date13 = (Get-Date).AddDays(-6).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout BackOffice - Dashboard et layout" $date13 @("View/BackOffice/dashboard.php", "View/BackOffice/layout/")

# ===== JOUR 14 (il y a 5 jours) =====
$date14 = (Get-Date).AddDays(-5).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout gestion utilisateurs BackOffice" $date14 @("View/BackOffice/ListerUsers.php", "View/BackOffice/AjoutUser.php", "View/BackOffice/ModifierUser.php")

# ===== JOUR 15 (il y a 4 jours) =====
$date15 = (Get-Date).AddDays(-4).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout gestion profils BackOffice" $date15 @("View/BackOffice/ListerProfil.php", "View/BackOffice/ModifierProfil.php")

# ===== JOUR 16 (il y a 3 jours) =====
$date16 = (Get-Date).AddDays(-3).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout gestion quiz BackOffice" $date16 @("View/BackOffice/quiz/")

# ===== JOUR 17 (il y a 2 jours) =====
$date17 = (Get-Date).AddDays(-2).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Ajout gestion questions BackOffice" $date17 @("View/BackOffice/questions/")

# ===== JOUR 18 (hier) =====
$date18 = (Get-Date).AddDays(-1).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Separation CSS/JS - Nettoyage code FrontOffice" $date18 @("View/FrontOffice/assets/css/", "View/FrontOffice/assets/js/")

# ===== JOUR 19 (aujourd'hui) =====
$date19 = (Get-Date).ToString("yyyy-MM-dd HH:mm:ss")
Git-Commit-Date "Separation CSS/JS - Nettoyage code BackOffice" $date19 @("View/BackOffice/assets/css/", "View/BackOffice/assets/js/")

# Afficher l'historique
Write-Host "`n===== HISTORIQUE GIT CREE =====" -ForegroundColor Green
git log --oneline --all

Write-Host "`nPour pousser vers GitHub:" -ForegroundColor Yellow
Write-Host "git push -u origin main --force"
