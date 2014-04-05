-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2014 at 09:48 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quickappscms2`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `table_alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `author_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author_web` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(4) DEFAULT NULL,
  `rght` int(4) NOT NULL,
  `lft` int(4) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `entity_id`, `table_alias`, `subject`, `body`, `author_name`, `author_email`, `author_web`, `user_id`, `parent_id`, `rght`, `lft`, `status`, `created`) VALUES
(1, '1', 'nodes', 'asda', 'asdasdad', 'Christopher Castro', 'chris@quickapps.es', 'ha', NULL, NULL, 4, 4, 1, '0000-00-00 00:00:00'),
(2, '1', 'nodes', 'dfasd', 'asdfasdfasf', '', '', '', NULL, 1, 4, 4, 1, '0000-00-00 00:00:00'),
(3, '1', 'nodes', 'Third Comment', 'Test of TreeBehavior', '', '', '', NULL, 2, 1, 0, 1, '0000-00-00 00:00:00'),
(4, '1', 'nodes', 'More nested comments', 'Do you like it?', '', '', '', NULL, 2, 3, 2, 1, '0000-00-00 00:00:00'),
(5, '1', 'nodes', 'Another Root Comment', 'Root Node', '', '', '', NULL, 0, 6, 5, 1, '0000-00-00 00:00:00'),
(6, '4', 'nodes', 'ssssssss', 'sssssssssss', NULL, NULL, NULL, 1, 0, 6, 1, 1, '0000-00-00 00:00:00'),
(7, '4', 'nodes', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit amet', NULL, NULL, NULL, 1, 6, 5, 2, 1, '0000-00-00 00:00:00'),
(8, '4', 'nodes', 'Lorem ipsum dolor sit amet', 'Lorem ipsum dolor sit ametLorem ipsum dolor sit ametLorem ipsum dolor sit ametLorem ipsum dolor sit ametLorem ipsum dolor sit ametLorem ipsum dolor sit amet', NULL, NULL, NULL, 1, 7, 4, 3, 1, '0000-00-00 00:00:00'),
(9, '4', 'nodes', 'syyyyyyyyyyyyy', 'hpppppppppppppppp', NULL, NULL, NULL, 1, 0, 8, 7, 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `field_instances`
--

CREATE TABLE IF NOT EXISTS `field_instances` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Machine name, must be unique',
  `table_alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the table to which this field belongs to. eg: comment, node_article. Must be unique',
  `handler` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of plugin handler',
  `label` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Human readble name, used in views. eg: `First Name` (for a textbox)',
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `settings` text COLLATE utf8_unicode_ci COMMENT 'Serialized information',
  `ordering` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `entity` (`table_alias`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `field_instances`
--

INSERT INTO `field_instances` (`id`, `slug`, `table_alias`, `handler`, `label`, `description`, `required`, `settings`, `ordering`) VALUES
(1, 'article-introduction', 'nodes_article', 'Text', 'Introduction', 'Brief description', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:8:"markdown";s:7:"max_len";s:3:"100";s:15:"validation_rule";s:17:"/^[a-z0-9]{3,}$/i";s:18:"validation_message";s:5:"norrr";}', 0),
(3, 'article-body', 'nodes_article', 'Text', 'Body', 'Long version.', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `field_values`
--

CREATE TABLE IF NOT EXISTS `field_values` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `field_instance_id` int(10) NOT NULL,
  `field_instance_slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `entity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'id of the entity in `table`',
  `table_alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `extra` text COLLATE utf8_unicode_ci COMMENT 'Extra information required by this field hadnler',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `field_values`
--

INSERT INTO `field_values` (`id`, `field_instance_id`, `field_instance_slug`, `entity_id`, `table_alias`, `value`, `extra`) VALUES
(1, 1, 'article-introduction', '1', 'nodes_article', 'Lorem ipsum dolor sit amet [random]1,2,3,pepe,4[/random]', 'a:1:{i:0;b:0;}'),
(9, 3, 'article-body', '1', 'nodes_article', 'Dolorem, sit amet', 'a:1:{i:0;b:0;}');

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_type_id` int(11) NOT NULL,
  `node_type_slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `promote` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Show in front page?',
  `sticky` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Show at top of lists',
  `comment` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=closed, 1=open, 2=readonly',
  `language` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `rght` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`id`, `node_type_id`, `node_type_slug`, `slug`, `title`, `description`, `promote`, `sticky`, `comment`, `language`, `status`, `parent_id`, `rght`, `lft`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(1, 1, 'article', 'my-first-article', 'My First Article', 'Custom meta description', 1, 1, 1, '', 1, NULL, 8, 1, '0000-00-00 00:00:00', '2014-04-01 19:47:13', 1, 0),
(2, 1, 'article', 'my-second-article', 'My Second Article', 'Custom meta description', 1, 1, 1, '', 1, 1, 5, 2, '0000-00-00 00:00:00', '2014-03-31 00:21:43', 1, 0),
(3, 1, 'article', 'about-us', 'About Us', 'Custom meta description', 0, 0, 1, '', 1, 2, 4, 3, '0000-00-00 00:00:00', '2014-03-31 00:49:39', 1, 0),
(4, 1, 'article', 'what-we-do', 'What we do', 'Custom meta description', 1, 0, 1, '', 1, 1, 7, 6, '0000-00-00 00:00:00', '2014-03-31 01:03:58', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `node_revisions`
--

CREATE TABLE IF NOT EXISTS `node_revisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `node_types`
--

CREATE TABLE IF NOT EXISTS `node_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'human-readable name',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_label` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'the label displayed for the title field on the edit form.',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `node_types`
--

INSERT INTO `node_types` (`id`, `slug`, `name`, `description`, `title_label`, `status`) VALUES
(1, 'article', 'Article', 'Use articles for time-sensitive content like news, press releases or blog posts.', 'Title', 1);

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `package` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `settings` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='list of installed plugins (we do not consider core plugins)';

-- --------------------------------------------------------

--
-- Table structure for table `search_datasets`
--

CREATE TABLE IF NOT EXISTS `search_datasets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `table_alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `words` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entity_id` (`entity_id`,`table_alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `search_datasets`
--

INSERT INTO `search_datasets` (`id`, `entity_id`, `table_alias`, `words`) VALUES
(1, '1', 'nodes', ' lorem ipsum dolor sit amet [random]1,2,3,pepe,4[/random] dolorem, sit amet my first article '),
(2, '2', 'nodes', ' my second article '),
(3, '3', 'nodes', ' about us '),
(4, '4', 'nodes', ' what we do ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `public_email` tinyint(1) NOT NULL DEFAULT '0',
  `locale` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` int(50) DEFAULT NULL,
  `code` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'random unique code, used for pass recovery',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `public_email`, `locale`, `timezone`, `code`, `status`, `last_login`, `created`) VALUES
(1, 'Christopher Castro', 'admin', '123456', 'chris@quickapps.es', 0, 'spa', NULL, '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `variables`
--

CREATE TABLE IF NOT EXISTS `variables` (
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `variables`
--

INSERT INTO `variables` (`name`, `value`) VALUES
('admin_theme', 'BackBootstrap'),
('default_language', 'eng'),
('site_theme', 'FrontBootstrap'),
('url_locale_prefix', '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
