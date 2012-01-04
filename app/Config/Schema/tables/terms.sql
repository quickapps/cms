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
-- Table structure for table `#__terms`
--

DROP TABLE IF EXISTS `#__terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vocabulary_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `modified` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT '0',
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__terms`
--

LOCK TABLES `#__terms` WRITE;
/*!40000 ALTER TABLE `#__terms` DISABLE KEYS */;
INSERT INTO `#__terms` VALUES (1,1,'Announcements','announcements',NULL,1319979841,1319979841,NULL,1,6),(2,1,'PHP','php',NULL,1319979888,1319979888,NULL,7,8),(3,1,'cakePHP','cakephp',NULL,1319979899,1319979899,NULL,9,10),(4,1,'Next Events','next-events','',1319979929,1319979911,1,2,3),(5,1,'Past','past',NULL,1319979918,1319979918,1,4,5),(6,2,'my cool term','my-cool-term',NULL,1325701956,1325701910,0,13,14),(7,2,'quickapps','quickapps',NULL,1325701956,1325701927,0,1,2),(8,2,'cms','cms',NULL,1325701956,1325701930,0,3,4),(9,2,'cakephp','cakephp-1',NULL,1325701956,1325701934,0,5,6),(10,2,'php','php-1',NULL,1325701956,1325701943,0,7,8),(11,2,'drupal','drupal',NULL,1325701956,1325701946,0,9,10),(12,2,'wordpress','wordpress',NULL,1325701956,1325701951,0,11,12);
/*!40000 ALTER TABLE `#__terms` ENABLE KEYS */;
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
