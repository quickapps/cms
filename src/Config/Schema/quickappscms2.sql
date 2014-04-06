-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2014 at 09:37 PM
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
(3, 'article-body', 'nodes_article', 'Text', 'Body', 'Long version.', 1, 'a:5:{s:4:"type";s:4:"text";s:15:"text_processing";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `field_values`
--

INSERT INTO `field_values` (`id`, `field_instance_id`, `field_instance_slug`, `entity_id`, `table_alias`, `value`, `extra`) VALUES
(1, 1, 'article-introduction', '1', 'nodes_article', 'Lorem ipsum dolor sit amet [random]1,2,3,pepe,4[/random]', 'a:1:{i:0;b:0;}'),
(9, 3, 'article-body', '1', 'nodes_article', '<p>Dolorem, sit amet.</p>\r\n', 'a:1:{i:0;b:0;}'),
(10, 3, 'article-body', '2', 'nodes_article', '<p>lorem picsum</p>\r\n', 'a:0:{}');

-- --------------------------------------------------------

--
-- Table structure for table `menu_links`
--

CREATE TABLE IF NOT EXISTS `menu_links` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `menu_slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The menu name. All links with the same menu name (such as ’navigation’) are part of the same menu.',
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT 'The parent link ID (plid) is the mlid of the link above in the hierarchy, or zero if the link is at the top level in its menu.',
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'the url',
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The text displayed for the link, which may be modified by a title callback stored in menu_router.',
  `plugin` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'menu' COMMENT 'The name of the module that generated this link.',
  `target` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self',
  `expanded` int(6) NOT NULL DEFAULT '0' COMMENT 'Flag for whether this link should be rendered as expanded in menus - expanded links always have their child links displayed, instead of only when the link is in the active trail (1 = expanded, 0 = not expanded)',
  `selected_on` text COLLATE utf8_unicode_ci COMMENT 'php code, or regular expression. based on selected_on_type',
  `selected_on_type` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'php = on php return TRUE. reg = on URL match',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `router_path` (`url`),
  KEY `menu_id` (`menu_slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

--
-- Dumping data for table `menu_links`
--

INSERT INTO `menu_links` (`id`, `menu_id`, `menu_slug`, `lft`, `rght`, `parent_id`, `url`, `description`, `title`, `plugin`, `target`, `expanded`, `selected_on`, `selected_on_type`, `status`) VALUES
(1, 0, 'management', 1, 2, 0, '/admin/system/dashboard', NULL, 'Dashboard', 'System', '_self', 0, NULL, NULL, 1),
(2, 0, 'management', 3, 12, 0, '/admin/system/structure', NULL, 'Structure', 'System', '_self', 0, NULL, NULL, 1),
(3, 0, 'management', 13, 14, 0, '/admin/node/manage', NULL, 'Content', 'System', '_self', 0, '/admin/node/manage*', 'reg', 1),
(4, 0, 'management', 15, 16, 0, '/admin/system/themes', NULL, 'Appearance', 'System', '_self', 0, NULL, NULL, 1),
(5, 0, 'management', 17, 18, 0, '/admin/system/plugins', NULL, 'Plugin', 'System', '_self', 0, NULL, NULL, 1),
(6, 0, 'management', 19, 20, 0, '/admin/user/manage', NULL, 'Users', 'System', '_self', 0, NULL, NULL, 1),
(7, 0, 'management', 23, 24, 0, '/admin/system/configuration', NULL, 'Configuration', 'System', '_self', 0, NULL, NULL, 1),
(8, 0, 'management', 25, 26, 0, '/admin/system/help', NULL, 'Help', 'System', '_self', 0, '/admin/system/help*', 'reg', 1),
(9, 0, 'management', 4, 5, 2, '/admin/block/manage', 'Configure what block content appears in your site''s sidebars and other regions.', 'Blocks', 'System', '_self', 0, NULL, NULL, 1),
(10, 0, 'management', 6, 7, 2, '/admin/node/types', 'Manage content types.', 'Content Types', 'System', '_self', 0, NULL, NULL, 1),
(11, 0, 'management', 8, 9, 2, '/admin/menu/manage', 'Add new menus to your site, edit existing menus, and rename and reorganize menu links.', 'Menus', 'System', '_self', 0, NULL, NULL, 1),
(12, 0, 'management', 10, 11, 2, '/admin/taxonomy/manage', 'Manage tagging, categorization, and classification of your content.', 'Taxonomy', 'System', '_self', 0, NULL, NULL, 1),
(13, 0, 'main-menu', 3, 4, 0, '/page/hooktags.html', '', 'Hooktags', 'Menu', '_self', 0, NULL, NULL, 1),
(17, 0, 'main-menu', 5, 6, 0, '/page/about.html', '', 'About', 'Menu', '_self', 0, NULL, NULL, 1),
(18, 0, 'management', 21, 22, 0, '/admin/locale', '', 'Languages', 'Locale', '_self', 0, NULL, NULL, 1),
(21, 0, 'main-menu', 1, 2, 0, '/', '', 'Home', 'Menu', '_self', 0, NULL, NULL, 1),
(22, 0, 'user-menu', 1, 2, 0, '/user/my_account', '', 'My account', 'Menu', '_self', 0, NULL, NULL, 1),
(23, 0, 'user-menu', 3, 4, 0, '/user/logout', '', 'Logout', 'Menu', '_self', 0, NULL, NULL, 1),
(24, 0, 'main-menu', 7, 8, 0, '/search/type:article', '', 'Blog', 'Menu', '_self', 0, '/article/*.html\r\n/search/type:article*', 'reg', 1),
(25, 0, 'navigation', 1, 2, 0, 'http://www.quickappscms.org', '', 'QuickApps Site', 'Menu', '_blank', 0, '', '', 1),
(26, 0, 'navigation', 3, 4, 0, 'https://github.com/QuickAppsCMS/QuickApps-CMS-Docs', '', 'Documentation', 'Menu', '_blank', 0, '', '', 1);

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
(1, 1, 'article', 'my-first-article', 'My First Article', 'Custom meta description', 1, 1, 1, '', 1, NULL, 8, 1, '0000-00-00 00:00:00', '2014-04-06 18:09:31', 1, 0),
(2, 1, 'article', 'my-second-article', 'My Second Article', 'Custom meta description', 1, 1, 0, '', 1, 1, 5, 2, '0000-00-00 00:00:00', '2014-04-06 18:44:12', 1, 0),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `node_revisions`
--

INSERT INTO `node_revisions` (`id`, `node_id`, `data`, `hash`, `created`) VALUES
(1, 1, 'O:22:"Node\\Model\\Entity\\Node":9:{s:14:"\0*\0_properties";a:21:{s:2:"id";i:1;s:12:"node_type_id";i:1;s:14:"node_type_slug";s:7:"article";s:4:"slug";s:16:"my-first-article";s:5:"title";s:16:"My First Article";s:11:"description";s:23:"Custom meta description";s:7:"promote";b:1;s:6:"sticky";b:1;s:7:"comment";i:1;s:8:"language";s:0:"";s:6:"status";b:1;s:9:"parent_id";N;s:4:"rght";i:8;s:3:"lft";i:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"modified";O:8:"DateTime":3:{s:4:"date";s:19:"2014-04-05 01:01:50";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:10:"created_by";i:1;s:11:"modified_by";i:0;s:8:"comments";a:2:{i:0;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:1;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:4:"asda";s:4:"body";s:8:"asdasdad";s:11:"author_name";s:18:"Christopher Castro";s:12:"author_email";s:18:"chris@quickapps.es";s:10:"author_web";s:2:"ha";s:7:"user_id";N;s:9:"parent_id";N;s:4:"rght";i:4;s:3:"lft";i:4;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:1:{i:0;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:2;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:5:"dfasd";s:4:"body";s:11:"asdfasdfasf";s:11:"author_name";s:0:"";s:12:"author_email";s:0:"";s:10:"author_web";s:0:"";s:7:"user_id";N;s:9:"parent_id";i:1;s:4:"rght";i:4;s:3:"lft";i:4;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:2:{i:0;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:3;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:13:"Third Comment";s:4:"body";s:20:"Test of TreeBehavior";s:11:"author_name";s:0:"";s:12:"author_email";s:0:"";s:10:"author_web";s:0:"";s:7:"user_id";N;s:9:"parent_id";i:2;s:4:"rght";i:1;s:3:"lft";i:0;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:0:{}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}i:1;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:4;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:20:"More nested comments";s:4:"body";s:15:"Do you like it?";s:11:"author_name";s:0:"";s:12:"author_email";s:0:"";s:10:"author_web";s:0:"";s:7:"user_id";N;s:9:"parent_id";i:2;s:4:"rght";i:3;s:3:"lft";i:2;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:0:{}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}i:1;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:5;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:20:"Another Root Comment";s:4:"body";s:9:"Root Node";s:11:"author_name";s:0:"";s:12:"author_email";s:0:"";s:10:"author_web";s:0:"";s:7:"user_id";N;s:9:"parent_id";i:0;s:4:"rght";i:6;s:3:"lft";i:5;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:0:{}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}}s:13:"comment_count";i:5;s:7:"_fields";a:2:{i:0;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:20:"article-introduction";s:5:"label";s:12:"Introduction";s:5:"value";s:56:"Lorem ipsum dolor sit amet [random]1,2,3,pepe,4[/random]";s:5:"extra";a:1:{i:0;b:0;}s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";i:1;s:17:"field_instance_id";i:1;s:9:"entity_id";i:1;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:17:"Brief description";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:0;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}i:1;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:12:"article-body";s:5:"label";s:4:"Body";s:5:"value";s:27:"<p>Dolorem, sit amet.</p>\r\n";s:5:"extra";a:1:{i:0;b:0;}s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";i:9;s:17:"field_instance_id";i:3;s:9:"entity_id";i:1;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:13:"Long version.";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:0;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:22:"Node\\Model\\Entity\\Node";s:9:"\0*\0_dirty";a:2:{s:13:"comment_count";b:1;s:7:"_fields";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:5:"Nodes";}', '14e80fbaf1bd73a6e89b4a4891609d80', '2014-04-05 01:05:13'),
(2, 2, 'O:22:"Node\\Model\\Entity\\Node":9:{s:14:"\0*\0_properties";a:20:{s:2:"id";i:2;s:12:"node_type_id";i:1;s:14:"node_type_slug";s:7:"article";s:4:"slug";s:17:"my-second-article";s:5:"title";s:17:"My Second Article";s:11:"description";s:23:"Custom meta description";s:7:"promote";b:1;s:6:"sticky";b:1;s:7:"comment";i:1;s:8:"language";s:0:"";s:6:"status";b:1;s:9:"parent_id";i:1;s:4:"rght";i:5;s:3:"lft";i:2;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"modified";O:8:"DateTime":3:{s:4:"date";s:19:"2014-03-31 00:21:43";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:10:"created_by";i:1;s:11:"modified_by";i:0;s:13:"comment_count";i:0;s:7:"_fields";a:2:{i:0;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:20:"article-introduction";s:5:"label";s:12:"Introduction";s:5:"value";N;s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";N;s:17:"field_instance_id";i:1;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:17:"Brief description";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}i:1;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:12:"article-body";s:5:"label";s:4:"Body";s:5:"value";N;s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";N;s:17:"field_instance_id";i:3;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:13:"Long version.";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:22:"Node\\Model\\Entity\\Node";s:9:"\0*\0_dirty";a:2:{s:13:"comment_count";b:1;s:7:"_fields";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:5:"Nodes";}', '97e9239563fc5b947284ea74e514cd41', '2014-04-05 04:45:48'),
(3, 2, 'O:22:"Node\\Model\\Entity\\Node":9:{s:14:"\0*\0_properties";a:20:{s:2:"id";i:2;s:12:"node_type_id";i:1;s:14:"node_type_slug";s:7:"article";s:4:"slug";s:17:"my-second-article";s:5:"title";s:17:"My Second Article";s:11:"description";s:23:"Custom meta description";s:7:"promote";b:1;s:6:"sticky";b:1;s:7:"comment";i:1;s:8:"language";s:0:"";s:6:"status";b:1;s:9:"parent_id";i:1;s:4:"rght";i:5;s:3:"lft";i:2;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"modified";O:8:"DateTime":3:{s:4:"date";s:19:"2014-04-05 04:45:48";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:10:"created_by";i:1;s:11:"modified_by";i:0;s:13:"comment_count";i:0;s:7:"_fields";a:2:{i:0;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:20:"article-introduction";s:5:"label";s:12:"Introduction";s:5:"value";N;s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";N;s:17:"field_instance_id";i:1;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:17:"Brief description";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}i:1;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:12:"article-body";s:5:"label";s:4:"Body";s:5:"value";N;s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";N;s:17:"field_instance_id";i:3;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:13:"Long version.";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:22:"Node\\Model\\Entity\\Node";s:9:"\0*\0_dirty";a:2:{s:13:"comment_count";b:1;s:7:"_fields";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:5:"Nodes";}', '4ded029d98549c960fca727c885397ef', '2014-04-05 04:46:02'),
(4, 2, 'O:22:"Node\\Model\\Entity\\Node":9:{s:14:"\0*\0_properties";a:20:{s:2:"id";i:2;s:12:"node_type_id";i:1;s:14:"node_type_slug";s:7:"article";s:4:"slug";s:17:"my-second-article";s:5:"title";s:17:"My Second Article";s:11:"description";s:23:"Custom meta description";s:7:"promote";b:1;s:6:"sticky";b:1;s:7:"comment";i:1;s:8:"language";s:0:"";s:6:"status";b:1;s:9:"parent_id";i:1;s:4:"rght";i:5;s:3:"lft";i:2;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"modified";O:8:"DateTime":3:{s:4:"date";s:19:"2014-04-05 04:46:02";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:10:"created_by";i:1;s:11:"modified_by";i:0;s:13:"comment_count";i:0;s:7:"_fields";a:2:{i:0;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:20:"article-introduction";s:5:"label";s:12:"Introduction";s:5:"value";N;s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";N;s:17:"field_instance_id";i:1;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:17:"Brief description";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}i:1;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:12:"article-body";s:5:"label";s:4:"Body";s:5:"value";N;s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";N;s:17:"field_instance_id";i:3;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:13:"Long version.";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:22:"Node\\Model\\Entity\\Node";s:9:"\0*\0_dirty";a:2:{s:13:"comment_count";b:1;s:7:"_fields";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:5:"Nodes";}', '8df6ff26933222f197c43d95c8439b32', '2014-04-05 04:46:14'),
(5, 2, 'O:22:"Node\\Model\\Entity\\Node":9:{s:14:"\0*\0_properties";a:20:{s:2:"id";i:2;s:12:"node_type_id";i:1;s:14:"node_type_slug";s:7:"article";s:4:"slug";s:17:"my-second-article";s:5:"title";s:17:"My Second Article";s:11:"description";s:23:"Custom meta description";s:7:"promote";b:1;s:6:"sticky";b:1;s:7:"comment";i:1;s:8:"language";s:0:"";s:6:"status";b:1;s:9:"parent_id";i:1;s:4:"rght";i:5;s:3:"lft";i:2;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"modified";O:8:"DateTime":3:{s:4:"date";s:19:"2014-04-05 04:46:14";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:10:"created_by";i:1;s:11:"modified_by";i:0;s:13:"comment_count";i:0;s:7:"_fields";a:2:{i:0;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:20:"article-introduction";s:5:"label";s:12:"Introduction";s:5:"value";N;s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";N;s:17:"field_instance_id";i:1;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:17:"Brief description";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}i:1;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:12:"article-body";s:5:"label";s:4:"Body";s:5:"value";s:30:"<p>&#39;&lt;/ul&gt;&#39;</p>\r\n";s:5:"extra";a:0:{}s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";i:10;s:17:"field_instance_id";i:3;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:13:"Long version.";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:0;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:22:"Node\\Model\\Entity\\Node";s:9:"\0*\0_dirty";a:2:{s:13:"comment_count";b:1;s:7:"_fields";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:5:"Nodes";}', '6a87bdb771bfeb54f3f0ab8e86c18d21', '2014-04-05 04:46:43'),
(6, 1, 'O:22:"Node\\Model\\Entity\\Node":9:{s:14:"\0*\0_properties";a:21:{s:2:"id";i:1;s:12:"node_type_id";i:1;s:14:"node_type_slug";s:7:"article";s:4:"slug";s:16:"my-first-article";s:5:"title";s:16:"My First Article";s:11:"description";s:23:"Custom meta description";s:7:"promote";b:1;s:6:"sticky";b:1;s:7:"comment";i:1;s:8:"language";s:0:"";s:6:"status";b:1;s:9:"parent_id";N;s:4:"rght";i:8;s:3:"lft";i:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"modified";O:8:"DateTime":3:{s:4:"date";s:19:"2014-04-05 01:05:13";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:10:"created_by";i:1;s:11:"modified_by";i:0;s:8:"comments";a:2:{i:0;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:1;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:4:"asda";s:4:"body";s:8:"asdasdad";s:11:"author_name";s:18:"Christopher Castro";s:12:"author_email";s:18:"chris@quickapps.es";s:10:"author_web";s:2:"ha";s:7:"user_id";N;s:9:"parent_id";N;s:4:"rght";i:4;s:3:"lft";i:4;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:1:{i:0;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:2;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:5:"dfasd";s:4:"body";s:11:"asdfasdfasf";s:11:"author_name";s:0:"";s:12:"author_email";s:0:"";s:10:"author_web";s:0:"";s:7:"user_id";N;s:9:"parent_id";i:1;s:4:"rght";i:4;s:3:"lft";i:4;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:2:{i:0;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:3;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:13:"Third Comment";s:4:"body";s:20:"Test of TreeBehavior";s:11:"author_name";s:0:"";s:12:"author_email";s:0:"";s:10:"author_web";s:0:"";s:7:"user_id";N;s:9:"parent_id";i:2;s:4:"rght";i:1;s:3:"lft";i:0;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:0:{}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}i:1;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:4;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:20:"More nested comments";s:4:"body";s:15:"Do you like it?";s:11:"author_name";s:0:"";s:12:"author_email";s:0:"";s:10:"author_web";s:0:"";s:7:"user_id";N;s:9:"parent_id";i:2;s:4:"rght";i:3;s:3:"lft";i:2;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:0:{}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}i:1;O:28:"Comment\\Model\\Entity\\Comment":9:{s:14:"\0*\0_properties";a:15:{s:2:"id";i:5;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:5:"nodes";s:7:"subject";s:20:"Another Root Comment";s:4:"body";s:9:"Root Node";s:11:"author_name";s:0:"";s:12:"author_email";s:0:"";s:10:"author_web";s:0:"";s:7:"user_id";N;s:9:"parent_id";i:0;s:4:"rght";i:6;s:3:"lft";i:5;s:6:"status";b:1;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"children";a:0:{}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:28:"Comment\\Model\\Entity\\Comment";s:9:"\0*\0_dirty";a:1:{s:8:"children";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:8:"Comments";}}s:13:"comment_count";i:5;s:7:"_fields";a:2:{i:0;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:20:"article-introduction";s:5:"label";s:12:"Introduction";s:5:"value";s:56:"Lorem ipsum dolor sit amet [random]1,2,3,pepe,4[/random]";s:5:"extra";a:1:{i:0;b:0;}s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";i:1;s:17:"field_instance_id";i:1;s:9:"entity_id";i:1;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:17:"Brief description";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:0;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}i:1;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:12:"article-body";s:5:"label";s:4:"Body";s:5:"value";s:27:"<p>Dolorem, sit amet.</p>\r\n";s:5:"extra";a:1:{i:0;b:0;}s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";i:9;s:17:"field_instance_id";i:3;s:9:"entity_id";i:1;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:13:"Long version.";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:0;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:22:"Node\\Model\\Entity\\Node";s:9:"\0*\0_dirty";a:2:{s:13:"comment_count";b:1;s:7:"_fields";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:5:"Nodes";}', '9f396a29c9fc614f764f251a077a7be4', '2014-04-06 18:09:30'),
(7, 2, 'O:22:"Node\\Model\\Entity\\Node":9:{s:14:"\0*\0_properties";a:20:{s:2:"id";i:2;s:12:"node_type_id";i:1;s:14:"node_type_slug";s:7:"article";s:4:"slug";s:17:"my-second-article";s:5:"title";s:17:"My Second Article";s:11:"description";s:23:"Custom meta description";s:7:"promote";b:1;s:6:"sticky";b:1;s:7:"comment";i:0;s:8:"language";s:0:"";s:6:"status";b:1;s:9:"parent_id";i:1;s:4:"rght";i:5;s:3:"lft";i:2;s:7:"created";O:8:"DateTime":3:{s:4:"date";s:20:"-0001-11-30 00:00:00";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"modified";O:8:"DateTime":3:{s:4:"date";s:19:"2014-04-05 04:46:43";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:10:"created_by";i:1;s:11:"modified_by";i:0;s:13:"comment_count";i:0;s:7:"_fields";a:2:{i:0;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:20:"article-introduction";s:5:"label";s:12:"Introduction";s:5:"value";N;s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";N;s:17:"field_instance_id";i:1;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:17:"Brief description";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}i:1;O:24:"Field\\Model\\Entity\\Field":9:{s:14:"\0*\0_properties";a:5:{s:4:"name";s:12:"article-body";s:5:"label";s:4:"Body";s:5:"value";s:30:"<p>&#39;&lt;/ul&gt;&#39;</p>\r\n";s:5:"extra";a:0:{}s:8:"metadata";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:8:{s:14:"field_value_id";i:10;s:17:"field_instance_id";i:3;s:9:"entity_id";i:2;s:11:"table_alias";s:13:"nodes_article";s:11:"description";s:13:"Long version.";s:8:"required";b:1;s:8:"settings";O:15:"Cake\\ORM\\Entity":9:{s:14:"\0*\0_properties";a:0:{}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:0:{}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";N;}s:7:"handler";s:4:"Text";}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:15:"Cake\\ORM\\Entity";s:9:"\0*\0_dirty";a:8:{s:14:"field_value_id";b:1;s:17:"field_instance_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:11:"description";b:1;s:8:"required";b:1;s:8:"settings";b:1;s:7:"handler";b:1;}s:7:"\0*\0_new";N;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:0;}s:19:"\0*\0_repositoryAlias";N;}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:24:"Field\\Model\\Entity\\Field";s:9:"\0*\0_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:3:{s:1:"*";b:0;s:5:"value";b:1;s:5:"extra";b:1;}s:19:"\0*\0_repositoryAlias";N;}}}s:10:"\0*\0_hidden";a:0:{}s:11:"\0*\0_virtual";a:0:{}s:13:"\0*\0_className";s:22:"Node\\Model\\Entity\\Node";s:9:"\0*\0_dirty";a:2:{s:13:"comment_count";b:1;s:7:"_fields";b:1;}s:7:"\0*\0_new";b:0;s:10:"\0*\0_errors";a:0:{}s:14:"\0*\0_accessible";a:1:{s:1:"*";b:1;}s:19:"\0*\0_repositoryAlias";s:5:"Nodes";}', 'c8afcfa91f08c0649a41bf2b62b015bc', '2014-04-06 18:44:12');

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
(1, '1', 'nodes', ' lorem ipsum dolor sit amet [random]1,2,3,pepe,4[/random] <p>dolorem, sit amet.</p> my first article '),
(2, '2', 'nodes', ' <p>lorem picsum</p> my second article '),
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
