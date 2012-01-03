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
-- Table structure for table `#__variables`
--

DROP TABLE IF EXISTS `#__variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__variables` (
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__variables`
--

LOCK TABLES `#__variables` WRITE;
/*!40000 ALTER TABLE `#__variables` DISABLE KEYS */;
INSERT INTO `#__variables` VALUES ('admin_theme','s:12:\"AdminDefault\";'),('date_default_timezone','s:13:\"Europe/Madrid\";'),('default_language','s:3:\"eng\";'),('default_nodes_main','s:1:\"8\";'),('failed_login_limit','i:5;'),('rows_per_page','i:10;'),('site_description','a:0:{}'),('site_frontpage','a:0:{}'),('site_logo','s:8:\"logo.gif\";'),('site_mail','s:24:\"no-reply@your-domain.com\";'),('site_maintenance_message','s:177:\"We sincerely apologize for the inconvenience.<br/>Our site is currently undergoing scheduled maintenance and upgrades, but will return shortly.<br/>Thanks you for your patience.\";'),('site_name','s:17:\"My QuickApps Site\";'),('site_online','s:1:\"1\";'),('site_slogan','s:36:\"Open Source CMS built on CakePHP 2.0\";'),('site_theme','s:7:\"Default\";'),('url_language_prefix','i:0;'),('user_default_avatar','s:25:\"/system/img/anonymous_avatar.jpg\";'),('user_mail_activation_body','s:246:\"[user_name],\r\n\r\nYour account at [site_name] has been activated.\r\n\r\nYou may now log in by clicking this link or copying and pasting it into your browser:\r\n\r\n[site_login_url]\r\n\r\nusername: [user_name]\r\npassword: Your password\r\n\r\n--  [site_name] team\";'),('user_mail_activation_notify','s:1:\"1\";'),('user_mail_activation_subject','s:57:\"Account details for [user_name] at [site_name] (approved)\";'),('user_mail_blocked_body','s:85:\"[user_name],\r\n\r\nYour account on [site_name] has been blocked.\r\n\r\n--  [site_name] team\";'),('user_mail_blocked_notify','s:1:\"1\";'),('user_mail_blocked_subject','s:56:\"Account details for [user_name] at [site_name] (blocked)\";'),('user_mail_canceled_body','s:86:\"[user_name],\r\n\r\nYour account on [site_name] has been canceled.\r\n\r\n--  [site_name] team\";'),('user_mail_canceled_notify','s:1:\"1\";'),('user_mail_canceled_subject','s:57:\"Account details for [user_name] at [site_name] (canceled)\";'),('user_mail_password_recovery_body','s:273:\"[user_name],\r\n\r\nA request to reset the password for your account has been made at [site_name].\r\nYou may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n[user_activation_url]\r\n\r\nAfter log in you can reset your password.\r\n\r\n--  [site_name] team\";'),('user_mail_password_recovery_subject','s:60:\"Replacement login information for [user_name] at [site_name]\";'),('user_mail_welcome_body','s:301:\"[user_name],\r\n\r\nThank you for registering at [site_name]. You may now activate your account by clicking this link or copying and pasting it to your browser:\r\n\r\n[user_activation_url]\r\n\r\nThis link can only be used once to log in.\r\n\r\nusername: [user_name]\r\npassword: Your password\r\n\r\n--  [site_name] team\";'),('user_mail_welcome_subject','s:46:\"Account details for [user_name] at [site_name]\";');
/*!40000 ALTER TABLE `#__variables` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-12-03 16:12:13
