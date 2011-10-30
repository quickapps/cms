-- MySQL dump 10.13  Distrib 5.5.8, for Win32 (x86)
--
-- Host: localhost    Database: quickapps
-- ------------------------------------------------------
-- Server version	5.5.8

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
-- Table structure for table `#__modules`
--

DROP TABLE IF EXISTS `#__modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__modules` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'machine name',
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'module' COMMENT 'module or theme',
  `settings` text COLLATE utf8_unicode_ci COMMENT 'serialized extra data',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__modules`
--

LOCK TABLES `#__modules` WRITE;
/*!40000 ALTER TABLE `#__modules` DISABLE KEYS */;
INSERT INTO `#__modules` VALUES ('Block','module','',1),('Comment','module','',1),('Field','module','',1),('Locale','module','',1),('Menu','module','',1),('Node','module','',1),('System','module','',1),('Taxonomy','module','',1),('ThemeAdminDefault','theme','a:4:{s:9:\"site_logo\";s:1:\"1\";s:9:\"site_name\";s:1:\"1\";s:11:\"site_slogan\";s:1:\"1\";s:12:\"site_favicon\";s:1:\"1\";}',1),('ThemeDefault','theme','a:7:{s:13:\"slider_folder\";s:6:\"slider\";s:9:\"site_logo\";s:1:\"1\";s:9:\"site_name\";s:1:\"0\";s:11:\"site_slogan\";s:1:\"1\";s:12:\"site_favicon\";s:1:\"1\";s:16:\"color_header_top\";s:7:\"#282727\";s:19:\"color_header_bottom\";s:7:\"#332f2f\";}',1),('User','module','',1);
/*!40000 ALTER TABLE `#__modules` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-10-30 14:46:35
