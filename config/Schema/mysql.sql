-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2014 at 03:57 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

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
-- Table structure for table `acos`
--

CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `plugin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias_hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=156 ;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `lft`, `rght`, `plugin`, `alias`, `alias_hash`) VALUES
(1, NULL, 1, 62, 'User', 'User', '8f9bfe9d1345237cb3b2b205864da075'),
(2, 1, 2, 13, 'User', 'Gateway', '926dec9494209cb088b4962509df1a91'),
(3, 2, 3, 4, 'User', 'login', 'd56b699830e77ba53855679cb1d252da'),
(4, 2, 5, 6, 'User', 'logout', '4236a440a662cc8253d7536e5aa17942'),
(5, 2, 7, 8, 'User', 'forgot', '790f6b6cf6a6fbead525927d69f409fe'),
(6, 2, 9, 10, 'User', 'profile', '7d97481b1fe66f4b51db90da7e794d9f'),
(7, 2, 11, 12, 'User', 'unauthorized', '36fd540552b3b1b34e8f0bd8897cbf1e'),
(8, 1, 14, 61, 'User', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(9, 8, 15, 32, 'User', 'Fields', 'a4ca5edd20d0b5d502ebece575681f58'),
(10, 9, 16, 17, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(11, 9, 18, 19, 'User', 'configure', 'e2d5a00791bce9a01f99bc6fd613a39d'),
(12, 9, 20, 21, 'User', 'attach', '915e375d95d78bf040a2e054caadfb56'),
(13, 9, 22, 23, 'User', 'detach', 'b6bc015ea9587c510c9017988e94e60d'),
(14, 9, 24, 25, 'User', 'view_mode_list', '50dc11f5c94a739237c8685e567a28d8'),
(15, 9, 26, 27, 'User', 'view_mode_edit', 'b04ebb03255647bd460b7f67b763fb89'),
(16, 9, 28, 29, 'User', 'view_mode_move', '6d54c39b597f25d371090b1de3bffbfa'),
(17, 9, 30, 31, 'User', 'move', '3734a903022249b3010be1897042568e'),
(18, 8, 33, 42, 'User', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(19, 18, 34, 35, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(20, 18, 36, 37, 'User', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(21, 18, 38, 39, 'User', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(22, 18, 40, 41, 'User', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(23, 8, 43, 50, 'User', 'Permissions', 'd08ccf52b4cdd08e41cfb99ec42e0b29'),
(24, 23, 44, 45, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(25, 23, 46, 47, 'User', 'aco', '111c03ddf31a2a03d3fa3377ab07eb56'),
(26, 23, 48, 49, 'User', 'update', '3ac340832f29c11538fbe2d6f75e8bcc'),
(27, 8, 51, 60, 'User', 'Roles', 'a5cd3ed116608dac017f14c046ea56bf'),
(28, 27, 52, 53, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(29, 27, 54, 55, 'User', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(30, 27, 56, 57, 'User', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(31, 27, 58, 59, 'User', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(32, NULL, 63, 94, 'Taxonomy', 'Taxonomy', '30d10883c017c4fd6751c8982e20dae1'),
(33, 32, 64, 93, 'Taxonomy', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(34, 33, 65, 68, 'Taxonomy', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(35, 34, 66, 67, 'Taxonomy', 'index', '6a992d5529f459a44fee58c733255e86'),
(36, 33, 69, 72, 'Taxonomy', 'Tagger', 'e34d9224f0bf63992e1e77451c6976d1'),
(37, 36, 70, 71, 'Taxonomy', 'search', '06a943c59f33a34bb5924aaf72cd2995'),
(38, 33, 73, 82, 'Taxonomy', 'Terms', '6f1bf85c9ebb3c7fa26251e1e335e032'),
(39, 38, 74, 75, 'Taxonomy', 'vocabulary', '09f06963f502addfeab2a7c87f38802e'),
(40, 38, 76, 77, 'Taxonomy', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(41, 38, 78, 79, 'Taxonomy', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(42, 38, 80, 81, 'Taxonomy', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(43, 33, 83, 92, 'Taxonomy', 'Vocabularies', '81a419751eb59e7d35acab8e532d59a7'),
(44, 43, 84, 85, 'Taxonomy', 'index', '6a992d5529f459a44fee58c733255e86'),
(45, 43, 86, 87, 'Taxonomy', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(46, 43, 88, 89, 'Taxonomy', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(47, 43, 90, 91, 'Taxonomy', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(48, NULL, 95, 146, 'System', 'System', 'a45da96d0bf6575970f2d27af22be28a'),
(49, 48, 96, 145, 'System', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(50, 49, 97, 100, 'System', 'Configuration', '254f642527b45bc260048e30704edb39'),
(51, 50, 98, 99, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(52, 49, 101, 104, 'System', 'Dashboard', '2938c7f7e560ed972f8a4f68e80ff834'),
(53, 52, 102, 103, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(54, 49, 105, 110, 'System', 'Help', '6a26f548831e6a8c26bfbbd9f6ec61e0'),
(55, 54, 106, 107, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(56, 54, 108, 109, 'System', 'about', '46b3931b9959c927df4fc65fdee94b07'),
(57, 49, 111, 124, 'System', 'Plugins', 'bb38096ab39160dc20d44f3ea6b44507'),
(58, 57, 112, 113, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(59, 57, 114, 115, 'System', 'install', '19ad89bc3e3c9d7ef68b89523eff1987'),
(60, 57, 116, 117, 'System', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(61, 57, 118, 119, 'System', 'enable', '208f156d4a803025c284bb595a7576b4'),
(62, 57, 120, 121, 'System', 'disable', '0aaa87422396fdd678498793b6d5250e'),
(63, 57, 122, 123, 'System', 'settings', '2e5d8aa3dfa8ef34ca5131d20f9dad51'),
(64, 49, 125, 128, 'System', 'Structure', 'dc4c71563b9bc39a65be853457e6b7b6'),
(65, 64, 126, 127, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(66, 49, 129, 144, 'System', 'Themes', '83915d1254927f41241e8630890bec6e'),
(67, 66, 130, 131, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(68, 66, 132, 133, 'System', 'install', '19ad89bc3e3c9d7ef68b89523eff1987'),
(69, 66, 134, 135, 'System', 'uninstall', 'fe98497efedbe156ecc4b953aea77e07'),
(70, 66, 136, 137, 'System', 'activate', 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922'),
(71, 66, 138, 139, 'System', 'details', '27792947ed5d5da7c0d1f43327ed9dab'),
(72, 66, 140, 141, 'System', 'screenshot', '62c92ba585f74ecdbef4c4498a438984'),
(73, 66, 142, 143, 'System', 'settings', '2e5d8aa3dfa8ef34ca5131d20f9dad51'),
(74, NULL, 147, 218, 'Node', 'Node', '6c3a6944a808a7c0bbb6788dbec54a9f'),
(75, 74, 148, 159, 'Node', 'Serve', 'bc9a5b9e9259199a79f67ded0b508dfc'),
(76, 75, 149, 150, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(77, 75, 151, 152, 'Node', 'home', '106a6c241b8797f52e1e77317b96a201'),
(78, 75, 153, 154, 'Node', 'details', '27792947ed5d5da7c0d1f43327ed9dab'),
(79, 75, 155, 156, 'Node', 'search', '06a943c59f33a34bb5924aaf72cd2995'),
(80, 75, 157, 158, 'Node', 'rss', '8bb856027f758e85ddf2085c98ae2908'),
(81, 74, 160, 217, 'Node', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(82, 81, 161, 172, 'Node', 'Comments', '8413c683b4b27cc3f4dbd4c90329d8ba'),
(83, 82, 162, 163, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(84, 82, 164, 165, 'Node', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(85, 82, 166, 167, 'Node', 'status', '9acb44549b41563697bb490144ec6258'),
(86, 82, 168, 169, 'Node', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(87, 82, 170, 171, 'Node', 'empty_trash', '5e0e12d2aafec2a296b4d8ed252147b8'),
(88, 81, 173, 190, 'Node', 'Fields', 'a4ca5edd20d0b5d502ebece575681f58'),
(89, 88, 174, 175, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(90, 88, 176, 177, 'Node', 'configure', 'e2d5a00791bce9a01f99bc6fd613a39d'),
(91, 88, 178, 179, 'Node', 'attach', '915e375d95d78bf040a2e054caadfb56'),
(92, 88, 180, 181, 'Node', 'detach', 'b6bc015ea9587c510c9017988e94e60d'),
(93, 88, 182, 183, 'Node', 'view_mode_list', '50dc11f5c94a739237c8685e567a28d8'),
(94, 88, 184, 185, 'Node', 'view_mode_edit', 'b04ebb03255647bd460b7f67b763fb89'),
(95, 88, 186, 187, 'Node', 'view_mode_move', '6d54c39b597f25d371090b1de3bffbfa'),
(96, 88, 188, 189, 'Node', 'move', '3734a903022249b3010be1897042568e'),
(97, 81, 191, 206, 'Node', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(98, 97, 192, 193, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(99, 97, 194, 195, 'Node', 'create', '76ea0bebb3c22822b4f0dd9c9fd021c5'),
(100, 97, 196, 197, 'Node', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(101, 97, 198, 199, 'Node', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(102, 97, 200, 201, 'Node', 'translate', 'fc46e26a907870744758b76166150f62'),
(103, 97, 202, 203, 'Node', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(104, 97, 204, 205, 'Node', 'delete_revision', '077308769b80b2240aa845a5dff20436'),
(105, 81, 207, 216, 'Node', 'Types', 'f2d346b1bb7c1c85ab6f7f21e3666b9f'),
(106, 105, 208, 209, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(107, 105, 210, 211, 'Node', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(108, 105, 212, 213, 'Node', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(109, 105, 214, 215, 'Node', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(110, NULL, 219, 242, 'Menu', 'Menu', 'b61541208db7fa7dba42c85224405911'),
(111, 110, 220, 241, 'Menu', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(112, 111, 221, 230, 'Menu', 'Links', 'bd908db5ccb07777ced8023dffc802f4'),
(113, 112, 222, 223, 'Menu', 'menu', '8d6ab84ca2af9fccd4e4048694176ebf'),
(114, 112, 224, 225, 'Menu', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(115, 112, 226, 227, 'Menu', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(116, 112, 228, 229, 'Menu', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(117, 111, 231, 240, 'Menu', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(118, 117, 232, 233, 'Menu', 'index', '6a992d5529f459a44fee58c733255e86'),
(119, 117, 234, 235, 'Menu', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(120, 117, 236, 237, 'Menu', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(121, 117, 238, 239, 'Menu', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(122, NULL, 243, 264, 'Locale', 'Locale', '911f0f24bdce6808f4614d6a263b143b'),
(123, 122, 244, 263, 'Locale', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(124, 123, 245, 262, 'Locale', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(125, 124, 246, 247, 'Locale', 'index', '6a992d5529f459a44fee58c733255e86'),
(126, 124, 248, 249, 'Locale', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(127, 124, 250, 251, 'Locale', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(128, 124, 252, 253, 'Locale', 'set_default', '4889ae9437342e57d774bc6d5705c7a4'),
(129, 124, 254, 255, 'Locale', 'move', '3734a903022249b3010be1897042568e'),
(130, 124, 256, 257, 'Locale', 'enable', '208f156d4a803025c284bb595a7576b4'),
(131, 124, 258, 259, 'Locale', 'disable', '0aaa87422396fdd678498793b6d5250e'),
(132, 124, 260, 261, 'Locale', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(133, NULL, 265, 282, 'Installer', 'Installer', 'd1be377656960ed04f1564da21d80c8d'),
(134, 133, 266, 281, 'Installer', 'Startup', '13e685964c2548aa748f7ea263bad4e5'),
(135, 134, 267, 268, 'Installer', 'index', '6a992d5529f459a44fee58c733255e86'),
(136, 134, 269, 270, 'Installer', 'language', '8512ae7d57b1396273f76fe6ed341a23'),
(137, 134, 271, 272, 'Installer', 'requirements', 'b4851e92b19af0c5c82447fc0937709d'),
(138, 134, 273, 274, 'Installer', 'license', '718779752b851ac0dc6281a8c8d77e7e'),
(139, 134, 275, 276, 'Installer', 'database', '11e0eed8d3696c0a632f822df385ab3c'),
(140, 134, 277, 278, 'Installer', 'account', 'e268443e43d93dab7ebef303bbe9642f'),
(141, 134, 279, 280, 'Installer', 'finish', '3248bc7547ce97b2a197b2a06cf7283d'),
(142, NULL, 283, 298, 'Block', 'Block', 'e1e4c8c9ccd9fc39c391da4bcd093fb2'),
(143, 142, 284, 297, 'Block', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(144, 143, 285, 296, 'Block', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(145, 144, 286, 287, 'Block', 'index', '6a992d5529f459a44fee58c733255e86'),
(146, 144, 288, 289, 'Block', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(147, 144, 290, 291, 'Block', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(148, 144, 292, 293, 'Block', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(149, 144, 294, 295, 'Block', 'duplicate', '24f1b0a79473250c195c7fb84e393392'),
(150, NULL, 299, 310, 'Wysiwyg', 'Wysiwyg', 'fcb1d5c3299a281fbb55851547dfac9e'),
(151, 150, 300, 309, 'Wysiwyg', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(152, 151, 301, 308, 'Wysiwyg', 'Finder', 'd151508da8d36994e1635f7875594424'),
(153, 152, 302, 303, 'Wysiwyg', 'index', '6a992d5529f459a44fee58c733255e86'),
(154, 152, 304, 305, 'Wysiwyg', 'connector', '266e0d3d29830abfe7d4ed98b47966f7'),
(155, 152, 306, 307, 'Wysiwyg', 'plugin_file', '53fcd0f3eb0844a4d22699a9b73a77cd');

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key - Unique block ID.',
  `copy_id` int(11) DEFAULT NULL COMMENT 'id of the block this block is a copy of',
  `delta` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'unique ID within a handler',
  `handler` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Block' COMMENT 'Name of the plugin that created this block. Used to generate event name, e.g. "Menu" triggers "Block.Menu.display" when rendering the block',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci,
  `visibility` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'except' COMMENT 'indicate how to show blocks on pages. (except = show on all pages except listed pages; only = show only on listed pages; php = use custom PHP code to determine visibility)',
  `pages` text COLLATE utf8_unicode_ci COMMENT 'Contents of the "Pages" block contains either a list of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.',
  `locale` text COLLATE utf8_unicode_ci,
  `settings` longtext COLLATE utf8_unicode_ci COMMENT 'additional information used by this block, used by blocks handlers <> `Block`',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `delta` (`delta`,`handler`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `copy_id`, `delta`, `handler`, `title`, `description`, `body`, `visibility`, `pages`, `locale`, `settings`, `status`) VALUES
(1, NULL, '1', 'System', 'Management [menu:1]', 'Associated block for "Management" menu.', NULL, 'except', NULL, NULL, NULL, 1),
(2, NULL, '2', 'System', 'Site Main Menu [menu:2]', 'Associated block for "Site Main Menu" menu.', NULL, 'except', NULL, NULL, NULL, 1),
(3, NULL, 'dashboard_recent_content', 'Node', 'Recent Content', 'Shows a list of latest created contents.', NULL, 'except', NULL, NULL, NULL, 1),
(4, NULL, 'dashboard_search', 'Node', 'Search', 'Quick Search Form', NULL, 'except', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `blocks_roles`
--

CREATE TABLE IF NOT EXISTS `blocks_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL,
  `role_id` int(10) NOT NULL COMMENT 'The user’s role ID from roles table',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `block_regions`
