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
-- Table structure for table `#__menu_links`
--

DROP TABLE IF EXISTS `#__menu_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__menu_links` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `menu_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The menu name. All links with the same menu name (such as ’navigation’) are part of the same menu.',
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The parent link ID (plid) is the mlid of the link above in the hierarchy, or zero if the link is at the top level in its menu.',
  `link_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'external path',
  `router_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'internal path',
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The text displayed for the link, which may be modified by a title callback stored in menu_router.',
  `options` text COLLATE utf8_unicode_ci COMMENT 'A serialized array of HTML attributes options.',
  `module` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'system' COMMENT 'The name of the module that generated this link.',
  `target` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self',
  `expanded` tinyint(6) NOT NULL DEFAULT '0' COMMENT 'Flag for whether this link should be rendered as expanded in menus - expanded links always have their child links displayed, instead of only when the link is in the active trail (1 = expanded, 0 = not expanded)',
  `selected_on` text COLLATE utf8_unicode_ci COMMENT 'php code, or regular expression. based on selected_on_type',
  `selected_on_type` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'php = on php return TRUE; reg = on URL match',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `router_path` (`router_path`(128)),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contains the individual links within a menu.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__menu_links`
--

LOCK TABLES `#__menu_links` WRITE;
/*!40000 ALTER TABLE `#__menu_links` DISABLE KEYS */;
INSERT INTO `#__menu_links` VALUES (1,'management',1,2,0,NULL,'/admin/system/dashboard',NULL,'Dashboard',NULL,'System','_self',0,NULL,NULL,1),(2,'management',3,12,0,NULL,'/admin/system/structure',NULL,'Structure',NULL,'System','_self',0,NULL,NULL,1),(3,'management',13,14,0,NULL,'/admin/node/contents',NULL,'Content',NULL,'System','_self',0,NULL,NULL,1),(4,'management',15,16,0,NULL,'/admin/system/themes',NULL,'Appearance',NULL,'System','_self',0,NULL,NULL,1),(5,'management',17,18,0,NULL,'/admin/system/modules',NULL,'Modules',NULL,'System','_self',0,NULL,NULL,1),(6,'management',19,20,0,NULL,'/admin/user',NULL,'Users',NULL,'System','_self',0,NULL,NULL,1),(7,'management',23,24,0,NULL,'/admin/system/configuration',NULL,'Configuration',NULL,'System','_self',0,NULL,NULL,1),(8,'management',25,26,0,NULL,'/admin/system/help',NULL,'Help',NULL,'System','_self',0,NULL,NULL,1),(9,'management',4,5,2,NULL,'/admin/block','Configure what block content appears in your site\'s sidebars and other regions.','Blocks',NULL,'System','_self',0,NULL,NULL,1),(10,'management',6,7,2,NULL,'/admin/node/types','Manage content types.','Content Types',NULL,'System','_self',0,NULL,NULL,1),(11,'management',8,9,2,NULL,'/admin/menu','Add new menus to your site, edit existing menus, and rename and reorganize menu links.','Menus',NULL,'System','_self',0,NULL,NULL,1),(12,'management',10,11,2,NULL,'/admin/taxonomy','Manage tagging, categorization, and classification of your content.','Taxonomy',NULL,'System','_self',0,NULL,NULL,1),(13,'main-menu',3,4,0,NULL,'/d/hook-tags','','Hook Tags',NULL,'Menu','_self',0,'/d/aboutt','reg',1),(17,'main-menu',5,6,0,NULL,'/d/about','','About',NULL,'Menu','_self',0,NULL,NULL,1),(18,'management',21,22,0,NULL,'/admin/locale','','Languages',NULL,'Locale','_self',0,NULL,NULL,1),(21,'main-menu',1,2,0,NULL,'/','','Home',NULL,'Menu','_self',0,NULL,NULL,1),(22,'user-menu',1,2,0,NULL,'/user/my_account','','My account',NULL,'Menu','_self',0,NULL,NULL,1),(23,'user-menu',3,4,0,NULL,'/user/logout','','Logout',NULL,'Menu','_self',0,NULL,NULL,1);
/*!40000 ALTER TABLE `#__menu_links` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-10-25 20:42:14
