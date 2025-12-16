# 🎧 HearMe - Plateforme de Bien-être Mental

<p align="center">
  <img src="View/FrontOffice/assets/images/logo.png" alt="HearMe Logo" width="200">
</p>

<p align="center">
  <strong>Une plateforme web dédiée au bien-être mental et à l'évaluation émotionnelle</strong>
</p>

<p align="center">
  <a href="#-fonctionnalités">Fonctionnalités</a> •
  <a href="#-technologies">Technologies</a> •
  <a href="#-installation">Installation</a> •
  <a href="#-structure">Structure</a> •
  <a href="#-équipe">Équipe</a>
</p>

---

## 📋 Description

**HearMe** est une application web qui permet aux utilisateurs d'évaluer leur état émotionnel à travers des tests psychologiques. La plateforme offre un suivi personnalisé et des conseils adaptés pour améliorer le bien-être mental.

### 🎯 Objectifs du projet
- Sensibiliser à l'importance de la santé mentale
- Fournir des outils d'auto-évaluation émotionnelle
- Offrir un suivi personnalisé des résultats
- Proposer des ressources et conseils adaptés

---

## ✨ Fonctionnalités

### 👤 Module Utilisateur (User/Profil)
- Inscription avec vérification email
- Connexion sécurisée (mot de passe hashé)
- Authentification Face ID
- Récupération de mot de passe
- Gestion du profil utilisateur
- Connexion OAuth (Google/Facebook)

### 📝 Module Test Émotionnel
- Catalogue de quiz par catégorie (stress, anxiété, bien-être...)
- Passage de tests avec barre de progression
- Calcul automatique des scores
- Affichage des résultats avec niveau et conseils
- Historique des tests passés
- Ressources recommandées selon les résultats

### 🔧 BackOffice Administration
- Dashboard avec statistiques
- Gestion des utilisateurs (CRUD)
- Gestion des profils
- Gestion des quiz et questions
- Outils de développement (logs, tokens)

---

## 🛠 Technologies

### Backend
| Technologie | Utilisation |
|-------------|-------------|
| **PHP 8.x** | Langage serveur |
| **MySQL** | Base de données |
| **PDO** | Connexion BDD sécurisée |
| **PHPMailer** | Envoi d'emails |

### Frontend
| Technologie | Utilisation |
|-------------|-------------|
| **HTML5/CSS3** | Structure et style |
| **JavaScript** | Interactivité |
| **Bootstrap 5** | Framework CSS |
| **Chart.js** | Graphiques dashboard |

### Architecture
- **Pattern MVC** (Model-View-Controller)
- **Séparation FrontOffice / BackOffice**
- **CSS/JS externalisés**

---

## 📁 Structure du Projet

```
HearMe/
├── Controller/                 # Contrôleurs MVC
│   ├── UserController.php      # Gestion utilisateurs
│   ├── ProfilController.php    # Gestion profils
│   ├── QuizController.php      # Gestion quiz
│   ├── QuestionController.php  # Gestion questions
│   └── TestController.php      # Passage des tests
│
├── Model/                      # Modèles MVC
│   ├── User.php                # Modèle utilisateur
│   ├── Profil.php              # Modèle profil
│   ├── Quiz.php                # Modèle quiz
│   ├── Question.php            # Modèle question
│   ├── EmailService.php        # Service email
│   └── CaptchaService.php      # Service captcha
│
├── View/
│   ├── FrontOffice/            # Interface utilisateur
│   │   ├── assets/             # CSS, JS, Images
│   │   ├── layout/             # Header, Footer
│   │   ├── test/               # Pages test émotionnel
│   │   ├── Login.php           # Connexion
│   │   ├── register.php        # Inscription
│   │   ├── Home.php            # Accueil
│   │   └── Profilfront.php     # Profil utilisateur
│   │
│   └── BackOffice/             # Interface admin
│       ├── assets/             # CSS, JS admin
│       ├── layout/             # Header, Footer admin
│       ├── quiz/               # Gestion quiz
│       ├── questions/          # Gestion questions
│       ├── dashboard.php       # Tableau de bord
│       └── ListerUsers.php     # Liste utilisateurs
│
├── config.php                  # Configuration BDD
├── database.sql                # Script BDD principale
├── database_test_emotionnel.sql # Script BDD tests
└── index.php                   # Point d'entrée
```