--

CREATE TABLE IF NOT EXISTS `block_regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL,
  `theme` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_id` (`block_id`,`theme`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `block_regions`
--

INSERT INTO `block_regions` (`id`, `block_id`, `theme`, `region`, `ordering`) VALUES
(1, 2, 'BackendTheme', '', 0),
(2, 2, 'FrontendTheme', 'main-menu', 0),
(3, 1, 'BackendTheme', 'main-menu', 0),
(4, 1, 'FrontendTheme', '', 0),
(5, 3, 'BackendTheme', 'dashboard-main', 0),
(6, 3, 'FrontendTheme', '', 0),
(7, 4, 'BackendTheme', 'dashboard-sidebar', 0),
(8, 4, 'FrontendTheme', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `table_alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `author_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author_web` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author_ip` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(4) DEFAULT NULL,
  `rght` int(4) NOT NULL,
  `lft` int(4) NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pending, approved, spam, trash',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `entity_id`, `user_id`, `table_alias`, `subject`, `body`, `author_name`, `author_email`, `author_web`, `author_ip`, `parent_id`, `rght`, `lft`, `status`, `created`) VALUES
(1, '1', NULL, 'nodes', 'This is an unstable repository', 'This is an unstable repository and should be treated as an alpha.', NULL, NULL, NULL, '192.168.1.1', NULL, 2, 2, 'approved', '2014-08-03 05:14:42'),
(4, '1', 1, 'nodes', 'asd ad asd', 'Lorem Ipsum', '', '', '', '192.168.1.1', 1, 1, 0, 'approved', '2014-08-03 08:01:29');

-- --------------------------------------------------------

--
-- Table structure for table `entities_terms`
--

CREATE TABLE IF NOT EXISTS `entities_terms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `entity_id` int(20) NOT NULL,
  `term_id` int(20) NOT NULL,
  `field_instance_id` int(11) NOT NULL,
  `table_alias` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `field_instances`
--

CREATE TABLE IF NOT EXISTS `field_instances` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Machine name, must be unique',
  `table_alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the table to which this field belongs to. eg: comment, node_article. Must be unique',
  `handler` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of event handler class under the `Field` namespace',
  `label` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Human readble name, used in views. eg: `First Name` (for a textbox)',
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'instructions to present to the user below this field on the editing form.',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `settings` text COLLATE utf8_unicode_ci COMMENT 'Serialized information',
  `view_modes` longtext COLLATE utf8_unicode_ci,
  `locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: (unlocked) users can edit this instance; 1: (locked) users can not modify this instance using web interface',
  `ordering` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `entity` (`table_alias`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `field_instances`
--

INSERT INTO `field_instances` (`id`, `slug`, `table_alias`, `handler`, `label`, `description`, `required`, `settings`, `view_modes`, `locked`, `ordering`) VALUES
(1, 'article-introduction', 'nodes_article', 'TextField', 'Introduction', 'Brief description', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:5:"plain";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 'a:5:{s:7:"default";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}s:6:"teaser";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}s:13:"search-result";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}s:3:"rss";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}s:4:"full";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}}', 0, 0),
(3, 'article-body', 'nodes_article', 'TextField', 'Body', '', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:8:"markdown";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:13:"search-result";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:4:"full";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}', 0, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `field_values`
--

INSERT INTO `field_values` (`id`, `field_instance_id`, `field_instance_slug`, `entity_id`, `table_alias`, `value`, `extra`) VALUES
(1, 1, 'article-introduction', '1', 'nodes_article', 'Lorem ipsum.[random]1,2,3,4,5[/random]', 'a:0:{}'),
(9, 3, 'article-body', '1', 'nodes_article', '# QuickApps CMS Site Skeleton\r\n\r\nA skeleton for creating web sites with [QuickAppsCMS](http://quickappscms.org) 2.0. This is an unstable repository and should be treated as an alpha.\r\n\r\n## Installation\r\n\r\n### Install with composer \r\n\r\n1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`. \r\n2. Run `php composer.phar create-project -s dev quickapps/website [website_name]`. \r\n\r\nIf Composer is installed globally, run `composer create-project -s dev quickapps/website [website_name]` After composer is done visit `http://example.com/` and start QuickAppsCMS installation.\r\n', 'a:0:{}'),
(10, 3, 'article-body', '2', 'nodes_article', 'Curabitur quis ultricies nisl. Donec eget rutrum nunc. Quisque accumsan, justo sit amet suscipit ullamcorper, nisl lacus dictum arcu, at vehicula enim velit et libero. Vivamus venenatis lacinia eros, et ultrices erat interdum vitae. Aliquam scelerisque leo in tristique tincidunt. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi iaculis nec quam sit amet viverra. Vestibulum sit amet faucibus elit, et mattis urna. In consequat justo vitae augue venenatis lacinia.', 'a:0:{}'),
(11, 1, 'article-introduction', '2', 'nodes_article', 'Curabitur quis ultricies nisl. Donec eget rutrum nunc. Quisque accumsan, justo sit amet suscipit ullamcorper, nisl lacus dictum arcu, at vehicula enim velit et libero.', 'a:0:{}');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(12) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Language code, e.g. ’eng’',
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Language name in English.',
  `direction` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ltr' COMMENT 'Direction of language (Left-to-Right , Right-to-Left ).',
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Enabled flag (1 = Enabled, 0 = Disabled).',
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT 'Weight, used in lists of languages.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `direction`, `icon`, `status`, `ordering`) VALUES
(1, 'en-us', 'English', 'ltr', 'us.gif', 1, 0),
(2, 'es', 'Spanish', 'ltr', 'es.gif', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Menu title, displayed at top of block.',
  `description` text COLLATE utf8_unicode_ci COMMENT 'Menu description.',
  `handler` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the plugin that created this menu.',
  `settings` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `slug`, `title`, `description`, `handler`, `settings`) VALUES
(1, 'management', 'Management', 'The Management menu contains links for administrative tasks.', 'System', NULL),
(2, 'site-main-menu', 'Site Main Menu', 'The Site Main Menu is used on many sites to show the major sections of the site, often in a top navigation bar.', 'System', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_links`
--

CREATE TABLE IF NOT EXISTS `menu_links` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL COMMENT 'All links with the same menu ID are part of the same menu.',
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT 'The parent link ID (plid) is the mlid of the link above in the hierarchy, or zero if the link is at the top level in its menu.',
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'the url',
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'The text displayed for the link, which may be modified by a title callback stored in menu_router.',
  `target` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self',
  `expanded` int(1) NOT NULL DEFAULT '1' COMMENT 'Flag for whether this link should be rendered as expanded in menus - expanded links always have their child links displayed, instead of only when the link is in the active trail (1 = expanded, 0 = not expanded)',
  `active` text COLLATE utf8_unicode_ci COMMENT 'php code, or regular expression. based on active_on_type',
  `activation` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'auto' COMMENT 'php: on php return TRUE. auto: auto-detect; any: request''s URL matches any in "active" column; none: request''s URL matches none of listed in "active" column',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `router_path` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `menu_links`
--

INSERT INTO `menu_links` (`id`, `menu_id`, `lft`, `rght`, `parent_id`, `url`, `description`, `title`, `target`, `expanded`, `active`, `activation`, `status`) VALUES
(1, 1, 1, 2, 0, '/admin/system/dashboard', NULL, 'Dashboard', '_self', 1, '/admin/system/dashboard\r\n/admin\r\n/admin/', 'any', 1),
(2, 1, 3, 12, 0, '/admin/system/structure', NULL, 'Structure', '_self', 0, NULL, 'auto', 1),
(3, 1, 13, 14, 0, '/admin/node/manage', NULL, 'Content', '_self', 0, '/admin/node/manage*\r\n/admin/node/comments*', 'any', 1),
(4, 1, 15, 16, 0, '/admin/system/themes', NULL, 'Themes', '_self', 0, NULL, NULL, 1),
(5, 1, 17, 18, 0, '/admin/system/plugins', NULL, 'Plugins', '_self', 0, NULL, NULL, 1),
(6, 1, 19, 20, 0, '/admin/user/manage', NULL, 'Users', '_self', 0, NULL, NULL, 1),
(7, 1, 23, 24, 0, '/admin/system/configuration', NULL, 'Configuration', '_self', 0, NULL, NULL, 1),
(8, 1, 25, 26, 0, '/admin/system/help', NULL, 'Help', '_self', 0, NULL, NULL, 1),
(9, 1, 4, 5, 2, '/admin/block/manage', 'Configure what block content appears in your site''s sidebars and other regions.', 'Blocks', '_self', 0, NULL, NULL, 1),
(10, 1, 6, 7, 2, '/admin/node/types', 'Manage content types.', 'Content Types', '_self', 0, NULL, NULL, 1),
(11, 1, 8, 9, 2, '/admin/menu/manage', 'Add new menus to your site, edit existing menus, and rename and reorganize menu links.', 'Menus', '_self', 0, NULL, NULL, 1),
(12, 1, 10, 11, 2, '/admin/taxonomy/manage', 'Manage tagging, categorization, and classification of your content.', 'Taxonomy', '_self', 0, NULL, NULL, 1),
(13, 1, 21, 22, 0, '/admin/locale', '', 'Languages', '_self', 0, NULL, NULL, 1),
(14, 2, 5, 6, 0, '/article/about.html', '', 'About', '_self', 0, NULL, NULL, 1),
(15, 2, 3, 4, 0, '/article/hooktags.html', '', 'Hooktags', '_self', 0, NULL, NULL, 1),
(16, 2, 1, 2, 0, '/', '', 'Home', '_self', 0, NULL, NULL, 1),
(17, 2, 7, 8, 0, '/find/type:article', '', 'Blog', '_self', 0, '/article/*.html\r\n/find/*type:article*', 'any', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_type_id` int(11) NOT NULL,
  `node_type_slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `translation_for` int(11) DEFAULT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `promote` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Show in front page?',
  `sticky` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Show at top of lists',
  `comment_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=closed, 1=open, 2=readonly',
  `language` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`id`, `node_type_id`, `node_type_slug`, `translation_for`, `slug`, `title`, `description`, `promote`, `sticky`, `comment_status`, `language`, `status`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(1, 1, 'article', NULL, 'my-first-article', 'My First Article!', 'Custom meta description', 1, 0, 1, '', 1, '2014-06-12 07:44:01', '2014-08-10 10:26:27', 1, 0),
(2, 1, 'article', NULL, 'curabitur-quis-ultricies-nisl', 'Curabitur quis ultricies nisl', 'Donec eget rutrum nunc. Vestibulum sit amet faucibus elit.', 1, 1, 0, '', 1, '2014-08-05 22:19:44', '2014-08-05 22:19:44', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `nodes_roles`
--

CREATE TABLE IF NOT EXISTS `nodes_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `role_id` int(10) NOT NULL COMMENT 'The user’s role ID from roles table',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  `defaults` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `node_types`
--

INSERT INTO `node_types` (`id`, `slug`, `name`, `description`, `title_label`, `defaults`) VALUES
(1, 'article', 'Article', 'Use articles for time-sensitive content like news, press releases or blog posts.', 'Title', 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"1";s:6:"sticky";s:1:"1";s:11:"author_name";s:1:"1";s:9:"show_date";s:1:"1";s:14:"comment_status";s:1:"0";s:8:"language";s:0:"";}');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `autoload` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1: true (autoload); 0:false',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `name`, `value`, `autoload`) VALUES
(1, 'front_theme', 'FrontendTheme', 1),
(2, 'default_language', 'en-us', 1),
(3, 'site_description', 'Open Source CMS built on CakePHP 3.0', 1),
(4, 'site_slogan', 'Open Source CMS built on CakePHP 3.0', 1),
(5, 'back_theme', 'BackendTheme', 1),
(6, 'site_title', 'My QuickApps CMS Site', 1),
(7, 'url_locale_prefix', '1', 1),
(8, 'site_email', 'demo@email.com', 0),
(9, 'site_maintenance_message', 'We sincerely apologize for the inconvenience.<br/>Our site is currently undergoing scheduled maintenance and upgrades, but will return shortly.<br/>Thanks you for your patience.', 0),
(10, 'site_maintenance_ip', NULL, 0),
(11, 'site_nodes_home', '5', 1),
(12, 'site_maintenance', '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aco_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `aco_id`, `role_id`) VALUES
(1, 73, 2),
(2, 73, 3),
(3, 74, 2),
(4, 74, 3),
(5, 75, 2),
(6, 75, 3),
(7, 76, 2),
(8, 76, 3),
(9, 77, 2),
(10, 77, 3),
(11, 80, 2),
(12, 80, 3),
(13, 79, 2),
(14, 79, 3),
(15, 78, 2),
(16, 78, 3),
(17, 3, 3),
(18, 4, 2),
(19, 5, 3),
(20, 6, 2),
(21, 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `package` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'composer package. e.g. user_name/plugin_name',
  `settings` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'serialized array of options',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='list of installed plugins';

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`name`, `package`, `settings`, `status`, `ordering`) VALUES
('BackendTheme', 'quickapps-theme/backend-theme', '', 1, 0),
('Block', 'quickapps-plugin/block', '', 1, 0),
('Comment', 'quickapps-plugin/comment', 'a:15:{s:12:"auto_approve";s:1:"0";s:15:"allow_anonymous";s:1:"1";s:14:"anonymous_name";s:1:"1";s:23:"anonymous_name_required";s:1:"1";s:15:"anonymous_email";s:1:"1";s:24:"anonymous_email_required";s:1:"1";s:13:"anonymous_web";s:1:"1";s:22:"anonymous_web_required";s:1:"0";s:15:"text_processing";s:5:"plain";s:8:"use_ayah";s:1:"1";s:18:"ayah_publisher_key";s:40:"a5613704624c0c213e3a51a3202dd22c1fc5f652";s:16:"ayah_scoring_key";s:40:"1bfe675e8061d1e83fc56090dbef62d4cc2e4912";s:11:"use_akismet";s:1:"0";s:11:"akismet_key";s:1:"s";s:14:"akismet_action";s:6:"delete";}', 1, 0),
('Field', 'quickapps-plugin/field', '', 1, 0),
('FrontendTheme', 'quickapps-theme/frontend-theme', '', 1, 0),
('Installer', 'quickapps-plugin/installer', '', 1, 0),
('Locale', 'quickapps-plugin/locale', '', 1, 0),
('Menu', 'quickapps-plugin/menu', '', 1, 0),
('Node', 'quickapps-plugin/node', '', 1, 0),
('Search', 'quickapps-plugin/search', '', 1, 0),
('System', 'quickapps-plugin/system', '', 1, 0),
('Taxonomy', 'quickapps-plugin/taxonomy', '', 1, 0),
('User', 'quickapps-plugin/user', '', 1, 0),
('Wysiwyg', 'quickapps-plugin/wysiwyg', '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`) VALUES
(1, 'administrator', 'Administrator'),
(2, 'authenticated ', 'Authenticated User'),
(3, 'anonymous', 'Anonymous User');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `search_datasets`
--

INSERT INTO `search_datasets` (`id`, `entity_id`, `table_alias`, `words`) VALUES
(1, '1', 'nodes', ' my first article custom meta description lorem ipsum random random quickapps cms site skeletona skeleton for creating web sites with quickappscms http quickappscms org this is an unstable repository and should be treated as an alpha installation install with composer download composer http getcomposer org doc intro md or update composer self update run php composer phar create project s dev quickapps website website name if composer is installed globally run composer create project s dev quickapps website website name after composer is done visit http example com and start quickappscms installation '),
(2, '2', 'nodes', ' curabitur quis ultricies nisl donec eget rutrum nunc vestibulum sit amet faucibus elit quisque accumsan justo suscipit ullamcorper lacus dictum arcu at vehicula enim velit et libero vivamus venenatis lacinia eros ultrices erat interdum vitae aliquam scelerisque leo in tristique tincidunt cum sociis natoque penatibus magnis dis parturient montes nascetur ridiculus mus morbi iaculis nec quam viverra mattis urna consequat augue ');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE IF NOT EXISTS `terms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vocabulary_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  `web` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
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

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `web`, `locale`, `code`, `status`, `last_login`, `created`) VALUES
(1, 'QuickApps CMS', 'admin', '$2y$10$EVI2DYmtDEGAqD0s9TbjL.wgbpKlSjLjeH70gXwKRhi6g5DpkR/Be', 'chris@quickapps.es', 'http://quickapps.es', 'en-us', '', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE IF NOT EXISTS `users_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(10) NOT NULL COMMENT 'The user’s role ID from roles table',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vocabularies`
--

CREATE TABLE IF NOT EXISTS `vocabularies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `ordering` int(11) DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if set to 1 users can not delete this vocabulary',
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
