-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `quickapps`
--

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

CREATE TABLE IF NOT EXISTS `acos` (
`id` int(10) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `plugin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias_hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `lft`, `rght`, `plugin`, `alias`, `alias_hash`) VALUES
(1, NULL, 1, 68, 'User', 'User', '8f9bfe9d1345237cb3b2b205864da075'),
(2, 1, 2, 15, 'User', 'Gateway', '926dec9494209cb088b4962509df1a91'),
(3, 2, 3, 4, 'User', 'login', 'd56b699830e77ba53855679cb1d252da'),
(4, 2, 5, 6, 'User', 'logout', '4236a440a662cc8253d7536e5aa17942'),
(5, 2, 7, 8, 'User', 'forgot', '790f6b6cf6a6fbead525927d69f409fe'),
(6, 2, 9, 10, 'User', 'me', 'ab86a1e1ef70dff97959067b723c5c24'),
(7, 2, 11, 12, 'User', 'profile', '7d97481b1fe66f4b51db90da7e794d9f'),
(8, 2, 13, 14, 'User', 'unauthorized', '36fd540552b3b1b34e8f0bd8897cbf1e'),
(9, 1, 16, 67, 'User', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(10, 9, 17, 34, 'User', 'Fields', 'a4ca5edd20d0b5d502ebece575681f58'),
(11, 10, 18, 19, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(12, 10, 20, 21, 'User', 'configure', 'e2d5a00791bce9a01f99bc6fd613a39d'),
(13, 10, 22, 23, 'User', 'attach', '915e375d95d78bf040a2e054caadfb56'),
(14, 10, 24, 25, 'User', 'detach', 'b6bc015ea9587c510c9017988e94e60d'),
(15, 10, 26, 27, 'User', 'view_mode_list', '50dc11f5c94a739237c8685e567a28d8'),
(16, 10, 28, 29, 'User', 'view_mode_edit', 'b04ebb03255647bd460b7f67b763fb89'),
(17, 10, 30, 31, 'User', 'view_mode_move', '6d54c39b597f25d371090b1de3bffbfa'),
(18, 10, 32, 33, 'User', 'move', '3734a903022249b3010be1897042568e'),
(19, 9, 35, 44, 'User', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(20, 19, 36, 37, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(21, 19, 38, 39, 'User', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(22, 19, 40, 41, 'User', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(23, 19, 42, 43, 'User', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(24, 9, 45, 56, 'User', 'Permissions', 'd08ccf52b4cdd08e41cfb99ec42e0b29'),
(25, 24, 46, 47, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(26, 24, 48, 49, 'User', 'aco', '111c03ddf31a2a03d3fa3377ab07eb56'),
(27, 24, 50, 51, 'User', 'update', '3ac340832f29c11538fbe2d6f75e8bcc'),
(28, 24, 52, 53, 'User', 'export', 'b2507468f95156358fa490fd543ad2f0'),
(29, 24, 54, 55, 'User', 'import', '93473a7344419b15c4219cc2b6c64c6f'),
(30, 9, 57, 66, 'User', 'Roles', 'a5cd3ed116608dac017f14c046ea56bf'),
(31, 30, 58, 59, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(32, 30, 60, 61, 'User', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(33, 30, 62, 63, 'User', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(34, 30, 64, 65, 'User', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(35, NULL, 69, 100, 'Taxonomy', 'Taxonomy', '30d10883c017c4fd6751c8982e20dae1'),
(36, 35, 70, 99, 'Taxonomy', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(37, 36, 71, 74, 'Taxonomy', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(38, 37, 72, 73, 'Taxonomy', 'index', '6a992d5529f459a44fee58c733255e86'),
(39, 36, 75, 78, 'Taxonomy', 'Tagger', 'e34d9224f0bf63992e1e77451c6976d1'),
(40, 39, 76, 77, 'Taxonomy', 'search', '06a943c59f33a34bb5924aaf72cd2995'),
(41, 36, 79, 88, 'Taxonomy', 'Terms', '6f1bf85c9ebb3c7fa26251e1e335e032'),
(42, 41, 80, 81, 'Taxonomy', 'vocabulary', '09f06963f502addfeab2a7c87f38802e'),
(43, 41, 82, 83, 'Taxonomy', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(44, 41, 84, 85, 'Taxonomy', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(45, 41, 86, 87, 'Taxonomy', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(46, 36, 89, 98, 'Taxonomy', 'Vocabularies', '81a419751eb59e7d35acab8e532d59a7'),
(47, 46, 90, 91, 'Taxonomy', 'index', '6a992d5529f459a44fee58c733255e86'),
(48, 46, 92, 93, 'Taxonomy', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(49, 46, 94, 95, 'Taxonomy', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(50, 46, 96, 97, 'Taxonomy', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(51, NULL, 101, 152, 'System', 'System', 'a45da96d0bf6575970f2d27af22be28a'),
(52, 51, 102, 151, 'System', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(53, 52, 103, 106, 'System', 'Configuration', '254f642527b45bc260048e30704edb39'),
(54, 53, 104, 105, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(55, 52, 107, 110, 'System', 'Dashboard', '2938c7f7e560ed972f8a4f68e80ff834'),
(56, 55, 108, 109, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(57, 52, 111, 116, 'System', 'Help', '6a26f548831e6a8c26bfbbd9f6ec61e0'),
(58, 57, 112, 113, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(59, 57, 114, 115, 'System', 'about', '46b3931b9959c927df4fc65fdee94b07'),
(60, 52, 117, 130, 'System', 'Plugins', 'bb38096ab39160dc20d44f3ea6b44507'),
(61, 60, 118, 119, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(62, 60, 120, 121, 'System', 'install', '19ad89bc3e3c9d7ef68b89523eff1987'),
(63, 60, 122, 123, 'System', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(64, 60, 124, 125, 'System', 'enable', '208f156d4a803025c284bb595a7576b4'),
(65, 60, 126, 127, 'System', 'disable', '0aaa87422396fdd678498793b6d5250e'),
(66, 60, 128, 129, 'System', 'settings', '2e5d8aa3dfa8ef34ca5131d20f9dad51'),
(67, 52, 131, 134, 'System', 'Structure', 'dc4c71563b9bc39a65be853457e6b7b6'),
(68, 67, 132, 133, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(69, 52, 135, 150, 'System', 'Themes', '83915d1254927f41241e8630890bec6e'),
(70, 69, 136, 137, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(71, 69, 138, 139, 'System', 'install', '19ad89bc3e3c9d7ef68b89523eff1987'),
(72, 69, 140, 141, 'System', 'uninstall', 'fe98497efedbe156ecc4b953aea77e07'),
(73, 69, 142, 143, 'System', 'activate', 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922'),
(74, 69, 144, 145, 'System', 'details', '27792947ed5d5da7c0d1f43327ed9dab'),
(75, 69, 146, 147, 'System', 'screenshot', '62c92ba585f74ecdbef4c4498a438984'),
(76, 69, 148, 149, 'System', 'settings', '2e5d8aa3dfa8ef34ca5131d20f9dad51'),
(77, NULL, 153, 224, 'Node', 'Node', '6c3a6944a808a7c0bbb6788dbec54a9f'),
(78, 77, 154, 165, 'Node', 'Serve', 'bc9a5b9e9259199a79f67ded0b508dfc'),
(79, 78, 155, 156, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(80, 78, 157, 158, 'Node', 'home', '106a6c241b8797f52e1e77317b96a201'),
(81, 78, 159, 160, 'Node', 'details', '27792947ed5d5da7c0d1f43327ed9dab'),
(82, 78, 161, 162, 'Node', 'search', '06a943c59f33a34bb5924aaf72cd2995'),
(83, 78, 163, 164, 'Node', 'rss', '8bb856027f758e85ddf2085c98ae2908'),
(84, 77, 166, 223, 'Node', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(85, 84, 167, 178, 'Node', 'Comments', '8413c683b4b27cc3f4dbd4c90329d8ba'),
(86, 85, 168, 169, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(87, 85, 170, 171, 'Node', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(88, 85, 172, 173, 'Node', 'status', '9acb44549b41563697bb490144ec6258'),
(89, 85, 174, 175, 'Node', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(90, 85, 176, 177, 'Node', 'empty_trash', '5e0e12d2aafec2a296b4d8ed252147b8'),
(91, 84, 179, 196, 'Node', 'Fields', 'a4ca5edd20d0b5d502ebece575681f58'),
(92, 91, 180, 181, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(93, 91, 182, 183, 'Node', 'configure', 'e2d5a00791bce9a01f99bc6fd613a39d'),
(94, 91, 184, 185, 'Node', 'attach', '915e375d95d78bf040a2e054caadfb56'),
(95, 91, 186, 187, 'Node', 'detach', 'b6bc015ea9587c510c9017988e94e60d'),
(96, 91, 188, 189, 'Node', 'view_mode_list', '50dc11f5c94a739237c8685e567a28d8'),
(97, 91, 190, 191, 'Node', 'view_mode_edit', 'b04ebb03255647bd460b7f67b763fb89'),
(98, 91, 192, 193, 'Node', 'view_mode_move', '6d54c39b597f25d371090b1de3bffbfa'),
(99, 91, 194, 195, 'Node', 'move', '3734a903022249b3010be1897042568e'),
(100, 84, 197, 212, 'Node', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(101, 100, 198, 199, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(102, 100, 200, 201, 'Node', 'create', '76ea0bebb3c22822b4f0dd9c9fd021c5'),
(103, 100, 202, 203, 'Node', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(104, 100, 204, 205, 'Node', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(105, 100, 206, 207, 'Node', 'translate', 'fc46e26a907870744758b76166150f62'),
(106, 100, 208, 209, 'Node', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(107, 100, 210, 211, 'Node', 'delete_revision', '077308769b80b2240aa845a5dff20436'),
(108, 84, 213, 222, 'Node', 'Types', 'f2d346b1bb7c1c85ab6f7f21e3666b9f'),
(109, 108, 214, 215, 'Node', 'index', '6a992d5529f459a44fee58c733255e86'),
(110, 108, 216, 217, 'Node', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(111, 108, 218, 219, 'Node', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(112, 108, 220, 221, 'Node', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(113, NULL, 225, 248, 'Menu', 'Menu', 'b61541208db7fa7dba42c85224405911'),
(114, 113, 226, 247, 'Menu', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(115, 114, 227, 236, 'Menu', 'Links', 'bd908db5ccb07777ced8023dffc802f4'),
(116, 115, 228, 229, 'Menu', 'menu', '8d6ab84ca2af9fccd4e4048694176ebf'),
(117, 115, 230, 231, 'Menu', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(118, 115, 232, 233, 'Menu', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(119, 115, 234, 235, 'Menu', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(120, 114, 237, 246, 'Menu', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(121, 120, 238, 239, 'Menu', 'index', '6a992d5529f459a44fee58c733255e86'),
(122, 120, 240, 241, 'Menu', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(123, 120, 242, 243, 'Menu', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(124, 120, 244, 245, 'Menu', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(125, NULL, 249, 270, 'Locale', 'Locale', '911f0f24bdce6808f4614d6a263b143b'),
(126, 125, 250, 269, 'Locale', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(127, 126, 251, 268, 'Locale', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(128, 127, 252, 253, 'Locale', 'index', '6a992d5529f459a44fee58c733255e86'),
(129, 127, 254, 255, 'Locale', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(130, 127, 256, 257, 'Locale', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(131, 127, 258, 259, 'Locale', 'set_default', '4889ae9437342e57d774bc6d5705c7a4'),
(132, 127, 260, 261, 'Locale', 'move', '3734a903022249b3010be1897042568e'),
(133, 127, 262, 263, 'Locale', 'enable', '208f156d4a803025c284bb595a7576b4'),
(134, 127, 264, 265, 'Locale', 'disable', '0aaa87422396fdd678498793b6d5250e'),
(135, 127, 266, 267, 'Locale', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(136, NULL, 271, 288, 'Installer', 'Installer', 'd1be377656960ed04f1564da21d80c8d'),
(137, 136, 272, 287, 'Installer', 'Startup', '13e685964c2548aa748f7ea263bad4e5'),
(138, 137, 273, 274, 'Installer', 'index', '6a992d5529f459a44fee58c733255e86'),
(139, 137, 275, 276, 'Installer', 'language', '8512ae7d57b1396273f76fe6ed341a23'),
(140, 137, 277, 278, 'Installer', 'requirements', 'b4851e92b19af0c5c82447fc0937709d'),
(141, 137, 279, 280, 'Installer', 'license', '718779752b851ac0dc6281a8c8d77e7e'),
(142, 137, 281, 282, 'Installer', 'database', '11e0eed8d3696c0a632f822df385ab3c'),
(143, 137, 283, 284, 'Installer', 'account', 'e268443e43d93dab7ebef303bbe9642f'),
(144, 137, 285, 286, 'Installer', 'finish', '3248bc7547ce97b2a197b2a06cf7283d'),
(145, NULL, 289, 304, 'Block', 'Block', 'e1e4c8c9ccd9fc39c391da4bcd093fb2'),
(146, 145, 290, 303, 'Block', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(147, 146, 291, 302, 'Block', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(148, 147, 292, 293, 'Block', 'index', '6a992d5529f459a44fee58c733255e86'),
(149, 147, 294, 295, 'Block', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(150, 147, 296, 297, 'Block', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(151, 147, 298, 299, 'Block', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(152, 147, 300, 301, 'Block', 'duplicate', '24f1b0a79473250c195c7fb84e393392'),
(153, NULL, 305, 316, 'Wysiwyg', 'Wysiwyg', 'fcb1d5c3299a281fbb55851547dfac9e'),
(154, 153, 306, 315, 'Wysiwyg', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(155, 154, 307, 314, 'Wysiwyg', 'Finder', 'd151508da8d36994e1635f7875594424'),
(156, 155, 308, 309, 'Wysiwyg', 'index', '6a992d5529f459a44fee58c733255e86'),
(157, 155, 310, 311, 'Wysiwyg', 'connector', '266e0d3d29830abfe7d4ed98b47966f7'),
(158, 155, 312, 313, 'Wysiwyg', 'plugin_file', '53fcd0f3eb0844a4d22699a9b73a77cd');

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
`id` int(11) NOT NULL COMMENT 'Primary Key - Unique block ID.',
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
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `copy_id`, `delta`, `handler`, `title`, `description`, `body`, `visibility`, `pages`, `locale`, `settings`, `status`) VALUES
(1, NULL, '1', 'System', 'Management [menu:1]', 'Associated block for "Management" menu.', NULL, 'except', NULL, NULL, NULL, 1),
(2, NULL, '2', 'System', 'Site Main Menu [menu:2]', 'Associated block for "Site Main Menu" menu.', NULL, 'except', NULL, NULL, NULL, 1),
(3, NULL, 'dashboard_recent_content', 'Node', 'Recent Content', 'Shows a list of latest created contents.', NULL, 'except', NULL, NULL, NULL, 1),
(4, NULL, 'dashboard_search', 'Node', 'Search', 'Quick Search Form', NULL, 'except', NULL, NULL, NULL, 1),
(5, NULL, 'language_switcher', 'Locale', 'Change Language', 'Language switcher block', NULL, 'except', '', '', 'a:2:{s:4:"type";s:4:"html";s:5:"flags";s:1:"1";}', 1),
(6, NULL, 'categories', 'Taxonomy', 'Categories', 'List of terms block', NULL, 'except', '', '', 'a:4:{s:12:"vocabularies";a:1:{i:0;s:1:"1";}s:13:"show_counters";s:1:"1";s:15:"show_vocabulary";s:1:"0";s:13:"link_template";s:0:"";}', 1),
(7, NULL, 'sub-menu', 'User', 'User sub-menu', 'Provides links to user''s account, login, logout, etc', NULL, 'except', '', '', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `blocks_roles`
--

CREATE TABLE IF NOT EXISTS `blocks_roles` (
`id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  `role_id` int(10) NOT NULL COMMENT 'The user’s role ID from roles table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `block_regions`
--

CREATE TABLE IF NOT EXISTS `block_regions` (
`id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  `theme` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(8, 4, 'FrontendTheme', '', 0),
(9, 7, 'BackendTheme', '', 0),
(10, 7, 'FrontendTheme', 'sub-menu', 0),
(11, 5, 'BackendTheme', '', 0),
(12, 5, 'FrontendTheme', 'sub-menu', 0),
(13, 6, 'BackendTheme', '', 0),
(14, 6, 'FrontendTheme', 'right-sidebar', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` int(11) NOT NULL,
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
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `entities_terms`
--

CREATE TABLE IF NOT EXISTS `entities_terms` (
`id` int(20) NOT NULL,
  `entity_id` int(20) NOT NULL,
  `term_id` int(20) NOT NULL,
  `field_instance_id` int(11) NOT NULL,
  `table_alias` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `entities_terms`
--

INSERT INTO `entities_terms` (`id`, `entity_id`, `term_id`, `field_instance_id`, `table_alias`) VALUES
(1, 1, 1, 5, 'nodes'),
(2, 1, 5, 5, 'nodes');

-- --------------------------------------------------------

--
-- Table structure for table `field_instances`
--

CREATE TABLE IF NOT EXISTS `field_instances` (
`id` int(10) NOT NULL,
  `slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Machine name, must be unique',
  `table_alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the table to which this field belongs to. eg: comment, node_article. Must be unique',
  `handler` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of event handler class under the `Field` namespace',
  `label` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Human readble name, used in views. eg: `First Name` (for a textbox)',
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'instructions to present to the user below this field on the editing form.',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `settings` text COLLATE utf8_unicode_ci COMMENT 'Serialized information',
  `view_modes` longtext COLLATE utf8_unicode_ci,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'varchar' COMMENT 'Data type for this field (datetime, decimal, int, text, varchar)',
  `locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: (unlocked) users can edit this instance; 1: (locked) users can not modify this instance using web interface',
  `ordering` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `field_instances`
--

INSERT INTO `field_instances` (`id`, `slug`, `table_alias`, `handler`, `label`, `description`, `required`, `settings`, `view_modes`, `type`, `locked`, `ordering`) VALUES
(1, 'article-introduction', 'nodes:article', 'TextField', 'Introduction', 'Brief description', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:5:"plain";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:3:"rss";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}', 'text', 0, 0),
(3, 'article-body', 'nodes:article', 'TextField', 'Body', '', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:13:"search-result";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:3:"rss";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}', 'text', 0, 1),
(4, 'page-body', 'nodes:page', 'TextField', 'Body', 'Page content', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"400";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}', 'text', 0, 0),
(5, 'article-category', 'nodes:article', 'TaxonomyField', 'Category', '', 0, 'a:4:{s:10:"vocabulary";s:1:"1";s:4:"type";s:6:"select";s:10:"max_values";s:1:"0";s:13:"error_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:40:"<a href="{url}"{{attrs}}>{{content}}</a>";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:2;s:9:"formatter";s:5:"plain";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}}', 'text', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `field_values`
--

CREATE TABLE IF NOT EXISTS `field_values` (
`id` int(20) NOT NULL,
  `field_instance_id` int(10) NOT NULL,
  `field_instance_slug` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `entity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'id of the entity in `table`',
  `table_alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'varchar' COMMENT 'Instance''s data type for this field (datetime, decimal, int, text, varchar)',
  `value_datetime` datetime DEFAULT NULL,
  `value_decimal` decimal(10,0) DEFAULT NULL,
  `value_int` int(11) DEFAULT NULL,
  `value_text` text COLLATE utf8_unicode_ci,
  `value_varchar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra` text COLLATE utf8_unicode_ci COMMENT 'Extra information required by this field hadnler'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `field_values`
--

INSERT INTO `field_values` (`id`, `field_instance_id`, `field_instance_slug`, `entity_id`, `table_alias`, `type`, `value_datetime`, `value_decimal`, `value_int`, `value_text`, `value_varchar`, `extra`) VALUES
(1, 1, 'article-introduction', '1', 'nodes:article', 'text', NULL, NULL, NULL, 'Welcome to QuickAppsCMS. This is an example content.', NULL, 'a:0:{}'),
(9, 3, 'article-body', '1', 'nodes:article', 'text', NULL, NULL, NULL, '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>\r\n\r\n<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc</p>\r\n', NULL, 'a:0:{}'),
(12, 4, 'page-body', '3', 'nodes:page', 'text', NULL, NULL, NULL, '<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.&nbsp;<span style="line-height:1.6">Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.</span></p>\r\n\r\n<p>Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.</p>\r\n\r\n<p>Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</p>\r\n', NULL, 'a:0:{}'),
(13, 5, 'article-category', '1', 'nodes:article', 'text', NULL, NULL, NULL, 'PHP QuickAppsCMS', NULL, 'a:2:{i:0;s:1:"1";i:1;s:1:"5";}');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
`id` int(11) NOT NULL,
  `code` varchar(12) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Language code, e.g. ’eng’',
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Language name in English.',
  `direction` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ltr' COMMENT 'Direction of language (Left-to-Right , Right-to-Left ).',
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Enabled flag (1 = Enabled, 0 = Disabled).',
  `ordering` int(11) NOT NULL DEFAULT '0' COMMENT 'Weight, used in lists of languages.'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `code`, `name`, `direction`, `icon`, `status`, `ordering`) VALUES
(1, 'en_US', 'English (US)', 'ltr', 'us.gif', 1, 0),
(2, 'es_ES', 'Spanish (ES)', 'ltr', 'es.gif', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
`id` int(11) NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Menu title, displayed at top of block.',
  `description` text COLLATE utf8_unicode_ci COMMENT 'Menu description.',
  `handler` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the plugin that created this menu.',
  `settings` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
`id` int(10) NOT NULL,
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
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menu_links`
--

INSERT INTO `menu_links` (`id`, `menu_id`, `lft`, `rght`, `parent_id`, `url`, `description`, `title`, `target`, `expanded`, `active`, `activation`, `status`) VALUES
(1, 1, 1, 2, 0, '/admin/system/dashboard', NULL, 'Dashboard', '_self', 1, '/admin/system/dashboard\r\n/admin\r\n/admin/', 'any', 1),
(2, 1, 3, 10, 0, '/admin/system/structure', NULL, 'Structure', '_self', 0, NULL, 'auto', 1),
(3, 1, 11, 12, 0, '/admin/node/manage', NULL, 'Content', '_self', 0, '/admin/node/manage*\r\n/admin/node/comments*\r\n/admin/node/types*\r\n/admin/node/fields*', 'any', 1),
(4, 1, 13, 14, 0, '/admin/system/themes', NULL, 'Themes', '_self', 0, NULL, NULL, 1),
(5, 1, 15, 16, 0, '/admin/system/plugins', NULL, 'Plugins', '_self', 0, NULL, NULL, 1),
(6, 1, 17, 18, 0, '/admin/user/manage', NULL, 'Users', '_self', 0, NULL, NULL, 1),
(7, 1, 21, 22, 0, '/admin/system/configuration', NULL, 'Configuration', '_self', 0, NULL, NULL, 1),
(8, 1, 23, 24, 0, '/admin/system/help', NULL, 'Help', '_self', 0, NULL, NULL, 1),
(9, 1, 4, 5, 2, '/admin/block/manage', 'Configure what block content appears in your site''s sidebars and other regions.', 'Blocks', '_self', 0, NULL, NULL, 1),
(11, 1, 6, 7, 2, '/admin/menu/manage', 'Add new menus to your site, edit existing menus, and rename and reorganize menu links.', 'Menus', '_self', 0, NULL, NULL, 1),
(12, 1, 8, 9, 2, '/admin/taxonomy/manage', 'Manage tagging, categorization, and classification of your content.', 'Taxonomy', '_self', 0, NULL, NULL, 1),
(13, 1, 19, 20, 0, '/admin/locale', '', 'Languages', '_self', 0, NULL, NULL, 1),
(14, 2, 3, 4, 0, '/page/about.html', '', 'About', '_self', 0, NULL, NULL, 1),
(16, 2, 1, 2, 0, '/', '', 'Home', '_self', 0, NULL, NULL, 1),
(17, 2, 5, 6, 0, '/find/type:article', '', 'Blog', '_self', 0, '/article/*.html\r\n/find/*type:article*', 'any', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
`id` int(11) NOT NULL,
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
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`id`, `node_type_id`, `node_type_slug`, `translation_for`, `slug`, `title`, `description`, `promote`, `sticky`, `comment_status`, `language`, `status`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(1, 1, 'article', NULL, 'hello-world', '¡Hello World!', 'hello world demo article', 1, 0, 1, '', 1, '2014-06-12 07:44:01', '2015-03-31 21:30:29', 1, 1),
(3, 2, 'page', NULL, 'about', 'About', 'about QuickAppsCMS demo page', 0, 0, 0, '', 1, '2015-03-31 21:06:50', '2015-03-31 21:06:50', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `nodes_roles`
--

CREATE TABLE IF NOT EXISTS `nodes_roles` (
`id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `role_id` int(10) NOT NULL COMMENT 'The user’s role ID from roles table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `node_revisions`
--

CREATE TABLE IF NOT EXISTS `node_revisions` (
`id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `node_types`
--

CREATE TABLE IF NOT EXISTS `node_types` (
`id` int(11) NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'human-readable name',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_label` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'the label displayed for the title field on the edit form.',
  `defaults` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `node_types`
--

INSERT INTO `node_types` (`id`, `slug`, `name`, `description`, `title_label`, `defaults`) VALUES
(1, 'article', 'Article', 'Use <em>Articles</em> for time-sensitive content like news, press releases or blog posts.', 'Title', 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"1";s:6:"sticky";s:1:"1";s:11:"author_name";s:1:"1";s:9:"show_date";s:1:"1";s:14:"comment_status";s:1:"1";s:8:"language";s:0:"";}'),
(2, 'page', 'Basic Page', 'Use <em>Basic Pages</em> for your static content, such as an ''About us'' page.', 'Title', 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"0";s:6:"sticky";s:1:"0";s:11:"author_name";s:1:"0";s:9:"show_date";s:1:"0";s:14:"comment_status";s:1:"0";s:8:"language";s:0:"";}');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `autoload` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1: true (autoload); 0:false'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `name`, `value`, `autoload`) VALUES
(1, 'front_theme', 'FrontendTheme', 1),
(2, 'default_language', 'en_US', 1),
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
`id` int(11) NOT NULL,
  `aco_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `aco_id`, `role_id`) VALUES
(1, 79, 2),
(2, 79, 3),
(3, 80, 2),
(4, 80, 3),
(5, 81, 2),
(6, 81, 3),
(7, 82, 2),
(8, 82, 3),
(9, 83, 2),
(10, 83, 3);

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `package` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'composer package. e.g. user_name/plugin_name',
  `settings` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'serialized array of options',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='list of installed plugins';

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`name`, `package`, `settings`, `status`, `ordering`) VALUES
('BackendTheme', 'quickapps-theme/backend-theme', '', 1, 0),
('Block', 'quickapps-plugin/block', '', 1, 1),
('Bootstrap', 'quickapps-plugin/bootstrap', '', 1, 2),
('Comment', 'quickapps-plugin/comment', 'a:15:{s:12:"auto_approve";s:1:"0";s:15:"allow_anonymous";s:1:"1";s:14:"anonymous_name";s:1:"1";s:23:"anonymous_name_required";s:1:"1";s:15:"anonymous_email";s:1:"1";s:24:"anonymous_email_required";s:1:"1";s:13:"anonymous_web";s:1:"1";s:22:"anonymous_web_required";s:1:"0";s:15:"text_processing";s:5:"plain";s:8:"use_ayah";s:1:"1";s:18:"ayah_publisher_key";s:40:"a5613704624c0c213e3a51a3202dd22c1fc5f652";s:16:"ayah_scoring_key";s:40:"1bfe675e8061d1e83fc56090dbef62d4cc2e4912";s:11:"use_akismet";s:1:"0";s:11:"akismet_key";s:1:"s";s:14:"akismet_action";s:6:"delete";}', 1, 3),
('Field', 'quickapps-plugin/field', '', 1, 4),
('FrontendTheme', 'quickapps-theme/frontend-theme', '', 1, 5),
('Installer', 'quickapps-plugin/installer', '', 1, 6),
('Jquery', 'quickapps-plugin/jquery', '', 1, 7),
('Locale', 'quickapps-plugin/locale', '', 1, 8),
('Menu', 'quickapps-plugin/menu', '', 1, 9),
('Node', 'quickapps-plugin/node', '', 1, 10),
('Search', 'quickapps-plugin/search', '', 1, 11),
('System', 'quickapps-plugin/system', '', 1, 12),
('Taxonomy', 'quickapps-plugin/taxonomy', '', 1, 13),
('User', 'quickapps-plugin/user', 'a:17:{s:21:"failed_login_attempts";s:0:"";s:35:"failed_login_attempts_block_seconds";s:0:"";s:23:"message_welcome_subject";s:50:"Account details for {{user:name}} at {{site:name}}";s:20:"message_welcome_body";s:462:"{{user:name}},\r\n\r\nThank you for registering at {{site:name}}. You may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{{user:one-time-login-url}}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {{site:login-url}} in the future using:\r\n\r\nusername: {{user:name}}\r\npassword: Your password\r\n\r\n--  {{site:name}} team";s:18:"message_activation";s:1:"1";s:26:"message_activation_subject";s:61:"Account details for {{user:name}} at {{site:name}} (approved)";s:23:"message_activation_body";s:473:"{{user:name}},\r\n\r\nYour account at {{site:name}} has been activated.\r\n\r\nYou may now log in by clicking this link or copying and pasting it into your browser:\r\n\r\n{{user:one-time-login-url}}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {{site:login-url}} in the future using:\r\n\r\nusername: {{user:name}}\r\npassword: Your password\r\n\r\n--  {{site:name}} team";s:15:"message_blocked";s:1:"1";s:23:"message_blocked_subject";s:60:"Account details for {{user:name}} at {{site:name}} (blocked)";s:20:"message_blocked_body";s:91:"{{user:name}},\r\n\r\nYour account on {{site:name}} has been blocked.\r\n\r\n--  {{site:name}} team";s:33:"message_password_recovery_subject";s:65:"Password recovery instructions for {{user:name}} at {{site:name}}";s:30:"message_password_recovery_body";s:348:"{{user:name}},\r\n\r\nA request to reset the password for your account has been made at {{site:name}}.\r\n\r\nYou may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{{user:one-time-login-url}}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\n--  {{site:name}} team";s:30:"message_cancel_request_subject";s:63:"Account cancellation request for {{user:name}} at {{site:name}}";s:27:"message_cancel_request_body";s:310:"{{user:name}},\r\n\r\nA request to cancel your account has been made at {{site:name}}.\r\n\r\nYou may now cancel your account on {{site:url}} by clicking this link or copying and pasting it into your browser:\r\n\r\n{{user:cancel-url}}\r\n\r\nNOTE: The cancellation of your account is not reversible.\r\n\r\n--  {{site:name}} team";s:16:"message_canceled";s:1:"1";s:24:"message_canceled_subject";s:61:"Account details for {{user:name}} at {{site:name}} (canceled)";s:21:"message_canceled_body";s:92:"{{user:name}},\r\n\r\nYour account on {{site:name}} has been canceled.\r\n\r\n--  {{site:name}} team";}', 1, 14),
('Wysiwyg', 'quickapps-plugin/wysiwyg', '', 1, 15);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
`id` int(11) NOT NULL,
  `slug` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`) VALUES
(1, 'administrator', 'Administrator'),
(2, 'authenticated', 'Authenticated User'),
(3, 'anonymous', 'Anonymous User');

-- --------------------------------------------------------

--
-- Table structure for table `search_datasets`
--

CREATE TABLE IF NOT EXISTS `search_datasets` (
`id` int(11) NOT NULL,
  `entity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `table_alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `words` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `search_datasets`
--

INSERT INTO `search_datasets` (`id`, `entity_id`, `table_alias`, `words`) VALUES
(1, '1', 'nodes', ' '),
(3, '3', 'nodes', ' about about quickappscms demo page p duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi nbsp span style line height nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum span p p typi non habent claritatem insitam est usus legentis in iis qui facit eorum claritatem investigationes demonstraverunt lectores legere me lius quod ii legunt saepius claritas est etiam processus dynamicus qui sequitur mutationem consuetudium lectorum p p mirum est notare quam littera gothica quam nunc putamus parum claram anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima eodem modo typi qui nunc nobis videntur parum clari fiant sollemnes in futurum p ');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE IF NOT EXISTS `terms` (
`id` int(10) NOT NULL,
  `vocabulary_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `vocabulary_id`, `lft`, `rght`, `parent_id`, `name`, `slug`, `created`, `modified`) VALUES
(1, 1, 1, 6, 0, 'PHP', 'php', '2015-03-31 21:20:39', '2015-03-31 21:20:39'),
(2, 1, 7, 10, 0, 'JavaScript', 'javascript', '2015-03-31 21:20:51', '2015-03-31 21:20:51'),
(3, 1, 2, 3, 1, 'CakePHP', 'cakephp', '2015-03-31 21:20:56', '2015-03-31 21:20:56'),
(4, 1, 8, 9, 2, 'jQuery', 'jquery', '2015-03-31 21:21:01', '2015-03-31 21:21:01'),
(5, 1, 4, 5, 1, 'QuickAppsCMS', 'quickappscms', '2015-03-31 21:21:07', '2015-03-31 21:21:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `web` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_profile` tinyint(1) NOT NULL,
  `public_email` tinyint(1) NOT NULL,
  `token` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'random unique code, used for pass recovery',
  `token_expiration` datetime DEFAULT NULL COMMENT 'expiration date of user token',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `web`, `locale`, `public_profile`, `public_email`, `token`, `token_expiration`, `status`, `last_login`, `created`) VALUES
(1, 'QuickApps CMS', 'admin', '$2y$10$EVI2DYmtDEGAqD0s9TbjL.wgbpKlSjLjeH70gXwKRhi6g5DpkR/Be', 'chris@quickapps.es', 'http://quickapps.es', 'en-us', 0, 0, '', NULL, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE IF NOT EXISTS `users_roles` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(10) NOT NULL COMMENT 'The user’s role ID from roles table'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
`id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `ordering` int(11) DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if set to 1 users can not delete this vocabulary',
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vocabularies`
--

INSERT INTO `vocabularies` (`id`, `name`, `slug`, `description`, `ordering`, `locked`, `modified`, `created`) VALUES
(1, 'Articles Categories', 'articles-categories', '', 0, 0, '2015-03-31 21:20:06', '2015-03-31 21:20:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acos`
--
ALTER TABLE `acos`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `delta` (`delta`,`handler`);

--
-- Indexes for table `blocks_roles`
--
ALTER TABLE `blocks_roles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `block_regions`
--
ALTER TABLE `block_regions`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `block_id` (`block_id`,`theme`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entities_terms`
--
ALTER TABLE `entities_terms`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `field_instances`
--
ALTER TABLE `field_instances`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`), ADD KEY `entity` (`table_alias`), ADD KEY `id` (`id`);

--
-- Indexes for table `field_values`
--
ALTER TABLE `field_values`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_links`
--
ALTER TABLE `menu_links`
 ADD PRIMARY KEY (`id`), ADD KEY `router_path` (`url`);

--
-- Indexes for table `nodes`
--
ALTER TABLE `nodes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nodes_roles`
--
ALTER TABLE `nodes_roles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_revisions`
--
ALTER TABLE `node_revisions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node_types`
--
ALTER TABLE `node_types`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plugins`
--
ALTER TABLE `plugins`
 ADD PRIMARY KEY (`name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `search_datasets`
--
ALTER TABLE `search_datasets`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `entity_id` (`entity_id`,`table_alias`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vocabularies`
--
ALTER TABLE `vocabularies`
 ADD PRIMARY KEY (`id`), ADD KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acos`
--
ALTER TABLE `acos`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=159;
--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key - Unique block ID.',AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `blocks_roles`
--
ALTER TABLE `blocks_roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `block_regions`
--
ALTER TABLE `block_regions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `entities_terms`
--
ALTER TABLE `entities_terms`
MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `field_instances`
--
ALTER TABLE `field_instances`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `field_values`
--
ALTER TABLE `field_values`
MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `menu_links`
--
ALTER TABLE `menu_links`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `nodes`
--
ALTER TABLE `nodes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `nodes_roles`
--
ALTER TABLE `nodes_roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_revisions`
--
ALTER TABLE `node_revisions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_types`
--
ALTER TABLE `node_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `search_datasets`
--
ALTER TABLE `search_datasets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users_roles`
--
ALTER TABLE `users_roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vocabularies`
--
ALTER TABLE `vocabularies`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
