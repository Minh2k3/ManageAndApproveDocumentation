-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               8.0.34 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

SET foreign_key_checks = 0;

-- Dumping database structure for backend
CREATE DATABASE IF NOT EXISTS `backend` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `backend`;

-- Dumping structure for table backend.admins
DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admins_user_id_foreign` (`user_id`),
  CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.admins: ~2 rows (approximately)
INSERT INTO `admins` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 16, '2025-05-26 16:45:51', '2025-05-26 16:45:51'),
	(2, 17, '2025-05-26 16:45:52', '2025-05-26 16:45:52');

-- Dumping structure for table backend.approval_permissions
DROP TABLE IF EXISTS `approval_permissions`;
CREATE TABLE IF NOT EXISTS `approval_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `roll_at_department_id` bigint unsigned NOT NULL,
  `document_type_id` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `approval_perm_unique` (`roll_at_department_id`,`document_type_id`),
  KEY `approval_permissions_document_type_id_foreign` (`document_type_id`),
  CONSTRAINT `approval_permissions_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `approval_permissions_roll_at_department_id_foreign` FOREIGN KEY (`roll_at_department_id`) REFERENCES `roll_at_departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quyền ký văn bản theo chức vụ trong đơn vị và loại văn bản';

-- Dumping data for table backend.approval_permissions: ~112 rows (approximately)
INSERT INTO `approval_permissions` (`id`, `roll_at_department_id`, `document_type_id`, `created_at`, `ended_at`) VALUES
	(1, 1, 2, '2025-05-26 16:45:29', NULL),
	(2, 1, 1, '2025-05-26 16:45:29', NULL),
	(3, 3, 2, '2025-05-26 16:45:29', NULL),
	(4, 3, 1, '2025-05-26 16:45:29', NULL),
	(5, 5, 2, '2025-05-26 16:45:29', NULL),
	(6, 5, 1, '2025-05-26 16:45:29', NULL),
	(7, 7, 2, '2025-05-26 16:45:29', NULL),
	(8, 7, 1, '2025-05-26 16:45:29', NULL),
	(9, 9, 2, '2025-05-26 16:45:29', NULL),
	(10, 9, 1, '2025-05-26 16:45:29', NULL),
	(11, 11, 2, '2025-05-26 16:45:29', NULL),
	(12, 11, 1, '2025-05-26 16:45:29', NULL),
	(13, 13, 2, '2025-05-26 16:45:29', NULL),
	(14, 13, 1, '2025-05-26 16:45:29', NULL),
	(15, 15, 2, '2025-05-26 16:45:29', NULL),
	(16, 15, 1, '2025-05-26 16:45:29', NULL),
	(32, 1, 8, '2025-05-26 16:45:29', NULL),
	(33, 1, 7, '2025-05-26 16:45:29', NULL),
	(34, 1, 6, '2025-05-26 16:45:29', NULL),
	(35, 1, 5, '2025-05-26 16:45:29', NULL),
	(36, 1, 4, '2025-05-26 16:45:29', NULL),
	(37, 1, 3, '2025-05-26 16:45:29', NULL),
	(38, 2, 8, '2025-05-26 16:45:29', NULL),
	(39, 2, 7, '2025-05-26 16:45:29', NULL),
	(40, 2, 6, '2025-05-26 16:45:29', NULL),
	(41, 2, 5, '2025-05-26 16:45:29', NULL),
	(42, 2, 4, '2025-05-26 16:45:29', NULL),
	(43, 2, 3, '2025-05-26 16:45:29', NULL),
	(44, 3, 8, '2025-05-26 16:45:29', NULL),
	(45, 3, 7, '2025-05-26 16:45:29', NULL),
	(46, 3, 6, '2025-05-26 16:45:29', NULL),
	(47, 3, 5, '2025-05-26 16:45:29', NULL),
	(48, 3, 4, '2025-05-26 16:45:29', NULL),
	(49, 3, 3, '2025-05-26 16:45:29', NULL),
	(50, 4, 8, '2025-05-26 16:45:29', NULL),
	(51, 4, 7, '2025-05-26 16:45:29', NULL),
	(52, 4, 6, '2025-05-26 16:45:29', NULL),
	(53, 4, 5, '2025-05-26 16:45:29', NULL),
	(54, 4, 4, '2025-05-26 16:45:29', NULL),
	(55, 4, 3, '2025-05-26 16:45:29', NULL),
	(56, 5, 8, '2025-05-26 16:45:29', NULL),
	(57, 5, 7, '2025-05-26 16:45:29', NULL),
	(58, 5, 6, '2025-05-26 16:45:29', NULL),
	(59, 5, 5, '2025-05-26 16:45:29', NULL),
	(60, 5, 4, '2025-05-26 16:45:29', NULL),
	(61, 5, 3, '2025-05-26 16:45:29', NULL),
	(62, 6, 8, '2025-05-26 16:45:29', NULL),
	(63, 6, 7, '2025-05-26 16:45:29', NULL),
	(64, 6, 6, '2025-05-26 16:45:29', NULL),
	(65, 6, 5, '2025-05-26 16:45:29', NULL),
	(66, 6, 4, '2025-05-26 16:45:29', NULL),
	(67, 6, 3, '2025-05-26 16:45:29', NULL),
	(68, 7, 8, '2025-05-26 16:45:29', NULL),
	(69, 7, 7, '2025-05-26 16:45:29', NULL),
	(70, 7, 6, '2025-05-26 16:45:29', NULL),
	(71, 7, 5, '2025-05-26 16:45:29', NULL),
	(72, 7, 4, '2025-05-26 16:45:29', NULL),
	(73, 7, 3, '2025-05-26 16:45:29', NULL),
	(74, 8, 8, '2025-05-26 16:45:29', NULL),
	(75, 8, 7, '2025-05-26 16:45:29', NULL),
	(76, 8, 6, '2025-05-26 16:45:29', NULL),
	(77, 8, 5, '2025-05-26 16:45:29', NULL),
	(78, 8, 4, '2025-05-26 16:45:29', NULL),
	(79, 8, 3, '2025-05-26 16:45:29', NULL),
	(80, 9, 8, '2025-05-26 16:45:29', NULL),
	(81, 9, 7, '2025-05-26 16:45:29', NULL),
	(82, 9, 6, '2025-05-26 16:45:29', NULL),
	(83, 9, 5, '2025-05-26 16:45:29', NULL),
	(84, 9, 4, '2025-05-26 16:45:29', NULL),
	(85, 9, 3, '2025-05-26 16:45:29', NULL),
	(86, 10, 8, '2025-05-26 16:45:29', NULL),
	(87, 10, 7, '2025-05-26 16:45:29', NULL),
	(88, 10, 6, '2025-05-26 16:45:29', NULL),
	(89, 10, 5, '2025-05-26 16:45:29', NULL),
	(90, 10, 4, '2025-05-26 16:45:29', NULL),
	(91, 10, 3, '2025-05-26 16:45:29', NULL),
	(92, 11, 8, '2025-05-26 16:45:29', NULL),
	(93, 11, 7, '2025-05-26 16:45:29', NULL),
	(94, 11, 6, '2025-05-26 16:45:29', NULL),
	(95, 11, 5, '2025-05-26 16:45:29', NULL),
	(96, 11, 4, '2025-05-26 16:45:29', NULL),
	(97, 11, 3, '2025-05-26 16:45:29', NULL),
	(98, 12, 8, '2025-05-26 16:45:29', NULL),
	(99, 12, 7, '2025-05-26 16:45:29', NULL),
	(100, 12, 6, '2025-05-26 16:45:29', NULL),
	(101, 12, 5, '2025-05-26 16:45:29', NULL),
	(102, 12, 4, '2025-05-26 16:45:29', NULL),
	(103, 12, 3, '2025-05-26 16:45:29', NULL),
	(104, 13, 8, '2025-05-26 16:45:29', NULL),
	(105, 13, 7, '2025-05-26 16:45:29', NULL),
	(106, 13, 6, '2025-05-26 16:45:29', NULL),
	(107, 13, 5, '2025-05-26 16:45:29', NULL),
	(108, 13, 4, '2025-05-26 16:45:29', NULL),
	(109, 13, 3, '2025-05-26 16:45:29', NULL),
	(110, 14, 8, '2025-05-26 16:45:29', NULL),
	(111, 14, 7, '2025-05-26 16:45:29', NULL),
	(112, 14, 6, '2025-05-26 16:45:29', NULL),
	(113, 14, 5, '2025-05-26 16:45:29', NULL),
	(114, 14, 4, '2025-05-26 16:45:29', NULL),
	(115, 14, 3, '2025-05-26 16:45:29', NULL),
	(116, 15, 8, '2025-05-26 16:45:29', NULL),
	(117, 15, 7, '2025-05-26 16:45:29', NULL),
	(118, 15, 6, '2025-05-26 16:45:29', NULL),
	(119, 15, 5, '2025-05-26 16:45:29', NULL),
	(120, 15, 4, '2025-05-26 16:45:29', NULL),
	(121, 15, 3, '2025-05-26 16:45:29', NULL),
	(122, 16, 8, '2025-05-26 16:45:29', NULL),
	(123, 16, 7, '2025-05-26 16:45:29', NULL),
	(124, 16, 6, '2025-05-26 16:45:29', NULL),
	(125, 16, 5, '2025-05-26 16:45:29', NULL),
	(126, 16, 4, '2025-05-26 16:45:29', NULL),
	(127, 16, 3, '2025-05-26 16:45:29', NULL);

-- Dumping structure for table backend.approvers
DROP TABLE IF EXISTS `approvers`;
CREATE TABLE IF NOT EXISTS `approvers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `roll_at_department_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `approvers_user_id_foreign` (`user_id`),
  KEY `approvers_department_id_foreign` (`department_id`),
  KEY `approvers_roll_at_department_id_foreign` (`roll_at_department_id`),
  CONSTRAINT `approvers_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `approvers_roll_at_department_id_foreign` FOREIGN KEY (`roll_at_department_id`) REFERENCES `roll_at_departments` (`id`),
  CONSTRAINT `approvers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.approvers: ~15 rows (approximately)
