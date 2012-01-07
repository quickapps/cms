-- MySQL dump 10.13  Distrib 5.5.16, for Win64 (x86)
--
-- Host: localhost    Database: #__dev
-- ------------------------------------------------------
-- Server version	5.5.16-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `#__block_regions`
--

DROP TABLE IF EXISTS `#__block_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__block_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL,
  `theme` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`block_id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__block_regions`
--

LOCK TABLES `#__block_regions` WRITE;
/*!40000 ALTER TABLE `#__block_regions` DISABLE KEYS */;
INSERT INTO `#__block_regions` VALUES (8,10,'Admin','dashboard_main',1),(9,10,'Default','dashboard_main',1),(13,4,'Admin','management-menu',1),(14,4,'Default','management-menu',1),(18,3,'Admin','footer',1),(48,3,'Default','footer',1),(131,6,'Default','main-menu',1),(133,26,'Default','slider',1),(140,9,'Default','language-switcher',1),(151,19,'Default','services-left',1),(153,20,'Default','services-center',1),(155,25,'Default','services-right',1),(157,15,'Default','search',1),(159,12,'Default','services-left',2),(161,11,'Default','services-center',2),(163,13,'Default','services-right',2),(165,14,'Default','slider',2),(166,15,'Admin','dashboard_sidebar',1),(169,7,'Admin','dashboard_sidebar',2),(172,5,'Default','user-menu',2),(173,16,'Admin','',1),(174,16,'Default','sidebar-left',1),(175,2,'Admin','',2),(176,2,'Default','sidebar-left',2),(177,11,'Admin','',3),(178,12,'Admin','',4),(179,13,'Admin','',5);
/*!40000 ALTER TABLE `#__block_regions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-07  2:18:51
