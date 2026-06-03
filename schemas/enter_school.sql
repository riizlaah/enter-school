-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.4.3 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk admin_antrian
DROP DATABASE IF EXISTS `admin_antrian`;
CREATE DATABASE IF NOT EXISTS `admin_antrian` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `admin_antrian`;

-- membuang struktur untuk table admin_antrian.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.cache: ~0 rows (lebih kurang)
DELETE FROM `cache`;

-- membuang struktur untuk table admin_antrian.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.cache_locks: ~0 rows (lebih kurang)
DELETE FROM `cache_locks`;

-- membuang struktur untuk table admin_antrian.counters
DROP TABLE IF EXISTS `counters`;
CREATE TABLE IF NOT EXISTS `counters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.counters: ~1 rows (lebih kurang)
DELETE FROM `counters`;
INSERT INTO `counters` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
	(4, 'Loket Pertama', 1, '2026-06-02 21:36:34', '2026-06-02 21:36:34');

-- membuang struktur untuk table admin_antrian.counter_service
DROP TABLE IF EXISTS `counter_service`;
CREATE TABLE IF NOT EXISTS `counter_service` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `counter_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `counter_service_counter_id_service_id_unique` (`counter_id`,`service_id`),
  KEY `counter_service_service_id_foreign` (`service_id`),
  CONSTRAINT `counter_service_counter_id_foreign` FOREIGN KEY (`counter_id`) REFERENCES `counters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `counter_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.counter_service: ~1 rows (lebih kurang)
DELETE FROM `counter_service`;
INSERT INTO `counter_service` (`id`, `counter_id`, `service_id`, `created_at`, `updated_at`) VALUES
	(5, 4, 4, NULL, NULL);

-- membuang struktur untuk table admin_antrian.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.failed_jobs: ~0 rows (lebih kurang)
DELETE FROM `failed_jobs`;

-- membuang struktur untuk table admin_antrian.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.jobs: ~0 rows (lebih kurang)
DELETE FROM `jobs`;

-- membuang struktur untuk table admin_antrian.job_batches
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.job_batches: ~0 rows (lebih kurang)
DELETE FROM `job_batches`;

-- membuang struktur untuk table admin_antrian.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.migrations: ~0 rows (lebih kurang)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_03_30_000001_create_services_table', 1),
	(5, '2026_03_30_000002_create_counters_table', 1),
	(6, '2026_03_30_000003_create_counter_service_table', 1),
	(7, '2026_03_30_000004_create_service_schedules_table', 1),
	(8, '2026_03_30_000005_create_queues_table', 1),
	(9, '2026_04_05_000001_add_visitor_fields_to_queues_table', 1);

-- membuang struktur untuk table admin_antrian.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.password_reset_tokens: ~0 rows (lebih kurang)
DELETE FROM `password_reset_tokens`;

-- membuang struktur untuk table admin_antrian.queues
DROP TABLE IF EXISTS `queues`;
CREATE TABLE IF NOT EXISTS `queues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `service_id` bigint unsigned NOT NULL,
  `counter_id` bigint unsigned DEFAULT NULL,
  `served_by` bigint unsigned DEFAULT NULL,
  `visitor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue_number` int NOT NULL,
  `appointment_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `queues_user_id_foreign` (`user_id`),
  KEY `queues_service_id_foreign` (`service_id`),
  KEY `queues_counter_id_foreign` (`counter_id`),
  KEY `queues_served_by_foreign` (`served_by`),
  CONSTRAINT `queues_counter_id_foreign` FOREIGN KEY (`counter_id`) REFERENCES `counters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `queues_served_by_foreign` FOREIGN KEY (`served_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `queues_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `queues_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.queues: ~2 rows (lebih kurang)
DELETE FROM `queues`;
INSERT INTO `queues` (`id`, `user_id`, `service_id`, `counter_id`, `served_by`, `visitor_name`, `visitor_phone`, `queue_number`, `appointment_date`, `status`, `created_at`, `updated_at`) VALUES
	(11, NULL, 4, 4, NULL, NULL, '081234567890', 1, '2026-06-03', 'waiting', '2026-06-03 04:43:35', '2026-06-03 04:43:37'),
	(12, NULL, 4, 4, NULL, NULL, '089777675644', 2, '2026-06-03', 'waiting', '2026-06-03 05:20:54', '2026-06-03 05:20:54');

-- membuang struktur untuk table admin_antrian.services
DROP TABLE IF EXISTS `services`;
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.services: ~1 rows (lebih kurang)
DELETE FROM `services`;
INSERT INTO `services` (`id`, `name`, `prefix`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(4, 'Pendaftaran Sekolah', 'PS', 'pendaftaran-sekolah', NULL, 1, '2026-06-02 21:36:16', '2026-06-02 21:36:16');

-- membuang struktur untuk table admin_antrian.service_schedules
DROP TABLE IF EXISTS `service_schedules`;
CREATE TABLE IF NOT EXISTS `service_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '1',
  `max_quota` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_schedules_service_id_date_unique` (`service_id`,`date`),
  CONSTRAINT `service_schedules_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.service_schedules: ~1 rows (lebih kurang)
DELETE FROM `service_schedules`;
INSERT INTO `service_schedules` (`id`, `service_id`, `date`, `is_open`, `max_quota`, `created_at`, `updated_at`) VALUES
	(4, 4, '2026-06-03', 1, 100, '2026-06-02 21:36:53', '2026-06-02 21:36:53');

-- membuang struktur untuk table admin_antrian.sessions
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.sessions: ~2 rows (lebih kurang)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('u6MKYjSW82OESZfhetbmdACwZDGD2bSOfyzcEctS', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiJVTE1MTGdFOEVSMTRnamdIczY0NjAyc3ZLdWtIOUdpandCb2o0SWJnIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvcnBsLWFudHJpYW4udGVzdFwvb2ZmaWNlciIsInJvdXRlIjoib2ZmaWNlci5kYXNoYm9hcmQifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsImNvdW50ZXJfaWQiOjQsImNvdW50ZXJfbmFtZSI6Ikxva2V0IFBlcnRhbWEifQ==', 1780464164);

-- membuang struktur untuk table admin_antrian.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plain_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Membuang data untuk tabel admin_antrian.users: ~2 rows (lebih kurang)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `plain_key`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Administrator', 'admin@antrian.test', '2026-05-03 23:48:33', '$2y$12$APUFZ7zNJTC8MG1lqH/liu4DiII1ge56iha7E0HIPG97z094xQApy', NULL, 'admin', 'ExkeFFP9Ctv5LmgQzxKIaWUIh165YuoVm9cqEyfu78kEipiEIeS4cEoZ2HBr', '2026-05-03 23:48:33', '2026-05-03 23:48:33'),
	(2, 'Petugas 1', 'officer@antrian.test', '2026-05-03 23:48:33', '$2y$12$APUFZ7zNJTC8MG1lqH/liu4DiII1ge56iha7E0HIPG97z094xQApy', NULL, 'officer', '6uaaeHSbf8QY17WRZU8utuzR67KuRiGeS98bFZPmVW8yPemalvXLH44XVo5Z', '2026-05-03 23:48:33', '2026-05-03 23:48:33');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