INSERT INTO `approvers` (`id`, `user_id`, `department_id`, `roll_at_department_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 4, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(2, 2, 4, 2, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(3, 3, 5, 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(4, 4, 5, 4, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(5, 5, 5, 4, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(6, 6, 6, 15, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(7, 7, 6, 16, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(8, 8, 6, 16, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(9, 9, 6, 16, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(10, 10, 7, 9, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(11, 11, 7, 10, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(12, 12, 7, 10, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(13, 13, 8, 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(14, 14, 8, 4, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(15, 15, 9, 13, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(16, 18, 4, 2, '2025-05-26 17:39:12', '2025-05-26 17:39:12'),
	(17, 19, 6, 16, '2025-05-26 17:43:11', '2025-05-26 17:43:11');

-- Dumping structure for table backend.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.cache: ~2 rows (approximately)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('he_thong_quan_ly_va_phe_duyet_van_ban_cache_a75f3f172bfb296f2e10cbfc6dfc1883', 'i:2;', 1749576845),
	('he_thong_quan_ly_va_phe_duyet_van_ban_cache_a75f3f172bfb296f2e10cbfc6dfc1883:timer', 'i:1749576845;', 1749576845);

-- Dumping structure for table backend.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.cache_locks: ~0 rows (approximately)

-- Dumping structure for table backend.ca_certificates
DROP TABLE IF EXISTS `ca_certificates`;
CREATE TABLE IF NOT EXISTS `ca_certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `private_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificate` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` timestamp NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ca_issued_at` (`issued_at`),
  KEY `idx_ca_expires_at` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.ca_certificates: ~0 rows (approximately)
INSERT INTO `ca_certificates` (`id`, `name`, `private_key`, `certificate`, `issued_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(1, 'TLU Document Management CA', 'eyJpdiI6InRSbmxJWWZlL1FzWXl1c1pGNWpvbmc9PSIsInZhbHVlIjoiYmZWMnlWL0hRSlk3SmlIM2R4Nnh3ajNTQUo4V0JLZnNXMlZIOXBzSTJ5NCs2NFR6NE5YaEhFSzlZcytGTTVGb0J0eDVMWWJKV294dTFnaTBqejlMK2twWjQrcWxPOUptc2pUV2lYY3hXbmM1amxIbUFHRUxwTnIxWFpJTzFkWU9MalJwUFY4TU9qVm5nTkZ4Mkt6cVJERXJIY05HZUxwYVdMY0oyOGdwWk1OWDd0SDQ0dStCaGNYYnN4V0dFVm8vZGdvYnh1UUhkdktCc1BkTmJ0MXpQYUtlV09la05zUFl4MFNmOW9WajFROUFUQ1lSYUxmNG5nQTl1RDVIa2VzZkg0aFAreXNyVHZoa1UyNmVYWHhhUFRrNGVhSFdXb0g5dElMTit2QU54UGpxVllweVNrU2tMbmRPc205TjN1aysyT05QVm1PWUs1MTlzTmNWUkNPRUZNa1h6czZWZzFUQTQyQmNsQk93bkxxcnVJTUZpc01Sb1lOQlZsR3I1ajcrbzI1dVcwSytmZjZlM3ZOWDFlNVFtVG9BY3pEQmE0eWljekJnUEhNVHBweW9Oc29LTDhHYlFaOFpMZWxxZW05Y0lmdzQwSE44Rk9GVDJGM3RVRFJ2TFZwQTFQVnhqWVlHZ3Q1bzlldEM3R0tUa29BblY5dlIrRnJzZjQ0U0VrZ1p0NVVUWEVBMGduSm1LUVpmcE9MdWZETFZ3allPK0kyall6VGNHNU02c2tDVFJ0dm8xYzZGTisvSnpDVHdIWTdtVlcxd1g5cEhYRXdtZFpjbGxQTXIveUhZZ05SbE1TUUMvR1ArSXZZOStvdFRPOFdIN0RwUldQUXc1UzA2akpsREtka05GcTZRU2tCRUxTUHQwUUtxU2NTVm5kZW4ybnNMSnhLN3pXTDVhYzlXRGo1TTdjcWxsdVJQRHdmcFkwOGlYckxHV3BIMzh2U3JBTEg1T3N0WktSVTdSVTNUTXNBZmVtd3AwWFVXZ0ZUQmFDaThwdS9FdzJDMGtQYWVuZWJXMmtPc05UL01qTXJ4Z003SGZlQ0g0cVBPU3Z3N29rdERrclEvc1JrRUo0Nm5iY05wR3dDb1RYOUs5MFN6NGVtcExCWUZtaE1EZWppZldLMXJMTXEwQ1lvTk1EQUV0blp2TlYyMGxFSHJUVzk0N0VQaThjWER0QXgrNnFlRW45ZHAxQXVrNUhPSzVKM3FXZmMvNThJMTg3YWFBOGdDd1dGZmh3RWRXV016WlIyM2JmTUFwOFo5RkcwOGg3NFFRYzlHV3FhWGkyd2pRZC9sK0svYzNEUnRScFVoQkFSTFlUT1I4MEp2WWkxckRaSFVHQmFUN2JNMnRaY2JrbW9tbXFIS3dKdXB3WmFadXIrTTV1WE5FbXhJWUljZnoyaWNLUjZmQjgySGVVNlNabXQ4OTZscEpoODdOU3JheVJoVVZhWG9wYkdTQlZMMXdLOFM3N21wRlhvRHhhZDVWOWRLN3liNlRJRkFNL3lmZ3YxdW9VSjRqZUxjOER6NGNqMmVwMXoxUGYwMGJnMmNGZjRHRVJEbTJFNnRCVlFWVS90RDZGbHgxSkxDQzRwYUhySmNGSlgxV1JCTFdSOWgvWDZBWFNUaGc2T0xHdklaT2dxYWlQYXFTc2dDVjJ5djZwdEZPR0w2OFpEQWVNL0NITFJJamhWcGs0QjBBckNibEdjUzhMc1VzR29xeFdTZnI4U2FVZ2FPdzRpVDJSQjhFRVlFQWF2UVF3cEdnVXFQR21Wc3ZjVUthdDR3b1U3MFFMZE85bHlkcU9DL0c1dFJVK3llYjU0cTlLbzliZXVDeDZlcFl1YkEvTG5xOHdpSHJaOXV0L0h3Y3YxcXlKbG9lKzIwNE1GWUlndHZHMDlvUEFSZUZlZjVyUWJzN3g4WFlXNTEyUUd4TkNMMjNFN0lVVTZMMy8yQkh2NG5ER3RpczhjVk5yRUVsV0dPSDQyODRCVUV0Um9PRjM3Y1BFMmN1WTFWWjVUMy9EVGdZR0dHcGFBRjdnemRLNTNjUlhTdGlmMUpmRzQreFZNWDNHdUZXVDdHT0w1Zy95L1VqemNjVkJVcytCb0x2QnZDS1FPWnM5RnNvdnNHY2QrZHp4SDB4cHBXT1BDR1RIdFk4UVRqUDR2R1lhVnpsUHVydGRvVCtGUi9VcFIxeEI3dC9iNXFpaWY4dms1YUhldGJqaHZYWHNCb29ZaFNHdVlUek1YQjJnOVhDelFiYlkxVm9id3NCeE4rR3IzeWI1STR2am1zMzNRV2I3WDJ3c3FVQXJ5UUxpcUtOaG00K1JieXpVb01UMTdZWE5OSk8wV2NCQkpsbW9TeWpUOXBxQ1FSQkl2YWhxcmJaWFF5dVpCSHkxZmFTNzB4am9VV1hUMm9EY2grN3JlM1hLbXdTOGNGdk5nMUlPZmNOamxERzVnZ1FKa0RScVh1V1htc0s2eWVuRW05S1E3YnExUHNFR1ZmeGVTL21aclZtc3IzaDR0TjNiNXQxOUdLc2Z1SmErdTFyazVoZjAxQkQrb3lldnJXbkpvYndpL3BNeWQwU1RhQlZGeUM1UjMzQTFTaTZETjNtT2FNMEJlc0Z5TnRNaHlFRndFOW5IYVh0YUcrR1dsQ2NHOU9UZVRRV1JMU1hJQnZFRDdaRHpGVldJRGZ0SDJPVHlXQ0U3M2k1SkI0bUE3K3lWYTg5d3plR0ZQOUZGK09NNEdCdUF2ZEVBVXQyWlNQMUN4YzA1cytabllCVkFUajB6QzlBbG1scXFldzdkUmZyK0ZLTUdyQTMyWFA3WWVVbmpUcnYxNW9EdFBMY3dVWndaSE9aWnpQaVEwdzRjdmo3YTY0d0pEVllZOHVoZVlVMzBaZVF6S09ldXNVeDJMN0czaGFrUzl6MElZa3J6WnZpb2JrUkFudE1ReEFjT3BJYTBQMWgxbDVEZmVLRTZSQzVWVFRZZ0hMRnRJNk9QeGxzN2VJVUhacDBFdCtZMENMZzFBbTJRVkE5dkpUa3dRMnhTbUxRS0pjVjUvWGJaNm5lTlBLWWpDTFU1NEl0enBDdU4rOEpNVXVhcjJpTnptbUpMYWsydXFPL2VuRGdIR3JBVmV6R25YN3R1OTZhdS8zZmNaTkxVZ0RRQUxIazVaUVhiUkxJaHhrMDZyY2ZwK3BsVnhOdXBDa1lyT1lMaUZlQ0dkd1VEb1E5bmw5MVR1dFBCVFNONkNSZ2tCMTFzL0k0cFgveXJybHZyODVhbHB1cGwxRDd6U2lQUHhRNS9DNlpkYWNQbUFuaWVzY2JWVjhRMU5oYkdLT3FMMW85QnlNRFFVRVdXODlBZjlSOEl5Tjl3ZWp1ZE4yTmJLREJFN01PRlNvSUh1Y2VhNklBUlIvYkFOOW4xcjh4ang0L3N5Vm5NUENLSldILzZza3NIZVo2S2RkSGM2R3c1WXhWQkVnQkN0amx2TCtMYU0xSVRHRGlkMFNET1czVU5zeVlyM2FNZ1VWME15a3lQUi8yM0N5aUFtbVV6TXlURklpb1oxQUpMdkNYdmZKc1VNVE9ZeS9lRDlsQ3gzUDlmaUJKU2NZYjBrUUJ4MDZaS3daYlhJa3BPZ1hxOVI3N3oxa2tCaFhTT1k4UDRGRE1SQXF3ekhQWGFIa2Z2R0EwVEdPRWNnbzd1ZE1JK09tM3ozRllTSUNzYmtCTXBDeWFmRGdrK1l4ZGY3RWY3eWdlRlcvUXBpa2FCd0taMlRrM2F0RWFBa0syb1BtS3AvSlJ1L1c4amRNaEdQQnpVNlAwTTR0alkxOWd4UjFvR09icjgxeFFrYzllMzFqeXkwR3FMUDIwQ2N1Ym5RLzRja0x2aFNLaUxhanlhM1RucTVYbkVNRmMxRGlGM1VSUmxKazk1bWV4cUVQeEREUDdiRU0zbE5BdTBFSUF5ZjJCeWhKcnJVSXRZQkxYQlBSaWQ2RVdLLzlZSHd2RXVnWXJ4VmNWbFpMUlJoM2tzdC82L2pCNm16dWdFUHZHMUd1VUdvSkVxdHV6R0FpTm5yc1RGZHc2azRybitTNEhPdHlnUTZ0LzlFOWNheEtZRC80OFlkQmxhVUtsVVlBeWxDTHBUaFBLMHRUdWk5Z28rUXhGMVJHVXlvVldOcHlPMXl4V1h5ZVFDVmJBRVl5cnlvVWxVVTY5aHZsVXhYaGxEd1dLKyszK0dWNWQzaEVaeGd0ZnBMQXdzaVZUb3RyNHFwOVg3RXA2Qml5RldOR0VGRHdFdVA1UDFGT3VWeVhlUmZXN1VLYXJVV1JJUHRNVnFNQjdjZFRTSTFJZDRzeEpWMHJCa3lxa3ViRWtXVzZvb2lEMmtKSGt6eWMraUZNb29hL0liTVcycG9HWkFDNDc4bElyaFM0MXl5NEdLZURRUWhPdGs4YUhIU3RlZERxOHVpYjdjWndxM1VFbHlORmZCZjlodlZNNkxRVFRxM3JHWGVheXpQWks4TUpJL3B3V2ZhUlVYMkxCMmU5dWpqNWJtMTUyUmlEcUpRWHYwZEhyZmxIMWtTajFONlRGRzBGSVNnSHNBT1hRMkFjT3VmbmxQL1RTY25JakpWbEt6VGpZZlIzZlBYUDk3ZEZ0cDdKalpGUlk3bW1OQXdKWFZJRW1BcHhwbmJoVmNTRVkwbmNkbnBSQ3I1bHJBUllhVjh5Zy9ZVElDajBFdjB6VCtKbnRDK1VMcHlSTHhSVVVGbXVxUVhiQUhzcVRhaXByeVpPSmNIQjFNOFRSQWRZdVJvUXRnb01sWVNkZ0gyZDdlSGV2ZklzMEcxa0pzMDRtQmlUMWh5Ym85RTIzdnN4ZnFxTU1adi9IN3RPNTRTNWhtN25FUkptUEtsbEExZGdJeVFodlA4eFhGYkxSRllJSng0QUR5TWJ0WVhrS1phZ0tlYlZGODZIN0NBbTBJRCs1dGxPM2JZOCtiRm9mN2xJSUdqVWlnaXcrTEk2dGZDVHcwenY4eXl2ak9mYXdMdlRkQVprZHFLbWxPSTNLM1V6cWxwZ2ZPZFkxbExPWUNOWmxRT0wrQWNWM2pHZGdxTVU4bTY5cm1valQ4Uzl0a0h2SS9pNlkxVnZyZHJlZEpMc2M3cXgyZE5tVWxnbUVwUEIxY1FDOE5ueVhud2RKRnpPR2RrallkbzJJVTBNNjgzMU9PaXFTR2JJcHphN3BwdkVrcCs0WFNoWmpmd3lnOWlMaGpFa0hnSjNRVjdzQzVRUFo2eVRCbklySGRxMDFJcnZZbW1tSlFxTUk0UXkwdytLWFk3SHl4LytpVnlEcktPT2JTaEZEUVQ2emVVVHR3NGNVd3RGeTRDVENqTU8xYkpZSVJlN3JBWnhBZk1wM1VLZi8xeUhDc0pvMWptZnlPTDBFU0JYMGJrd1hjOUpjY2FZNE1QeERwSTU3MmY0aDVsOUZvUzdhdC9Mcmc5d2NwWmJHWE5jdk1jWGNOMDA0M3YvQXlGUFErTUtQQ203bEo1VEEyUE1iL0pvNEdOQnAxVkN2OFAveGh4WHBaVTdmbStRTm5xOWRXeVgzM240U05aVFNJUi91ZkZQTFBRWGFzZk03ZURUMVlvWjR6QS9OdEs3SVhKZkpDOUxLK3A4UXRVRmJvbklpa1FXeThwRVNRNzFLbDNpYmlpT0NYWFFXRjlsZHBkbHFWMFd4L3M5R3loVHlsTHZoTXpkN0JiTGtDSnhhYTJIa3lxMUhFRWtmVVBWbExHVGtrR3J4QndhZUppMHV0M1Avemlmck5BL3hxQ0ViL0gxY1dFRDU0SUNwV2VrUGpMSUhVbWhnU0dURzBNV2YyM0dNS000UU1VTWYvL0lSQ1B1end2NFI2VVl1SThqOCsvTGhVMmZNTmhqY2EzU2NIT3RDN2VjL2JsalpHVlJwZXlMZWtJTXBBditranZERHhBalNGTGhsaUtOMHJ2MzNITVlLMHpuS1ozdU9kQ0xGdUZLbVVKYkZPTT0iLCJtYWMiOiIyYTgwM2I3MDdjZDljZDRjMzFlMzI4ZTAzZDA1NTZjNGRkOGFiYWFjNmVkYzcyYWI1ZWQ3N2I1ZWY3Nzk1M2EzIiwidGFnIjoiIn0=', '-----BEGIN CERTIFICATE-----\r\nMIIFsjCCA2WgAwIBAgIBATBCBgkqhkiG9w0BAQowNaANMAsGCWCGSAFlAwQCAaEa\r\nMBgGCSqGSIb3DQEBCDALBglghkgBZQMEAgGiAwIBIKMDAgEBME0xIzAhBgNVBAMM\r\nGlRMVSBEb2N1bWVudCBNYW5hZ2VtZW50IENBMRkwFwYDVQQKDBBEYWkgaG9jIFRo\r\ndXkgbG9pMQswCQYDVQQGDAJWTjAeFw0yNTA2MDQwMjM4MjNaFw0zNTA2MDUwMjM4\r\nMjNaME0xIzAhBgNVBAMMGlRMVSBEb2N1bWVudCBNYW5hZ2VtZW50IENBMRkwFwYD\r\nVQQKDBBEYWkgaG9jIFRodXkgbG9pMQswCQYDVQQGDAJWTjCCAlcwQgYJKoZIhvcN\r\nAQEKMDWgDTALBglghkgBZQMEAgGhGjAYBgkqhkiG9w0BAQgwCwYJYIZIAWUDBAIB\r\nogMCASCjAwIBAQOCAg8AMIICCgKCAgEA4cGo2Et8s22b2HWyrzbTEZMJHOVDHeoj\r\n7Th2lXc+IYIDdKOnro4AUzdpblyQRhUGtaB+5QLqB49PixUozLWvQX31uapIzEAl\r\nJYyIOgh4eTSgXD3oCg6NfI8OHHDnyUJtsO/rfXtpNmGLJ8nMCoAfa+7jfDIkdscq\r\n7pmcOUdfischZJo39kkgx5gDWyA1CsmQUIq5+15wvrOJ23wLla6oOyzBZbYvfkrQ\r\noggM3lycMUP1g8171o2HcijspDW+HIUlPS4nXm1sj7zhUsTaZEprVWcdvOt2M1OS\r\n6adfXF7/3qY90S+kbirLGTCJSw8/+a4y9SrVUmfEc3u7m5oayRgKACQ1ZgH9Zr16\r\nrCEw3CctCIQRZSGBxvMOa3hFJ44WwNvD9d1gKm99N+CoxibAmdzm8gJsFxJaHz06\r\nDXVZ8yShfIqy255uhINR0+j4UcWFJmOJJR5eI/IqCzvXGqKGqvIlATuMHk3nA/Tt\r\nf4sypNHrRXEsNFzG50g9qLYBDZJpvL9uThNJTumZyOsDmrvAlwQSzGM5gQKl8tBf\r\nJKCBm0WvBI0bB1g8cLB0WYY/eoLWncGGMazoZLdxEWagGrKSQJz74r3DfIDeiImj\r\n3aKE4rPEofTgE/po3nCGwtf4G/Msddkuyanm1JAHNntGS9+Wh4qPCpiE3jAbnR4W\r\nhhlb3cHecdMCAwEAATBCBgkqhkiG9w0BAQowNaANMAsGCWCGSAFlAwQCAaEaMBgG\r\nCSqGSIb3DQEBCDALBglghkgBZQMEAgGiAwIBIKMDAgEBA4ICAQCEASHMXjXCrD45\r\nIKVxgsPDmF4NWWk+QWeXI+ffHpnujBlyjFWrsyrwRcpxq3bi/Qf4Dp2JoPTwGHD7\r\nkl6GO8rszKRy75qMq/FtmSsBrYWafCBKSCXhbdxLITVrXBGPO0bLJnBxavPDWNDT\r\n5vDjnEO7MQaJxvlY3C5b2s3soa4CHOnuI9B53MPwNJAZE1hAq8YRzfoJBgw9mJfI\r\n2FUghPSgj8YeO4SlwJJH016p2LJTmRmHJ7r30jAGQ4G4LZTyBZWrSEQ2FJCLvT6g\r\n3BzmS0c7gVSIFlfrAVcVNQ58UTRRT1sYaVu7LFy24Q8FZX/XZBMyC90lCv2DVHSx\r\n6DUojq3BTGU9pJjrUISEBLxWqaxw7JuvqGWDkETe0MTPu2n3FLkrCUm9M5UNs6lx\r\ntmGNsmEX4VsTAnKh0sbM0ccglXZg2yyxb1XM3mq9xaFZ0Oy+h4X1Xh/vXl2vCWzU\r\nO0YTdZyAbpRU0oq1C+c7RBL2jNNNdv1Gx3lm1dGQu6qT966t450E3pKyg5e041QV\r\nAiUvJFoE2Jf707pafhpS3DHukDx/4N85nEZKrdQN+eYxM0HflyniFzYe8FMBSJdY\r\nwokO1QCGMDlH9iqIljAjOOBx7nEeFefTFUfljZfqhmHao3TXLrJrbD3L0CupW+CB\r\nxyaIEDqRKe1tNgxmnmXG6+/ewDBREw==\r\n-----END CERTIFICATE-----', '2025-06-05 02:38:23', '2035-06-05 02:38:23', '2025-06-05 02:38:23', '2025-06-05 02:38:23');

-- Dumping structure for table backend.certificates
DROP TABLE IF EXISTS `certificates`;
CREATE TABLE IF NOT EXISTS `certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `public_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `private_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificate` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` timestamp NOT NULL,
  `expires_at` timestamp NOT NULL,
  `status` enum('active','revoked','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `required_renewal` tinyint(1) DEFAULT '0',
  `used_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_status` (`user_id`,`status`),
  KEY `idx_issued_at` (`issued_at`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.certificates: ~0 rows (approximately)

-- Dumping structure for table backend.creators
DROP TABLE IF EXISTS `creators`;
CREATE TABLE IF NOT EXISTS `creators` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `roll_at_department_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creators_user_id_foreign` (`user_id`),
  KEY `creators_department_id_foreign` (`department_id`),
  KEY `creators_roll_at_department_id_foreign` (`roll_at_department_id`),
  CONSTRAINT `creators_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `creators_roll_at_department_id_foreign` FOREIGN KEY (`roll_at_department_id`) REFERENCES `roll_at_departments` (`id`),
  CONSTRAINT `creators_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.creators: ~0 rows (approximately)
INSERT INTO `creators` (`id`, `user_id`, `department_id`, `roll_at_department_id`, `created_at`, `updated_at`) VALUES
	(1, 20, 7, 17, '2025-05-26 17:54:15', '2025-05-26 17:54:15');

-- Dumping structure for table backend.departments
DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `group` enum('Khoa/Trung tâm','LCĐ','LCH','Phòng/Ban','CLB/Đội','Khác') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','non-active') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'where this department situated in',
  `can_approve` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'check if this department has approval permission or not',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.departments: ~49 rows (approximately)
INSERT INTO `departments` (`id`, `name`, `description`, `group`, `status`, `avatar`, `phone_number`, `position`, `can_approve`, `created_at`, `updated_at`) VALUES
	(1, 'Hãy chọn khoa tương ứng', 'Sử dụng trong luồng mẫu khi một đơn vị duyệt phụ thuộc vào người yêu cầu ví dụ nếu là từ LCĐ khoa CNTT thì có thể chọn khoa CNTT, và tương tự...', 'Khác', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(2, 'Hãy chọn LCĐ tương ứng', 'Sử dụng trong luồng mẫu khi một đơn vị duyệt phụ thuộc vào người yêu cầu ví dụ nếu là từ LCĐ khoa CNTT thì có thể chọn khoa CNTT, và tương tự...', 'Khác', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(3, 'Hãy chọn LCH tương ứng', 'Sử dụng trong luồng mẫu khi một đơn vị duyệt phụ thuộc vào người yêu cầu ví dụ nếu là từ LCĐ khoa CNTT thì có thể chọn khoa CNTT, và tương tự...', 'Khác', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(4, 'Phòng CT & CTSV', 'Hay còn gọi là P7', 'Phòng/Ban', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(5, 'Đoàn Thanh niên', 'Đoàn Thanh niên trường', 'Phòng/Ban', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(6, 'Hội Sinh viên', 'Hội Sinh viên trường', 'Phòng/Ban', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(7, 'Khoa Công nghệ thông tin', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(8, 'LCĐ Khoa Công nghệ thông tin', 'LCĐ trực thuộc Khoa Công nghệ thông tin', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(9, 'LCH Khoa Công nghệ thông tin', 'LCH trực thuộc Khoa Công nghệ thông tin', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(10, 'Khoa Công trình', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(11, 'LCĐ Khoa Công trình', 'LCĐ trực thuộc Khoa Công trình', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(12, 'LCH Khoa Công trình', 'LCH trực thuộc Khoa Công trình', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(13, 'Khoa Cơ khí', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(14, 'LCĐ Khoa Cơ khí', 'LCĐ trực thuộc Khoa Cơ khí', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(15, 'LCH Khoa Cơ khí', 'LCH trực thuộc Khoa Cơ khí', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(16, 'Khoa Điện - Điện tử', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(17, 'LCĐ Khoa Điện - Điện tử', 'LCĐ trực thuộc Khoa Điện - Điện tử', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(18, 'LCH Khoa Điện - Điện tử', 'LCH trực thuộc Khoa Điện - Điện tử', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(19, 'Khoa Hóa & Môi trường', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(20, 'LCĐ Khoa Hóa & Môi trường', 'LCĐ trực thuộc Khoa Hóa & Môi trường', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(21, 'LCH Khoa Hóa & Môi trường', 'LCH trực thuộc Khoa Hóa & Môi trường', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(22, 'Khoa Kế toán & Kinh doanh', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(23, 'LCĐ Khoa Kế toán & Kinh doanh', 'LCĐ trực thuộc Khoa Kế toán & Kinh doanh', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(24, 'LCH Khoa Kế toán & Kinh doanh', 'LCH trực thuộc Khoa Kế toán & Kinh doanh', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(25, 'Khoa Kinh tế quản lý', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(26, 'LCĐ Khoa Kinh tế quản lý', 'LCĐ trực thuộc Khoa Kinh tế quản lý', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(27, 'LCH Khoa Kinh tế quản lý', 'LCH trực thuộc Khoa Kinh tế quản lý', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(28, 'Khoa KTTNN', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(29, 'LCĐ Khoa KTTNN', 'LCĐ trực thuộc Khoa KTTNN', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(30, 'LCH Khoa KTTNN', 'LCH trực thuộc Khoa KTTNN', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(31, 'Khoa Luật & LLCT', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(32, 'LCĐ Khoa Luật & LLCT', 'LCĐ trực thuộc Khoa Luật & LLCT', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(33, 'LCH Khoa Luật & LLCT', 'LCH trực thuộc Khoa Luật & LLCT', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(34, 'Trung tâm Đào tạo Quốc tế', 'Một khoa thuộc trường', 'Khoa/Trung tâm', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(35, 'LCĐ Trung tâm Đào tạo Quốc tế', 'LCĐ trực thuộc Trung tâm Đào tạo Quốc tế', 'LCĐ', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(36, 'LCH Trung tâm Đào tạo Quốc tế', 'LCH trực thuộc Trung tâm Đào tạo Quốc tế', 'LCH', 'active', NULL, NULL, NULL, 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(37, 'CLB SVTN', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(38, 'CLB SVTH', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(39, 'CLB Khởi nghiệp', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(40, 'CLB Tiếng Anh', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(41, 'CLB TNTN HMNĐ', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(42, 'CLB Kinh tế số', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(43, 'CLB Sách và HĐ', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(44, 'CLB Phát triển kỹ năng', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(45, 'Ban Nữ sinh', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(46, 'Ban Văn nghệ', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(47, 'CLB Báo chí', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(48, 'CLB Nhảy', 'Một CLB ở trường', 'CLB/Đội', 'active', NULL, NULL, NULL, 0, '2025-05-26 16:45:29', '2025-05-26 16:45:29');

-- Dumping structure for table backend.digital_signatures
DROP TABLE IF EXISTS `digital_signatures`;
CREATE TABLE IF NOT EXISTS `digital_signatures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `public_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `private_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature_image_path` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.digital_signatures: ~0 rows (approximately)

-- Dumping structure for table backend.documents
DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `status` enum('draft','in_review','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `document_flow_id` bigint unsigned DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Check if this document can display for everyone',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_document_type_id_foreign` (`document_type_id`),
  KEY `documents_created_by_foreign` (`created_by`),
  KEY `documents_document_flow_id_foreign` (`document_flow_id`),
  KEY `documents_status` (`status`) USING BTREE,
  CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_document_flow_id_foreign` FOREIGN KEY (`document_flow_id`) REFERENCES `document_flows` (`id`),
  CONSTRAINT `documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.documents: ~10 rows (approximately)
INSERT INTO `documents` (`id`, `title`, `description`, `file_path`, `document_type_id`, `created_by`, `status`, `document_flow_id`, `is_public`, `created_at`, `updated_at`) VALUES
	(1, 'Văn bản đầu tiên', 'Mong là không có sai sót gì cả', 'f19e9c22-d7f6-45ea-b8b5-29eadb166d17.pdf', 2, 20, 'in_review', 5, 1, '2025-05-26 17:56:27', '2025-05-26 17:56:33'),
	(2, 'Người phê duyệt gửi đi', 'Thử nghiệm văn bản của người có quyền phê duyệt gửi đi', 'bdc5df2b-2a54-4c12-af88-b8abda324bd9.pdf', 2, 19, 'in_review', 6, 1, '2025-05-26 18:21:54', '2025-05-26 18:21:57'),
	(3, 'Test', 'Giờ gửi cho Thúy đầu tiên', '5876b7b3-78c5-4638-b1f1-15783009e963.pdf', 3, 20, 'rejected', 7, 1, '2025-05-27 02:21:40', '2025-05-27 03:30:00'),
	(4, 'Test thêm nháp', 'Mong là không có sai sót gì', 'db8dcbe3-f622-421e-abc9-bae90b920404.pdf', 4, 20, 'draft', 8, 1, '2025-05-27 02:23:02', '2025-05-27 02:23:04'),
	(5, 'Thêm để test', 'Thêm văn bản có nhiều người đồng cấp', 'db639109-df44-4ee2-8df1-3543f0f682fb.pdf', 5, 20, 'in_review', 9, 1, '2025-05-27 08:09:47', '2025-05-27 08:09:55'),
	(6, 'Tạo để test', 'Mô tả gì đây, đói quá', 'cc25d34e-3a5d-46a9-8eb6-601231ce917c.pdf', 7, 20, 'approved', 10, 1, '2025-05-27 14:44:59', '2025-05-27 15:09:55'),
	(7, 'Tạo mới để test', 'Mô tả vài câu', '8d3fbf72-7931-4da3-ba8b-d35e5855c017.pdf', 3, 20, 'approved', 11, 1, '2025-05-28 09:00:11', '2025-05-28 09:09:26'),
	(20, 'Test chức năng', 'Mô tả gì được, bugs lắm vãi', 'bb255400-497e-4fce-92c1-43ed3bb4015c.pdf', 5, 19, 'approved', 24, 1, '2025-05-28 16:16:54', '2025-05-28 16:17:45'),
	(21, 'Như anh mơ', 'Không như anh mơ ngày đó, thật khó gặp nhau giữa góc phố nhỏ này\nEm ghé nơi đây với trái tim này nhớ, sâu trong lòng anh có đôi lời tỏ bày\nEm có nghe lời thì thầm mùa thu, lại nhắc chúng ta những chiều hôm đó\nDụi bàn tay anh vu vơ tờ giấy gấp, nháy mắt trao em nụ hôm gió', '98f2839b-23d7-456c-b875-e4ac0afe5536.pdf', 4, 20, 'in_review', 25, 1, '2025-05-30 15:14:42', '2025-05-30 15:14:47'),
	(22, 'Đăng ký văn bản', 'Mô tả', '1e30857c-1ac5-4a41-bde9-88fc29243258.pdf', 4, 20, 'in_review', 26, 1, '2025-05-31 07:29:55', '2025-05-31 07:30:14'),
	(23, 'Thử thông báo', 'Mô tả gì vậy', '9b8ccbb6-e793-4cce-93b8-382c7e37a949.pdf', 4, 20, 'in_review', 27, 2, '2025-06-01 17:21:42', '2025-06-01 17:21:47'),
	(24, 'Test lại thông báo', 'Mô tả dự án', '3dd8b616-e986-4b14-a5d0-43ed3cd20aab.pdf', 3, 20, 'in_review', 28, 1, '2025-06-10 08:53:15', '2025-06-10 08:53:18');

-- Dumping structure for table backend.document_comments
DROP TABLE IF EXISTS `document_comments`;
CREATE TABLE IF NOT EXISTS `document_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_version_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '2025-05-26 16:44:29',
  PRIMARY KEY (`id`),
  KEY `document_comments_document_version_id_foreign` (`document_version_id`),
  KEY `document_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `document_comments_document_version_id_foreign` FOREIGN KEY (`document_version_id`) REFERENCES `document_versions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.document_comments: ~5 rows (approximately)
INSERT INTO `document_comments` (`id`, `document_version_id`, `user_id`, `comment`, `created_at`) VALUES
	(6, 1, 20, 'Nhận xét văn bản', '2025-05-26 19:11:21'),
	(7, 1, 19, 'Thử nhận xét ở phía người phê duyệt', '2025-05-26 19:14:13'),
	(8, 3, 19, 'Văn bản chuẩn chỉnh', '2025-05-27 02:50:40'),
	(9, 7, 18, 'Nhận xét về văn bản', '2025-05-28 09:03:43'),
	(10, 7, 18, 'Nhận xét mới', '2025-05-28 09:08:28');

-- Dumping structure for table backend.document_flows
DROP TABLE IF EXISTS `document_flows`;
CREATE TABLE IF NOT EXISTS `document_flows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Is this template being used',
  `is_template` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Check if this flow is a template flow',
  `process` int NOT NULL DEFAULT '0' COMMENT 'The process of the document flow',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_flows_created_by_foreign` (`created_by`),
  KEY `document_flows_is_active` (`is_active`) USING BTREE,
  KEY `document_flows_is_template` (`is_template`) USING BTREE,
  CONSTRAINT `document_flows_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.document_flows: ~16 rows (approximately)
INSERT INTO `document_flows` (`id`, `name`, `description`, `created_by`, `is_active`, `is_template`, `process`, `created_at`, `updated_at`) VALUES
	(1, 'Tổ chức sự kiện CLB', 'Luồng phê duyệt cho văn bản xin tổ chức sự kiện cấp CLB', 16, 1, 1, 2, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(2, 'Tổ chức sự kiện LCĐ', 'Luồng phê duyệt cho văn bản xin tổ chức sự kiện cấp LCĐ', 16, 1, 1, 3, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(3, 'Xin khen thưởng CLB', 'Luồng phê duyệt cho văn bản xin cấp khen thưởng CLB', 16, 1, 1, 1, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(4, 'Xin mượn hội trường', 'Luồng phê duyệt cho văn bản xin mượn hội trường', 16, 1, 1, 3, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(5, 'Luồng phê duyệt cho \'Văn bản đầu tiên\' của Hải Nam - 5/27/2025, 12:56:26 AM', NULL, 20, 0, 0, 0, '2025-05-26 17:56:27', '2025-05-26 17:56:27'),
	(6, 'Luồng phê duyệt cho \'Người phê duyệt gửi đi\' của Ngọc Thúy - 01:21:53 27/5/2025', NULL, 19, 0, 0, 0, '2025-05-26 18:21:54', '2025-05-26 18:21:54'),
	(7, 'Luồng phê duyệt cho \'Test\' của Hải Nam - 5/27/2025, 9:21:39 AM', NULL, 20, 0, 0, 0, '2025-05-27 02:21:40', '2025-05-27 02:21:40'),
	(8, 'Luồng phê duyệt cho \'Test thêm nháp\' của Hải Nam - 5/27/2025, 9:23:01 AM', NULL, 20, 0, 0, 0, '2025-05-27 02:23:02', '2025-05-27 02:23:02'),
	(9, 'Luồng phê duyệt cho \'Thêm để test\' của Hải Nam - 5/27/2025, 3:09:46 PM', NULL, 20, 0, 0, 1, '2025-05-27 08:09:47', '2025-05-27 10:13:00'),
	(10, 'Luồng phê duyệt cho \'Tạo để test\' của Hải Nam - 5/27/2025, 9:44:58 PM', NULL, 20, 0, 0, 1, '2025-05-27 14:44:59', '2025-05-27 15:09:55'),
	(11, 'Luồng phê duyệt cho \'Tạo mới để test\' của Hải Nam - 5/28/2025, 4:00:10 PM', NULL, 20, 0, 0, 2, '2025-05-28 09:00:11', '2025-05-28 09:09:26'),
	(24, 'Luồng phê duyệt cho \'Test chức năng\' của Ngọc Thúy - 23:16:54 28/5/2025', NULL, 19, 0, 0, 1, '2025-05-28 16:16:54', '2025-05-28 16:17:45'),
	(25, 'Luồng phê duyệt cho \'Như anh mơ\' của Hải Nam - 5/30/2025, 10:14:40 PM', NULL, 20, 0, 0, 0, '2025-05-30 15:14:42', '2025-05-30 15:14:42'),
	(26, 'Luồng phê duyệt cho \'Đăng ký văn bản\' của Hải Nam - 5/31/2025, 2:29:52 PM', NULL, 20, 0, 0, 1, '2025-05-31 07:29:55', '2025-05-31 07:31:05'),
	(27, 'Luồng phê duyệt cho \'Thử thông báo\' của Hải Nam - 6/2/2025, 12:21:41 AM', NULL, 20, 0, 0, 0, '2025-06-01 17:21:42', '2025-06-01 17:21:42'),
	(28, 'Luồng phê duyệt cho \'Test lại thông báo\' của Hải Nam - 6/10/2025, 3:53:15 PM', NULL, 20, 0, 0, 0, '2025-06-10 08:53:15', '2025-06-10 08:53:15');

-- Dumping structure for table backend.document_flow_steps
DROP TABLE IF EXISTS `document_flow_steps`;
CREATE TABLE IF NOT EXISTS `document_flow_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_flow_id` bigint unsigned NOT NULL,
  `step` int NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `approver_id` bigint unsigned DEFAULT NULL,
  `multichoice` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('pending','in_review','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `decision` enum('approved','rejected','not_yet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_yet',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `signed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_flow_steps_document_flow_id_foreign` (`document_flow_id`),
  KEY `document_flow_steps_department_id_foreign` (`department_id`),
  KEY `document_flow_steps_approver_id_foreign` (`approver_id`),
  KEY `document_flow_steps_status` (`status`) USING BTREE,
  CONSTRAINT `document_flow_steps_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `approvers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_flow_steps_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_flow_steps_document_flow_id_foreign` FOREIGN KEY (`document_flow_id`) REFERENCES `document_flows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.document_flow_steps: ~28 rows (approximately)
INSERT INTO `document_flow_steps` (`id`, `document_flow_id`, `step`, `department_id`, `approver_id`, `multichoice`, `status`, `decision`, `reason`, `signed_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 6, NULL, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(2, 1, 2, 4, NULL, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(3, 2, 1, 1, NULL, 1, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(4, 2, 2, 5, NULL, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(5, 2, 3, 4, NULL, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(6, 3, 1, 6, NULL, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(7, 4, 1, 5, NULL, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(8, 4, 2, 6, NULL, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(9, 4, 3, 4, NULL, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 16:45:57', '2025-05-26 16:45:57'),
	(10, 5, 1, 8, 14, 0, 'in_review', 'not_yet', NULL, NULL, '2025-05-26 17:56:27', '2025-05-26 17:56:27'),
	(11, 5, 2, 6, 17, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 17:56:27', '2025-05-26 17:56:27'),
	(12, 6, 1, 6, 6, 0, 'in_review', 'not_yet', NULL, NULL, '2025-05-26 18:21:54', '2025-05-26 18:21:54'),
	(13, 6, 2, 4, 16, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-26 18:21:54', '2025-05-26 18:21:54'),
	(14, 7, 1, 6, 17, 0, 'rejected', 'rejected', NULL, NULL, '2025-05-27 02:21:40', '2025-05-27 03:30:00'),
	(15, 8, 1, 6, 17, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-27 02:23:02', '2025-05-27 02:23:02'),
	(16, 8, 2, 4, 16, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-27 02:23:02', '2025-05-27 02:23:02'),
	(17, 9, 1, 6, 6, 1, 'in_review', 'not_yet', NULL, NULL, '2025-05-27 08:09:47', '2025-05-27 08:09:47'),
	(18, 9, 1, 6, 17, 1, 'approved', 'approved', NULL, '2025-05-27 10:13:00', '2025-05-27 08:09:47', '2025-05-27 10:13:00'),
	(19, 9, 2, 4, 16, 0, 'pending', 'not_yet', NULL, NULL, '2025-05-27 08:09:47', '2025-05-27 08:09:47'),
	(20, 10, 1, 6, 17, 0, 'approved', 'approved', NULL, '2025-05-27 15:09:55', '2025-05-27 14:44:59', '2025-05-27 15:09:55'),
	(21, 11, 1, 6, 17, 0, 'approved', 'approved', NULL, '2025-05-28 09:01:48', '2025-05-28 09:00:11', '2025-05-28 09:01:48'),
	(22, 11, 2, 4, 16, 0, 'approved', 'approved', NULL, '2025-05-28 09:09:26', '2025-05-28 09:00:11', '2025-05-28 09:09:26'),
	(35, 24, 1, 4, 16, 0, 'approved', 'approved', NULL, '2025-05-28 16:17:45', '2025-05-28 16:16:54', '2025-05-28 16:17:45'),
	(36, 25, 1, 6, 17, 0, 'in_review', 'not_yet', NULL, NULL, '2025-05-30 15:14:42', '2025-05-30 15:14:42'),
	(37, 26, 1, 6, 17, 0, 'approved', 'approved', NULL, '2025-05-31 07:31:05', '2025-05-31 07:29:55', '2025-05-31 07:31:05'),
	(38, 26, 2, 4, 16, 0, 'in_review', 'not_yet', NULL, NULL, '2025-05-31 07:29:55', '2025-05-31 07:31:05'),
	(39, 27, 1, 6, 17, 0, 'in_review', 'not_yet', NULL, NULL, '2025-06-01 17:21:42', '2025-06-01 17:21:42'),
	(40, 28, 1, 6, 17, 0, 'in_review', 'not_yet', NULL, NULL, '2025-06-10 08:53:15', '2025-06-10 08:53:15');

-- Dumping structure for table backend.document_signatures
DROP TABLE IF EXISTS `document_signatures`;
CREATE TABLE IF NOT EXISTS `document_signatures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_flow_step_id` bigint unsigned NOT NULL,
  `document_version_id` bigint unsigned NOT NULL,
  `certificate_id` bigint unsigned NOT NULL,
  `signature` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `signed_at` timestamp NOT NULL,
  `is_valid` tinyint(1) NOT NULL DEFAULT '1',
  `verification_details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_signatures_document_version_id_foreign` (`document_version_id`),
  KEY `idx_flow_step_version` (`document_flow_step_id`,`document_version_id`),
  KEY `idx_signed_at` (`signed_at`),
  KEY `idx_certificate_id` (`certificate_id`),
  CONSTRAINT `document_signatures_certificate_id_foreign` FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`),
  CONSTRAINT `document_signatures_document_flow_step_id_foreign` FOREIGN KEY (`document_flow_step_id`) REFERENCES `document_flow_steps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_signatures_document_version_id_foreign` FOREIGN KEY (`document_version_id`) REFERENCES `document_versions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.document_signatures: ~0 rows (approximately)

-- Dumping structure for table backend.document_templates
DROP TABLE IF EXISTS `document_templates`;
CREATE TABLE IF NOT EXISTS `document_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `document_type_id` bigint unsigned NOT NULL,
  `downloaded` int NOT NULL DEFAULT '0',
  `liked` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive','pending','reject') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_templates_created_by_foreign` (`created_by`),
  KEY `document_templates_document_type_id_foreign` (`document_type_id`),
  CONSTRAINT `document_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `document_templates_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.document_templates: ~8 rows (approximately)
INSERT INTO `document_templates` (`id`, `name`, `description`, `file_path`, `created_by`, `document_type_id`, `downloaded`, `liked`, `status`, `created_at`, `updated_at`) VALUES
	(12, 'Lần này được chưa', 'Mô tả cho được đi nào', '32272dde-6467-46b0-b72b-ca5d19e6a169.pdf', 16, 4, 12, 0, 'active', '2025-06-09 16:40:47', '2025-06-10 04:18:20'),
	(13, 'Mẫu từ người dùng', 'Thử đăng mẫu ở người dùng', 'afe7c006-b131-4a36-9f10-2ca0445f69be.pdf', 20, 3, 0, 0, 'active', '2025-06-10 04:49:33', '2025-06-10 08:54:19'),
	(14, 'Mẫu từ phía người duyệt bài', 'Mô tả chi rứa', 'e996b6a6-831b-43a0-ae48-35c2c588916e.pdf', 19, 4, 0, 0, 'active', '2025-06-10 08:32:44', '2025-06-10 09:01:32'),
	(15, 'Thử mẫu có thông báo', 'Mô tả', '2fe5cff8-e5e1-42db-915d-9bb6e5fbbbdb.pdf', 20, 3, 0, 0, 'pending', '2025-06-10 08:45:45', '2025-06-10 08:45:46'),
	(16, 'Mẫu mới thông báo', 'Mô tả', '07af88eb-3b3d-49e7-b332-b7445da896a6.pdf', 20, 3, 0, 0, 'reject', '2025-06-10 08:48:55', '2025-06-10 09:10:27'),
	(17, 'Mẫu mới thông báo', 'Mô tả để thành công', '8d129531-31b8-4b29-af6b-f763835a1f28.pdf', 20, 5, 0, 0, 'pending', '2025-06-10 09:28:44', '2025-06-10 09:28:45'),
	(18, 'Thông báo cho admin', 'Mô tả', 'f3af3c99-824c-425d-b649-638c60e09cd2.pdf', 20, 4, 0, 0, 'pending', '2025-06-10 09:32:47', '2025-06-10 09:32:47'),
	(19, 'Lại nữa', 'hjkadfghllakẹq', '286bae6d-5bda-4c9d-902e-2f4b85eac3a9.pdf', 20, 7, 0, 0, 'pending', '2025-06-10 09:34:53', '2025-06-10 09:34:54');

-- Dumping structure for table backend.document_types
DROP TABLE IF EXISTS `document_types`;
CREATE TABLE IF NOT EXISTS `document_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.document_types: ~8 rows (approximately)
INSERT INTO `document_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Phê duyệt kinh phí', 'Yêu cầu phê duyệt kinh phí tổ chức', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(2, 'Đơn bổ nhiệm', 'Đơn bổ nhiệm vị trí nào đó', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(3, 'Văn bản thường 1', 'Văn bản thường 1', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(4, 'Văn bản thường 2', 'Văn bản thường 2', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(5, 'Văn bản thường 3', 'Văn bản thường 3', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(6, 'Văn bản thường 4', 'Văn bản thường 4', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(7, 'Văn bản thường 5', 'Văn bản thường 5', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(8, 'Văn bản thường 6', 'Văn bản thường 6', '2025-05-26 16:45:29', '2025-05-26 16:45:29');

-- Dumping structure for table backend.document_versions
DROP TABLE IF EXISTS `document_versions`;
CREATE TABLE IF NOT EXISTS `document_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned NOT NULL,
  `version` int NOT NULL,
  `document_data` json NOT NULL COMMENT 'Save informations about document of each version',
  `status` enum('draft','in_review','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_versions_document_id_version_unique` (`document_id`,`version`),
  KEY `document_versions_status` (`status`) USING BTREE,
  CONSTRAINT `document_versions_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.document_versions: ~12 rows (approximately)
INSERT INTO `document_versions` (`id`, `document_id`, `version`, `document_data`, `status`, `created_at`) VALUES
	(1, 1, 1, '{"id": 1, "title": "Văn bản đầu tiên", "status": "in_review", "file_path": "f19e9c22-d7f6-45ea-b8b5-29eadb166d17.pdf", "is_public": true, "created_at": "17:56:27 26/05/2025", "created_by": 20, "updated_at": "17:56:33 26/05/2025", "description": "Mong là không có sai sót gì cả", "document_flow_id": 5, "document_type_id": 2}', 'in_review', '2025-05-26 17:56:27'),
	(2, 2, 1, '{"id": 2, "title": "Người phê duyệt gửi đi", "status": "in_review", "file_path": "bdc5df2b-2a54-4c12-af88-b8abda324bd9.pdf", "is_public": true, "created_at": "18:21:54 26/05/2025", "created_by": 19, "updated_at": "18:21:57 26/05/2025", "description": "Thử nghiệm văn bản của người có quyền phê duyệt gửi đi", "document_flow_id": 6, "document_type_id": 2}', 'in_review', '2025-05-26 18:21:54'),
	(3, 3, 1, '{"id": 3, "title": "Test", "status": "in_review", "file_path": "5876b7b3-78c5-4638-b1f1-15783009e963.pdf", "is_public": true, "created_at": "02:21:40 27/05/2025", "created_by": 20, "updated_at": "02:21:42 27/05/2025", "description": "Giờ gửi cho Thúy đầu tiên", "document_flow_id": 7, "document_type_id": 3}', 'rejected', '2025-05-27 02:21:40'),
	(4, 4, 1, '{"id": 4, "title": "Test thêm nháp", "status": "draft", "file_path": "db8dcbe3-f622-421e-abc9-bae90b920404.pdf", "is_public": true, "created_at": "02:23:02 27/05/2025", "created_by": 20, "updated_at": "02:23:04 27/05/2025", "description": "Mong là không có sai sót gì", "document_flow_id": 8, "document_type_id": 4}', 'draft', '2025-05-27 02:23:02'),
	(5, 5, 1, '{"id": 5, "title": "Thêm để test", "status": "in_review", "file_path": "db639109-df44-4ee2-8df1-3543f0f682fb.pdf", "is_public": true, "created_at": "08:09:47 27/05/2025", "created_by": 20, "updated_at": "08:09:55 27/05/2025", "description": "Thêm văn bản có nhiều người đồng cấp", "document_flow_id": 9, "document_type_id": 5}', 'in_review', '2025-05-27 08:09:47'),
	(6, 6, 1, '{"id": 6, "title": "Tạo để test", "status": "in_review", "file_path": "cc25d34e-3a5d-46a9-8eb6-601231ce917c.pdf", "is_public": true, "created_at": "14:44:59 27/05/2025", "created_by": 20, "updated_at": "14:45:02 27/05/2025", "description": "Mô tả gì đây, đói quá", "document_flow_id": 10, "document_type_id": 7}', 'approved', '2025-05-27 14:44:59'),
	(7, 7, 1, '{"id": 7, "title": "Tạo mới để test", "status": "in_review", "file_path": "8d3fbf72-7931-4da3-ba8b-d35e5855c017.pdf", "is_public": true, "created_at": "09:00:11 28/05/2025", "created_by": 20, "updated_at": "09:00:17 28/05/2025", "description": "Mô tả vài câu", "document_flow_id": 11, "document_type_id": 3}', 'approved', '2025-05-28 09:00:11'),
	(20, 20, 1, '{"id": 20, "title": "Test chức năng", "status": "in_review", "file_path": "bb255400-497e-4fce-92c1-43ed3bb4015c.pdf", "is_public": true, "created_at": "16:16:54 28/05/2025", "created_by": 19, "updated_at": "16:17:01 28/05/2025", "description": "Mô tả gì được, bugs lắm vãi", "document_flow_id": 24, "document_type_id": 5}', 'approved', '2025-05-28 16:16:54'),
	(21, 21, 1, '{"id": 21, "title": "Như anh mơ", "status": "in_review", "file_path": "98f2839b-23d7-456c-b875-e4ac0afe5536.pdf", "is_public": true, "created_at": "15:14:42 30/05/2025", "created_by": 20, "updated_at": "15:14:47 30/05/2025", "description": "Không như anh mơ ngày đó, thật khó gặp nhau giữa góc phố nhỏ này\\nEm ghé nơi đây với trái tim này nhớ, sâu trong lòng anh có đôi lời tỏ bày\\nEm có nghe lời thì thầm mùa thu, lại nhắc chúng ta những chiều hôm đó\\nDụi bàn tay anh vu vơ tờ giấy gấp, nháy mắt trao em nụ hôm gió", "document_flow_id": 25, "document_type_id": 4}', 'in_review', '2025-05-30 15:14:42'),
	(22, 22, 1, '{"id": 22, "title": "Đăng ký văn bản", "status": "in_review", "file_path": "1e30857c-1ac5-4a41-bde9-88fc29243258.pdf", "is_public": true, "created_at": "07:29:55 31/05/2025", "created_by": 20, "updated_at": "07:30:14 31/05/2025", "description": "Mô tả", "document_flow_id": 26, "document_type_id": 4}', 'in_review', '2025-05-31 07:29:55'),
	(23, 23, 1, '{"id": 23, "title": "Thử thông báo", "status": "in_review", "file_path": "9b8ccbb6-e793-4cce-93b8-382c7e37a949.pdf", "is_public": true, "created_at": "17:21:42 01/06/2025", "created_by": 20, "updated_at": "17:21:47 01/06/2025", "description": "Mô tả gì vậy", "document_flow_id": 27, "document_type_id": 4}', 'in_review', '2025-06-01 17:21:42'),
	(24, 24, 1, '{"id": 24, "title": "Test lại thông báo", "status": "in_review", "file_path": "3dd8b616-e986-4b14-a5d0-43ed3cd20aab.pdf", "is_public": true, "created_at": "08:53:15 10/06/2025", "created_by": 20, "updated_at": "08:53:18 10/06/2025", "description": "Mô tả dự án", "document_flow_id": 28, "document_type_id": 3}', 'in_review', '2025-06-10 08:53:15');

-- Dumping structure for table backend.failed_jobs
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

-- Dumping data for table backend.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table backend.jobs
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

-- Dumping data for table backend.jobs: ~0 rows (approximately)

-- Dumping structure for table backend.job_batches
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

-- Dumping data for table backend.job_batches: ~0 rows (approximately)

-- Dumping structure for table backend.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.migrations: ~32 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '00000_create_roles_table', 1),
	(2, '00005_create_departments_table', 1),
	(3, '00010_create_roll_at_departments_table', 1),
	(4, '00015_create_notification_categories_table', 1),
	(5, '00020_create_digital_signatures_table', 1),
	(6, '00025_create_document_types_table', 1),
	(7, '00030_create_users_table', 1),
	(8, '00035_create_document_flows_table', 1),
	(9, '00040_create_document_flow_steps_table', 1),
	(10, '00045_create_admins_table', 1),
	(11, '00050_create_creators_table', 1),
	(12, '00055_create_approvers_table', 1),
	(13, '00060_create_notifications_table', 1),
	(14, '00065_create_document_templates_table', 1),
	(15, '00070_create_user_bans_table', 1),
	(16, '00075_create_approval_permissions_table', 1),
	(17, '00080_create_documents_table', 1),
	(18, '00085_create_document_versions_table', 1),
	(19, '00090_create_template_users_table', 1),
	(20, '00095_create_document_comments_table', 1),
	(21, '00100_create_document_signatures_table', 1),
	(22, '10000_create_cache_table', 1),
	(23, '10001_create_jobs_table', 1),
	(24, '10002_add_two_factor_columns_to_users_table', 1),
	(25, '10003_create_personal_access_tokens_table', 1),
	(26, '2025_05_20_142729_create_user_access_logs_table', 1),
	(27, '2025_05_23_235212_add_process_column_document_flows_table', 1),
	(28, '00040_create_admins_table', 1),
	(29, '2025_06_05_090315_create_ca_certificates_table', 2),
	(30, '2025_06_05_090325_create_certificates_table', 3),
	(31, '10005_create_document_signatures_table', 4),
	(32, '00045_create_creators_table', 1),
	(33, '00050_create_approvers_table', 1),
	(34, '00055_create_document_flow_steps_table', 1);

-- Dumping structure for table backend.notifications
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `notification_category_id` bigint unsigned NOT NULL,
  `from_user_id` bigint unsigned DEFAULT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notification_category_id_foreign` (`notification_category_id`),
  KEY `notifications_from_user_id_foreign` (`from_user_id`),
  KEY `notifications_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `notifications_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `notifications_notification_category_id_foreign` FOREIGN KEY (`notification_category_id`) REFERENCES `notification_categories` (`id`),
  CONSTRAINT `notifications_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.notifications: ~100 rows (approximately)
INSERT INTO `notifications` (`id`, `notification_category_id`, `from_user_id`, `receiver_id`, `title`, `content`, `data`, `is_read`, `created_at`) VALUES
	(1, 2, 18, 16, 'Đăng ký tài khoản', 'Tạo tài khoản mới trên hệ thống.', NULL, 0, '2025-05-26 17:39:20'),
	(2, 2, 18, 17, 'Đăng ký tài khoản', 'Tạo tài khoản mới trên hệ thống.', NULL, 0, '2025-05-26 17:39:22'),
	(3, 2, 18, 16, 'Xác thực tài khoản', 'Xác thực tài khoản thành công.', NULL, 0, '2025-05-26 17:39:29'),
	(4, 2, 18, 17, 'Xác thực tài khoản', 'Xác thực tài khoản thành công.', NULL, 0, '2025-05-26 17:39:30'),
	(5, 2, 19, 16, 'Đăng ký tài khoản', 'Tạo tài khoản mới trên hệ thống.', NULL, 0, '2025-05-26 17:43:15'),
	(6, 2, 19, 17, 'Đăng ký tài khoản', 'Tạo tài khoản mới trên hệ thống.', NULL, 0, '2025-05-26 17:43:16'),
	(7, 2, 20, 16, 'Đăng ký tài khoản', 'Tạo tài khoản mới trên hệ thống.', NULL, 0, '2025-05-26 17:54:18'),
	(8, 2, 20, 17, 'Đăng ký tài khoản', 'Tạo tài khoản mới trên hệ thống.', NULL, 0, '2025-05-26 17:54:19'),
	(9, 2, 20, 16, 'Xác thực tài khoản', 'Xác thực tài khoản thành công.', NULL, 0, '2025-05-26 17:54:37'),
	(10, 2, 20, 17, 'Xác thực tài khoản', 'Xác thực tài khoản thành công.', NULL, 0, '2025-05-26 17:54:37'),
	(11, 2, 20, 16, 'Phê duyệt văn bản', 'Tạo một luồng phê duyệt cho văn bản \'Văn bản đầu tiên\'', NULL, 0, '2025-05-26 17:56:27'),
	(12, 2, 20, 17, 'Phê duyệt văn bản', 'Tạo một luồng phê duyệt cho văn bản \'Văn bản đầu tiên\'', NULL, 0, '2025-05-26 17:56:28'),
	(13, 3, 20, 14, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Văn bản đầu tiên\' do Hải Nam tạo', NULL, 0, '2025-05-26 17:56:29'),
	(14, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Thêm bạn vào luồng phê duyệt cho văn bản \'Văn bản đầu tiên\'', NULL, 0, '2025-05-26 17:56:30'),
	(15, 2, 19, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Người phê duyệt gửi đi\'', NULL, 0, '2025-05-26 18:21:54'),
	(16, 2, 19, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Người phê duyệt gửi đi\'', NULL, 0, '2025-05-26 18:21:55'),
	(17, 3, 19, 6, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Người phê duyệt gửi đi\' do Ngọc Thúy tạo', NULL, 0, '2025-05-26 18:21:56'),
	(18, 3, 19, 18, 'Thêm vào luồng phê duyệt văn bản', 'Thêm bạn vào luồng phê duyệt cho văn bản \'Người phê duyệt gửi đi\'', NULL, 0, '2025-05-26 18:21:56'),
	(19, 2, 20, 16, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Văn bản đầu tiên\' của Hải Nam', NULL, 0, '2025-05-26 19:11:21'),
	(20, 2, 20, 17, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Văn bản đầu tiên\' của Hải Nam', NULL, 0, '2025-05-26 19:11:22'),
	(21, 2, 20, 14, 'Nhận xét văn bản', 'Có nhận xét mới về văn bản \'Văn bản đầu tiên\' trong luồng phê duyệt', NULL, 0, '2025-05-26 19:11:23'),
	(22, 2, 20, 19, 'Nhận xét văn bản', 'Có nhận xét mới về văn bản \'Văn bản đầu tiên\' trong luồng phê duyệt', NULL, 0, '2025-05-26 19:11:23'),
	(23, 2, 19, 16, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Văn bản đầu tiên\' của Hải Nam', NULL, 0, '2025-05-26 19:14:13'),
	(24, 2, 19, 17, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Văn bản đầu tiên\' của Hải Nam', NULL, 0, '2025-05-26 19:14:14'),
	(25, 2, 19, 14, 'Nhận xét văn bản', 'Có nhận xét mới về văn bản \'Văn bản đầu tiên\' trong luồng phê duyệt', NULL, 0, '2025-05-26 19:14:14'),
	(26, 2, NULL, 20, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Văn bản đầu tiên\' của bạn', NULL, 0, '2025-05-26 19:14:15'),
	(27, 2, 20, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Test\'', NULL, 0, '2025-05-27 02:21:40'),
	(28, 2, 20, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Test\'', NULL, 0, '2025-05-27 02:21:40'),
	(29, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Test\' do Hải Nam tạo', NULL, 0, '2025-05-27 02:21:41'),
	(30, 1, 20, 16, 'Lưu nháp văn bản', 'Lưu bản nháp văn bản \'Test thêm nháp\'', NULL, 0, '2025-05-27 02:23:02'),
	(31, 1, 20, 17, 'Lưu nháp văn bản', 'Lưu bản nháp văn bản \'Test thêm nháp\'', NULL, 0, '2025-05-27 02:23:03'),
	(32, 2, 19, 16, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Test\' của Hải Nam', NULL, 0, '2025-05-27 02:50:40'),
	(33, 2, 19, 17, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Test\' của Hải Nam', NULL, 0, '2025-05-27 02:50:41'),
	(34, 2, NULL, 20, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Test\' của bạn', NULL, 0, '2025-05-27 02:50:41'),
	(35, 2, 19, 16, 'Từ chối văn bản', 'Từ chối phê duyệt cho văn bản \'Test\'', NULL, 0, '2025-05-27 03:30:00'),
	(36, 2, 19, 17, 'Từ chối văn bản', 'Từ chối phê duyệt cho văn bản \'Test\'', NULL, 0, '2025-05-27 03:30:01'),
	(37, 2, 19, 20, 'Từ chối văn bản', 'Từ chối phê duyệt cho văn bản \'Test\' của bạn.', NULL, 0, '2025-05-27 03:30:02'),
	(38, 2, 20, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Thêm để test\'', NULL, 0, '2025-05-27 08:09:47'),
	(39, 2, 20, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Thêm để test\'', NULL, 0, '2025-05-27 08:09:49'),
	(40, 3, 20, 6, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Thêm để test\' do Hải Nam tạo', NULL, 0, '2025-05-27 08:09:50'),
	(41, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Thêm để test\' do Hải Nam tạo', NULL, 0, '2025-05-27 08:09:51'),
	(42, 3, 20, 18, 'Thêm vào luồng phê duyệt văn bản', 'Thêm bạn vào luồng phê duyệt cho văn bản \'Thêm để test\'', NULL, 0, '2025-05-27 08:09:51'),
	(43, 2, 20, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Tạo để test\'', NULL, 0, '2025-05-27 14:44:59'),
	(44, 2, 20, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Tạo để test\'', NULL, 0, '2025-05-27 14:45:00'),
	(45, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Tạo để test\' do Hải Nam tạo', NULL, 0, '2025-05-27 14:45:00'),
	(46, 2, 19, 20, 'Văn bản đã được phê duyệt', 'Văn bản \'Tạo để test\' của bạn đã được phê duyệt hoàn tất.', NULL, 0, '2025-05-27 15:09:55'),
	(47, 2, 19, 16, 'Hoàn tất phê duyệt', 'Văn bản \'Tạo để test\' đã được phê duyệt hoàn tất', NULL, 0, '2025-05-27 15:09:56'),
	(48, 2, 19, 17, 'Hoàn tất phê duyệt', 'Văn bản \'Tạo để test\' đã được phê duyệt hoàn tất', NULL, 0, '2025-05-27 15:09:57'),
	(49, 2, 20, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Tạo mới để test\'', NULL, 0, '2025-05-28 09:00:11'),
	(50, 2, 20, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Tạo mới để test\'', NULL, 0, '2025-05-28 09:00:13'),
	(51, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Tạo mới để test\' do Hải Nam tạo', NULL, 0, '2025-05-28 09:00:13'),
	(52, 3, 20, 18, 'Thêm vào luồng phê duyệt văn bản', 'Thêm bạn vào luồng phê duyệt cho văn bản \'Tạo mới để test\'', NULL, 0, '2025-05-28 09:00:14'),
	(53, 2, 19, 18, 'Văn bản cần phê duyệt', 'Bạn có văn bản \'Tạo mới để test\' cần phê duyệt.', NULL, 0, '2025-05-28 09:01:48'),
	(54, 2, 19, 20, 'Văn bản được phê duyệt', 'Văn bản \'Tạo mới để test\' đã được phê duyệt bởi Ngọc Thúy.', NULL, 0, '2025-05-28 09:01:49'),
	(55, 2, 19, 16, 'Đồng ý phê duyệt', 'Đồng ý phê duyệt cho văn bản \'Tạo mới để test\'.', NULL, 0, '2025-05-28 09:01:50'),
	(56, 2, 19, 17, 'Đồng ý phê duyệt', 'Đồng ý phê duyệt cho văn bản \'Tạo mới để test\'.', NULL, 0, '2025-05-28 09:01:50'),
	(57, 2, 18, 16, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Tạo mới để test\' của Hải Nam', NULL, 0, '2025-05-28 09:03:43'),
	(58, 2, 18, 17, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Tạo mới để test\' của Hải Nam', NULL, 0, '2025-05-28 09:03:48'),
	(59, 2, 18, 19, 'Nhận xét văn bản', 'Có nhận xét mới về văn bản \'Tạo mới để test\' trong luồng phê duyệt', NULL, 0, '2025-05-28 09:03:49'),
	(60, 2, NULL, 20, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Tạo mới để test\' của bạn', NULL, 0, '2025-05-28 09:03:50'),
	(61, 2, 18, 16, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Tạo mới để test\' của Hải Nam', NULL, 0, '2025-05-28 09:08:28'),
	(62, 2, 18, 17, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Tạo mới để test\' của Hải Nam', NULL, 0, '2025-05-28 09:08:28'),
	(63, 2, 18, 19, 'Nhận xét văn bản', 'Có nhận xét mới về văn bản \'Tạo mới để test\' trong luồng phê duyệt', NULL, 0, '2025-05-28 09:08:29'),
	(64, 2, NULL, 20, 'Nhận xét văn bản', 'Nhận xét về văn bản \'Tạo mới để test\' của bạn', NULL, 0, '2025-05-28 09:08:30'),
	(65, 2, 18, 20, 'Văn bản đã được phê duyệt', 'Văn bản \'Tạo mới để test\' của bạn đã được phê duyệt hoàn tất.', NULL, 1, '2025-05-28 09:09:26'),
	(66, 2, 18, 16, 'Hoàn tất phê duyệt', 'Văn bản \'Tạo mới để test\' đã được phê duyệt hoàn tất', NULL, 0, '2025-05-28 09:09:27'),
	(67, 2, 18, 17, 'Hoàn tất phê duyệt', 'Văn bản \'Tạo mới để test\' đã được phê duyệt hoàn tất', NULL, 0, '2025-05-28 09:09:28'),
	(70, 2, 19, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Test chức năng\'', NULL, 0, '2025-05-28 16:16:54'),
	(71, 2, 19, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Test chức năng\'', NULL, 0, '2025-05-28 16:16:56'),
	(72, 3, 19, 18, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Test chức năng\' do Ngọc Thúy tạo', NULL, 0, '2025-05-28 16:16:57'),
	(73, 2, 18, 19, 'Văn bản đã được phê duyệt', 'Văn bản \'Test chức năng\' của bạn đã được phê duyệt hoàn tất.', NULL, 1, '2025-05-28 16:17:45'),
	(74, 2, 18, 16, 'Hoàn tất phê duyệt', 'Văn bản \'Test chức năng\' đã được phê duyệt hoàn tất', NULL, 0, '2025-05-28 16:17:46'),
	(75, 2, 18, 17, 'Hoàn tất phê duyệt', 'Văn bản \'Test chức năng\' đã được phê duyệt hoàn tất', NULL, 0, '2025-05-28 16:17:47'),
	(76, 2, 20, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Như anh mơ\'', NULL, 0, '2025-05-30 15:14:42'),
	(77, 2, 20, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Như anh mơ\'', NULL, 0, '2025-05-30 15:14:44'),
	(78, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Như anh mơ\' do Hải Nam tạo', NULL, 0, '2025-05-30 15:14:44'),
	(79, 2, 20, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Đăng ký văn bản\'', NULL, 0, '2025-05-31 07:29:55'),
	(80, 2, 20, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Đăng ký văn bản\'', NULL, 0, '2025-05-31 07:30:01'),
	(81, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Đăng ký văn bản\' do Hải Nam tạo', NULL, 0, '2025-05-31 07:30:02'),
	(82, 3, 20, 18, 'Thêm vào luồng phê duyệt văn bản', 'Thêm bạn vào luồng phê duyệt cho văn bản \'Đăng ký văn bản\'', NULL, 0, '2025-05-31 07:30:04'),
	(83, 2, 19, 18, 'Văn bản cần phê duyệt', 'Bạn có văn bản \'Đăng ký văn bản\' cần phê duyệt.', NULL, 0, '2025-05-31 07:31:05'),
	(84, 2, 19, 20, 'Văn bản được phê duyệt', 'Văn bản \'Đăng ký văn bản\' đã được phê duyệt bởi Ngọc Thúy.', NULL, 0, '2025-05-31 07:31:07'),
	(85, 2, 19, 16, 'Đồng ý phê duyệt', 'Đồng ý phê duyệt cho văn bản \'Đăng ký văn bản\'.', NULL, 0, '2025-05-31 07:31:09'),
	(86, 2, 19, 17, 'Đồng ý phê duyệt', 'Đồng ý phê duyệt cho văn bản \'Đăng ký văn bản\'.', NULL, 0, '2025-05-31 07:31:11'),
	(87, 2, 20, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Thử thông báo\'', NULL, 0, '2025-06-01 17:21:42'),
	(88, 2, 20, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Thử thông báo\'', NULL, 0, '2025-06-01 17:21:44'),
	(89, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Thử thông báo\' do Hải Nam tạo', NULL, 0, '2025-06-01 17:21:44'),
	(90, 1, 20, 16, 'Mẫu văn bản mới', 'Đề xuất mẫu văn bản "Mẫu mới thông báo".', NULL, 0, '2025-06-10 08:48:56'),
	(91, 1, 20, 17, 'Mẫu văn bản mới', 'Đề xuất mẫu văn bản "Mẫu mới thông báo".', NULL, 0, '2025-06-10 08:48:58'),
	(92, 2, 20, 16, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Test lại thông báo\'', NULL, 0, '2025-06-10 08:53:15'),
	(93, 2, 20, 17, 'Tạo văn bản phê duyệt', 'Tạo một luồng phê duyệt cho văn bản \'Test lại thông báo\'', NULL, 0, '2025-06-10 08:53:16'),
	(94, 3, 20, 19, 'Thêm vào luồng phê duyệt văn bản', 'Bạn là người phê duyệt đầu tiên cho văn bản \'Test lại thông báo\' do Hải Nam tạo', NULL, 0, '2025-06-10 08:53:16'),
	(95, 1, 16, 20, 'Mẫu văn bản "Mẫu từ người dùng" của bạn đã được chấp thuận', 'Mẫu văn bản "Mẫu từ người dùng" đã được chấp thuận và có thể sử dụng.', NULL, 0, '2025-06-10 08:54:19'),
	(96, 1, 16, 19, 'Mẫu văn bản "Mẫu từ phía người duyệt bài" của bạn đã được chấp thuận', 'Mẫu văn bản "Mẫu từ phía người duyệt bài" đã được chấp thuận và có thể sử dụng.', NULL, 0, '2025-06-10 09:01:32'),
	(97, 1, 16, 20, 'Mẫu văn bản "Mẫu mới thông báo" của bạn đã được chấp thuận', 'Mẫu văn bản "Mẫu mới thông báo" đã được chấp thuận và có thể sử dụng.', NULL, 0, '2025-06-10 09:10:28'),
	(98, 1, 20, 16, 'Mẫu văn bản mới', 'Đề xuất mẫu văn bản "Mẫu mới thông báo".', NULL, 0, '2025-06-10 09:28:46'),
	(99, 1, 20, 17, 'Mẫu văn bản mới', 'Đề xuất mẫu văn bản "Mẫu mới thông báo".', NULL, 0, '2025-06-10 09:28:47'),
	(100, 1, 20, 16, 'Mẫu văn bản mới', 'Đề xuất mẫu văn bản "Thông báo cho admin".', NULL, 0, '2025-06-10 09:32:48'),
	(101, 1, 20, 17, 'Mẫu văn bản mới', 'Đề xuất mẫu văn bản "Thông báo cho admin".', NULL, 0, '2025-06-10 09:32:49'),
	(102, 1, 20, 16, 'Mẫu văn bản mới', 'Đề xuất mẫu văn bản "Lại nữa".', NULL, 0, '2025-06-10 09:34:55');

-- Dumping structure for table backend.notification_categories
DROP TABLE IF EXISTS `notification_categories`;
CREATE TABLE IF NOT EXISTS `notification_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.notification_categories: ~3 rows (approximately)
INSERT INTO `notification_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Văn bản', 'Thông báo cho các hoạt động liên quan đến văn bản như: Thêm mới, lưu nháp, ...', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(2, 'Người dùng', 'Thông báo cho các hoạt động liên quan đến người dùng như: Đăng ký mới, Đăng nhập, ...', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(3, 'Hệ thống', 'Thông báo chung của hệ thống đến tất cả người dùng', '2025-05-26 16:45:29', '2025-05-26 16:45:29');

-- Dumping structure for table backend.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.password_reset_tokens: ~0 rows (approximately)
INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
	('namrm7@gmail.com', '$2y$12$mBTOvOpz1hX9JVvItr8YTeBNAsiWJMWe5z6eqg4Ao/LNXOx/4rRAe', '2025-06-08 15:42:34');

-- Dumping structure for table backend.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table backend.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` enum('admin','approver','creator') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu thông tin về các chức vụ có thể có trong hệ thống';

-- Dumping data for table backend.roles: ~3 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'Quản trị viên hệ thống', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(2, 'creator', 'Người dùng chỉ có quyền đề xuất văn bản để phê duyệt', '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(3, 'approver', 'Người dùng có cả quyền phê duyệt và đề xuất văn bản để phê duyệt', '2025-05-26 16:45:29', '2025-05-26 16:45:29');

-- Dumping structure for table backend.roll_at_departments
DROP TABLE IF EXISTS `roll_at_departments`;
CREATE TABLE IF NOT EXISTS `roll_at_departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `level` int NOT NULL COMMENT 'Lưu trình tự của các chức vụ trong đơn vị, từ 1 đến n, 1 là chức vụ cao nhất',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roll_at_departments_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.roll_at_departments: ~17 rows (approximately)
INSERT INTO `roll_at_departments` (`id`, `name`, `description`, `level`, `created_at`, `updated_at`) VALUES
	(1, 'Trưởng phòng', 'Người đứng đầu của phòng', 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(2, 'Phó phòng', 'Người đứng thứ hai của phòng', 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(3, 'Bí thư', 'Người đứng đầu của đơn vị', 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(4, 'Phó Bí thư', 'Người đứng thứ hai của đơn vị', 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(5, 'Trưởng ban', 'Người đứng đầu của ban', 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(6, 'Phó ban', 'Người đứng thứ hai của ban', 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(7, 'Chủ nhiệm', 'Người đứng đầu của CLB', 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(8, 'Phó Chủ nhiệm', 'Người đứng thứ hai của CLB', 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(9, 'Trưởng khoa', 'Người đứng đầu của khoa', 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(10, 'Phó Trưởng khoa', 'Người đứng thứ hai của khoa', 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(11, 'Giám đốc', 'Người đứng đầu của Trung tâm', 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(12, 'Phó Giám đốc', 'Người đứng thứ hai của Trung tâm', 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(13, 'LCH Trưởng', 'Người đứng đầu của LCH', 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(14, 'LCH Phó', 'Người đứng thứ hai của LCH', 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(15, 'Chủ tịch', 'Người đứng đầu của hội', 1, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(16, 'Phó Chủ tịch', 'Người đứng thứ hai của hội', 3, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(17, 'Sinh viên', 'Sinh viên thuộc', 10, '2025-05-26 16:45:29', '2025-05-26 16:45:29');

-- Dumping structure for table backend.sessions
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

-- Dumping data for table backend.sessions: ~0 rows (approximately)

-- Dumping structure for table backend.template_users
DROP TABLE IF EXISTS `template_users`;
CREATE TABLE IF NOT EXISTS `template_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `template_id` bigint unsigned NOT NULL,
  `is_liked` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `template_users_user_id_template_id_unique` (`user_id`,`template_id`),
  KEY `template_users_template_id_foreign` (`template_id`),
  CONSTRAINT `template_users_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `document_templates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `template_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.template_users: ~0 rows (approximately)

-- Dumping structure for table backend.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint unsigned NOT NULL,
  `signature_id` bigint unsigned DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('inactive','pending','active','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive' COMMENT 'Trạng thái tài khoản, inactive: chưa kích hoạt, pending: đang chờ duyệt, active: đã kích hoạt, banned: bị cấm',
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_token_expiry` timestamp NULL DEFAULT NULL,
  `verification_resent_count` int NOT NULL DEFAULT '0',
  `last_verification_resent_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  KEY `users_signature_id_foreign` (`signature_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `users_signature_id_foreign` FOREIGN KEY (`signature_id`) REFERENCES `digital_signatures` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu thông tin người dùng';

-- Dumping data for table backend.users: ~20 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified`, `email_verified_at`, `phone`, `description`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `role_id`, `signature_id`, `avatar`, `sex`, `status`, `verification_token`, `verification_token_expiry`, `verification_resent_count`, `last_verification_resent_at`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Đặng Hương Giang', 'giangdh@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'pending', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(2, 'Nguyễn Đình Trinh', 'trinhnd@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'inactive', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(3, 'Nguyễn Thu Nga', 'ngant@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(4, 'Đặng Ngọc Duyên', 'duyendn@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(5, 'Bùi Anh Tú', 'tuba@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'inactive', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(6, 'Nguyễn Minh Tuấn', 'tuannm@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(7, 'Phan Thị Trang Nhung', 'nhungptt@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(8, 'Chu Ngọc Thúy', 'thuycn@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'inactive', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(9, 'Nguyễn Hoàng Hà', 'hanh@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'pending', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(10, 'Nguyễn Văn Hưng', 'hungnv@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'pending', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(11, 'Đỗ Lân', 'land@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(12, 'Tạ Quang Chiểu', 'chieutq@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'inactive', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(13, 'Võ Tá Hoàng', 'hoangvt@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(14, 'Trần Tuấn Minh', 'minhtt@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(15, 'Trần Hà Trang', 'trangth@tlu.edu.vn', 0, NULL, NULL, NULL, 'test', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'pending', NULL, NULL, 0, NULL, NULL, '2025-05-26 16:45:29', '2025-05-26 16:45:29'),
	(16, 'Vũ Ngọc Sơn', 'sonvn@tlu.edu.vn', 1, '2025-05-26 16:45:51', NULL, NULL, '$2y$12$JWiU.147RIVZfHA65ktmc.E4dQvKIbt.uXGOn6buD144mM2kzfGcm', NULL, NULL, NULL, 1, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, NULL, NULL),
	(17, 'Trần Minh 2', 'minh@tlu.edu.vn', 1, '2025-05-26 16:45:52', NULL, NULL, '$2y$12$w8yuHRU0bRukx9rUUlv.Su7DXrFuQyQ7zbzrnbciWX.5SfDNZCQ9u', NULL, NULL, NULL, 1, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, NULL, NULL),
	(18, 'Diệp Hoàng Trúc Mai', 'longrm31@gmail.com', 1, '2025-05-26 17:39:29', NULL, NULL, '$2y$12$SfjF8skTnEejsKNIWqwI3uBYFfew.KJ3bN6zcqwHCI3zBR58It8E2', NULL, NULL, NULL, 3, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, NULL, '2025-05-26 17:39:12', '2025-05-26 17:50:13'),
	(19, 'Ngọc Thúy', 'hotboylh2@gmail.com', 1, '2025-05-26 17:46:46', NULL, NULL, '$2y$12$lSbULjFVhRGLhNUcIg/avuJCydjnZW6vlwTVxE5.Js2uHO6yNH8ca', NULL, NULL, NULL, 3, NULL, '19.jpg', NULL, 'active', '', '2025-05-26 17:43:11', 0, NULL, NULL, '2025-05-26 17:43:11', '2025-05-26 17:50:00'),
	(20, 'Hải Nam', 'namrm7@gmail.com', 1, '2025-05-26 17:54:37', NULL, NULL, '$2y$12$Bfzw.ASXwrvMdLi4gtE15.Ghkug54AZ80mLynxke3esoqJ5JtgLla', NULL, NULL, NULL, 2, NULL, NULL, NULL, 'active', NULL, NULL, 0, NULL, 'mob6TA7WE4YJfqKUQAu41Vbvz81efT9DyuECWDxoHEly3CMOs1jawIR01OqN', '2025-05-26 17:54:15', '2025-06-08 10:53:34');

-- Dumping structure for table backend.user_access_logs
DROP TABLE IF EXISTS `user_access_logs`;
CREATE TABLE IF NOT EXISTS `user_access_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `access_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_authenticated` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_access_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `user_access_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.user_access_logs: ~64 rows (approximately)
INSERT INTO `user_access_logs` (`id`, `user_id`, `access_time`, `ip_address`, `is_authenticated`, `created_at`, `updated_at`) VALUES
	(1, 16, '2025-05-26 17:29:33', '127.0.0.1', 1, '2025-05-26 17:29:33', '2025-05-26 17:29:33'),
	(2, 19, '2025-05-26 17:50:21', '127.0.0.1', 1, '2025-05-26 17:50:21', '2025-05-26 17:50:21'),
	(3, 20, '2025-05-26 17:55:11', '127.0.0.1', 1, '2025-05-26 17:55:11', '2025-05-26 17:55:11'),
	(4, 19, '2025-05-27 02:09:59', '127.0.0.1', 1, '2025-05-27 02:09:59', '2025-05-27 02:09:59'),
	(5, 20, '2025-05-27 02:10:10', '127.0.0.1', 1, '2025-05-27 02:10:10', '2025-05-27 02:10:10'),
	(6, 16, '2025-05-27 02:20:39', '127.0.0.1', 1, '2025-05-27 02:20:39', '2025-05-27 02:20:39'),
	(7, 20, '2025-05-27 07:40:24', '127.0.0.1', 1, '2025-05-27 07:40:24', '2025-05-27 07:40:24'),
	(8, 16, '2025-05-27 07:40:46', '127.0.0.1', 1, '2025-05-27 07:40:46', '2025-05-27 07:40:46'),
	(9, 19, '2025-05-27 07:41:02', '127.0.0.1', 1, '2025-05-27 07:41:02', '2025-05-27 07:41:02'),
	(10, 18, '2025-05-27 10:42:11', '127.0.0.1', 1, '2025-05-27 10:42:11', '2025-05-27 10:42:11'),
	(11, 19, '2025-05-27 13:21:33', '127.0.0.1', 1, '2025-05-27 13:21:33', '2025-05-27 13:21:33'),
	(12, 16, '2025-05-27 14:09:43', '127.0.0.1', 1, '2025-05-27 14:09:43', '2025-05-27 14:09:43'),
	(13, 20, '2025-05-27 14:10:12', '127.0.0.1', 1, '2025-05-27 14:10:12', '2025-05-27 14:10:12'),
	(14, 18, '2025-05-27 14:10:15', '127.0.0.1', 1, '2025-05-27 14:10:15', '2025-05-27 14:10:15'),
	(15, 19, '2025-05-28 08:42:52', '127.0.0.1', 1, '2025-05-28 08:42:52', '2025-05-28 08:42:52'),
	(16, 20, '2025-05-28 08:43:03', '127.0.0.1', 1, '2025-05-28 08:43:03', '2025-05-28 08:43:03'),
	(17, 16, '2025-05-28 08:43:58', '127.0.0.1', 1, '2025-05-28 08:43:58', '2025-05-28 08:43:58'),
	(18, 18, '2025-05-28 08:57:26', '127.0.0.1', 1, '2025-05-28 08:57:26', '2025-05-28 08:57:26'),
	(19, 19, '2025-05-28 13:57:47', '127.0.0.1', 1, '2025-05-28 13:57:47', '2025-05-28 13:57:47'),
	(20, 16, '2025-05-28 13:57:59', '127.0.0.1', 1, '2025-05-28 13:57:59', '2025-05-28 13:57:59'),
	(21, 20, '2025-05-28 13:58:03', '127.0.0.1', 1, '2025-05-28 13:58:03', '2025-05-28 13:58:03'),
	(22, 18, '2025-05-28 13:58:05', '127.0.0.1', 1, '2025-05-28 13:58:05', '2025-05-28 13:58:05'),
	(23, 19, '2025-05-28 16:10:47', '127.0.0.1', 1, '2025-05-28 16:10:47', '2025-05-28 16:10:47'),
	(24, 18, '2025-05-28 16:11:05', '127.0.0.1', 1, '2025-05-28 16:11:05', '2025-05-28 16:11:05'),
	(25, 16, '2025-05-28 16:11:18', '127.0.0.1', 1, '2025-05-28 16:11:18', '2025-05-28 16:11:18'),
	(26, 20, '2025-05-28 23:49:54', '127.0.0.1', 1, '2025-05-28 23:49:54', '2025-05-28 23:49:54'),
	(27, 19, '2025-05-30 01:18:45', '127.0.0.1', 1, '2025-05-30 01:18:45', '2025-05-30 01:18:45'),
	(28, 20, '2025-05-30 01:24:13', '127.0.0.1', 1, '2025-05-30 01:24:13', '2025-05-30 01:24:13'),
	(29, 16, '2025-05-30 02:04:37', '127.0.0.1', 1, '2025-05-30 02:04:37', '2025-05-30 02:04:37'),
	(30, 19, '2025-05-30 13:53:23', '127.0.0.1', 1, '2025-05-30 13:53:23', '2025-05-30 13:53:23'),
	(31, 16, '2025-05-30 14:21:08', '127.0.0.1', 1, '2025-05-30 14:21:08', '2025-05-30 14:21:08'),
	(32, 20, '2025-05-30 15:12:02', '127.0.0.1', 1, '2025-05-30 15:12:02', '2025-05-30 15:12:02'),
	(33, 19, '2025-05-31 01:03:10', '127.0.0.1', 1, '2025-05-31 01:03:10', '2025-05-31 01:03:10'),
	(34, 20, '2025-05-31 02:42:21', '127.0.0.1', 1, '2025-05-31 02:42:21', '2025-05-31 02:42:21'),
	(35, 19, '2025-05-31 07:05:06', '127.0.0.1', 1, '2025-05-31 07:05:06', '2025-05-31 07:05:06'),
	(36, 16, '2025-05-31 07:05:31', '127.0.0.1', 1, '2025-05-31 07:05:31', '2025-05-31 07:05:31'),
	(37, 18, '2025-05-31 07:06:16', '127.0.0.1', 1, '2025-05-31 07:06:16', '2025-05-31 07:06:16'),
	(38, 19, '2025-06-01 06:40:50', '127.0.0.1', 1, '2025-06-01 06:40:50', '2025-06-01 06:40:50'),
	(39, 19, '2025-06-01 14:15:36', '127.0.0.1', 1, '2025-06-01 14:15:36', '2025-06-01 14:15:36'),
	(40, 20, '2025-06-01 17:21:06', '127.0.0.1', 1, '2025-06-01 17:21:06', '2025-06-01 17:21:06'),
	(41, 16, '2025-06-05 03:06:53', '127.0.0.1', 1, '2025-06-05 03:06:53', '2025-06-05 03:06:53'),
	(42, 16, '2025-06-06 02:25:08', '127.0.0.1', 1, '2025-06-06 02:25:08', '2025-06-06 02:25:08'),
	(43, 16, '2025-06-06 13:28:18', '127.0.0.1', 1, '2025-06-06 13:28:18', '2025-06-06 13:28:18'),
	(44, 16, '2025-06-06 14:16:19', '127.0.0.1', 1, '2025-06-06 14:16:19', '2025-06-06 14:16:19'),
	(45, 16, '2025-06-07 08:24:51', '127.0.0.1', 1, '2025-06-07 08:24:51', '2025-06-07 08:24:51'),
	(46, 19, '2025-06-07 14:26:01', '127.0.0.1', 1, '2025-06-07 14:26:01', '2025-06-07 14:26:01'),
	(47, 20, '2025-06-07 14:26:27', '127.0.0.1', 1, '2025-06-07 14:26:27', '2025-06-07 14:26:27'),
	(48, 19, '2025-06-08 09:04:41', '127.0.0.1', 1, '2025-06-08 09:04:41', '2025-06-08 09:04:41'),
	(49, 20, '2025-06-08 10:53:47', '127.0.0.1', 1, '2025-06-08 10:53:47', '2025-06-08 10:53:47'),
	(50, 20, '2025-06-08 16:05:48', '127.0.0.1', 1, '2025-06-08 16:05:48', '2025-06-08 16:05:48'),
	(51, 16, '2025-06-08 16:55:58', '127.0.0.1', 1, '2025-06-08 16:55:58', '2025-06-08 16:55:58'),
	(52, 16, '2025-06-09 01:11:59', '127.0.0.1', 1, '2025-06-09 01:11:59', '2025-06-09 01:11:59'),
	(53, 20, '2025-06-09 02:01:28', '127.0.0.1', 1, '2025-06-09 02:01:28', '2025-06-09 02:01:28'),
	(54, 20, '2025-06-09 07:49:39', '127.0.0.1', 1, '2025-06-09 07:49:39', '2025-06-09 07:49:39'),
	(55, 16, '2025-06-09 14:44:10', '127.0.0.1', 1, '2025-06-09 14:44:10', '2025-06-09 14:44:10'),
	(56, 20, '2025-06-09 16:58:50', '127.0.0.1', 1, '2025-06-09 16:58:50', '2025-06-09 16:58:50'),
	(57, 19, '2025-06-09 16:59:09', '127.0.0.1', 1, '2025-06-09 16:59:09', '2025-06-09 16:59:09'),
	(58, 19, '2025-06-09 16:59:16', '127.0.0.1', 1, '2025-06-09 16:59:16', '2025-06-09 16:59:16'),
	(59, 16, '2025-06-10 02:13:01', '127.0.0.1', 1, '2025-06-10 02:13:01', '2025-06-10 02:13:01'),
	(60, 19, '2025-06-10 04:24:10', '127.0.0.1', 1, '2025-06-10 04:24:10', '2025-06-10 04:24:10'),
	(61, 20, '2025-06-10 04:24:27', '127.0.0.1', 1, '2025-06-10 04:24:27', '2025-06-10 04:24:27'),
	(62, 20, '2025-06-10 04:24:46', '127.0.0.1', 1, '2025-06-10 04:24:46', '2025-06-10 04:24:46'),
	(63, 19, '2025-06-10 05:16:12', '127.0.0.1', 1, '2025-06-10 05:16:12', '2025-06-10 05:16:12'),
	(64, 16, '2025-06-10 14:25:20', '127.0.0.1', 1, '2025-06-10 14:25:20', '2025-06-10 14:25:20'),
	(65, 20, '2025-06-10 15:24:35', '127.0.0.1', 1, '2025-06-10 15:24:35', '2025-06-10 15:24:35');

-- Dumping structure for table backend.user_bans
DROP TABLE IF EXISTS `user_bans`;
CREATE TABLE IF NOT EXISTS `user_bans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `banned_by` bigint unsigned NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NOT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_bans_user_id_foreign` (`user_id`),
  KEY `user_bans_banned_by_foreign` (`banned_by`),
  CONSTRAINT `user_bans_banned_by_foreign` FOREIGN KEY (`banned_by`) REFERENCES `admins` (`id`),
  CONSTRAINT `user_bans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table backend.user_bans: ~0 rows (approximately)

SET foreign_key_checks = 1;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
