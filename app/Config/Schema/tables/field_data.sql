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
-- Table structure for table `#__field_data`
--

DROP TABLE IF EXISTS `#__field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `#__field_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `foreignKey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `belongsTo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `#__field_data`
--

LOCK TABLES `#__field_data` WRITE;
/*!40000 ALTER TABLE `#__field_data` DISABLE KEYS */;
INSERT INTO `#__field_data` VALUES (1,1,'1','Node','<h3>Content Boxes</h3>\r\n<p>\r\n	[content_box type=success]Maecenas pellentesque cursus auctor.[/content_box]</p>\r\n<p>\r\n	[content_box type=error]Nam sagittis nisl non turpis aliquam mollis. Suspendisse ac metus nisi, sed vulputate arcu.[/content_box]</p>\r\n<p>\r\n	[content_box type=alert]Cras interdum leo quis arcu sagittis pulvinar. Curabitur suscipit vulputate erat eu rhoncus. Morbi facilisis mi in ligula ornare ultricies.[/content_box]</p>\r\n<p>\r\n	[content_box type=bubble]Fusce interdum cursus turpis vitae gravida. Aenean aliquet venenatis posuere. Etiam gravida ullamcorper purus.[/content_box]</p>\r\n<hr />\r\n<h3>\r\n	Buttons</h3>\r\n<p>\r\n	Using buttons hookTags, you can easily create a variety of buttons. These buttons all stem from a single tag, but vary in color and size (each of which are adjustable using color=&rdquo;&quot; and size=&rdquo;&quot; parameters).<br />\r\n	Allowed parameters:</p>\r\n<ol>\r\n	<li>\r\n		<strong>size:</strong> big, small</li>\r\n	<li>\r\n		<strong>color:</strong>\r\n		<ul>\r\n			<li>\r\n				small: black, blue, green, lightblue, orange, pink, purple, red, silver, teal</li>\r\n			<li>\r\n				big: blue, green, orange, purple, red, turquoise</li>\r\n		</ul>\r\n	</li>\r\n	<li>\r\n		<strong>link:</strong> url of your button</li>\r\n	<li>\r\n		<strong>target:</strong> open link en new window (_blank), open in same window (_self or unset parameter)</li>\r\n</ol>\r\n<h4>\r\n	&nbsp;</h4>\r\n<p>\r\n	&nbsp;</p>\r\n<h4>\r\n	Small Buttons</h4>\r\n<table style=\"width: 478px; height: 25px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n				[button color=black]Button text[/button]</td>\r\n			<td>\r\n				[button color=blue]Button text[/button]</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				[button color=green]Button text[/button]</td>\r\n			<td>\r\n				[button color=lightblue]Button text[/button]</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				[button color=orange]Button text[/button]</td>\r\n			<td>\r\n				[button color=pink]Button text[/button]</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				[button color=purple]Button text[/button]</td>\r\n			<td>\r\n				[button color=red]Button text[/button]</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				[button color=silver]Button text[/button]</td>\r\n			<td>\r\n				[button color=teal]Button text[/button]</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<h4>\r\n	&nbsp;</h4>\r\n<p>\r\n	&nbsp;</p>\r\n<h4>\r\n	Big Buttons</h4>\r\n<table style=\"width: 478px; height: 25px;\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n				[button color=blue size=big]Button text[/button]</td>\r\n			<td>\r\n				[button color=green size=big]Button text[/button]</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				[button color=orange size=big]Button text[/button]</td>\r\n			<td>\r\n				[button color=purple size=big]Button text[/button]</td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n				[button color=red size=big]Button text[/button]</td>\r\n			<td>\r\n				[button color=turquoise size=big]Button text[/button]</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<p>\r\n	&nbsp;</p>\r\n'),(2,1,'2','Node','Nam in iaculis lectus? Sed egestas dui quis leo porttitor vitae bibendum ipsum ultrices. Mauris nisi nulla, volutpat vel vestibulum non, lobortis sed lectus. Integer quis volutpat.'),(3,2,'3','Node','Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum turpis mi, pulvinar ac placerat ut, luctus vel arcu. Cras ac vulputate sed.'),(4,3,'3','Node','Integer in augue a neque mollis semper eget nec est. Donec eros justo, ornare non sollicitudin ut, viverra nec ligula. Cras quis nisl magna. Vivamus tortor est, lobortis sit amet vehicula sed, porta vitae risus. Quisque sit amet justo elit. Fusce in eros augue, sed gravida ligula. Integer ac sem neque. Nulla vitae neque a nibh ultricies vehicula vel a massa.\r\n\r\nQuisque at ante sit amet metus auctor dignissim nec nec est. Nullam et lacus a diam viverra suscipit vitae ut neque. Suspendisse in lacus vel ipsum lacinia rutrum id eget ligula. Vestibulum vehicula elit vel nunc ultricies scelerisque sagittis mi consectetur. Maecenas bibendum augue ut urna sodales molestie! Quisque ultrices hendrerit ipsum, ac dictum mi porta eget. Integer fringilla suscipit nisl, id hendrerit elit fringilla sed! Curabitur quis elit vitae est vulputate adipiscing nec a risus. Curabitur euismod sodales risus non commodo?Integer tincidunt dolor a urna convallis interdum. Curabitur quis velit et ante convallis venenatis. \r\n\r\nUt nec ipsum et arcu ultrices mattis? Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam nec est neque. Donec vitae interdum velit? Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis venenatis faucibus odio, sed lobortis enim euismod et. Fusce vel risus et mauris feugiat consectetur. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.Aenean condimentum feugiat lectus eget porttitor. \r\n\r\nSed volutpat pretium felis, ac pulvinar sapien dapibus quis.'),(5,4,'3','Node','1'),(6,5,'3','Node','7,8,9'),(7,5,'3','Node','7,8,9'),(8,5,'3','Node','7,8,9'),(9,5,'3','Node','7,8,9'),(10,5,'3','Node','7,8,9'),(11,5,'3','Node','7,8,9'),(12,5,'3','Node','7,8,9'),(13,5,'3','Node','7,8,9'),(14,5,'3','Node','7,8,9'),(15,5,'3','Node','7,8,9'),(16,5,'3','Node','7,8,9'),(17,5,'3','Node','7,8,9'),(18,5,'3','Node','7,8,9'),(19,5,'3','Node','7,8,9'),(20,5,'3','Node','7,8,9'),(21,5,'3','Node','7,8,9'),(22,5,'3','Node','7,8,9'),(23,5,'3','Node','7,8,9'),(24,5,'3','Node','7,8,9');
/*!40000 ALTER TABLE `#__field_data` ENABLE KEYS */;
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
