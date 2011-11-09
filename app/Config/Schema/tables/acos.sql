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
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__acos`
--

LOCK TABLES `#__acos` WRITE;
/*!40000 ALTER TABLE `#__acos` DISABLE KEYS */;
INSERT INTO `#__acos` VALUES (1,NULL,NULL,NULL,'Block',1,20),(2,1,NULL,NULL,'Block',2,5),(3,2,NULL,NULL,'admin_index',3,4),(4,1,NULL,NULL,'Manage',6,19),(5,4,NULL,NULL,'admin_index',7,8),(6,4,NULL,NULL,'admin_move',9,10),(7,4,NULL,NULL,'admin_clone',11,12),(8,4,NULL,NULL,'admin_edit',13,14),(9,4,NULL,NULL,'admin_add',15,16),(10,4,NULL,NULL,'admin_delete',17,18),(11,NULL,NULL,NULL,'Comment',21,38),(12,11,NULL,NULL,'Comment',22,25),(13,12,NULL,NULL,'admin_index',23,24),(14,11,NULL,NULL,'List',26,37),(15,14,NULL,NULL,'admin_show',27,28),(16,14,NULL,NULL,'admin_view',29,30),(17,14,NULL,NULL,'admin_approve',31,32),(18,14,NULL,NULL,'admin_delete',33,34),(19,14,NULL,NULL,'admin_unapprove',35,36),(20,NULL,NULL,NULL,'Field',39,46),(21,20,NULL,NULL,'Handler',40,45),(22,21,NULL,NULL,'admin_delete',41,42),(23,21,NULL,NULL,'admin_move',43,44),(24,NULL,NULL,NULL,'FieldFile',47,54),(25,24,NULL,NULL,'Uploadify',48,53),(26,25,NULL,NULL,'delete',49,50),(27,25,NULL,NULL,'upload',51,52),(28,NULL,NULL,NULL,'Locale',55,94),(29,28,NULL,NULL,'Languages',56,67),(30,29,NULL,NULL,'admin_index',57,58),(31,29,NULL,NULL,'admin_set_default',59,60),(32,29,NULL,NULL,'admin_add',61,62),(33,29,NULL,NULL,'admin_edit',63,64),(34,29,NULL,NULL,'admin_delete',65,66),(35,28,NULL,NULL,'Locale',68,71),(36,35,NULL,NULL,'admin_index',69,70),(37,28,NULL,NULL,'Packages',72,81),(38,37,NULL,NULL,'admin_index',73,74),(39,37,NULL,NULL,'admin_download_package',75,76),(40,37,NULL,NULL,'admin_uninstall',77,78),(41,37,NULL,NULL,'admin_install',79,80),(42,28,NULL,NULL,'Translations',82,93),(43,42,NULL,NULL,'admin_index',83,84),(44,42,NULL,NULL,'admin_list',85,86),(45,42,NULL,NULL,'admin_edit',87,88),(46,42,NULL,NULL,'admin_add',89,90),(47,42,NULL,NULL,'admin_delete',91,92),(48,NULL,NULL,NULL,'Menu',95,118),(49,48,NULL,NULL,'Manage',96,113),(50,49,NULL,NULL,'admin_index',97,98),(51,49,NULL,NULL,'admin_delete',99,100),(52,49,NULL,NULL,'admin_add',101,102),(53,49,NULL,NULL,'admin_edit',103,104),(54,49,NULL,NULL,'admin_delete_link',105,106),(55,49,NULL,NULL,'admin_add_link',107,108),(56,49,NULL,NULL,'admin_edit_link',109,110),(57,49,NULL,NULL,'admin_links',111,112),(58,48,NULL,NULL,'Menu',114,117),(59,58,NULL,NULL,'admin_index',115,116),(60,NULL,NULL,NULL,'Node',119,162),(61,60,NULL,NULL,'Contents',120,133),(62,61,NULL,NULL,'admin_index',121,122),(63,61,NULL,NULL,'admin_edit',123,124),(64,61,NULL,NULL,'admin_create',125,126),(65,61,NULL,NULL,'admin_add',127,128),(66,61,NULL,NULL,'admin_delete',129,130),(67,61,NULL,NULL,'admin_clear_cache',131,132),(68,60,NULL,NULL,'Node',134,143),(69,68,NULL,NULL,'admin_index',135,136),(70,68,NULL,NULL,'index',137,138),(71,68,NULL,NULL,'details',139,140),(72,68,NULL,NULL,'search',141,142),(73,60,NULL,NULL,'Types',144,161),(74,73,NULL,NULL,'admin_index',145,146),(75,73,NULL,NULL,'admin_edit',147,148),(76,73,NULL,NULL,'admin_add',149,150),(77,73,NULL,NULL,'admin_delete',151,152),(78,73,NULL,NULL,'admin_display',153,154),(79,73,NULL,NULL,'admin_field_settings',155,156),(80,73,NULL,NULL,'admin_field_formatter',157,158),(81,73,NULL,NULL,'admin_fields',159,160),(82,NULL,NULL,NULL,'System',163,212),(83,82,NULL,NULL,'Configuration',164,167),(84,83,NULL,NULL,'admin_index',165,166),(85,82,NULL,NULL,'Dashboard',168,171),(86,85,NULL,NULL,'admin_index',169,170),(87,82,NULL,NULL,'Help',172,177),(88,87,NULL,NULL,'admin_index',173,174),(89,87,NULL,NULL,'admin_module',175,176),(90,82,NULL,NULL,'Modules',178,189),(91,90,NULL,NULL,'admin_index',179,180),(92,90,NULL,NULL,'admin_settings',181,182),(93,90,NULL,NULL,'admin_toggle',183,184),(94,90,NULL,NULL,'admin_uninstall',185,186),(95,90,NULL,NULL,'admin_install',187,188),(96,82,NULL,NULL,'Structure',190,193),(97,96,NULL,NULL,'admin_index',191,192),(98,82,NULL,NULL,'System',194,197),(99,98,NULL,NULL,'admin_index',195,196),(100,82,NULL,NULL,'Themes',198,211),(101,100,NULL,NULL,'admin_index',199,200),(102,100,NULL,NULL,'admin_set_theme',201,202),(103,100,NULL,NULL,'admin_settings',203,204),(104,100,NULL,NULL,'admin_uninstall',205,206),(105,100,NULL,NULL,'admin_install',207,208),(106,100,NULL,NULL,'admin_theme_tn',209,210),(107,NULL,NULL,NULL,'Taxonomy',213,236),(108,107,NULL,NULL,'Taxonomy',214,217),(109,108,NULL,NULL,'admin_index',215,216),(110,107,NULL,NULL,'Vocabularies',218,235),(111,110,NULL,NULL,'admin_index',219,220),(112,110,NULL,NULL,'admin_add',221,222),(113,110,NULL,NULL,'admin_move',223,224),(114,110,NULL,NULL,'admin_edit',225,226),(115,110,NULL,NULL,'admin_delete',227,228),(116,110,NULL,NULL,'admin_terms',229,230),(117,110,NULL,NULL,'admin_delete_term',231,232),(118,110,NULL,NULL,'admin_edit_term',233,234),(119,NULL,NULL,NULL,'User',237,302),(120,119,NULL,NULL,'Display',238,243),(121,120,NULL,NULL,'admin_index',239,240),(122,120,NULL,NULL,'admin_field_formatter',241,242),(123,119,NULL,NULL,'Fields',244,249),(124,123,NULL,NULL,'admin_index',245,246),(125,123,NULL,NULL,'admin_field_settings',247,248),(126,119,NULL,NULL,'List',250,263),(127,126,NULL,NULL,'admin_index',251,252),(128,126,NULL,NULL,'admin_delete',253,254),(129,126,NULL,NULL,'admin_block',255,256),(130,126,NULL,NULL,'admin_activate',257,258),(131,126,NULL,NULL,'admin_add',259,260),(132,126,NULL,NULL,'admin_edit',261,262),(133,119,NULL,NULL,'Permissions',264,271),(134,133,NULL,NULL,'admin_index',265,266),(135,133,NULL,NULL,'admin_edit',267,268),(136,133,NULL,NULL,'admin_toggle',269,270),(137,119,NULL,NULL,'Roles',272,279),(138,137,NULL,NULL,'admin_index',273,274),(139,137,NULL,NULL,'admin_edit',275,276),(140,137,NULL,NULL,'admin_delete',277,278),(141,119,NULL,NULL,'User',280,301),(142,141,NULL,NULL,'admin_index',281,282),(143,141,NULL,NULL,'login',283,284),(144,141,NULL,NULL,'logout',285,286),(145,141,NULL,NULL,'admin_login',287,288),(146,141,NULL,NULL,'admin_logout',289,290),(147,141,NULL,NULL,'register',291,292),(148,141,NULL,NULL,'activate',293,294),(149,141,NULL,NULL,'password_recovery',295,296),(150,141,NULL,NULL,'profile',297,298),(151,141,NULL,NULL,'my_account',299,300);
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

-- Dump completed on 2011-11-09 14:46:08
