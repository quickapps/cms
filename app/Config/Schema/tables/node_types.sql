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
-- Table structure for table `#__node_types`
--

DROP TABLE IF EXISTS `#__node_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__node_types` (
  `id` varchar(36) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The machine-readable name of this type.',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'The human-readable name of this type.',
  `base` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The base string used to construct callbacks corresponding to this node type.',
  `module` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The module defining this node type.',
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'A brief description of this type.',
  `title_label` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'The label displayed for the title field on the edit form.',
  `comments_approve` tinyint(1) DEFAULT '0',
  `comments_per_page` int(4) NOT NULL DEFAULT '10',
  `comments_anonymous` tinyint(3) NOT NULL DEFAULT '0',
  `comments_subject_field` tinyint(1) NOT NULL DEFAULT '1',
  `node_show_author` tinyint(1) DEFAULT '1',
  `node_show_date` tinyint(1) DEFAULT '1',
  `default_comment` int(11) DEFAULT NULL,
  `default_language` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_status` int(11) DEFAULT NULL,
  `default_promote` int(11) DEFAULT NULL,
  `default_sticky` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'A boolean indicating whether the node type is disabled.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores information about all defined node types.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__node_types`
--

LOCK TABLES `#__node_types` WRITE;
/*!40000 ALTER TABLE `#__node_types` DISABLE KEYS */;
INSERT INTO `#__node_types` VALUES ('page','Basic page','node_content','system','Use <em>basic pages</em> for your static content, such as an \'About us\' page.','Title',1,10,2,1,0,0,0,'es',1,0,0,1);
/*!40000 ALTER TABLE `#__node_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-26 11:37:46
