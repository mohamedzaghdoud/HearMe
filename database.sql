CREATE DATABASE IF NOT EXISTS hearme_db;
USE hearme_db;

-- Table des utilisateurs (MISE À JOUR avec validation email)
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    
    -- Validation email
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(64) NULL,
    verification_expires DATETIME NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_verification (verification_token)
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

-- Table pour la réinitialisation des mots de passe
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reset_token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    INDEX idx_reset_token (reset_token),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour stocker les encodages faciaux
ALTER TABLE users ADD COLUMN face_encoding TEXT NULL;

-- Insérer un admin par défaut (mot de passe: Admin123!)
-- Le hash sera généré automatiquement par config.php
INSERT INTO users (email, password, role, email_verified) VALUES 
('admin@hearme.com', '$2y$10$YourHashedPasswordHere', 'admin', TRUE);

-- Insérer un utilisateur test (mot de passe: User123!)
INSERT INTO users (email, password, role, email_verified) VALUES 
('user@hearme.com', '$2y$10$YourHashedPasswordHere', 'user', TRUE);

-- Insérer un profil pour l'utilisateur test
INSERT INTO profil (id_user, bio, competences, formation, localisation, disponibilite) VALUES 
(2, 'Utilisateur test de la plateforme HearMe', 'Écoute active, Empathie', 'Psychologie', 'Tunis, Tunisie', 'disponible');