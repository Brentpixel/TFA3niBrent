-- =============================================================================
-- Tasks for Today Management System — Database Setup
-- IT0049 Technical Summative Assessment 1
-- Developer: Brent Verdera
-- =============================================================================
-- Usage:
--   1. Open phpMyAdmin (or MySQL CLI).
--   2. Create database:  CREATE DATABASE tasks_today CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
--   3. Select it:        USE tasks_today;
--   4. Run this file.
-- =============================================================================

SET NAMES utf8mb4;
SET time_zone = '+08:00';   -- Asia/Manila

-- -----------------------------------------------------------------------------
-- Table: tasks
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tasks` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `title`      VARCHAR(150) NOT NULL,
  `status`     VARCHAR(20)  NOT NULL DEFAULT 'pending',
  `task_date`  DATE         NOT NULL,
  `created_at` DATETIME     NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: users
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(50)  NOT NULL,
  `full_name`  VARCHAR(100) NOT NULL,
  `email`      VARCHAR(100) NOT NULL,
  `created_at` DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Seed data — tasks (8 rows, 3 dates)
-- INSERT IGNORE prevents duplicate rows if the script is re-run.
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `tasks` (`id`, `title`, `status`, `task_date`, `created_at`) VALUES
(1, 'Review Chapter 3 – Database Normalization',              'done',        '2026-09-27', '2026-09-27 07:30:00'),
(2, 'Submit Networking Lab Report',                           'done',        '2026-09-27', '2026-09-27 08:15:00'),
(3, 'Study for IT0049 Midterm Examination',                   'done',        '2026-09-28', '2026-09-28 06:45:00'),
(4, 'Push CodeIgniter activity to GitHub',                    'done',        '2026-09-28', '2026-09-28 09:00:00'),
(5, 'Read documentation on MVC architecture',                 'done',        '2026-09-28', '2026-09-28 11:30:00'),
(6, 'Complete Summative Assessment 1 – Tasks for Today App',  'in-progress', '2026-09-29', '2026-09-29 08:00:00'),
(7, 'Deploy project to a live host and get the public URL',   'pending',     '2026-09-29', '2026-09-29 08:05:00'),
(8, 'Prepare GitHub repository and push all project files',   'pending',     '2026-09-29', '2026-09-29 08:10:00');

-- -----------------------------------------------------------------------------
-- Seed data — users (1 demo user)
-- -----------------------------------------------------------------------------
INSERT IGNORE INTO `users` (`id`, `username`, `full_name`, `email`, `created_at`) VALUES
(1, 'bverdera', 'Brent Verdera', 'brent.verdera@student.edu.ph', '2026-09-01 08:00:00');
