-- Création de la base de données
CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;
-- Table des projets
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('Web', 'Design', 'Infographie') NOT NULL,
    technologies TEXT,
    link VARCHAR(500),
    image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- Table des compétences
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category ENUM('Programmation', 'Outils', 'Design/Infographie') NOT NULL,
    level INT NOT NULL CHECK (
        level >= 0
        AND level <= 100
    ),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Table des demandes de devis
CREATE TABLE IF NOT EXISTS devis_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    type_projet ENUM(
        'site_web',
        'application',
        'design',
        'infographie'
    ) NOT NULL,
    budget ENUM('50000', '100000', '250000', '500000', '1000000') NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('new', 'contacted', 'completed') DEFAULT 'new'
);
-- Insertion des données initiales pour les compétences
INSERT INTO skills (name, category, level)
VALUES ('HTML', 'Programmation', 90),
    ('CSS', 'Programmation', 85),
    ('JavaScript', 'Programmation', 80),
    ('PHP', 'Programmation', 75),
    ('Python', 'Programmation', 70),
    ('SQL', 'Programmation', 80),
    ('Java', 'Programmation', 65),
    ('C/C++', 'Programmation', 60),
    ('Rust', 'Programmation', 50),
    ('Go', 'Programmation', 55),
    ('Kotlin', 'Programmation', 60),
    ('Shell/Bash', 'Programmation', 70),
    ('Office (Word, Excel, Access)', 'Outils', 85),
    ('VS Code', 'Outils', 90),
    ('Git/GitHub', 'Outils', 80),
    ('Docker', 'Outils', 65),
    ('Motion', 'Outils', 70),
    ('Swagger', 'Outils', 60),
    ('Flyers', 'Design/Infographie', 85),
    ('Cartes d''invitation', 'Design/Infographie', 80),
    ('Supports visuels', 'Design/Infographie', 75);
-- Insertion des données initiales pour les projets
INSERT INTO projects (
        title,
        description,
        category,
        technologies,
        link,
        image
    )
VALUES (
        'Site E-commerce Moderne',
        'Développement d''une plateforme e-commerce complète avec système de paiement intégré.',
        'Web',
        'PHP, JavaScript, MySQL, Tailwind CSS',
        'https://example.com',
        ''
    ),
    (
        'Identité Visuelle Restaurant',
        'Création d''une identité visuelle complète pour un restaurant gastronomique.',
        'Design',
        'Adobe Illustrator, Photoshop',
        '',
        ''
    ),
    (
        'Campagne Publicitaire Digital',
        'Conception d''une campagne publicitaire digitale avec supports visuels variés.',
        'Infographie',
        'Adobe Creative Suite',
        '',
        ''
    ),
    (
        'Application Web React',
        'Développement d''une application web moderne avec React et Node.js.',
        'Web',
        'React, Node.js, MongoDB, Express',
        'https://example.com',
        ''
    ),
    (
        'Brochure Corporate',
        'Design et mise en page d''une brochure corporate pour une entreprise internationale.',
        'Design',
        'InDesign, Illustrator',
        '',
        ''
    ),
    (
        'Infographies Éducatives',
        'Création d''une série d''infographies pour un organisme de formation.',
        'Infographie',
        'Illustrator, Photoshop',
        '',
        ''
    );