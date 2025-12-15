-- =============================================
-- TABLES POUR LE MODULE TEST ÉMOTIONNEL
-- À exécuter sur la base hearme_db
-- =============================================

USE hearme_db;

-- TABLE: quiz
CREATE TABLE IF NOT EXISTS quiz (
    id_quiz INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    categorie VARCHAR(100) NOT NULL,
    image VARCHAR(255) DEFAULT 'default-quiz.png',
    duree_estimee INT DEFAULT 5,
    actif TINYINT(1) DEFAULT 1,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_actif (actif),
    INDEX idx_categorie (categorie)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: question
CREATE TABLE IF NOT EXISTS question (
    id_question INT AUTO_INCREMENT PRIMARY KEY,
    id_quiz INT NOT NULL,
    texte_question TEXT NOT NULL,
    ordre INT DEFAULT 1,
    option1_texte VARCHAR(500) NOT NULL,
    option1_score INT NOT NULL DEFAULT 1,
    option2_texte VARCHAR(500) NOT NULL,
    option2_score INT NOT NULL DEFAULT 2,
    option3_texte VARCHAR(500) NOT NULL,
    option3_score INT NOT NULL DEFAULT 3,
    option4_texte VARCHAR(500) DEFAULT NULL,
    option4_score INT DEFAULT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_quiz) REFERENCES quiz(id_quiz) ON DELETE CASCADE,
    INDEX idx_quiz (id_quiz),
    INDEX idx_ordre (ordre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLE: resultat_utilisateur
CREATE TABLE IF NOT EXISTS resultat_utilisateur (
    id_resultat INT AUTO_INCREMENT PRIMARY KEY,
    id_quiz INT NOT NULL,
    user_id INT NOT NULL DEFAULT 1,
    score_total INT NOT NULL,
    niveau VARCHAR(50),
    conseil_genere TEXT,
    date_test DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_quiz) REFERENCES quiz(id_quiz) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_date (date_test)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DONNÉES D'EXEMPLE
INSERT INTO quiz (titre, description, categorie, image, duree_estimee, actif) VALUES
('Évaluation du Stress', 'Découvre ton niveau de stress actuel et reçois des conseils personnalisés', 'stress', 'stress.jpg', 5, 1),
('Test d\'Anxiété', 'Évalue ton niveau d\'anxiété et apprends à mieux la gérer', 'anxiété', 'anxiety.jpg', 7, 1),
('Bilan Émotionnel Général', 'Un test complet pour évaluer ton bien-être émotionnel global', 'général', 'general.jpg', 10, 1);

-- Questions pour "Évaluation du Stress"
INSERT INTO question (id_quiz, texte_question, ordre, option1_texte, option1_score, option2_texte, option2_score, option3_texte, option3_score, option4_texte, option4_score) VALUES
(1, 'Comment te sens-tu en ce moment ?', 1, 'Je me sens très bien et détendu(e) 😊', 1, 'Je me sens normal(e), quelques soucis légers', 2, 'Je me sens assez stressé(e) 😰', 3, 'Je me sens très stressé(e) et anxieux(se) 😫', 4),
(1, 'Comment dors-tu ces derniers temps ?', 2, 'Je dors très bien, je me réveille reposé(e) 😴', 1, 'Je dors correctement la plupart du temps', 2, 'J\'ai du mal à m\'endormir ou je me réveille souvent', 3, 'Je dors très mal, insomnies fréquentes 😵', 4),
(1, 'Comment gères-tu ton travail/tes études ?', 3, 'Tout se passe bien, je gère facilement 💪', 1, 'C\'est gérable mais parfois difficile', 2, 'Je me sens souvent débordé(e) 😓', 3, 'Je suis complètement dépassé(e) 😰', 4),
(1, 'Comment sont tes relations sociales ?', 4, 'Excellentes, je profite de mes proches 💕', 1, 'Bonnes, mais je pourrais passer plus de temps avec eux', 2, 'Difficiles, je me sens parfois isolé(e) 😔', 3, 'Très compliquées, je m\'isole beaucoup 😞', 4),
(1, 'As-tu des symptômes physiques de stress ?', 5, 'Non, je me sens en pleine forme', 1, 'Rarement, quelques petits maux', 2, 'Oui, maux de tête ou tensions réguliers 🤕', 3, 'Oui, symptômes fréquents et gênants 😖', 4);

-- Questions pour "Test d'Anxiété"
INSERT INTO question (id_quiz, texte_question, ordre, option1_texte, option1_score, option2_texte, option2_score, option3_texte, option3_score, option4_texte, option4_score) VALUES
(2, 'Te sens-tu nerveux(se) ou tendu(e) ?', 1, 'Jamais ou presque jamais', 1, 'Parfois', 2, 'Souvent', 3, 'Presque tout le temps 😰', 4),
(2, 'As-tu du mal à arrêter de t\'inquiéter ?', 2, 'Non, pas du tout', 1, 'Un peu, mais je contrôle', 2, 'Oui, c\'est difficile 😟', 3, 'Impossible d\'arrêter de m\'inquiéter 😱', 4),
(2, 'As-tu peur que quelque chose de grave arrive ?', 3, 'Non, je suis serein(e)', 1, 'Rarement', 2, 'Assez souvent 😨', 3, 'Très souvent, j\'ai peur en permanence 😰', 4);

-- Questions pour "Bilan Émotionnel Général"
INSERT INTO question (id_quiz, texte_question, ordre, option1_texte, option1_score, option2_texte, option2_score, option3_texte, option3_score, option4_texte, option4_score) VALUES
(3, 'Comment évalues-tu ton niveau d\'énergie général ?', 1, 'Excellent, je suis plein(e) d\'énergie 🔋', 1, 'Bon, quelques baisses de temps en temps', 2, 'Faible, je me sens souvent fatigué(e) 😪', 3, 'Très faible, épuisement constant 😴', 4),
(3, 'Te sens-tu satisfait(e) de ta vie actuelle ?', 2, 'Oui, je suis très satisfait(e) 😊', 1, 'Plutôt oui, dans l\'ensemble', 2, 'Pas vraiment, j\'aimerais changer des choses', 3, 'Non, je suis insatisfait(e) 😔', 4),
(3, 'Arrives-tu à gérer tes émotions au quotidien ?', 3, 'Oui, facilement et naturellement 🧘', 1, 'Généralement oui', 2, 'Difficilement, je déborde parfois 😣', 3, 'Non, mes émotions me submergent souvent 😭', 4);