---

## ⚙️ Installation

### Prérequis
- **XAMPP** (PHP 8.x + MySQL)
- **Git**
- **Navigateur web moderne**

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone https://github.com/mohamedzaghdoud/HearMe.git
cd HearMe
```

2. **Placer dans le dossier XAMPP**
```bash
# Copier vers htdocs
cp -r HearMe C:/xampp/htdocs/hearme_user
```

3. **Créer la base de données**
```sql
-- Dans phpMyAdmin, créer la BDD
CREATE DATABASE hearme_db;
```

4. **Importer les tables**
```bash
# Importer le script SQL principal
mysql -u root hearme_db < database.sql

# Importer les tables du module test
mysql -u root hearme_db < database_test_emotionnel.sql
```

5. **Configurer la connexion**
```php
// Éditer config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'hearme_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

6. **Lancer l'application**
```
http://localhost/hearme_user/
```

---

## 🗄️ Base de Données

### Schéma des tables principales

```sql
-- Table utilisateurs
users (id_user, email, password, role, email_verified, created_at)

-- Table profils
profil (id_profil, id_user, bio, formation, competences, disponibilite, localisation)

-- Table quiz
quiz (id_quiz, titre, description, categorie, duree_estimee, image, actif)

-- Table questions
questions (id_question, id_quiz, texte_question, option1_texte, option1_score, ...)

-- Table résultats
resultats_tests (id, id_user, id_quiz, score_total, niveau, conseil_genere, date_test)
```

---

## 📸 Captures d'écran

### FrontOffice
| Page d'accueil | Test Émotionnel | Résultat |
|----------------|-----------------|----------|
| Dégradé bleu-vert moderne | Barre de progression | Score et conseils |

### BackOffice
| Dashboard | Gestion Quiz | Gestion Questions |
|-----------|--------------|-------------------|
| Statistiques temps réel | CRUD complet | Options de réponse |

---

## 👥 Équipe

| Membre | Module | Branche Git |
|--------|--------|-------------|
| **Mohamed Zaghdoud** | Test Émotionnel + Intégration | `mohamedzaghdoud-patch-1`, `integration` |
| **Malek Kassous** | User / Profil | `malekkassous` |

---

## 🔄 Workflow Git

```
main
  │
  ├── malekkassous ────────── Module User/Profil
  │
  ├── mohamedzaghdoud-patch-1 ── Module Test Émotionnel
  │
  └── integration ─────────── Fusion des modules
          │
          └── Pull Request → main
```

### Commits d'intégration
- Integration module Test Emotionnel - Models Quiz et Question
- Integration Controllers - Quiz, Question, Test, User
- Integration pages Test Emotionnel FrontOffice
- Ajout layout header/footer FrontOffice
- Mise a jour pages FrontOffice - Template unifie
- Separation CSS/JS FrontOffice
- Ajout layout BackOffice - Template dark
- Ajout gestion Quiz/Questions BackOffice
- Separation CSS/JS BackOffice

---

## 🚀 Améliorations futures

- [ ] Application mobile (React Native)
- [ ] Notifications push
- [ ] Chat avec psychologue
- [ ] Statistiques avancées
- [ ] Export PDF des résultats
- [ ] Multi-langue (FR/EN/AR)

---

## 📄 Licence

Ce projet est réalisé dans le cadre du cours **Technologies Web** - ESPRIT 2024/2025.

---

<p align="center">
  Développé avec ❤️ par l'équipe HearMe
</p>
