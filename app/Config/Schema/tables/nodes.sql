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
-- Table structure for table `#__nodes`
--

DROP TABLE IF EXISTS `#__nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__nodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The primary identifier for a node.',
  `node_type_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The node_type.type of this node.',
  `node_type_base` varchar(36) COLLATE utf8_unicode_ci NOT NULL COMMENT 'performance data for models',
  `language` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The languages.language of this node.',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The title of this node, always treated as non-markup plain text.',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` text COLLATE utf8_unicode_ci NOT NULL,
  `terms_cache` text COLLATE utf8_unicode_ci COMMENT 'serialized data for find performance',
  `roles_cache` text COLLATE utf8_unicode_ci COMMENT 'serialized data for find performance',
  `created_by` int(11) NOT NULL DEFAULT '0' COMMENT 'The users.uid that owns this node; initially, this is the user that created it.',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT 'Boolean indicating whether the node is published (visible to non-administrators).',
  `created` int(11) NOT NULL DEFAULT '0' COMMENT 'The Unix timestamp when the node was created.',
  `modified` int(11) NOT NULL DEFAULT '0' COMMENT 'The Unix timestamp when the node was most recently saved.',
  `modified_by` int(11) DEFAULT NULL,
  `comment` int(11) NOT NULL DEFAULT '0' COMMENT 'Whether comments are allowed on this node: 0 = no, 1 = closed (read only), 2 = open (read/write).',
  `comment_count` int(11) DEFAULT '0',
  `promote` int(11) NOT NULL DEFAULT '0' COMMENT 'Boolean indicating whether the node should be displayed on the front page.',
  `sticky` int(11) NOT NULL DEFAULT '0' COMMENT 'Boolean indicating whether the node should be displayed at the top of lists in which it appears.',
  `cache` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `params` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`,`node_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='The base table for nodes.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__nodes`
--

LOCK TABLES `#__nodes` WRITE;
/*!40000 ALTER TABLE `#__nodes` DISABLE KEYS */;
INSERT INTO `#__nodes` VALUES (1,'page','node_content','','Hook Tags','','hook-tags','','',1,1,1310424311,1310424311,1,0,0,0,0,'',NULL),(2,'page','node_content','','About','','about','','',1,1,1310424311,1310424311,1,0,1,1,0,'',NULL);
/*!40000 ALTER TABLE `#__nodes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-09-13 16:46:05
