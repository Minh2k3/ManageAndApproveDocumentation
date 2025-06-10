-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: backend
-- ------------------------------------------------------
-- Server version	8.0.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admins_user_id_foreign` (`user_id`),
  CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,16,'2025-05-26 16:45:51','2025-05-26 16:45:51'),(2,17,'2025-05-26 16:45:52','2025-05-26 16:45:52');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approval_permissions`
--

DROP TABLE IF EXISTS `approval_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approval_permissions` (
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
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quyền ký văn bản theo chức vụ trong đơn vị và loại văn bản';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approval_permissions`
--

LOCK TABLES `approval_permissions` WRITE;
/*!40000 ALTER TABLE `approval_permissions` DISABLE KEYS */;
INSERT INTO `approval_permissions` VALUES (1,1,2,'2025-05-26 16:45:29',NULL),(2,1,1,'2025-05-26 16:45:29',NULL),(3,3,2,'2025-05-26 16:45:29',NULL),(4,3,1,'2025-05-26 16:45:29',NULL),(5,5,2,'2025-05-26 16:45:29',NULL),(6,5,1,'2025-05-26 16:45:29',NULL),(7,7,2,'2025-05-26 16:45:29',NULL),(8,7,1,'2025-05-26 16:45:29',NULL),(9,9,2,'2025-05-26 16:45:29',NULL),(10,9,1,'2025-05-26 16:45:29',NULL),(11,11,2,'2025-05-26 16:45:29',NULL),(12,11,1,'2025-05-26 16:45:29',NULL),(13,13,2,'2025-05-26 16:45:29',NULL),(14,13,1,'2025-05-26 16:45:29',NULL),(15,15,2,'2025-05-26 16:45:29',NULL),(16,15,1,'2025-05-26 16:45:29',NULL),(32,1,8,'2025-05-26 16:45:29',NULL),(33,1,7,'2025-05-26 16:45:29',NULL),(34,1,6,'2025-05-26 16:45:29',NULL),(35,1,5,'2025-05-26 16:45:29',NULL),(36,1,4,'2025-05-26 16:45:29',NULL),(37,1,3,'2025-05-26 16:45:29',NULL),(38,2,8,'2025-05-26 16:45:29',NULL),(39,2,7,'2025-05-26 16:45:29',NULL),(40,2,6,'2025-05-26 16:45:29',NULL),(41,2,5,'2025-05-26 16:45:29',NULL),(42,2,4,'2025-05-26 16:45:29',NULL),(43,2,3,'2025-05-26 16:45:29',NULL),(44,3,8,'2025-05-26 16:45:29',NULL),(45,3,7,'2025-05-26 16:45:29',NULL),(46,3,6,'2025-05-26 16:45:29',NULL),(47,3,5,'2025-05-26 16:45:29',NULL),(48,3,4,'2025-05-26 16:45:29',NULL),(49,3,3,'2025-05-26 16:45:29',NULL),(50,4,8,'2025-05-26 16:45:29',NULL),(51,4,7,'2025-05-26 16:45:29',NULL),(52,4,6,'2025-05-26 16:45:29',NULL),(53,4,5,'2025-05-26 16:45:29',NULL),(54,4,4,'2025-05-26 16:45:29',NULL),(55,4,3,'2025-05-26 16:45:29',NULL),(56,5,8,'2025-05-26 16:45:29',NULL),(57,5,7,'2025-05-26 16:45:29',NULL),(58,5,6,'2025-05-26 16:45:29',NULL),(59,5,5,'2025-05-26 16:45:29',NULL),(60,5,4,'2025-05-26 16:45:29',NULL),(61,5,3,'2025-05-26 16:45:29',NULL),(62,6,8,'2025-05-26 16:45:29',NULL),(63,6,7,'2025-05-26 16:45:29',NULL),(64,6,6,'2025-05-26 16:45:29',NULL),(65,6,5,'2025-05-26 16:45:29',NULL),(66,6,4,'2025-05-26 16:45:29',NULL),(67,6,3,'2025-05-26 16:45:29',NULL),(68,7,8,'2025-05-26 16:45:29',NULL),(69,7,7,'2025-05-26 16:45:29',NULL),(70,7,6,'2025-05-26 16:45:29',NULL),(71,7,5,'2025-05-26 16:45:29',NULL),(72,7,4,'2025-05-26 16:45:29',NULL),(73,7,3,'2025-05-26 16:45:29',NULL),(74,8,8,'2025-05-26 16:45:29',NULL),(75,8,7,'2025-05-26 16:45:29',NULL),(76,8,6,'2025-05-26 16:45:29',NULL),(77,8,5,'2025-05-26 16:45:29',NULL),(78,8,4,'2025-05-26 16:45:29',NULL),(79,8,3,'2025-05-26 16:45:29',NULL),(80,9,8,'2025-05-26 16:45:29',NULL),(81,9,7,'2025-05-26 16:45:29',NULL),(82,9,6,'2025-05-26 16:45:29',NULL),(83,9,5,'2025-05-26 16:45:29',NULL),(84,9,4,'2025-05-26 16:45:29',NULL),(85,9,3,'2025-05-26 16:45:29',NULL),(86,10,8,'2025-05-26 16:45:29',NULL),(87,10,7,'2025-05-26 16:45:29',NULL),(88,10,6,'2025-05-26 16:45:29',NULL),(89,10,5,'2025-05-26 16:45:29',NULL),(90,10,4,'2025-05-26 16:45:29',NULL),(91,10,3,'2025-05-26 16:45:29',NULL),(92,11,8,'2025-05-26 16:45:29',NULL),(93,11,7,'2025-05-26 16:45:29',NULL),(94,11,6,'2025-05-26 16:45:29',NULL),(95,11,5,'2025-05-26 16:45:29',NULL),(96,11,4,'2025-05-26 16:45:29',NULL),(97,11,3,'2025-05-26 16:45:29',NULL),(98,12,8,'2025-05-26 16:45:29',NULL),(99,12,7,'2025-05-26 16:45:29',NULL),(100,12,6,'2025-05-26 16:45:29',NULL),(101,12,5,'2025-05-26 16:45:29',NULL),(102,12,4,'2025-05-26 16:45:29',NULL),(103,12,3,'2025-05-26 16:45:29',NULL),(104,13,8,'2025-05-26 16:45:29',NULL),(105,13,7,'2025-05-26 16:45:29',NULL),(106,13,6,'2025-05-26 16:45:29',NULL),(107,13,5,'2025-05-26 16:45:29',NULL),(108,13,4,'2025-05-26 16:45:29',NULL),(109,13,3,'2025-05-26 16:45:29',NULL),(110,14,8,'2025-05-26 16:45:29',NULL),(111,14,7,'2025-05-26 16:45:29',NULL),(112,14,6,'2025-05-26 16:45:29',NULL),(113,14,5,'2025-05-26 16:45:29',NULL),(114,14,4,'2025-05-26 16:45:29',NULL),(115,14,3,'2025-05-26 16:45:29',NULL),(116,15,8,'2025-05-26 16:45:29',NULL),(117,15,7,'2025-05-26 16:45:29',NULL),(118,15,6,'2025-05-26 16:45:29',NULL),(119,15,5,'2025-05-26 16:45:29',NULL),(120,15,4,'2025-05-26 16:45:29',NULL),(121,15,3,'2025-05-26 16:45:29',NULL),(122,16,8,'2025-05-26 16:45:29',NULL),(123,16,7,'2025-05-26 16:45:29',NULL),(124,16,6,'2025-05-26 16:45:29',NULL),(125,16,5,'2025-05-26 16:45:29',NULL),(126,16,4,'2025-05-26 16:45:29',NULL),(127,16,3,'2025-05-26 16:45:29',NULL);
/*!40000 ALTER TABLE `approval_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `approvers`
--

DROP TABLE IF EXISTS `approvers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approvers` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approvers`
--

LOCK TABLES `approvers` WRITE;
/*!40000 ALTER TABLE `approvers` DISABLE KEYS */;
INSERT INTO `approvers` VALUES (1,1,4,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(2,2,4,2,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(3,3,5,3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(4,4,5,4,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(5,5,5,4,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(6,6,6,15,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(7,7,6,16,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(8,8,6,16,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(9,9,6,16,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(10,10,7,9,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(11,11,7,10,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(12,12,7,10,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(13,13,8,3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(14,14,8,4,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(15,15,9,13,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(16,18,4,2,'2025-05-26 17:39:12','2025-05-26 17:39:12'),(17,19,6,16,'2025-05-26 17:43:11','2025-05-26 17:43:11');
/*!40000 ALTER TABLE `approvers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('he_thong_quan_ly_va_phe_duyet_van_ban_cache_a75f3f172bfb296f2e10cbfc6dfc1883','i:15;',1748798525),('he_thong_quan_ly_va_phe_duyet_van_ban_cache_a75f3f172bfb296f2e10cbfc6dfc1883:timer','i:1748798525;',1748798525);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creators`
--

DROP TABLE IF EXISTS `creators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `creators` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creators`
--

LOCK TABLES `creators` WRITE;
/*!40000 ALTER TABLE `creators` DISABLE KEYS */;
INSERT INTO `creators` VALUES (1,20,7,17,'2025-05-26 17:54:15','2025-05-26 17:54:15');
/*!40000 ALTER TABLE `creators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'where this department situated in',
  `can_approve` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'check if this department has approval permission or not',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Hãy chọn khoa tương ứng','Sử dụng trong luồng mẫu khi một đơn vị duyệt phụ thuộc vào người yêu cầu ví dụ nếu là từ LCĐ khoa CNTT thì có thể chọn khoa CNTT, và tương tự...',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(2,'Hãy chọn LCĐ tương ứng','Sử dụng trong luồng mẫu khi một đơn vị duyệt phụ thuộc vào người yêu cầu ví dụ nếu là từ LCĐ khoa CNTT thì có thể chọn khoa CNTT, và tương tự...',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(3,'Hãy chọn LCH tương ứng','Sử dụng trong luồng mẫu khi một đơn vị duyệt phụ thuộc vào người yêu cầu ví dụ nếu là từ LCĐ khoa CNTT thì có thể chọn khoa CNTT, và tương tự...',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(4,'Phòng CT & CTSV','Hay còn gọi là P7',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(5,'Đoàn Thanh niên','Đoàn Thanh niên trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(6,'Hội Sinh viên','Hội Sinh viên trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(7,'Khoa Công nghệ thông tin','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(8,'LCĐ Khoa Công nghệ thông tin','LCĐ trực thuộc Khoa Công nghệ thông tin',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(9,'LCH Khoa Công nghệ thông tin','LCH trực thuộc Khoa Công nghệ thông tin',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(10,'Khoa Công trình','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(11,'LCĐ Khoa Công trình','LCĐ trực thuộc Khoa Công trình',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(12,'LCH Khoa Công trình','LCH trực thuộc Khoa Công trình',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(13,'Khoa Cơ khí','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(14,'LCĐ Khoa Cơ khí','LCĐ trực thuộc Khoa Cơ khí',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(15,'LCH Khoa Cơ khí','LCH trực thuộc Khoa Cơ khí',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(16,'Khoa Điện - Điện tử','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(17,'LCĐ Khoa Điện - Điện tử','LCĐ trực thuộc Khoa Điện - Điện tử',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(18,'LCH Khoa Điện - Điện tử','LCH trực thuộc Khoa Điện - Điện tử',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(19,'Khoa Hóa & Môi trường','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(20,'LCĐ Khoa Hóa & Môi trường','LCĐ trực thuộc Khoa Hóa & Môi trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(21,'LCH Khoa Hóa & Môi trường','LCH trực thuộc Khoa Hóa & Môi trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(22,'Khoa Kế toán & Kinh doanh','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(23,'LCĐ Khoa Kế toán & Kinh doanh','LCĐ trực thuộc Khoa Kế toán & Kinh doanh',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(24,'LCH Khoa Kế toán & Kinh doanh','LCH trực thuộc Khoa Kế toán & Kinh doanh',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(25,'Khoa Kinh tế quản lý','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(26,'LCĐ Khoa Kinh tế quản lý','LCĐ trực thuộc Khoa Kinh tế quản lý',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(27,'LCH Khoa Kinh tế quản lý','LCH trực thuộc Khoa Kinh tế quản lý',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(28,'Khoa KTTNN','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(29,'LCĐ Khoa KTTNN','LCĐ trực thuộc Khoa KTTNN',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(30,'LCH Khoa KTTNN','LCH trực thuộc Khoa KTTNN',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(31,'Khoa Luật & LLCT','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(32,'LCĐ Khoa Luật & LLCT','LCĐ trực thuộc Khoa Luật & LLCT',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(33,'LCH Khoa Luật & LLCT','LCH trực thuộc Khoa Luật & LLCT',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(34,'Trung tâm Đào tạo Quốc tế','Một khoa thuộc trường',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(35,'LCĐ Trung tâm Đào tạo Quốc tế','LCĐ trực thuộc Trung tâm Đào tạo Quốc tế',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(36,'LCH Trung tâm Đào tạo Quốc tế','LCH trực thuộc Trung tâm Đào tạo Quốc tế',NULL,NULL,NULL,1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(37,'CLB SVTN','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(38,'CLB SVTH','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(39,'CLB Khởi nghiệp','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(40,'CLB Tiếng Anh','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(41,'CLB TNTN HMNĐ','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(42,'CLB Kinh tế số','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(43,'CLB Sách và HĐ','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(44,'CLB Phát triển kỹ năng','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(45,'Ban Nữ sinh','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(46,'Ban Văn nghệ','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(47,'CLB Báo chí','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(48,'CLB Nhảy','Một CLB ở trường',NULL,NULL,NULL,0,'2025-05-26 16:45:29','2025-05-26 16:45:29');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `digital_signatures`
--

DROP TABLE IF EXISTS `digital_signatures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `digital_signatures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `public_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `private_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature_image_path` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `digital_signatures`
--

LOCK TABLES `digital_signatures` WRITE;
/*!40000 ALTER TABLE `digital_signatures` DISABLE KEYS */;
/*!40000 ALTER TABLE `digital_signatures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_comments`
--

DROP TABLE IF EXISTS `document_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_comments` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_comments`
--

LOCK TABLES `document_comments` WRITE;
/*!40000 ALTER TABLE `document_comments` DISABLE KEYS */;
INSERT INTO `document_comments` VALUES (6,1,20,'Nhận xét văn bản','2025-05-26 19:11:21'),(7,1,19,'Thử nhận xét ở phía người phê duyệt','2025-05-26 19:14:13'),(8,3,19,'Văn bản chuẩn chỉnh','2025-05-27 02:50:40'),(9,7,18,'Nhận xét về văn bản','2025-05-28 09:03:43'),(10,7,18,'Nhận xét mới','2025-05-28 09:08:28');
/*!40000 ALTER TABLE `document_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_flow_steps`
--

DROP TABLE IF EXISTS `document_flow_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_flow_steps` (
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
  CONSTRAINT `document_flow_steps_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `approvers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_flow_steps_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_flow_steps_document_flow_id_foreign` FOREIGN KEY (`document_flow_id`) REFERENCES `document_flows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_flow_steps`
--

LOCK TABLES `document_flow_steps` WRITE;
/*!40000 ALTER TABLE `document_flow_steps` DISABLE KEYS */;
INSERT INTO `document_flow_steps` VALUES (1,1,1,6,NULL,0,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(2,1,2,4,NULL,0,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(3,2,1,1,NULL,1,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(4,2,2,5,NULL,0,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(5,2,3,4,NULL,0,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(6,3,1,6,NULL,0,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(7,4,1,5,NULL,0,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(8,4,2,6,NULL,0,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(9,4,3,4,NULL,0,'pending','not_yet',NULL,NULL,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(10,5,1,8,14,0,'in_review','not_yet',NULL,NULL,'2025-05-26 17:56:27','2025-05-26 17:56:27'),(11,5,2,6,17,0,'pending','not_yet',NULL,NULL,'2025-05-26 17:56:27','2025-05-26 17:56:27'),(12,6,1,6,6,0,'in_review','not_yet',NULL,NULL,'2025-05-26 18:21:54','2025-05-26 18:21:54'),(13,6,2,4,16,0,'pending','not_yet',NULL,NULL,'2025-05-26 18:21:54','2025-05-26 18:21:54'),(14,7,1,6,17,0,'rejected','rejected',NULL,NULL,'2025-05-27 02:21:40','2025-05-27 03:30:00'),(15,8,1,6,17,0,'pending','not_yet',NULL,NULL,'2025-05-27 02:23:02','2025-05-27 02:23:02'),(16,8,2,4,16,0,'pending','not_yet',NULL,NULL,'2025-05-27 02:23:02','2025-05-27 02:23:02'),(17,9,1,6,6,1,'in_review','not_yet',NULL,NULL,'2025-05-27 08:09:47','2025-05-27 08:09:47'),(18,9,1,6,17,1,'approved','approved',NULL,'2025-05-27 10:13:00','2025-05-27 08:09:47','2025-05-27 10:13:00'),(19,9,2,4,16,0,'pending','not_yet',NULL,NULL,'2025-05-27 08:09:47','2025-05-27 08:09:47'),(20,10,1,6,17,0,'approved','approved',NULL,'2025-05-27 15:09:55','2025-05-27 14:44:59','2025-05-27 15:09:55'),(21,11,1,6,17,0,'approved','approved',NULL,'2025-05-28 09:01:48','2025-05-28 09:00:11','2025-05-28 09:01:48'),(22,11,2,4,16,0,'approved','approved',NULL,'2025-05-28 09:09:26','2025-05-28 09:00:11','2025-05-28 09:09:26'),(35,24,1,4,16,0,'approved','approved',NULL,'2025-05-28 16:17:45','2025-05-28 16:16:54','2025-05-28 16:17:45'),(36,25,1,6,17,0,'in_review','not_yet',NULL,NULL,'2025-05-30 15:14:42','2025-05-30 15:14:42'),(37,26,1,6,17,0,'approved','approved',NULL,'2025-05-31 07:31:05','2025-05-31 07:29:55','2025-05-31 07:31:05'),(38,26,2,4,16,0,'in_review','not_yet',NULL,NULL,'2025-05-31 07:29:55','2025-05-31 07:31:05'),(39,27,1,6,17,0,'in_review','not_yet',NULL,NULL,'2025-06-01 17:21:42','2025-06-01 17:21:42');
/*!40000 ALTER TABLE `document_flow_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_flows`
--

DROP TABLE IF EXISTS `document_flows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_flows` (
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
  CONSTRAINT `document_flows_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_flows`
--

LOCK TABLES `document_flows` WRITE;
/*!40000 ALTER TABLE `document_flows` DISABLE KEYS */;
INSERT INTO `document_flows` VALUES (1,'Tổ chức sự kiện CLB','Luồng phê duyệt cho văn bản xin tổ chức sự kiện cấp CLB',16,1,1,0,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(2,'Tổ chức sự kiện LCĐ','Luồng phê duyệt cho văn bản xin tổ chức sự kiện cấp LCĐ',16,1,1,0,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(3,'Xin khen thưởng CLB','Luồng phê duyệt cho văn bản xin cấp khen thưởng CLB',16,1,1,0,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(4,'Xin mượn hội trường','Luồng phê duyệt cho văn bản xin mượn hội trường',16,1,1,0,'2025-05-26 16:45:57','2025-05-26 16:45:57'),(5,'Luồng phê duyệt cho \'Văn bản đầu tiên\' của Hải Nam - 5/27/2025, 12:56:26 AM',NULL,20,0,0,0,'2025-05-26 17:56:27','2025-05-26 17:56:27'),(6,'Luồng phê duyệt cho \'Người phê duyệt gửi đi\' của Ngọc Thúy - 01:21:53 27/5/2025',NULL,19,0,0,0,'2025-05-26 18:21:54','2025-05-26 18:21:54'),(7,'Luồng phê duyệt cho \'Test\' của Hải Nam - 5/27/2025, 9:21:39 AM',NULL,20,0,0,0,'2025-05-27 02:21:40','2025-05-27 02:21:40'),(8,'Luồng phê duyệt cho \'Test thêm nháp\' của Hải Nam - 5/27/2025, 9:23:01 AM',NULL,20,0,0,0,'2025-05-27 02:23:02','2025-05-27 02:23:02'),(9,'Luồng phê duyệt cho \'Thêm để test\' của Hải Nam - 5/27/2025, 3:09:46 PM',NULL,20,0,0,1,'2025-05-27 08:09:47','2025-05-27 10:13:00'),(10,'Luồng phê duyệt cho \'Tạo để test\' của Hải Nam - 5/27/2025, 9:44:58 PM',NULL,20,0,0,1,'2025-05-27 14:44:59','2025-05-27 15:09:55'),(11,'Luồng phê duyệt cho \'Tạo mới để test\' của Hải Nam - 5/28/2025, 4:00:10 PM',NULL,20,0,0,2,'2025-05-28 09:00:11','2025-05-28 09:09:26'),(24,'Luồng phê duyệt cho \'Test chức năng\' của Ngọc Thúy - 23:16:54 28/5/2025',NULL,19,0,0,1,'2025-05-28 16:16:54','2025-05-28 16:17:45'),(25,'Luồng phê duyệt cho \'Như anh mơ\' của Hải Nam - 5/30/2025, 10:14:40 PM',NULL,20,0,0,0,'2025-05-30 15:14:42','2025-05-30 15:14:42'),(26,'Luồng phê duyệt cho \'Đăng ký văn bản\' của Hải Nam - 5/31/2025, 2:29:52 PM',NULL,20,0,0,1,'2025-05-31 07:29:55','2025-05-31 07:31:05'),(27,'Luồng phê duyệt cho \'Thử thông báo\' của Hải Nam - 6/2/2025, 12:21:41 AM',NULL,20,0,0,0,'2025-06-01 17:21:42','2025-06-01 17:21:42');
/*!40000 ALTER TABLE `document_flows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_templates`
--

DROP TABLE IF EXISTS `document_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `document_type_id` bigint unsigned NOT NULL,
  `downloaded` int NOT NULL DEFAULT '0',
  `liked` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_templates_created_by_foreign` (`created_by`),
  KEY `document_templates_document_type_id_foreign` (`document_type_id`),
  CONSTRAINT `document_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `document_templates_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_templates`
--

LOCK TABLES `document_templates` WRITE;
/*!40000 ALTER TABLE `document_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_types`
--

DROP TABLE IF EXISTS `document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_types`
--

LOCK TABLES `document_types` WRITE;
/*!40000 ALTER TABLE `document_types` DISABLE KEYS */;
INSERT INTO `document_types` VALUES (1,'Phê duyệt kinh phí','Yêu cầu phê duyệt kinh phí tổ chức','2025-05-26 16:45:29','2025-05-26 16:45:29'),(2,'Đơn bổ nhiệm','Đơn bổ nhiệm vị trí nào đó','2025-05-26 16:45:29','2025-05-26 16:45:29'),(3,'Văn bản thường 1','Văn bản thường 1','2025-05-26 16:45:29','2025-05-26 16:45:29'),(4,'Văn bản thường 2','Văn bản thường 2','2025-05-26 16:45:29','2025-05-26 16:45:29'),(5,'Văn bản thường 3','Văn bản thường 3','2025-05-26 16:45:29','2025-05-26 16:45:29'),(6,'Văn bản thường 4','Văn bản thường 4','2025-05-26 16:45:29','2025-05-26 16:45:29'),(7,'Văn bản thường 5','Văn bản thường 5','2025-05-26 16:45:29','2025-05-26 16:45:29'),(8,'Văn bản thường 6','Văn bản thường 6','2025-05-26 16:45:29','2025-05-26 16:45:29');
/*!40000 ALTER TABLE `document_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_versions`
--

DROP TABLE IF EXISTS `document_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint unsigned NOT NULL,
  `version` int NOT NULL,
  `document_data` json NOT NULL COMMENT 'Save informations about document of each version',
  `status` enum('draft','in_review','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_versions_document_id_version_unique` (`document_id`,`version`),
  CONSTRAINT `document_versions_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_versions`
--

LOCK TABLES `document_versions` WRITE;
/*!40000 ALTER TABLE `document_versions` DISABLE KEYS */;
INSERT INTO `document_versions` VALUES (1,1,1,'{\"id\": 1, \"title\": \"Văn bản đầu tiên\", \"status\": \"in_review\", \"file_path\": \"f19e9c22-d7f6-45ea-b8b5-29eadb166d17.pdf\", \"is_public\": true, \"created_at\": \"17:56:27 26/05/2025\", \"created_by\": 20, \"updated_at\": \"17:56:33 26/05/2025\", \"description\": \"Mong là không có sai sót gì cả\", \"document_flow_id\": 5, \"document_type_id\": 2}','in_review','2025-05-26 17:56:27'),(2,2,1,'{\"id\": 2, \"title\": \"Người phê duyệt gửi đi\", \"status\": \"in_review\", \"file_path\": \"bdc5df2b-2a54-4c12-af88-b8abda324bd9.pdf\", \"is_public\": true, \"created_at\": \"18:21:54 26/05/2025\", \"created_by\": 19, \"updated_at\": \"18:21:57 26/05/2025\", \"description\": \"Thử nghiệm văn bản của người có quyền phê duyệt gửi đi\", \"document_flow_id\": 6, \"document_type_id\": 2}','in_review','2025-05-26 18:21:54'),(3,3,1,'{\"id\": 3, \"title\": \"Test\", \"status\": \"in_review\", \"file_path\": \"5876b7b3-78c5-4638-b1f1-15783009e963.pdf\", \"is_public\": true, \"created_at\": \"02:21:40 27/05/2025\", \"created_by\": 20, \"updated_at\": \"02:21:42 27/05/2025\", \"description\": \"Giờ gửi cho Thúy đầu tiên\", \"document_flow_id\": 7, \"document_type_id\": 3}','rejected','2025-05-27 02:21:40'),(4,4,1,'{\"id\": 4, \"title\": \"Test thêm nháp\", \"status\": \"draft\", \"file_path\": \"db8dcbe3-f622-421e-abc9-bae90b920404.pdf\", \"is_public\": true, \"created_at\": \"02:23:02 27/05/2025\", \"created_by\": 20, \"updated_at\": \"02:23:04 27/05/2025\", \"description\": \"Mong là không có sai sót gì\", \"document_flow_id\": 8, \"document_type_id\": 4}','draft','2025-05-27 02:23:02'),(5,5,1,'{\"id\": 5, \"title\": \"Thêm để test\", \"status\": \"in_review\", \"file_path\": \"db639109-df44-4ee2-8df1-3543f0f682fb.pdf\", \"is_public\": true, \"created_at\": \"08:09:47 27/05/2025\", \"created_by\": 20, \"updated_at\": \"08:09:55 27/05/2025\", \"description\": \"Thêm văn bản có nhiều người đồng cấp\", \"document_flow_id\": 9, \"document_type_id\": 5}','in_review','2025-05-27 08:09:47'),(6,6,1,'{\"id\": 6, \"title\": \"Tạo để test\", \"status\": \"in_review\", \"file_path\": \"cc25d34e-3a5d-46a9-8eb6-601231ce917c.pdf\", \"is_public\": true, \"created_at\": \"14:44:59 27/05/2025\", \"created_by\": 20, \"updated_at\": \"14:45:02 27/05/2025\", \"description\": \"Mô tả gì đây, đói quá\", \"document_flow_id\": 10, \"document_type_id\": 7}','approved','2025-05-27 14:44:59'),(7,7,1,'{\"id\": 7, \"title\": \"Tạo mới để test\", \"status\": \"in_review\", \"file_path\": \"8d3fbf72-7931-4da3-ba8b-d35e5855c017.pdf\", \"is_public\": true, \"created_at\": \"09:00:11 28/05/2025\", \"created_by\": 20, \"updated_at\": \"09:00:17 28/05/2025\", \"description\": \"Mô tả vài câu\", \"document_flow_id\": 11, \"document_type_id\": 3}','approved','2025-05-28 09:00:11'),(20,20,1,'{\"id\": 20, \"title\": \"Test chức năng\", \"status\": \"in_review\", \"file_path\": \"bb255400-497e-4fce-92c1-43ed3bb4015c.pdf\", \"is_public\": true, \"created_at\": \"16:16:54 28/05/2025\", \"created_by\": 19, \"updated_at\": \"16:17:01 28/05/2025\", \"description\": \"Mô tả gì được, bugs lắm vãi\", \"document_flow_id\": 24, \"document_type_id\": 5}','approved','2025-05-28 16:16:54'),(21,21,1,'{\"id\": 21, \"title\": \"Như anh mơ\", \"status\": \"in_review\", \"file_path\": \"98f2839b-23d7-456c-b875-e4ac0afe5536.pdf\", \"is_public\": true, \"created_at\": \"15:14:42 30/05/2025\", \"created_by\": 20, \"updated_at\": \"15:14:47 30/05/2025\", \"description\": \"Không như anh mơ ngày đó, thật khó gặp nhau giữa góc phố nhỏ này\\nEm ghé nơi đây với trái tim này nhớ, sâu trong lòng anh có đôi lời tỏ bày\\nEm có nghe lời thì thầm mùa thu, lại nhắc chúng ta những chiều hôm đó\\nDụi bàn tay anh vu vơ tờ giấy gấp, nháy mắt trao em nụ hôm gió\", \"document_flow_id\": 25, \"document_type_id\": 4}','in_review','2025-05-30 15:14:42'),(22,22,1,'{\"id\": 22, \"title\": \"Đăng ký văn bản\", \"status\": \"in_review\", \"file_path\": \"1e30857c-1ac5-4a41-bde9-88fc29243258.pdf\", \"is_public\": true, \"created_at\": \"07:29:55 31/05/2025\", \"created_by\": 20, \"updated_at\": \"07:30:14 31/05/2025\", \"description\": \"Mô tả\", \"document_flow_id\": 26, \"document_type_id\": 4}','in_review','2025-05-31 07:29:55'),(23,23,1,'{\"id\": 23, \"title\": \"Thử thông báo\", \"status\": \"in_review\", \"file_path\": \"9b8ccbb6-e793-4cce-93b8-382c7e37a949.pdf\", \"is_public\": true, \"created_at\": \"17:21:42 01/06/2025\", \"created_by\": 20, \"updated_at\": \"17:21:47 01/06/2025\", \"description\": \"Mô tả gì vậy\", \"document_flow_id\": 27, \"document_type_id\": 4}','in_review','2025-06-01 17:21:42');
/*!40000 ALTER TABLE `document_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
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
  CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_document_flow_id_foreign` FOREIGN KEY (`document_flow_id`) REFERENCES `document_flows` (`id`),
  CONSTRAINT `documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (1,'Văn bản đầu tiên','Mong là không có sai sót gì cả','f19e9c22-d7f6-45ea-b8b5-29eadb166d17.pdf',2,20,'in_review',5,1,'2025-05-26 17:56:27','2025-05-26 17:56:33'),(2,'Người phê duyệt gửi đi','Thử nghiệm văn bản của người có quyền phê duyệt gửi đi','bdc5df2b-2a54-4c12-af88-b8abda324bd9.pdf',2,19,'in_review',6,1,'2025-05-26 18:21:54','2025-05-26 18:21:57'),(3,'Test','Giờ gửi cho Thúy đầu tiên','5876b7b3-78c5-4638-b1f1-15783009e963.pdf',3,20,'rejected',7,1,'2025-05-27 02:21:40','2025-05-27 03:30:00'),(4,'Test thêm nháp','Mong là không có sai sót gì','db8dcbe3-f622-421e-abc9-bae90b920404.pdf',4,20,'draft',8,1,'2025-05-27 02:23:02','2025-05-27 02:23:04'),(5,'Thêm để test','Thêm văn bản có nhiều người đồng cấp','db639109-df44-4ee2-8df1-3543f0f682fb.pdf',5,20,'in_review',9,1,'2025-05-27 08:09:47','2025-05-27 08:09:55'),(6,'Tạo để test','Mô tả gì đây, đói quá','cc25d34e-3a5d-46a9-8eb6-601231ce917c.pdf',7,20,'approved',10,1,'2025-05-27 14:44:59','2025-05-27 15:09:55'),(7,'Tạo mới để test','Mô tả vài câu','8d3fbf72-7931-4da3-ba8b-d35e5855c017.pdf',3,20,'approved',11,1,'2025-05-28 09:00:11','2025-05-28 09:09:26'),(20,'Test chức năng','Mô tả gì được, bugs lắm vãi','bb255400-497e-4fce-92c1-43ed3bb4015c.pdf',5,19,'approved',24,1,'2025-05-28 16:16:54','2025-05-28 16:17:45'),(21,'Như anh mơ','Không như anh mơ ngày đó, thật khó gặp nhau giữa góc phố nhỏ này\nEm ghé nơi đây với trái tim này nhớ, sâu trong lòng anh có đôi lời tỏ bày\nEm có nghe lời thì thầm mùa thu, lại nhắc chúng ta những chiều hôm đó\nDụi bàn tay anh vu vơ tờ giấy gấp, nháy mắt trao em nụ hôm gió','98f2839b-23d7-456c-b875-e4ac0afe5536.pdf',4,20,'in_review',25,1,'2025-05-30 15:14:42','2025-05-30 15:14:47'),(22,'Đăng ký văn bản','Mô tả','1e30857c-1ac5-4a41-bde9-88fc29243258.pdf',4,20,'in_review',26,1,'2025-05-31 07:29:55','2025-05-31 07:30:14'),(23,'Thử thông báo','Mô tả gì vậy','9b8ccbb6-e793-4cce-93b8-382c7e37a949.pdf',4,20,'in_review',27,2,'2025-06-01 17:21:42','2025-06-01 17:21:47');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'00000_create_roles_table',1),(2,'00005_create_departments_table',1),(3,'00010_create_roll_at_departments_table',1),(4,'00015_create_notification_categories_table',1),(5,'00020_create_digital_signatures_table',1),(6,'00025_create_document_types_table',1),(7,'00030_create_users_table',1),(8,'00035_create_document_flows_table',1),(9,'00040_create_document_flow_steps_table',1),(10,'00045_create_admins_table',1),(11,'00050_create_creators_table',1),(12,'00055_create_approvers_table',1),(13,'00060_create_notifications_table',1),(14,'00065_create_document_templates_table',1),(15,'00070_create_user_bans_table',1),(16,'00075_create_approval_permissions_table',1),(17,'00080_create_documents_table',1),(18,'00085_create_document_versions_table',1),(19,'00090_create_template_users_table',1),(20,'00095_create_document_comments_table',1),(21,'00100_create_document_signatures_table',1),(22,'10000_create_cache_table',1),(23,'10001_create_jobs_table',1),(24,'10002_add_two_factor_columns_to_users_table',1),(25,'10003_create_personal_access_tokens_table',1),(26,'2025_05_20_142729_create_user_access_logs_table',1),(27,'2025_05_23_235212_add_process_column_document_flows_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_categories`
--

DROP TABLE IF EXISTS `notification_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_categories`
--

LOCK TABLES `notification_categories` WRITE;
/*!40000 ALTER TABLE `notification_categories` DISABLE KEYS */;
INSERT INTO `notification_categories` VALUES (1,'Văn bản','Thông báo cho các hoạt động liên quan đến văn bản như: Thêm mới, lưu nháp, ...','2025-05-26 16:45:29','2025-05-26 16:45:29'),(2,'Người dùng','Thông báo cho các hoạt động liên quan đến người dùng như: Đăng ký mới, Đăng nhập, ...','2025-05-26 16:45:29','2025-05-26 16:45:29'),(3,'Hệ thống','Thông báo chung của hệ thống đến tất cả người dùng','2025-05-26 16:45:29','2025-05-26 16:45:29');
/*!40000 ALTER TABLE `notification_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
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
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,2,18,16,'Đăng ký tài khoản','Tạo tài khoản mới trên hệ thống.',NULL,0,'2025-05-26 17:39:20'),(2,2,18,17,'Đăng ký tài khoản','Tạo tài khoản mới trên hệ thống.',NULL,0,'2025-05-26 17:39:22'),(3,2,18,16,'Xác thực tài khoản','Xác thực tài khoản thành công.',NULL,0,'2025-05-26 17:39:29'),(4,2,18,17,'Xác thực tài khoản','Xác thực tài khoản thành công.',NULL,0,'2025-05-26 17:39:30'),(5,2,19,16,'Đăng ký tài khoản','Tạo tài khoản mới trên hệ thống.',NULL,0,'2025-05-26 17:43:15'),(6,2,19,17,'Đăng ký tài khoản','Tạo tài khoản mới trên hệ thống.',NULL,0,'2025-05-26 17:43:16'),(7,2,20,16,'Đăng ký tài khoản','Tạo tài khoản mới trên hệ thống.',NULL,0,'2025-05-26 17:54:18'),(8,2,20,17,'Đăng ký tài khoản','Tạo tài khoản mới trên hệ thống.',NULL,0,'2025-05-26 17:54:19'),(9,2,20,16,'Xác thực tài khoản','Xác thực tài khoản thành công.',NULL,0,'2025-05-26 17:54:37'),(10,2,20,17,'Xác thực tài khoản','Xác thực tài khoản thành công.',NULL,0,'2025-05-26 17:54:37'),(11,2,20,16,'Phê duyệt văn bản','Tạo một luồng phê duyệt cho văn bản \'Văn bản đầu tiên\'',NULL,0,'2025-05-26 17:56:27'),(12,2,20,17,'Phê duyệt văn bản','Tạo một luồng phê duyệt cho văn bản \'Văn bản đầu tiên\'',NULL,0,'2025-05-26 17:56:28'),(13,3,20,14,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Văn bản đầu tiên\' do Hải Nam tạo',NULL,0,'2025-05-26 17:56:29'),(14,3,20,19,'Thêm vào luồng phê duyệt văn bản','Thêm bạn vào luồng phê duyệt cho văn bản \'Văn bản đầu tiên\'',NULL,0,'2025-05-26 17:56:30'),(15,2,19,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Người phê duyệt gửi đi\'',NULL,0,'2025-05-26 18:21:54'),(16,2,19,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Người phê duyệt gửi đi\'',NULL,0,'2025-05-26 18:21:55'),(17,3,19,6,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Người phê duyệt gửi đi\' do Ngọc Thúy tạo',NULL,0,'2025-05-26 18:21:56'),(18,3,19,18,'Thêm vào luồng phê duyệt văn bản','Thêm bạn vào luồng phê duyệt cho văn bản \'Người phê duyệt gửi đi\'',NULL,0,'2025-05-26 18:21:56'),(19,2,20,16,'Nhận xét văn bản','Nhận xét về văn bản \'Văn bản đầu tiên\' của Hải Nam',NULL,0,'2025-05-26 19:11:21'),(20,2,20,17,'Nhận xét văn bản','Nhận xét về văn bản \'Văn bản đầu tiên\' của Hải Nam',NULL,0,'2025-05-26 19:11:22'),(21,2,20,14,'Nhận xét văn bản','Có nhận xét mới về văn bản \'Văn bản đầu tiên\' trong luồng phê duyệt',NULL,0,'2025-05-26 19:11:23'),(22,2,20,19,'Nhận xét văn bản','Có nhận xét mới về văn bản \'Văn bản đầu tiên\' trong luồng phê duyệt',NULL,0,'2025-05-26 19:11:23'),(23,2,19,16,'Nhận xét văn bản','Nhận xét về văn bản \'Văn bản đầu tiên\' của Hải Nam',NULL,0,'2025-05-26 19:14:13'),(24,2,19,17,'Nhận xét văn bản','Nhận xét về văn bản \'Văn bản đầu tiên\' của Hải Nam',NULL,0,'2025-05-26 19:14:14'),(25,2,19,14,'Nhận xét văn bản','Có nhận xét mới về văn bản \'Văn bản đầu tiên\' trong luồng phê duyệt',NULL,0,'2025-05-26 19:14:14'),(26,2,NULL,20,'Nhận xét văn bản','Nhận xét về văn bản \'Văn bản đầu tiên\' của bạn',NULL,0,'2025-05-26 19:14:15'),(27,2,20,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Test\'',NULL,0,'2025-05-27 02:21:40'),(28,2,20,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Test\'',NULL,0,'2025-05-27 02:21:40'),(29,3,20,19,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Test\' do Hải Nam tạo',NULL,0,'2025-05-27 02:21:41'),(30,1,20,16,'Lưu nháp văn bản','Lưu bản nháp văn bản \'Test thêm nháp\'',NULL,0,'2025-05-27 02:23:02'),(31,1,20,17,'Lưu nháp văn bản','Lưu bản nháp văn bản \'Test thêm nháp\'',NULL,0,'2025-05-27 02:23:03'),(32,2,19,16,'Nhận xét văn bản','Nhận xét về văn bản \'Test\' của Hải Nam',NULL,0,'2025-05-27 02:50:40'),(33,2,19,17,'Nhận xét văn bản','Nhận xét về văn bản \'Test\' của Hải Nam',NULL,0,'2025-05-27 02:50:41'),(34,2,NULL,20,'Nhận xét văn bản','Nhận xét về văn bản \'Test\' của bạn',NULL,0,'2025-05-27 02:50:41'),(35,2,19,16,'Từ chối văn bản','Từ chối phê duyệt cho văn bản \'Test\'',NULL,0,'2025-05-27 03:30:00'),(36,2,19,17,'Từ chối văn bản','Từ chối phê duyệt cho văn bản \'Test\'',NULL,0,'2025-05-27 03:30:01'),(37,2,19,20,'Từ chối văn bản','Từ chối phê duyệt cho văn bản \'Test\' của bạn.',NULL,0,'2025-05-27 03:30:02'),(38,2,20,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Thêm để test\'',NULL,0,'2025-05-27 08:09:47'),(39,2,20,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Thêm để test\'',NULL,0,'2025-05-27 08:09:49'),(40,3,20,6,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Thêm để test\' do Hải Nam tạo',NULL,0,'2025-05-27 08:09:50'),(41,3,20,19,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Thêm để test\' do Hải Nam tạo',NULL,0,'2025-05-27 08:09:51'),(42,3,20,18,'Thêm vào luồng phê duyệt văn bản','Thêm bạn vào luồng phê duyệt cho văn bản \'Thêm để test\'',NULL,0,'2025-05-27 08:09:51'),(43,2,20,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Tạo để test\'',NULL,0,'2025-05-27 14:44:59'),(44,2,20,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Tạo để test\'',NULL,0,'2025-05-27 14:45:00'),(45,3,20,19,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Tạo để test\' do Hải Nam tạo',NULL,0,'2025-05-27 14:45:00'),(46,2,19,20,'Văn bản đã được phê duyệt','Văn bản \'Tạo để test\' của bạn đã được phê duyệt hoàn tất.',NULL,0,'2025-05-27 15:09:55'),(47,2,19,16,'Hoàn tất phê duyệt','Văn bản \'Tạo để test\' đã được phê duyệt hoàn tất',NULL,0,'2025-05-27 15:09:56'),(48,2,19,17,'Hoàn tất phê duyệt','Văn bản \'Tạo để test\' đã được phê duyệt hoàn tất',NULL,0,'2025-05-27 15:09:57'),(49,2,20,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Tạo mới để test\'',NULL,0,'2025-05-28 09:00:11'),(50,2,20,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Tạo mới để test\'',NULL,0,'2025-05-28 09:00:13'),(51,3,20,19,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Tạo mới để test\' do Hải Nam tạo',NULL,0,'2025-05-28 09:00:13'),(52,3,20,18,'Thêm vào luồng phê duyệt văn bản','Thêm bạn vào luồng phê duyệt cho văn bản \'Tạo mới để test\'',NULL,0,'2025-05-28 09:00:14'),(53,2,19,18,'Văn bản cần phê duyệt','Bạn có văn bản \'Tạo mới để test\' cần phê duyệt.',NULL,0,'2025-05-28 09:01:48'),(54,2,19,20,'Văn bản được phê duyệt','Văn bản \'Tạo mới để test\' đã được phê duyệt bởi Ngọc Thúy.',NULL,0,'2025-05-28 09:01:49'),(55,2,19,16,'Đồng ý phê duyệt','Đồng ý phê duyệt cho văn bản \'Tạo mới để test\'.',NULL,0,'2025-05-28 09:01:50'),(56,2,19,17,'Đồng ý phê duyệt','Đồng ý phê duyệt cho văn bản \'Tạo mới để test\'.',NULL,0,'2025-05-28 09:01:50'),(57,2,18,16,'Nhận xét văn bản','Nhận xét về văn bản \'Tạo mới để test\' của Hải Nam',NULL,0,'2025-05-28 09:03:43'),(58,2,18,17,'Nhận xét văn bản','Nhận xét về văn bản \'Tạo mới để test\' của Hải Nam',NULL,0,'2025-05-28 09:03:48'),(59,2,18,19,'Nhận xét văn bản','Có nhận xét mới về văn bản \'Tạo mới để test\' trong luồng phê duyệt',NULL,0,'2025-05-28 09:03:49'),(60,2,NULL,20,'Nhận xét văn bản','Nhận xét về văn bản \'Tạo mới để test\' của bạn',NULL,0,'2025-05-28 09:03:50'),(61,2,18,16,'Nhận xét văn bản','Nhận xét về văn bản \'Tạo mới để test\' của Hải Nam',NULL,0,'2025-05-28 09:08:28'),(62,2,18,17,'Nhận xét văn bản','Nhận xét về văn bản \'Tạo mới để test\' của Hải Nam',NULL,0,'2025-05-28 09:08:28'),(63,2,18,19,'Nhận xét văn bản','Có nhận xét mới về văn bản \'Tạo mới để test\' trong luồng phê duyệt',NULL,0,'2025-05-28 09:08:29'),(64,2,NULL,20,'Nhận xét văn bản','Nhận xét về văn bản \'Tạo mới để test\' của bạn',NULL,0,'2025-05-28 09:08:30'),(65,2,18,20,'Văn bản đã được phê duyệt','Văn bản \'Tạo mới để test\' của bạn đã được phê duyệt hoàn tất.',NULL,1,'2025-05-28 09:09:26'),(66,2,18,16,'Hoàn tất phê duyệt','Văn bản \'Tạo mới để test\' đã được phê duyệt hoàn tất',NULL,0,'2025-05-28 09:09:27'),(67,2,18,17,'Hoàn tất phê duyệt','Văn bản \'Tạo mới để test\' đã được phê duyệt hoàn tất',NULL,0,'2025-05-28 09:09:28'),(70,2,19,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Test chức năng\'',NULL,0,'2025-05-28 16:16:54'),(71,2,19,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Test chức năng\'',NULL,0,'2025-05-28 16:16:56'),(72,3,19,18,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Test chức năng\' do Ngọc Thúy tạo',NULL,0,'2025-05-28 16:16:57'),(73,2,18,19,'Văn bản đã được phê duyệt','Văn bản \'Test chức năng\' của bạn đã được phê duyệt hoàn tất.',NULL,1,'2025-05-28 16:17:45'),(74,2,18,16,'Hoàn tất phê duyệt','Văn bản \'Test chức năng\' đã được phê duyệt hoàn tất',NULL,0,'2025-05-28 16:17:46'),(75,2,18,17,'Hoàn tất phê duyệt','Văn bản \'Test chức năng\' đã được phê duyệt hoàn tất',NULL,0,'2025-05-28 16:17:47'),(76,2,20,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Như anh mơ\'',NULL,0,'2025-05-30 15:14:42'),(77,2,20,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Như anh mơ\'',NULL,0,'2025-05-30 15:14:44'),(78,3,20,19,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Như anh mơ\' do Hải Nam tạo',NULL,0,'2025-05-30 15:14:44'),(79,2,20,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Đăng ký văn bản\'',NULL,0,'2025-05-31 07:29:55'),(80,2,20,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Đăng ký văn bản\'',NULL,0,'2025-05-31 07:30:01'),(81,3,20,19,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Đăng ký văn bản\' do Hải Nam tạo',NULL,0,'2025-05-31 07:30:02'),(82,3,20,18,'Thêm vào luồng phê duyệt văn bản','Thêm bạn vào luồng phê duyệt cho văn bản \'Đăng ký văn bản\'',NULL,0,'2025-05-31 07:30:04'),(83,2,19,18,'Văn bản cần phê duyệt','Bạn có văn bản \'Đăng ký văn bản\' cần phê duyệt.',NULL,0,'2025-05-31 07:31:05'),(84,2,19,20,'Văn bản được phê duyệt','Văn bản \'Đăng ký văn bản\' đã được phê duyệt bởi Ngọc Thúy.',NULL,0,'2025-05-31 07:31:07'),(85,2,19,16,'Đồng ý phê duyệt','Đồng ý phê duyệt cho văn bản \'Đăng ký văn bản\'.',NULL,0,'2025-05-31 07:31:09'),(86,2,19,17,'Đồng ý phê duyệt','Đồng ý phê duyệt cho văn bản \'Đăng ký văn bản\'.',NULL,0,'2025-05-31 07:31:11'),(87,2,20,16,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Thử thông báo\'',NULL,0,'2025-06-01 17:21:42'),(88,2,20,17,'Tạo văn bản phê duyệt','Tạo một luồng phê duyệt cho văn bản \'Thử thông báo\'',NULL,0,'2025-06-01 17:21:44'),(89,3,20,19,'Thêm vào luồng phê duyệt văn bản','Bạn là người phê duyệt đầu tiên cho văn bản \'Thử thông báo\' do Hải Nam tạo',NULL,0,'2025-06-01 17:21:44');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` enum('admin','approver','creator') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu thông tin về các chức vụ có thể có trong hệ thống';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Quản trị viên hệ thống','2025-05-26 16:45:29','2025-05-26 16:45:29'),(2,'creator','Người dùng chỉ có quyền đề xuất văn bản để phê duyệt','2025-05-26 16:45:29','2025-05-26 16:45:29'),(3,'approver','Người dùng có cả quyền phê duyệt và đề xuất văn bản để phê duyệt','2025-05-26 16:45:29','2025-05-26 16:45:29');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roll_at_departments`
--

DROP TABLE IF EXISTS `roll_at_departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roll_at_departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `level` int NOT NULL COMMENT 'Lưu trình tự của các chức vụ trong đơn vị, từ 1 đến n, 1 là chức vụ cao nhất',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roll_at_departments_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roll_at_departments`
--

LOCK TABLES `roll_at_departments` WRITE;
/*!40000 ALTER TABLE `roll_at_departments` DISABLE KEYS */;
INSERT INTO `roll_at_departments` VALUES (1,'Trưởng phòng','Người đứng đầu của phòng',1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(2,'Phó phòng','Người đứng thứ hai của phòng',3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(3,'Bí thư','Người đứng đầu của đơn vị',1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(4,'Phó Bí thư','Người đứng thứ hai của đơn vị',3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(5,'Trưởng ban','Người đứng đầu của ban',1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(6,'Phó ban','Người đứng thứ hai của ban',3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(7,'Chủ nhiệm','Người đứng đầu của CLB',1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(8,'Phó Chủ nhiệm','Người đứng thứ hai của CLB',3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(9,'Trưởng khoa','Người đứng đầu của khoa',1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(10,'Phó Trưởng khoa','Người đứng thứ hai của khoa',3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(11,'Giám đốc','Người đứng đầu của Trung tâm',1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(12,'Phó Giám đốc','Người đứng thứ hai của Trung tâm',3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(13,'LCH Trưởng','Người đứng đầu của LCH',1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(14,'LCH Phó','Người đứng thứ hai của LCH',3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(15,'Chủ tịch','Người đứng đầu của hội',1,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(16,'Phó Chủ tịch','Người đứng thứ hai của hội',3,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(17,'Sinh viên','Sinh viên thuộc',10,'2025-05-26 16:45:29','2025-05-26 16:45:29');
/*!40000 ALTER TABLE `roll_at_departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `template_users`
--

DROP TABLE IF EXISTS `template_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `template_users` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `template_users`
--

LOCK TABLES `template_users` WRITE;
/*!40000 ALTER TABLE `template_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `template_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_access_logs`
--

DROP TABLE IF EXISTS `user_access_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_access_logs` (
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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_access_logs`
--

LOCK TABLES `user_access_logs` WRITE;
/*!40000 ALTER TABLE `user_access_logs` DISABLE KEYS */;
INSERT INTO `user_access_logs` VALUES (1,16,'2025-05-26 17:29:33','127.0.0.1',1,'2025-05-26 17:29:33','2025-05-26 17:29:33'),(2,19,'2025-05-26 17:50:21','127.0.0.1',1,'2025-05-26 17:50:21','2025-05-26 17:50:21'),(3,20,'2025-05-26 17:55:11','127.0.0.1',1,'2025-05-26 17:55:11','2025-05-26 17:55:11'),(4,19,'2025-05-27 02:09:59','127.0.0.1',1,'2025-05-27 02:09:59','2025-05-27 02:09:59'),(5,20,'2025-05-27 02:10:10','127.0.0.1',1,'2025-05-27 02:10:10','2025-05-27 02:10:10'),(6,16,'2025-05-27 02:20:39','127.0.0.1',1,'2025-05-27 02:20:39','2025-05-27 02:20:39'),(7,20,'2025-05-27 07:40:24','127.0.0.1',1,'2025-05-27 07:40:24','2025-05-27 07:40:24'),(8,16,'2025-05-27 07:40:46','127.0.0.1',1,'2025-05-27 07:40:46','2025-05-27 07:40:46'),(9,19,'2025-05-27 07:41:02','127.0.0.1',1,'2025-05-27 07:41:02','2025-05-27 07:41:02'),(10,18,'2025-05-27 10:42:11','127.0.0.1',1,'2025-05-27 10:42:11','2025-05-27 10:42:11'),(11,19,'2025-05-27 13:21:33','127.0.0.1',1,'2025-05-27 13:21:33','2025-05-27 13:21:33'),(12,16,'2025-05-27 14:09:43','127.0.0.1',1,'2025-05-27 14:09:43','2025-05-27 14:09:43'),(13,20,'2025-05-27 14:10:12','127.0.0.1',1,'2025-05-27 14:10:12','2025-05-27 14:10:12'),(14,18,'2025-05-27 14:10:15','127.0.0.1',1,'2025-05-27 14:10:15','2025-05-27 14:10:15'),(15,19,'2025-05-28 08:42:52','127.0.0.1',1,'2025-05-28 08:42:52','2025-05-28 08:42:52'),(16,20,'2025-05-28 08:43:03','127.0.0.1',1,'2025-05-28 08:43:03','2025-05-28 08:43:03'),(17,16,'2025-05-28 08:43:58','127.0.0.1',1,'2025-05-28 08:43:58','2025-05-28 08:43:58'),(18,18,'2025-05-28 08:57:26','127.0.0.1',1,'2025-05-28 08:57:26','2025-05-28 08:57:26'),(19,19,'2025-05-28 13:57:47','127.0.0.1',1,'2025-05-28 13:57:47','2025-05-28 13:57:47'),(20,16,'2025-05-28 13:57:59','127.0.0.1',1,'2025-05-28 13:57:59','2025-05-28 13:57:59'),(21,20,'2025-05-28 13:58:03','127.0.0.1',1,'2025-05-28 13:58:03','2025-05-28 13:58:03'),(22,18,'2025-05-28 13:58:05','127.0.0.1',1,'2025-05-28 13:58:05','2025-05-28 13:58:05'),(23,19,'2025-05-28 16:10:47','127.0.0.1',1,'2025-05-28 16:10:47','2025-05-28 16:10:47'),(24,18,'2025-05-28 16:11:05','127.0.0.1',1,'2025-05-28 16:11:05','2025-05-28 16:11:05'),(25,16,'2025-05-28 16:11:18','127.0.0.1',1,'2025-05-28 16:11:18','2025-05-28 16:11:18'),(26,20,'2025-05-28 23:49:54','127.0.0.1',1,'2025-05-28 23:49:54','2025-05-28 23:49:54'),(27,19,'2025-05-30 01:18:45','127.0.0.1',1,'2025-05-30 01:18:45','2025-05-30 01:18:45'),(28,20,'2025-05-30 01:24:13','127.0.0.1',1,'2025-05-30 01:24:13','2025-05-30 01:24:13'),(29,16,'2025-05-30 02:04:37','127.0.0.1',1,'2025-05-30 02:04:37','2025-05-30 02:04:37'),(30,19,'2025-05-30 13:53:23','127.0.0.1',1,'2025-05-30 13:53:23','2025-05-30 13:53:23'),(31,16,'2025-05-30 14:21:08','127.0.0.1',1,'2025-05-30 14:21:08','2025-05-30 14:21:08'),(32,20,'2025-05-30 15:12:02','127.0.0.1',1,'2025-05-30 15:12:02','2025-05-30 15:12:02'),(33,19,'2025-05-31 01:03:10','127.0.0.1',1,'2025-05-31 01:03:10','2025-05-31 01:03:10'),(34,20,'2025-05-31 02:42:21','127.0.0.1',1,'2025-05-31 02:42:21','2025-05-31 02:42:21'),(35,19,'2025-05-31 07:05:06','127.0.0.1',1,'2025-05-31 07:05:06','2025-05-31 07:05:06'),(36,16,'2025-05-31 07:05:31','127.0.0.1',1,'2025-05-31 07:05:31','2025-05-31 07:05:31'),(37,18,'2025-05-31 07:06:16','127.0.0.1',1,'2025-05-31 07:06:16','2025-05-31 07:06:16'),(38,19,'2025-06-01 06:40:50','127.0.0.1',1,'2025-06-01 06:40:50','2025-06-01 06:40:50'),(39,19,'2025-06-01 14:15:36','127.0.0.1',1,'2025-06-01 14:15:36','2025-06-01 14:15:36'),(40,20,'2025-06-01 17:21:06','127.0.0.1',1,'2025-06-01 17:21:06','2025-06-01 17:21:06');
/*!40000 ALTER TABLE `user_access_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_bans`
--

DROP TABLE IF EXISTS `user_bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_bans` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_bans`
--

LOCK TABLES `user_bans` WRITE;
/*!40000 ALTER TABLE `user_bans` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_bans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Đặng Hương Giang','giangdh@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'pending',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(2,'Nguyễn Đình Trinh','trinhnd@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'inactive',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(3,'Nguyễn Thu Nga','ngant@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(4,'Đặng Ngọc Duyên','duyendn@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(5,'Bùi Anh Tú','tuba@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'inactive',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(6,'Nguyễn Minh Tuấn','tuannm@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(7,'Phan Thị Trang Nhung','nhungptt@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(8,'Chu Ngọc Thúy','thuycn@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'inactive',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(9,'Nguyễn Hoàng Hà','hanh@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'pending',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(10,'Nguyễn Văn Hưng','hungnv@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'pending',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(11,'Đỗ Lân','land@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(12,'Tạ Quang Chiểu','chieutq@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'inactive',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(13,'Võ Tá Hoàng','hoangvt@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(14,'Trần Tuấn Minh','minhtt@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(15,'Trần Hà Trang','trangth@tlu.edu.vn',0,NULL,NULL,NULL,'test',NULL,NULL,NULL,3,NULL,NULL,NULL,'pending',NULL,NULL,0,NULL,NULL,'2025-05-26 16:45:29','2025-05-26 16:45:29'),(16,'Vũ Ngọc Sơn','sonvn@tlu.edu.vn',1,'2025-05-26 16:45:51',NULL,NULL,'$2y$12$JWiU.147RIVZfHA65ktmc.E4dQvKIbt.uXGOn6buD144mM2kzfGcm',NULL,NULL,NULL,1,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,NULL,NULL),(17,'Trần Minh 2','minh@tlu.edu.vn',1,'2025-05-26 16:45:52',NULL,NULL,'$2y$12$w8yuHRU0bRukx9rUUlv.Su7DXrFuQyQ7zbzrnbciWX.5SfDNZCQ9u',NULL,NULL,NULL,1,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,NULL,NULL),(18,'Diệp Hoàng Trúc Mai','longrm31@gmail.com',1,'2025-05-26 17:39:29',NULL,NULL,'$2y$12$SfjF8skTnEejsKNIWqwI3uBYFfew.KJ3bN6zcqwHCI3zBR58It8E2',NULL,NULL,NULL,3,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 17:39:12','2025-05-26 17:50:13'),(19,'Ngọc Thúy','hotboylh2@gmail.com',1,'2025-05-26 17:46:46',NULL,NULL,'$2y$12$lSbULjFVhRGLhNUcIg/avuJCydjnZW6vlwTVxE5.Js2uHO6yNH8ca',NULL,NULL,NULL,3,NULL,'19.jpg',NULL,'active','','2025-05-26 17:43:11',0,NULL,NULL,'2025-05-26 17:43:11','2025-05-26 17:50:00'),(20,'Hải Nam','namrm7@gmail.com',1,'2025-05-26 17:54:37',NULL,NULL,'$2y$12$gGHnCmbliAXQqJvR9foF7.9JrcyMEqImB/7X8MWtJL9Nwk8wj.Ksm',NULL,NULL,NULL,2,NULL,NULL,NULL,'active',NULL,NULL,0,NULL,NULL,'2025-05-26 17:54:15','2025-05-26 17:54:56');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-02 16:28:17
