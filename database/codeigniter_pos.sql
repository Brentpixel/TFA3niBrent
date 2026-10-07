-- phpMyAdmin SQL Dump
-- IT0049 TFA4: Who's Allowed In? Sessions and Authentication
-- Generated for: CodeIgniter 4 POS System
-- Database: codeigniter_pos
-- PHP Version: 8.2.12 | MariaDB 10.4.32
--
-- INSTRUCTIONS:
--  1. Create the database first: CREATE DATABASE codeigniter_pos;
--  2. Import this file via phpMyAdmin or: mysql -u root codeigniter_pos < database/codeigniter_pos.sql
--  3. The `users.avatar` column stores only the filename (e.g. "avatar_1_abc.jpg").
--     Uploaded files live in: public/uploads/avatars/
--  4. Default demo credentials:
--     Username: bverdera  | Password: Password123!
--     Username: boykanin  | Password: Password123!
-- ─────────────────────────────────────────────────────────────────────────────

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ─────────────────────────────────────────────────────────────────────────────
-- Database: `codeigniter_pos`
-- ─────────────────────────────────────────────────────────────────────────────

-- ── Table: customers ─────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `customers` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `full_name`  varchar(100) NOT NULL,
  `email`      varchar(100) NOT NULL,
  `phone`      varchar(20)  DEFAULT NULL,
  `created_at` datetime     NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=7;

INSERT INTO `customers` (`id`, `full_name`, `email`, `phone`, `created_at`) VALUES
(1, 'Boy Kanin',     'boykanin@example.com',     '09171234567', '2026-09-20 13:12:20'),
(2, 'Toto Bibo',     'totobibo@example.com',     '09181234567', '2026-09-20 13:12:20'),
(3, 'Inday Joke',    'indayjoke@example.com',    '09191234567', '2026-09-20 13:12:20'),
(4, 'Jun Jun Kulit', 'junjunkulit@example.com',  '09201234567', '2026-09-20 13:12:20'),
(5, 'Nene Banat',    'nenebanat@example.com',    '09211234567', '2026-09-20 13:12:20'),
(6, 'Brent Verdera', 'brentverdera02@gmail.com', '+63961557910', '2026-09-30 00:06:13')
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);

-- ── Table: users ─────────────────────────────────────────────────────────────
-- NOTE:
--   `avatar` stores only filename of uploaded thumbnail.
--   `password` stores secure bcrypt password_hash() digests.
--   Default password for all seed accounts: Password123!

CREATE TABLE IF NOT EXISTS `users` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `username`   varchar(50)  NOT NULL,
  `full_name`  varchar(100) NOT NULL,
  `email`      varchar(100) DEFAULT NULL,
  `password`   varchar(255) DEFAULT NULL,
  `avatar`     varchar(100) DEFAULT NULL,
  `created_at` datetime     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=8;

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `password`, `avatar`, `created_at`) VALUES
(1, 'boykanin',  'Boy Kanin',     'boykanin@example.com',        '$2y$10$KI2hBcCCsKeP64V3db3em.TWJ5EsZU8a7q35dz57XmOoMg.Sn.Qmy', NULL, '2026-09-20 13:12:42'),
(2, 'totobibo',  'Toto Bibo',     'totobibo@example.com',        '$2y$10$KI2hBcCCsKeP64V3db3em.TWJ5EsZU8a7q35dz57XmOoMg.Sn.Qmy', NULL, '2026-09-20 13:12:42'),
(3, 'indayjoke', 'Inday Joke',    'indayjoke@example.com',       '$2y$10$KI2hBcCCsKeP64V3db3em.TWJ5EsZU8a7q35dz57XmOoMg.Sn.Qmy', NULL, '2026-09-20 13:12:42'),
(4, 'junjunkulit','Jun Jun Kulit','junjunkulit@example.com',     '$2y$10$KI2hBcCCsKeP64V3db3em.TWJ5EsZU8a7q35dz57XmOoMg.Sn.Qmy', NULL, '2026-09-20 13:12:42'),
(5, 'nenebanat', 'Nene Banat',    'nenebanat@example.com',       '$2y$10$KI2hBcCCsKeP64V3db3em.TWJ5EsZU8a7q35dz57XmOoMg.Sn.Qmy', NULL, '2026-09-20 13:12:42'),
(6, 'Bpverdera', 'Brent Verdera', 'brentverdera02@gmail.com',    '$2y$10$KI2hBcCCsKeP64V3db3em.TWJ5EsZU8a7q35dz57XmOoMg.Sn.Qmy', NULL, '2026-09-30 00:06:13'),
(7, 'bverdera',  'Brent Verdera', 'brent.verdera@student.edu.ph','$2y$10$KI2hBcCCsKeP64V3db3em.TWJ5EsZU8a7q35dz57XmOoMg.Sn.Qmy', NULL, '2026-09-01 08:00:00')
ON DUPLICATE KEY UPDATE `password` = VALUES(`password`);

-- ── CodeIgniter migrations tracking table ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `migrations` (
  `id`        bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version`   varchar(255)        NOT NULL,
  `class`     text                NOT NULL,
  `group`     varchar(255)        NOT NULL,
  `namespace` varchar(255)        NOT NULL,
  `time`      int(11)             NOT NULL,
  `batch`     int(11) unsigned    NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `migrations` (`version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
('2026-09-29-000001', 'App\\Database\\Migrations\\CreateTasksTable', 'default', 'App', 1759148400, 1),
('2026-09-29-000002', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1759148400, 1),
('2026-09-29-000003', 'App\\Database\\Migrations\\AddAvatarToUsers',  'default', 'App', 1759148400, 2),
('2026-09-30-000001', 'App\\Database\\Migrations\\AddEmailToUsers',   'default', 'App', 1759248400, 2),
('2026-10-07-000001', 'App\\Database\\Migrations\\AddPasswordToUsers','default', 'App', 1759850000, 3)
ON DUPLICATE KEY UPDATE `version` = VALUES(`version`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
