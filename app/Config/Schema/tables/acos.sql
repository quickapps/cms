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
-- Table structure for table `#__acos`
--

DROP TABLE IF EXISTS `#__acos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__acos`
--

LOCK TABLES `#__acos` WRITE;
/*!40000 ALTER TABLE `#__acos` DISABLE KEYS */;
INSERT INTO `#__acos` VALUES (1,NULL,NULL,NULL,'Block',1,20),(2,1,NULL,NULL,'Block',2,5),(3,2,NULL,NULL,'admin_index',3,4),(4,1,NULL,NULL,'Manage',6,19),(5,4,NULL,NULL,'admin_index',7,8),(6,4,NULL,NULL,'admin_move',9,10),(7,4,NULL,NULL,'admin_clone',11,12),(8,4,NULL,NULL,'admin_edit',13,14),(9,4,NULL,NULL,'admin_add',15,16),(10,4,NULL,NULL,'admin_delete',17,18),(11,NULL,NULL,NULL,'Comment',21,34),(12,11,NULL,NULL,'Comment',22,25),(13,12,NULL,NULL,'admin_index',23,24),(14,11,NULL,NULL,'Published',26,29),(15,14,NULL,NULL,'admin_index',27,28),(16,11,NULL,NULL,'Unpublished',30,33),(17,16,NULL,NULL,'admin_index',31,32),(18,NULL,NULL,NULL,'Field',35,42),(19,18,NULL,NULL,'Handler',36,41),(20,19,NULL,NULL,'admin_delete',37,38),(21,19,NULL,NULL,'admin_move',39,40),(22,NULL,NULL,NULL,'FieldFile',43,50),(23,22,NULL,NULL,'Uploadify',44,49),(24,23,NULL,NULL,'delete',45,46),(25,23,NULL,NULL,'upload',47,48),(26,NULL,NULL,NULL,'Locale',51,90),(27,26,NULL,NULL,'Languages',52,63),(28,27,NULL,NULL,'admin_index',53,54),(29,27,NULL,NULL,'admin_set_default',55,56),(30,27,NULL,NULL,'admin_add',57,58),(31,27,NULL,NULL,'admin_edit',59,60),(32,27,NULL,NULL,'admin_delete',61,62),(33,26,NULL,NULL,'Locale',64,67),(34,33,NULL,NULL,'admin_index',65,66),(35,26,NULL,NULL,'Packages',68,77),(36,35,NULL,NULL,'admin_index',69,70),(37,35,NULL,NULL,'admin_download_package',71,72),(38,35,NULL,NULL,'admin_uninstall',73,74),(39,35,NULL,NULL,'admin_install',75,76),(40,26,NULL,NULL,'Translations',78,89),(41,40,NULL,NULL,'admin_index',79,80),(42,40,NULL,NULL,'admin_list',81,82),(43,40,NULL,NULL,'admin_edit',83,84),(44,40,NULL,NULL,'admin_add',85,86),(45,40,NULL,NULL,'admin_delete',87,88),(46,NULL,NULL,NULL,'Menu',91,114),(47,46,NULL,NULL,'Manage',92,109),(48,47,NULL,NULL,'admin_index',93,94),(49,47,NULL,NULL,'admin_delete',95,96),(50,47,NULL,NULL,'admin_add',97,98),(51,47,NULL,NULL,'admin_edit',99,100),(52,47,NULL,NULL,'admin_delete_link',101,102),(53,47,NULL,NULL,'admin_add_link',103,104),(54,47,NULL,NULL,'admin_edit_link',105,106),(55,47,NULL,NULL,'admin_links',107,108),(56,46,NULL,NULL,'Menu',110,113),(57,56,NULL,NULL,'admin_index',111,112),(58,NULL,NULL,NULL,'Node',115,158),(59,58,NULL,NULL,'Contents',116,129),(60,59,NULL,NULL,'admin_index',117,118),(61,59,NULL,NULL,'admin_edit',119,120),(62,59,NULL,NULL,'admin_create',121,122),(63,59,NULL,NULL,'admin_add',123,124),(64,59,NULL,NULL,'admin_delete',125,126),(65,59,NULL,NULL,'admin_clear_cache',127,128),(66,58,NULL,NULL,'Node',130,139),(67,66,NULL,NULL,'admin_index',131,132),(68,66,NULL,NULL,'index',133,134),(69,66,NULL,NULL,'details',135,136),(70,66,NULL,NULL,'search',137,138),(71,58,NULL,NULL,'Types',140,157),(72,71,NULL,NULL,'admin_index',141,142),(73,71,NULL,NULL,'admin_edit',143,144),(74,71,NULL,NULL,'admin_add',145,146),(75,71,NULL,NULL,'admin_delete',147,148),(76,71,NULL,NULL,'admin_display',149,150),(77,71,NULL,NULL,'admin_field_settings',151,152),(78,71,NULL,NULL,'admin_field_formatter',153,154),(79,71,NULL,NULL,'admin_fields',155,156),(80,NULL,NULL,NULL,'System',159,208),(81,80,NULL,NULL,'Configuration',160,163),(82,81,NULL,NULL,'admin_index',161,162),(83,80,NULL,NULL,'Dashboard',164,167),(84,83,NULL,NULL,'admin_index',165,166),(85,80,NULL,NULL,'Help',168,173),(86,85,NULL,NULL,'admin_index',169,170),(87,85,NULL,NULL,'admin_module',171,172),(88,80,NULL,NULL,'Modules',174,185),(89,88,NULL,NULL,'admin_index',175,176),(90,88,NULL,NULL,'admin_settings',177,178),(91,88,NULL,NULL,'admin_toggle',179,180),(92,88,NULL,NULL,'admin_uninstall',181,182),(93,88,NULL,NULL,'admin_install',183,184),(94,80,NULL,NULL,'Structure',186,189),(95,94,NULL,NULL,'admin_index',187,188),(96,80,NULL,NULL,'System',190,193),(97,96,NULL,NULL,'admin_index',191,192),(98,80,NULL,NULL,'Themes',194,207),(99,98,NULL,NULL,'admin_index',195,196),(100,98,NULL,NULL,'admin_set_theme',197,198),(101,98,NULL,NULL,'admin_settings',199,200),(102,98,NULL,NULL,'admin_uninstall',201,202),(103,98,NULL,NULL,'admin_install',203,204),(104,98,NULL,NULL,'admin_theme_tn',205,206),(105,NULL,NULL,NULL,'Taxonomy',209,232),(106,105,NULL,NULL,'Taxonomy',210,213),(107,106,NULL,NULL,'admin_index',211,212),(108,105,NULL,NULL,'Vocabularies',214,231),(109,108,NULL,NULL,'admin_index',215,216),(110,108,NULL,NULL,'admin_add',217,218),(111,108,NULL,NULL,'admin_move',219,220),(112,108,NULL,NULL,'admin_edit',221,222),(113,108,NULL,NULL,'admin_delete',223,224),(114,108,NULL,NULL,'admin_terms',225,226),(115,108,NULL,NULL,'admin_delete_term',227,228),(116,108,NULL,NULL,'admin_edit_term',229,230),(117,NULL,NULL,NULL,'User',233,298),(118,117,NULL,NULL,'Display',234,239),(119,118,NULL,NULL,'admin_index',235,236),(120,118,NULL,NULL,'admin_field_formatter',237,238),(121,117,NULL,NULL,'Fields',240,245),(122,121,NULL,NULL,'admin_index',241,242),(123,121,NULL,NULL,'admin_field_settings',243,244),(124,117,NULL,NULL,'List',246,259),(125,124,NULL,NULL,'admin_index',247,248),(126,124,NULL,NULL,'admin_delete',249,250),(127,124,NULL,NULL,'admin_block',251,252),(128,124,NULL,NULL,'admin_activate',253,254),(129,124,NULL,NULL,'admin_add',255,256),(130,124,NULL,NULL,'admin_edit',257,258),(131,117,NULL,NULL,'Permissions',260,267),(132,131,NULL,NULL,'admin_index',261,262),(133,131,NULL,NULL,'admin_edit',263,264),(134,131,NULL,NULL,'admin_toggle',265,266),(135,117,NULL,NULL,'Roles',268,275),(136,135,NULL,NULL,'admin_index',269,270),(137,135,NULL,NULL,'admin_edit',271,272),(138,135,NULL,NULL,'admin_delete',273,274),(139,117,NULL,NULL,'User',276,297),(140,139,NULL,NULL,'admin_index',277,278),(141,139,NULL,NULL,'login',279,280),(142,139,NULL,NULL,'logout',281,282),(143,139,NULL,NULL,'admin_login',283,284),(144,139,NULL,NULL,'admin_logout',285,286),(145,139,NULL,NULL,'register',287,288),(146,139,NULL,NULL,'activate',289,290),(147,139,NULL,NULL,'password_recovery',291,292),(148,139,NULL,NULL,'profile',293,294),(149,139,NULL,NULL,'my_account',295,296);
/*!40000 ALTER TABLE `#__acos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-10-30 14:46:34
