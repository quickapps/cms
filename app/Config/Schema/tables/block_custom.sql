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
-- Table structure for table `#__block_custom`
--

DROP TABLE IF EXISTS `#__block_custom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__block_custom` (
  `block_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The block’s block.bid.',
  `body` longtext COLLATE utf8_unicode_ci COMMENT 'Block contents.',
  `description` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Block description.',
  `format` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The filter_format.format of the block body.',
  PRIMARY KEY (`block_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores contents of custom-made blocks.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__block_custom`
--

LOCK TABLES `#__block_custom` WRITE;
/*!40000 ALTER TABLE `#__block_custom` DISABLE KEYS */;
INSERT INTO `#__block_custom` VALUES (11,'<p>Duis tellus nunc, egestas a interdum sed, congue vitae magna. Curabitur a tellus quis lacus blandit sagittis a sit amet elit. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas metus sed neque ultricies.\r\n[button size=small color=silver]Read more[/button]</p>\r\n','Services-LEFT',NULL),(12,'<p>Integer egestas ultricies urna vitae molestie. Donec nec facilisis nisi. Vivamus tempor feugiat velit gravida vehicula. Donec faucibus pellentesque ipsum id varius. Ut rutrum metus sed neque ultricies a dictum ante sagittis.\r\n[button size=small color=silver]Read more[/button]</p>\r\n','Services-CENTER',NULL),(13,'<p>Praesent et metus sit amet nisl luctus commodo ut a risus. Mauris vehicula, ligula quis consectetur feugiat, arcu nibh tempor nisi, at varius dolor dolor nec dolor. Donec auctor mi vitae neque. Praesent sollicitudin egestas felis vitae gravida.\r\n[button size=small color=silver]Read more[/button]\r\n</p>\r\n','Services-RIGHT',NULL);
/*!40000 ALTER TABLE `#__block_custom` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-26 11:37:45
