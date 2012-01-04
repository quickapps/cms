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
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__acos`
--

LOCK TABLES `#__acos` WRITE;
/*!40000 ALTER TABLE `#__acos` DISABLE KEYS */;
INSERT INTO `#__acos` VALUES (1,NULL,NULL,NULL,'Block',1,20),(2,1,NULL,NULL,'Block',2,5),(3,2,NULL,NULL,'admin_index',3,4),(4,1,NULL,NULL,'Manage',6,19),(5,4,NULL,NULL,'admin_index',7,8),(6,4,NULL,NULL,'admin_move',9,10),(7,4,NULL,NULL,'admin_clone',11,12),(8,4,NULL,NULL,'admin_edit',13,14),(9,4,NULL,NULL,'admin_add',15,16),(10,4,NULL,NULL,'admin_delete',17,18),(11,NULL,NULL,NULL,'Comment',21,38),(12,11,NULL,NULL,'Comment',22,25),(13,12,NULL,NULL,'admin_index',23,24),(14,11,NULL,NULL,'List',26,37),(15,14,NULL,NULL,'admin_show',27,28),(16,14,NULL,NULL,'admin_view',29,30),(17,14,NULL,NULL,'admin_approve',31,32),(18,14,NULL,NULL,'admin_unapprove',33,34),(19,14,NULL,NULL,'admin_delete',35,36),(20,NULL,NULL,NULL,'Field',39,46),(21,20,NULL,NULL,'Handler',40,45),(22,21,NULL,NULL,'admin_delete',41,42),(23,21,NULL,NULL,'admin_move',43,44),(24,NULL,NULL,NULL,'FieldFile',47,54),(25,24,NULL,NULL,'Uploadify',48,53),(26,25,NULL,NULL,'delete',49,50),(27,25,NULL,NULL,'upload',51,52),(28,NULL,NULL,NULL,'Locale',55,98),(29,28,NULL,NULL,'Languages',56,69),(30,29,NULL,NULL,'admin_index',57,58),(31,29,NULL,NULL,'admin_set_default',59,60),(32,29,NULL,NULL,'admin_add',61,62),(33,29,NULL,NULL,'admin_edit',63,64),(34,29,NULL,NULL,'admin_move',65,66),(35,29,NULL,NULL,'admin_delete',67,68),(36,28,NULL,NULL,'Locale',70,73),(37,36,NULL,NULL,'admin_index',71,72),(38,28,NULL,NULL,'Packages',74,83),(39,38,NULL,NULL,'admin_index',75,76),(40,38,NULL,NULL,'admin_download_package',77,78),(41,38,NULL,NULL,'admin_uninstall',79,80),(42,38,NULL,NULL,'admin_install',81,82),(43,28,NULL,NULL,'Translations',84,97),(44,43,NULL,NULL,'admin_index',85,86),(45,43,NULL,NULL,'admin_list',87,88),(46,43,NULL,NULL,'admin_edit',89,90),(47,43,NULL,NULL,'admin_add',91,92),(48,43,NULL,NULL,'admin_regenerate',93,94),(49,43,NULL,NULL,'admin_delete',95,96),(50,NULL,NULL,NULL,'Menu',99,122),(51,50,NULL,NULL,'Manage',100,117),(52,51,NULL,NULL,'admin_index',101,102),(53,51,NULL,NULL,'admin_delete',103,104),(54,51,NULL,NULL,'admin_add',105,106),(55,51,NULL,NULL,'admin_edit',107,108),(56,51,NULL,NULL,'admin_delete_link',109,110),(57,51,NULL,NULL,'admin_add_link',111,112),(58,51,NULL,NULL,'admin_edit_link',113,114),(59,51,NULL,NULL,'admin_links',115,116),(60,50,NULL,NULL,'Menu',118,121),(61,60,NULL,NULL,'admin_index',119,120),(62,NULL,NULL,NULL,'Node',123,166),(63,62,NULL,NULL,'Contents',124,137),(64,63,NULL,NULL,'admin_index',125,126),(65,63,NULL,NULL,'admin_edit',127,128),(66,63,NULL,NULL,'admin_create',129,130),(67,63,NULL,NULL,'admin_add',131,132),(68,63,NULL,NULL,'admin_delete',133,134),(69,63,NULL,NULL,'admin_clear_cache',135,136),(70,62,NULL,NULL,'Node',138,147),(71,70,NULL,NULL,'admin_index',139,140),(72,70,NULL,NULL,'index',141,142),(73,70,NULL,NULL,'details',143,144),(74,70,NULL,NULL,'search',145,146),(75,62,NULL,NULL,'Types',148,165),(76,75,NULL,NULL,'admin_index',149,150),(77,75,NULL,NULL,'admin_edit',151,152),(78,75,NULL,NULL,'admin_add',153,154),(79,75,NULL,NULL,'admin_delete',155,156),(80,75,NULL,NULL,'admin_display',157,158),(81,75,NULL,NULL,'admin_field_settings',159,160),(82,75,NULL,NULL,'admin_field_formatter',161,162),(83,75,NULL,NULL,'admin_fields',163,164),(84,NULL,NULL,NULL,'System',167,216),(85,84,NULL,NULL,'Configuration',168,171),(86,85,NULL,NULL,'admin_index',169,170),(87,84,NULL,NULL,'Dashboard',172,175),(88,87,NULL,NULL,'admin_index',173,174),(89,84,NULL,NULL,'Help',176,181),(90,89,NULL,NULL,'admin_index',177,178),(91,89,NULL,NULL,'admin_module',179,180),(92,84,NULL,NULL,'Modules',182,193),(93,92,NULL,NULL,'admin_index',183,184),(94,92,NULL,NULL,'admin_settings',185,186),(95,92,NULL,NULL,'admin_toggle',187,188),(96,92,NULL,NULL,'admin_uninstall',189,190),(97,92,NULL,NULL,'admin_install',191,192),(98,84,NULL,NULL,'Structure',194,197),(99,98,NULL,NULL,'admin_index',195,196),(100,84,NULL,NULL,'System',198,201),(101,100,NULL,NULL,'admin_index',199,200),(102,84,NULL,NULL,'Themes',202,215),(103,102,NULL,NULL,'admin_index',203,204),(104,102,NULL,NULL,'admin_set_theme',205,206),(105,102,NULL,NULL,'admin_settings',207,208),(106,102,NULL,NULL,'admin_uninstall',209,210),(107,102,NULL,NULL,'admin_install',211,212),(108,102,NULL,NULL,'admin_theme_tn',213,214),(109,NULL,NULL,NULL,'Taxonomy',217,240),(110,109,NULL,NULL,'Taxonomy',218,221),(111,110,NULL,NULL,'admin_index',219,220),(112,109,NULL,NULL,'Vocabularies',222,239),(113,112,NULL,NULL,'admin_index',223,224),(114,112,NULL,NULL,'admin_add',225,226),(115,112,NULL,NULL,'admin_move',227,228),(116,112,NULL,NULL,'admin_edit',229,230),(117,112,NULL,NULL,'admin_delete',231,232),(118,112,NULL,NULL,'admin_terms',233,234),(119,112,NULL,NULL,'admin_delete_term',235,236),(120,112,NULL,NULL,'admin_edit_term',237,238),(121,NULL,NULL,NULL,'User',241,306),(122,121,NULL,NULL,'Display',242,247),(123,122,NULL,NULL,'admin_index',243,244),(124,122,NULL,NULL,'admin_field_formatter',245,246),(125,121,NULL,NULL,'Fields',248,253),(126,125,NULL,NULL,'admin_index',249,250),(127,125,NULL,NULL,'admin_field_settings',251,252),(128,121,NULL,NULL,'List',254,267),(129,128,NULL,NULL,'admin_index',255,256),(130,128,NULL,NULL,'admin_delete',257,258),(131,128,NULL,NULL,'admin_block',259,260),(132,128,NULL,NULL,'admin_activate',261,262),(133,128,NULL,NULL,'admin_add',263,264),(134,128,NULL,NULL,'admin_edit',265,266),(135,121,NULL,NULL,'Permissions',268,275),(136,135,NULL,NULL,'admin_index',269,270),(137,135,NULL,NULL,'admin_edit',271,272),(138,135,NULL,NULL,'admin_toggle',273,274),(139,121,NULL,NULL,'Roles',276,283),(140,139,NULL,NULL,'admin_index',277,278),(141,139,NULL,NULL,'admin_edit',279,280),(142,139,NULL,NULL,'admin_delete',281,282),(143,121,NULL,NULL,'User',284,305),(144,143,NULL,NULL,'admin_index',285,286),(145,143,NULL,NULL,'login',287,288),(146,143,NULL,NULL,'logout',289,290),(147,143,NULL,NULL,'admin_login',291,292),(148,143,NULL,NULL,'admin_logout',293,294),(149,143,NULL,NULL,'register',295,296),(150,143,NULL,NULL,'activate',297,298),(151,143,NULL,NULL,'password_recovery',299,300),(152,143,NULL,NULL,'profile',301,302),(153,143,NULL,NULL,'my_account',303,304),(154,NULL,NULL,NULL,'FieldTerms',307,312),(155,154,NULL,NULL,'Tokeninput',308,311),(156,155,NULL,NULL,'admin_suggest',309,310);
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

-- Dump completed on 2012-01-04 20:26:56
