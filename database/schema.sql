-- MySQL dump 10.13  Distrib 9.1.0, for Win64 (x86_64)
--
-- Host: localhost    Database: dbproject
-- ------------------------------------------------------
-- Server version	9.1.0

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
-- Table structure for table `business`
--

DROP TABLE IF EXISTS `business`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business` (
  `B_id` int unsigned NOT NULL AUTO_INCREMENT,
  `B_contact` varchar(255) NOT NULL,
  `B_NIP` varchar(20) NOT NULL,
  `B_name` varchar(255) NOT NULL,
  `B_photo` varchar(255) NOT NULL,
  PRIMARY KEY (`B_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `C_id` int unsigned NOT NULL AUTO_INCREMENT,
  `C_Name` varchar(255) NOT NULL,
  PRIMARY KEY (`C_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `entire_order`
--

DROP TABLE IF EXISTS `entire_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entire_order` (
  `EO_id` int unsigned NOT NULL AUTO_INCREMENT,
  `EO_rent_id` int unsigned NOT NULL,
  `EO_eq_id` int unsigned NOT NULL,
  PRIMARY KEY (`EO_id`),
  KEY `EO_rent_id` (`EO_rent_id`),
  KEY `EO_eq_id` (`EO_eq_id`),
  CONSTRAINT `entire_order_ibfk_1` FOREIGN KEY (`EO_rent_id`) REFERENCES `rent` (`R_id`),
  CONSTRAINT `entire_order_ibfk_2` FOREIGN KEY (`EO_eq_id`) REFERENCES `equipment` (`E_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment` (
  `E_id` int unsigned NOT NULL AUTO_INCREMENT,
  `E_producer` int unsigned NOT NULL,
  `E_category` int unsigned NOT NULL,
  `E_size` float NOT NULL,
  `E_price` decimal(10,2) NOT NULL,
  `E_if_rent` enum('Dost─Öpny','Wynaj─Öty','Zarezerwowany') NOT NULL DEFAULT 'Dost─Öpny',
  `E_photo` varchar(255) NOT NULL,
  `E_rent_price_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`E_id`),
  KEY `E_producer` (`E_producer`),
  KEY `E_category` (`E_category`),
  KEY `equipment_ibfk_3` (`E_rent_price_id`),
  CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`E_producer`) REFERENCES `business` (`B_id`),
  CONSTRAINT `equipment_ibfk_2` FOREIGN KEY (`E_category`) REFERENCES `category` (`C_id`),
  CONSTRAINT `equipment_ibfk_3` FOREIGN KEY (`E_rent_price_id`) REFERENCES `rent_price` (`RP_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `N_id` int unsigned NOT NULL AUTO_INCREMENT,
  `N_title` varchar(255) NOT NULL,
  `N_content` text NOT NULL,
  `N_creator` int unsigned NOT NULL,
  `N_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`N_id`),
  KEY `N_creator` (`N_creator`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`N_creator`) REFERENCES `users` (`U_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rent`
--

DROP TABLE IF EXISTS `rent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rent` (
  `R_id` int unsigned NOT NULL AUTO_INCREMENT,
  `R_user_id` int unsigned NOT NULL,
  `R_date_rental` date NOT NULL,
  `R_date_submission` date DEFAULT NULL,
  `R_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`R_id`),
  KEY `R_user_id` (`R_user_id`),
  CONSTRAINT `rent_ibfk_1` FOREIGN KEY (`R_user_id`) REFERENCES `users` (`U_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rent_price`
--

DROP TABLE IF EXISTS `rent_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rent_price` (
  `RP_id` int unsigned NOT NULL AUTO_INCREMENT,
  `RP_price_of_eq` decimal(10,2) NOT NULL,
  `RP_category` int unsigned NOT NULL,
  PRIMARY KEY (`RP_id`),
  KEY `RP_category` (`RP_category`),
  CONSTRAINT `rent_price_ibfk_1` FOREIGN KEY (`RP_category`) REFERENCES `category` (`C_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `S_id` int unsigned NOT NULL AUTO_INCREMENT,
  `S_user` int unsigned NOT NULL,
  `S_price` int unsigned NOT NULL,
  `S_date_in` date NOT NULL COMMENT 'Data przyj─Öcia',
  `S_date_out` date DEFAULT NULL COMMENT 'Data wydania',
  `S_status` enum('Przyj─Öty','Gotowy','Wydany') NOT NULL DEFAULT 'Przyj─Öty',
  `S_operation` int unsigned NOT NULL,
  PRIMARY KEY (`S_id`),
  KEY `S_user` (`S_user`),
  KEY `S_price` (`S_price`),
  CONSTRAINT `service_ibfk_1` FOREIGN KEY (`S_user`) REFERENCES `users` (`U_id`),
  CONSTRAINT `service_ibfk_2` FOREIGN KEY (`S_price`) REFERENCES `service_price` (`SP_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service_price`
--

DROP TABLE IF EXISTS `service_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_price` (
  `SP_id` int unsigned NOT NULL AUTO_INCREMENT,
  `SP_service` varchar(255) NOT NULL,
  `SP_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`SP_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `U_id` int unsigned NOT NULL AUTO_INCREMENT,
  `U_name` varchar(255) NOT NULL,
  `U_surname` varchar(255) NOT NULL,
  `U_password` varchar(255) NOT NULL,
  `U_mail` varchar(255) NOT NULL,
  `U_role` tinyint(1) NOT NULL,
  `U_photo` varchar(255) NOT NULL,
  PRIMARY KEY (`U_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-17 23:22:44
