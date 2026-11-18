-- Khotwa - Module M3 : Recovery Journey
CREATE DATABASE IF NOT EXISTS khotwa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE khotwa;

CREATE TABLE IF NOT EXISTS recovery_plan (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(150) NOT NULL,
  description TEXT NULL,
  start_date  DATE NOT NULL,
  end_date    DATE NULL,
  status      ENUM('active','paused','completed') NOT NULL DEFAULT 'active',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS goal (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(150) NOT NULL,
  description TEXT NULL,
  category    ENUM('physical','mental','social','professional','other') NOT NULL DEFAULT 'other',
  target_date DATE NULL,
  status      ENUM('todo','in_progress','done') NOT NULL DEFAULT 'todo',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Association table: RecoveryPlan N--N Goal
CREATE TABLE IF NOT EXISTS plan_goal (
  plan_id  INT NOT NULL,
  goal_id  INT NOT NULL,
  priority INT NOT NULL DEFAULT 1,
  PRIMARY KEY (plan_id, goal_id),
  FOREIGN KEY (plan_id) REFERENCES recovery_plan(id) ON DELETE CASCADE,
  FOREIGN KEY (goal_id) REFERENCES goal(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Used by the progress dashboard (next iteration)
CREATE TABLE IF NOT EXISTS progress_entry (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  plan_id        INT NOT NULL,
  note           TEXT NULL,
  progress_value TINYINT UNSIGNED NOT NULL DEFAULT 0,
  entry_date     DATE NOT NULL,
  FOREIGN KEY (plan_id) REFERENCES recovery_plan(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO goal (title, category, status) VALUES
 ('Marcher 20 minutes par jour', 'physical', 'in_progress'),
 ('Dormir 8 heures par nuit', 'mental', 'todo'),
 ('Reprendre contact avec un proche', 'social', 'todo');
INSERT INTO recovery_plan (title, description, start_date, status) VALUES
 ('Mon premier pas', 'Plan de démarrage progressif', CURDATE(), 'active');
INSERT INTO plan_goal (plan_id, goal_id, priority) VALUES (1,1,1),(1,2,2);
