-- MySQL dump 10.13  Distrib 8.4.9, for Linux (x86_64)
--
-- Host: localhost    Database: u7942055_cbn
-- ------------------------------------------------------
-- Server version	8.4.9

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
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shipments_tracking_number_unique` (`tracking_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipments`
--

LOCK TABLES `shipments` WRITE;
/*!40000 ALTER TABLE `shipments` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tracking_histories`
--

DROP TABLE IF EXISTS `tracking_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tracking_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `updated_by` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tracking_histories_shipment_id_foreign` (`shipment_id`),
  KEY `tracking_histories_updated_by_foreign` (`updated_by`),
  CONSTRAINT `tracking_histories_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tracking_histories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tracking_histories`
--

LOCK TABLES `tracking_histories` WRITE;
/*!40000 ALTER TABLE `tracking_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tracking_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_commissions`
--

DROP TABLE IF EXISTS `agent_commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agent_commissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` int unsigned NOT NULL,
  `shipment_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_commissions_agent_id_foreign` (`agent_id`),
  KEY `agent_commissions_shipment_id_foreign` (`shipment_id`),
  CONSTRAINT `agent_commissions_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `user` (`id`),
  CONSTRAINT `agent_commissions_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_commissions`
--

LOCK TABLES `agent_commissions` WRITE;
/*!40000 ALTER TABLE `agent_commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `agent_commissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-07  4:39:55
