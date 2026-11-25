





CREATE DATABASE IF NOT EXISTS khotwa_final CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE khotwa_final;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50)  NOT NULL UNIQUE,
  email    VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','professional','admin') NOT NULL DEFAULT 'user',
  actif  TINYINT(1) NOT NULL DEFAULT 1,           -- 0 = compte bloque par l'admin
  valide TINYINT(1) NOT NULL DEFAULT 1,           -- 0 = professionnel en attente de validation

  specialite    VARCHAR(100) NULL,
  ville         VARCHAR(100) NULL,
  telephone     VARCHAR(20)  NULL,
  description   TEXT         NULL,
  numero_ordre  VARCHAR(50)  NULL,
  horaire_debut TIME NULL DEFAULT '09:00:00',
  horaire_fin   TIME NULL DEFAULT '17:00:00',
  photo VARCHAR(255) NULL,

  tentatives     INT NOT NULL DEFAULT 0,          -- anti brute force
  bloque_jusqu_a DATETIME NULL,
  reset_token    VARCHAR(64) NULL,                -- empreinte SHA-256 du jeton "mot de passe oublie"
  reset_expire   DATETIME NULL,
  derniere_connexion DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_role (role),
  INDEX idx_reset (reset_token)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  action  VARCHAR(50)  NOT NULL,
  details VARCHAR(255) NULL,
  ip      VARCHAR(45)  NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_action (action),
  INDEX idx_created (created_at),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO users (username, email, password, role, valide, specialite, ville, telephone, description, numero_ordre) VALUES
('admin',     'admin@khotwa.tn',   '$2y$12$9dmRbFjFgDChB.PG2jUjN.pp3cVis2PM.fRxTZJUkxa/11IqHONY2', 'admin',        1, NULL, NULL, NULL, NULL, NULL),
('sami_92',   'sami@khotwa.tn',    '$2y$12$9dmRbFjFgDChB.PG2jUjN.pp3cVis2PM.fRxTZJUkxa/11IqHONY2', 'user',         1, NULL, NULL, NULL, NULL, NULL),
('dr_sonia',  'sonia@khotwa.tn',   '$2y$12$9dmRbFjFgDChB.PG2jUjN.pp3cVis2PM.fRxTZJUkxa/11IqHONY2', 'professional', 1, 'Psychologue',  'Tunis', '20123456', 'Accompagnement des jeunes en difficulte avec l''alcool.', 'PSY-1024'),
('dr_karim',  'karim@khotwa.tn',   '$2y$12$9dmRbFjFgDChB.PG2jUjN.pp3cVis2PM.fRxTZJUkxa/11IqHONY2', 'professional', 0, 'Addictologue', 'Sfax',  '22987654', 'Prise en charge des addictions.', 'ADD-2231');
