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
-- Table structure for table `#__blocks`
--

DROP TABLE IF EXISTS `#__blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key - Unique block ID.',
  `module` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'The module from which the block originates; for example, ’user’ for the Who’s Online block, and ’block’ for any custom blocks.',
  `delta` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Unique ID for block within a module. Or menu_id',
  `clone_of` int(11) NOT NULL DEFAULT '0',
  `themes_cache` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'store all themes that belongs to (see block_regions table)',
  `ordering` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Block enabled status. (1 = enabled, 0 = disabled)',
  `visibility` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Flag to indicate how to show blocks on pages. (0 = Show on all pages except listed pages, 1 = Show only on listed pages, 2 = Use custom PHP code to determine visibility)',
  `pages` text COLLATE utf8_unicode_ci COMMENT 'Contents of the "Pages" block; contains either a list of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.',
  `title` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Custom title for the block. (Empty string will use block default title, <none> will remove the title, text will cause block to use specified title.)',
  `locale` text COLLATE utf8_unicode_ci,
  `settings` text COLLATE utf8_unicode_ci,
  `params` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores block settings, such as region and visibility...';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__blocks`
--

LOCK TABLES `#__blocks` WRITE;
/*!40000 ALTER TABLE `#__blocks` DISABLE KEYS */;
INSERT INTO `#__blocks` VALUES (1,'user','login',0,'',1,0,0,'','User Login','a:0:{}','',NULL),(2,'menu','navigation',0,':Default:',2,1,0,'','',NULL,'',NULL),(3,'system','powered_by',0,':AdminDefault:Default:',1,1,0,'','Powered By','a:0:{}','',NULL),(4,'menu','management',0,':AdminDefault:',1,1,1,'/admin/*','','a:0:{}','',NULL),(5,'menu','user-menu',0,':Default:',4,1,0,'','User Menu','a:0:{}','',NULL),(6,'menu','main-menu',0,':Default:',1,1,0,'','','a:0:{}','',NULL),(7,'user','new',0,':AdminDefault:',5,1,0,'','New Users','a:0:{}','a:1:{s:10:\"show_limit\";s:1:\"5\";}',NULL),(9,'locale','language_switcher',0,':Default:',3,1,0,'','Language switcher','a:0:{}','a:2:{s:5:\"flags\";s:1:\"1\";s:4:\"name\";s:1:\"1\";}',NULL),(10,'system','recent_content',0,':AdminDefault:',1,1,0,'','Updates','a:0:{}','',NULL),(11,'block','5',0,':Default:',1,1,0,'','WHAT WE DO','a:0:{}','',NULL),(12,'block','6',0,':Default:',1,1,0,'','OUR MISSION','a:0:{}','',NULL),(13,'block','7',0,':Default:',1,1,0,'','WHO WE ARE','a:0:{}','',NULL),(14,'theme_default','slider',0,':Default:',1,1,1,'/','Slider','a:0:{}','a:1:{s:12:\"slider_order\";s:52:\"1_[language].jpg\r\n2_[language].jpg\r\n3_[language].jpg\";}',NULL),(15,'node','search',0,':AdminDefault:Default:',1,1,0,'','Search','a:0:{}','',NULL),(16,'taxonomy','vocabularies',0,NULL,1,1,0,NULL,'Vocabularies',NULL,NULL,NULL);
/*!40000 ALTER TABLE `#__blocks` ENABLE KEYS */;
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
