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
-- Table structure for table `#__fields`
--

DROP TABLE IF EXISTS `#__fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for a field',
  `name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The name of this field.  Must be unique',
  `label` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Human name',
  `belongsTo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `field_module` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The module that implements the field object',
  `description` text COLLATE utf8_unicode_ci,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `settings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Rendering settings (View mode)',
  `ordering` int(11) DEFAULT '1' COMMENT 'edit form ordering',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fields instances';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__fields`
--

LOCK TABLES `#__fields` WRITE;
/*!40000 ALTER TABLE `#__fields` DISABLE KEYS */;
INSERT INTO `#__fields` VALUES (1,'body','Body','NodeType-page','FieldText','',1,'a:7:{s:7:\"display\";a:4:{s:7:\"default\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:4:\"full\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:1;s:11:\"trim_length\";s:3:\"180\";}s:4:\"full\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:4:\"full\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:3:\"600\";}s:4:\"list\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:7:\"trimmed\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:3:\"400\";}s:3:\"rss\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:7:\"trimmed\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:3:\"400\";}}s:4:\"type\";s:8:\"textarea\";s:11:\"text_format\";s:4:\"full\";s:7:\"max_len\";s:0:\"\";s:15:\"validation_rule\";s:0:\"\";s:18:\"validation_message\";s:0:\"\";s:15:\"text_processing\";s:4:\"full\";}',1),(2,'field_article_introduction','Introduction','NodeType-article','FieldText','',1,'a:6:{s:4:\"type\";s:8:\"textarea\";s:15:\"text_processing\";s:5:\"plain\";s:7:\"display\";a:4:{s:7:\"default\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:0:\"\";}s:4:\"full\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:0:\"\";}s:4:\"list\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:5:\"plain\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:0:\"\";}s:3:\"rss\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:5:\"plain\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:0:\"\";}}s:7:\"max_len\";s:0:\"\";s:15:\"validation_rule\";s:0:\"\";s:18:\"validation_message\";s:0:\"\";}',1),(3,'field_aricle_content','Article content','NodeType-article','FieldText','',1,'a:6:{s:4:\"type\";s:8:\"textarea\";s:15:\"text_processing\";s:4:\"full\";s:7:\"display\";a:4:{s:7:\"default\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:4:\"full\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:0:\"\";}s:4:\"full\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:4:\"full\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:0:\"\";}s:4:\"list\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:0:\"\";}s:3:\"rss\";a:5:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;s:11:\"trim_length\";s:0:\"\";}}s:7:\"max_len\";s:0:\"\";s:15:\"validation_rule\";s:0:\"\";s:18:\"validation_message\";s:0:\"\";}',1),(4,'field_article_category','Category','NodeType-article','FieldTerms','',1,'a:4:{s:7:\"display\";a:4:{s:7:\"default\";a:4:{s:5:\"label\";s:6:\"inline\";s:4:\"type\";s:14:\"link-localized\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;}s:4:\"list\";a:3:{s:5:\"label\";s:6:\"inline\";s:4:\"type\";s:14:\"link-localized\";s:10:\"url_prefix\";s:12:\"type:article\";}s:3:\"rss\";a:2:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";}s:4:\"full\";a:3:{s:5:\"label\";s:6:\"inline\";s:4:\"type\";s:15:\"plain-localized\";s:10:\"url_prefix\";s:0:\"\";}}s:10:\"vocabulary\";s:1:\"1\";s:4:\"type\";s:6:\"select\";s:10:\"max_values\";s:1:\"1\";}',1),(5,'field_article_tags','Tags','NodeType-article','FieldTerms','',0,'a:4:{s:7:\"display\";a:4:{s:7:\"default\";a:4:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";s:8:\"settings\";a:0:{}s:8:\"ordering\";i:0;}s:4:\"full\";a:3:{s:5:\"label\";s:6:\"inline\";s:4:\"type\";s:14:\"link-localized\";s:10:\"url_prefix\";s:12:\"type:article\";}s:4:\"list\";a:3:{s:5:\"label\";s:6:\"inline\";s:4:\"type\";s:14:\"link-localized\";s:10:\"url_prefix\";s:12:\"type:article\";}s:3:\"rss\";a:2:{s:5:\"label\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";}}s:10:\"vocabulary\";s:1:\"2\";s:4:\"type\";s:12:\"autocomplete\";s:10:\"max_values\";s:1:\"0\";}',1);
/*!40000 ALTER TABLE `#__fields` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-04 20:22:22
