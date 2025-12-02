
CREATE DATABASE IF NOT EXISTS hearme_db;
USE hearme_db;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des profils (liée aux users)
CREATE TABLE IF NOT EXISTS profil (
    id_profil INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    bio TEXT,
    competences TEXT,
    formation TEXT,
    experience TEXT,
    reseaux_sociaux VARCHAR(255),
    disponibilite ENUM('disponible', 'occupé', 'indisponible') DEFAULT 'disponible',
    localisation VARCHAR(255),
    preferences TEXT,
    autre_theme TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    INDEX idx_user (id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insérer un admin par défaut (mot de passe: Admin123!)
INSERT INTO users (email, password, role) VALUES 
('admin@hearme.com', '$2y$10$YourHashedPasswordHere', 'admin');

-- Insérer un utilisateur test (mot de passe: User123!)
INSERT INTO users (email, password, role) VALUES 
('user@hearme.com', '$2y$10$YourHashedPasswordHere', 'user');

-- Insérer un profil pour l'utilisateur test
INSERT INTO profil (id_user, bio, competences, formation, localisation, disponibilite) VALUES 
(2, 'Utilisateur test de la plateforme HearMe', 'Écoute active, Empathie', 'Psychologie', 'Tunis, Tunisie', 'disponible');