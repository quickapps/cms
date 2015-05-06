-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `lft`, `rght`, `plugin`, `alias`, `alias_hash`) VALUES
(1, NULL, 1, 16, 'Block', 'Block', 'e1e4c8c9ccd9fc39c391da4bcd093fb2'),
(2, 1, 2, 15, 'Block', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(3, 2, 3, 14, 'Block', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(4, 3, 4, 5, 'Block', 'index', '6a992d5529f459a44fee58c733255e86'),
(5, 3, 6, 7, 'Block', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(6, 3, 8, 9, 'Block', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(7, 3, 10, 11, 'Block', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(8, 3, 12, 13, 'Block', 'duplicate', '24f1b0a79473250c195c7fb84e393392'),
(9, NULL, 17, 88, 'Content', 'Content', 'f15c1cae7882448b3fb0404682e17e61'),
(10, 9, 18, 29, 'Content', 'Serve', 'bc9a5b9e9259199a79f67ded0b508dfc'),
(11, 10, 19, 20, 'Content', 'index', '6a992d5529f459a44fee58c733255e86'),
(12, 10, 21, 22, 'Content', 'home', '106a6c241b8797f52e1e77317b96a201'),
(13, 10, 23, 24, 'Content', 'details', '27792947ed5d5da7c0d1f43327ed9dab'),
(14, 10, 25, 26, 'Content', 'search', '06a943c59f33a34bb5924aaf72cd2995'),
(15, 10, 27, 28, 'Content', 'rss', '8bb856027f758e85ddf2085c98ae2908'),
(16, 9, 30, 87, 'Content', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(17, 16, 31, 42, 'Content', 'Comments', '8413c683b4b27cc3f4dbd4c90329d8ba'),
(18, 17, 32, 33, 'Content', 'index', '6a992d5529f459a44fee58c733255e86'),
(19, 17, 34, 35, 'Content', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(20, 17, 36, 37, 'Content', 'status', '9acb44549b41563697bb490144ec6258'),
(21, 17, 38, 39, 'Content', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(22, 17, 40, 41, 'Content', 'emptyTrash', 'b13b982da42afc395fd5f9ad46381e23'),
(23, 16, 43, 60, 'Content', 'Fields', 'a4ca5edd20d0b5d502ebece575681f58'),
(24, 23, 44, 45, 'Content', 'index', '6a992d5529f459a44fee58c733255e86'),
(25, 23, 46, 47, 'Content', 'configure', 'e2d5a00791bce9a01f99bc6fd613a39d'),
(26, 23, 48, 49, 'Content', 'attach', '915e375d95d78bf040a2e054caadfb56'),
(27, 23, 50, 51, 'Content', 'detach', 'b6bc015ea9587c510c9017988e94e60d'),
(28, 23, 52, 53, 'Content', 'viewModeList', 'f6730c40f9e93768852502275e0c9ed5'),
(29, 23, 54, 55, 'Content', 'viewModeEdit', 'ecb551e7896f4007ed8df082a8184878'),
(30, 23, 56, 57, 'Content', 'viewModeMove', '5a2933f9feebe723793d21c183be08c6'),
(31, 23, 58, 59, 'Content', 'move', '3734a903022249b3010be1897042568e'),
(32, 16, 61, 76, 'Content', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(33, 32, 62, 63, 'Content', 'index', '6a992d5529f459a44fee58c733255e86'),
(34, 32, 64, 65, 'Content', 'create', '76ea0bebb3c22822b4f0dd9c9fd021c5'),
(35, 32, 66, 67, 'Content', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(36, 32, 68, 69, 'Content', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(37, 32, 70, 71, 'Content', 'translate', 'fc46e26a907870744758b76166150f62'),
(38, 32, 72, 73, 'Content', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(39, 32, 74, 75, 'Content', 'deleteRevision', '0049d291ee36657bd271c65979383af3'),
(40, 16, 77, 86, 'Content', 'Types', 'f2d346b1bb7c1c85ab6f7f21e3666b9f'),
(41, 40, 78, 79, 'Content', 'index', '6a992d5529f459a44fee58c733255e86'),
(42, 40, 80, 81, 'Content', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(43, 40, 82, 83, 'Content', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(44, 40, 84, 85, 'Content', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(45, NULL, 89, 100, 'Field', 'Field', '6f16a5f8ff5d75ab84c018adacdfcbb7'),
(46, 45, 90, 95, 'Field', 'FileHandler', 'd3d5308974962037be1ce87e7b7bbfe2'),
(47, 46, 91, 92, 'Field', 'upload', '76ee3de97a1b8b903319b7c013d8c877'),
(48, 46, 93, 94, 'Field', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(49, 45, 96, 99, 'Field', 'ImageHandler', '0f6984d93393387e52ea884c7ca0fd93'),
(50, 49, 97, 98, 'Field', 'thumbnail', '951d4dff3c22e9fcc4a2707009f45ea8'),
(51, NULL, 101, 118, 'Installer', 'Installer', 'd1be377656960ed04f1564da21d80c8d'),
(52, 51, 102, 117, 'Installer', 'Startup', '13e685964c2548aa748f7ea263bad4e5'),
(53, 52, 103, 104, 'Installer', 'index', '6a992d5529f459a44fee58c733255e86'),
(54, 52, 105, 106, 'Installer', 'language', '8512ae7d57b1396273f76fe6ed341a23'),
(55, 52, 107, 108, 'Installer', 'requirements', 'b4851e92b19af0c5c82447fc0937709d'),
(56, 52, 109, 110, 'Installer', 'license', '718779752b851ac0dc6281a8c8d77e7e'),
(57, 52, 111, 112, 'Installer', 'database', '11e0eed8d3696c0a632f822df385ab3c'),
(58, 52, 113, 114, 'Installer', 'account', 'e268443e43d93dab7ebef303bbe9642f'),
(59, 52, 115, 116, 'Installer', 'finish', '3248bc7547ce97b2a197b2a06cf7283d'),
(60, NULL, 119, 140, 'Locale', 'Locale', '911f0f24bdce6808f4614d6a263b143b'),
(61, 60, 120, 139, 'Locale', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(62, 61, 121, 138, 'Locale', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(63, 62, 122, 123, 'Locale', 'index', '6a992d5529f459a44fee58c733255e86'),
(64, 62, 124, 125, 'Locale', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(65, 62, 126, 127, 'Locale', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(66, 62, 128, 129, 'Locale', 'setDefault', 'd16b26f218cfb8cde187e3b95a78813c'),
(67, 62, 130, 131, 'Locale', 'move', '3734a903022249b3010be1897042568e'),
(68, 62, 132, 133, 'Locale', 'enable', '208f156d4a803025c284bb595a7576b4'),
(69, 62, 134, 135, 'Locale', 'disable', '0aaa87422396fdd678498793b6d5250e'),
(70, 62, 136, 137, 'Locale', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(71, NULL, 141, 152, 'MediaManager', 'MediaManager', 'ce0a1f03091160e6528b72e9f9ea7eff'),
(72, 71, 142, 151, 'MediaManager', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(73, 72, 143, 150, 'MediaManager', 'Explorer', '94fbbf67e0c8cea8cbaff55287746f3e'),
(74, 73, 144, 145, 'MediaManager', 'index', '6a992d5529f459a44fee58c733255e86'),
(75, 73, 146, 147, 'MediaManager', 'connector', '266e0d3d29830abfe7d4ed98b47966f7'),
(76, 73, 148, 149, 'MediaManager', 'pluginFile', 'a840980787c4260a4a710f753641a8c6'),
(77, NULL, 153, 176, 'Menu', 'Menu', 'b61541208db7fa7dba42c85224405911'),
(78, 77, 154, 175, 'Menu', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(79, 78, 155, 164, 'Menu', 'Links', 'bd908db5ccb07777ced8023dffc802f4'),
(80, 79, 156, 157, 'Menu', 'menu', '8d6ab84ca2af9fccd4e4048694176ebf'),
(81, 79, 158, 159, 'Menu', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(82, 79, 160, 161, 'Menu', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(83, 79, 162, 163, 'Menu', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(84, 78, 165, 174, 'Menu', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(85, 84, 166, 167, 'Menu', 'index', '6a992d5529f459a44fee58c733255e86'),
(86, 84, 168, 169, 'Menu', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(87, 84, 170, 171, 'Menu', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(88, 84, 172, 173, 'Menu', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(89, NULL, 177, 228, 'System', 'System', 'a45da96d0bf6575970f2d27af22be28a'),
(90, 89, 178, 227, 'System', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(91, 90, 179, 182, 'System', 'Configuration', '254f642527b45bc260048e30704edb39'),
(92, 91, 180, 181, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(93, 90, 183, 186, 'System', 'Dashboard', '2938c7f7e560ed972f8a4f68e80ff834'),
(94, 93, 184, 185, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(95, 90, 187, 192, 'System', 'Help', '6a26f548831e6a8c26bfbbd9f6ec61e0'),
(96, 95, 188, 189, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(97, 95, 190, 191, 'System', 'about', '46b3931b9959c927df4fc65fdee94b07'),
(98, 90, 193, 206, 'System', 'Plugins', 'bb38096ab39160dc20d44f3ea6b44507'),
(99, 98, 194, 195, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(100, 98, 196, 197, 'System', 'install', '19ad89bc3e3c9d7ef68b89523eff1987'),
(101, 98, 198, 199, 'System', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(102, 98, 200, 201, 'System', 'enable', '208f156d4a803025c284bb595a7576b4'),
(103, 98, 202, 203, 'System', 'disable', '0aaa87422396fdd678498793b6d5250e'),
(104, 98, 204, 205, 'System', 'settings', '2e5d8aa3dfa8ef34ca5131d20f9dad51'),
(105, 90, 207, 210, 'System', 'Structure', 'dc4c71563b9bc39a65be853457e6b7b6'),
(106, 105, 208, 209, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(107, 90, 211, 226, 'System', 'Themes', '83915d1254927f41241e8630890bec6e'),
(108, 107, 212, 213, 'System', 'index', '6a992d5529f459a44fee58c733255e86'),
(109, 107, 214, 215, 'System', 'install', '19ad89bc3e3c9d7ef68b89523eff1987'),
(110, 107, 216, 217, 'System', 'uninstall', 'fe98497efedbe156ecc4b953aea77e07'),
(111, 107, 218, 219, 'System', 'activate', 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922'),
(112, 107, 220, 221, 'System', 'details', '27792947ed5d5da7c0d1f43327ed9dab'),
(113, 107, 222, 223, 'System', 'screenshot', '62c92ba585f74ecdbef4c4498a438984'),
(114, 107, 224, 225, 'System', 'settings', '2e5d8aa3dfa8ef34ca5131d20f9dad51'),
(115, NULL, 229, 260, 'Taxonomy', 'Taxonomy', '30d10883c017c4fd6751c8982e20dae1'),
(116, 115, 230, 259, 'Taxonomy', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(117, 116, 231, 234, 'Taxonomy', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(118, 117, 232, 233, 'Taxonomy', 'index', '6a992d5529f459a44fee58c733255e86'),
(119, 116, 235, 238, 'Taxonomy', 'Tagger', 'e34d9224f0bf63992e1e77451c6976d1'),
(120, 119, 236, 237, 'Taxonomy', 'search', '06a943c59f33a34bb5924aaf72cd2995'),
(121, 116, 239, 248, 'Taxonomy', 'Terms', '6f1bf85c9ebb3c7fa26251e1e335e032'),
(122, 121, 240, 241, 'Taxonomy', 'vocabulary', '09f06963f502addfeab2a7c87f38802e'),
(123, 121, 242, 243, 'Taxonomy', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(124, 121, 244, 245, 'Taxonomy', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(125, 121, 246, 247, 'Taxonomy', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(126, 116, 249, 258, 'Taxonomy', 'Vocabularies', '81a419751eb59e7d35acab8e532d59a7'),
(127, 126, 250, 251, 'Taxonomy', 'index', '6a992d5529f459a44fee58c733255e86'),
(128, 126, 252, 253, 'Taxonomy', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(129, 126, 254, 255, 'Taxonomy', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(130, 126, 256, 257, 'Taxonomy', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(131, NULL, 261, 350, 'User', 'User', '8f9bfe9d1345237cb3b2b205864da075'),
(132, 131, 262, 285, 'User', 'Gateway', '926dec9494209cb088b4962509df1a91'),
(133, 132, 263, 264, 'User', 'forgot', '790f6b6cf6a6fbead525927d69f409fe'),
(134, 132, 265, 266, 'User', 'cancelRequest', 'd101217dd06f14b4a695fca3b2407320'),
(135, 132, 267, 268, 'User', 'cancel', '10aec35353f9c4096a71c38654c3d402'),
(136, 132, 269, 270, 'User', 'register', '9de4a97425678c5b1288aa70c1669a64'),
(137, 132, 271, 272, 'User', 'activationEmail', '86b62e721d1fb2f94f296bda930ffd34'),
(138, 132, 273, 274, 'User', 'activate', 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922'),
(139, 132, 275, 276, 'User', 'unauthorized', '36fd540552b3b1b34e8f0bd8897cbf1e'),
(140, 132, 277, 278, 'User', 'me', 'ab86a1e1ef70dff97959067b723c5c24'),
(141, 132, 279, 280, 'User', 'profile', '7d97481b1fe66f4b51db90da7e794d9f'),
(142, 132, 281, 282, 'User', 'login', 'd56b699830e77ba53855679cb1d252da'),
(143, 132, 283, 284, 'User', 'logout', '4236a440a662cc8253d7536e5aa17942'),
(144, 131, 286, 349, 'User', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(145, 144, 287, 304, 'User', 'Fields', 'a4ca5edd20d0b5d502ebece575681f58'),
(146, 145, 288, 289, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(147, 145, 290, 291, 'User', 'configure', 'e2d5a00791bce9a01f99bc6fd613a39d'),
(148, 145, 292, 293, 'User', 'attach', '915e375d95d78bf040a2e054caadfb56'),
(149, 145, 294, 295, 'User', 'detach', 'b6bc015ea9587c510c9017988e94e60d'),
(150, 145, 296, 297, 'User', 'viewModeList', 'f6730c40f9e93768852502275e0c9ed5'),
(151, 145, 298, 299, 'User', 'viewModeEdit', 'ecb551e7896f4007ed8df082a8184878'),
(152, 145, 300, 301, 'User', 'viewModeMove', '5a2933f9feebe723793d21c183be08c6'),
(153, 145, 302, 303, 'User', 'move', '3734a903022249b3010be1897042568e'),
(154, 144, 305, 310, 'User', 'Gateway', '926dec9494209cb088b4962509df1a91'),
(155, 154, 306, 307, 'User', 'login', 'd56b699830e77ba53855679cb1d252da'),
(156, 154, 308, 309, 'User', 'logout', '4236a440a662cc8253d7536e5aa17942'),
(157, 144, 311, 326, 'User', 'Manage', '34e34c43ec6b943c10a3cc1a1a16fb11'),
(158, 157, 312, 313, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(159, 157, 314, 315, 'User', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(160, 157, 316, 317, 'User', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(161, 157, 318, 319, 'User', 'block', '14511f2f5564650d129ca7cabc333278'),
(162, 157, 320, 321, 'User', 'activate', 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922'),
(163, 157, 322, 323, 'User', 'passwordInstructions', '1aae12034ad9d692f6802c1721cc622f'),
(164, 157, 324, 325, 'User', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(165, 144, 327, 338, 'User', 'Permissions', 'd08ccf52b4cdd08e41cfb99ec42e0b29'),
(166, 165, 328, 329, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(167, 165, 330, 331, 'User', 'aco', '111c03ddf31a2a03d3fa3377ab07eb56'),
(168, 165, 332, 333, 'User', 'update', '3ac340832f29c11538fbe2d6f75e8bcc'),
(169, 165, 334, 335, 'User', 'export', 'b2507468f95156358fa490fd543ad2f0'),
(170, 165, 336, 337, 'User', 'import', '93473a7344419b15c4219cc2b6c64c6f'),
(171, 144, 339, 348, 'User', 'Roles', 'a5cd3ed116608dac017f14c046ea56bf'),
(172, 171, 340, 341, 'User', 'index', '6a992d5529f459a44fee58c733255e86'),
(173, 171, 342, 343, 'User', 'add', '34ec78fcc91ffb1e54cd85e4a0924332'),
(174, 171, 344, 345, 'User', 'edit', 'de95b43bceeb4b998aed4aed5cef1ae7'),
(175, 171, 346, 347, 'User', 'delete', '099af53f601532dbd31e0ea99ffdeb64'),
(176, NULL, 351, 358, 'Wysiwyg', 'Wysiwyg', 'fcb1d5c3299a281fbb55851547dfac9e'),
(177, 176, 352, 357, 'Wysiwyg', 'Admin', 'e3afed0047b08059d0fada10f400c1e5'),
(178, 177, 353, 356, 'Wysiwyg', 'Finder', 'd151508da8d36994e1635f7875594424'),
(179, 178, 354, 355, 'Wysiwyg', 'index', '6a992d5529f459a44fee58c733255e86');

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
-- Table structure for table `contents`
--

CREATE TABLE IF NOT EXISTS `contents` (
`id` int(11) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `content_type_slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `content_type_id`, `content_type_slug`, `translation_for`, `slug`, `title`, `description`, `promote`, `sticky`, `comment_status`, `language`, `status`, `created`, `modified`, `created_by`, `modified_by`) VALUES
(1, 1, 'article', NULL, 'hello-world', '¡Hello World!', 'hello world demo article', 1, 0, 1, '', 1, '2014-06-12 07:44:01', '2015-04-04 03:00:33', 1, 1),
(2, 2, 'page', NULL, 'about', 'About', 'about QuickAppsCMS demo page', 0, 0, 0, '', 1, '2015-03-31 21:06:50', '2015-03-31 21:06:50', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contents_roles`
--

CREATE TABLE IF NOT EXISTS `contents_roles` (
`id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `role_id` int(10) NOT NULL COMMENT 'The user’s role ID from roles table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_revisions`
--

CREATE TABLE IF NOT EXISTS `content_revisions` (
`id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `summary` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_types`
--

CREATE TABLE IF NOT EXISTS `content_types` (
`id` int(11) NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'human-readable name',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title_label` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'the label displayed for the title field on the edit form.',
  `defaults` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `content_types`
--

INSERT INTO `content_types` (`id`, `slug`, `name`, `description`, `title_label`, `defaults`) VALUES
(1, 'article', 'Article', 'Use <em>Articles</em> for time-sensitive content like news, press releases or blog posts.', 'Title', 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"1";s:6:"sticky";s:1:"1";s:11:"author_name";s:1:"1";s:9:"show_date";s:1:"1";s:14:"comment_status";s:1:"1";s:8:"language";s:0:"";}'),
(2, 'page', 'Basic Page', 'Use <em>Basic Pages</em> for your static content, such as an ''About us'' page.', 'Title', 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"0";s:6:"sticky";s:1:"0";s:11:"author_name";s:1:"0";s:9:"show_date";s:1:"0";s:14:"comment_status";s:1:"0";s:8:"language";s:0:"";}');

-- --------------------------------------------------------

--
-- Table structure for table `content_type_permissions`
--

CREATE TABLE IF NOT EXISTS `content_type_permissions` (
`id` int(11) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `action` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'create, edit, delete, publish'
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eav_attributes`
--

CREATE TABLE IF NOT EXISTS `eav_attributes` (
`id` int(11) NOT NULL,
  `table_alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bundle` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'varchar',
  `searchable` tinyint(1) NOT NULL DEFAULT '1',
  `extra` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `eav_attributes`
--

INSERT INTO `eav_attributes` (`id`, `table_alias`, `bundle`, `name`, `type`, `searchable`, `extra`) VALUES
(1, 'contents', 'article', 'article-introduction', 'text', 1, NULL),
(2, 'contents', 'article', 'article-body', 'text', 1, NULL),
(3, 'contents', 'article', 'article-category', 'text', 1, NULL),
(4, 'contents', 'page', 'page-body', 'text', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `eav_values`
--

CREATE TABLE IF NOT EXISTS `eav_values` (
`id` int(20) NOT NULL,
  `eav_attribute_id` int(11) NOT NULL,
  `entity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'id of the entity in `table`',
  `value_datetime` datetime DEFAULT NULL,
  `value_decimal` decimal(10,0) DEFAULT NULL,
  `value_int` int(11) DEFAULT NULL,
  `value_text` text COLLATE utf8_unicode_ci,
  `value_varchar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra` text COLLATE utf8_unicode_ci COMMENT 'serialized additional information'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `eav_values`
--

INSERT INTO `eav_values` (`id`, `eav_attribute_id`, `entity_id`, `value_datetime`, `value_decimal`, `value_int`, `value_text`, `value_varchar`, `extra`) VALUES
(1, 1, '1', NULL, NULL, NULL, 'Welcome to QuickAppsCMS. This is an example content.', NULL, ''),
(2, 2, '1', NULL, NULL, NULL, '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\r\n\r\n<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>\r\n\r\n<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc</p>\r\n', NULL, ''),
(3, 4, '2', NULL, NULL, NULL, '<p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.&nbsp;<span style="line-height:1.6">Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum.</span></p>\r\n\r\n<p>Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.</p>\r\n\r\n<p>Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</p>\r\n', NULL, 'a:0:{}'),
(4, 3, '1', NULL, NULL, NULL, 'PHP QuickAppsCMS', NULL, 'a:2:{i:0;s:1:"1";i:1;s:1:"5";}');

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
(1, 1, 1, 4, 'contents'),
(2, 1, 5, 4, 'contents');

-- --------------------------------------------------------

--
-- Table structure for table `field_instances`
--

CREATE TABLE IF NOT EXISTS `field_instances` (
`id` int(10) NOT NULL,
  `eav_attribute_id` int(11) NOT NULL,
  `handler` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of event handler class under the `Field` namespace',
  `label` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Human readble name, used in views. eg: `First Name` (for a textbox)',
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'instructions to present to the user below this field on the editing form.',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `settings` text COLLATE utf8_unicode_ci COMMENT 'Serialized information',
  `view_modes` longtext COLLATE utf8_unicode_ci,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'varchar' COMMENT 'Data type for this field (datetime, decimal, int, text, varchar)',
  `locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: (unlocked) users can edit this instance; 1: (locked) users can not modify this instance using web interface',
  `ordering` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `field_instances`
--

INSERT INTO `field_instances` (`id`, `eav_attribute_id`, `handler`, `label`, `description`, `required`, `settings`, `view_modes`, `type`, `locked`, `ordering`) VALUES
(1, 1, 'TextField', 'Introduction', 'Brief description', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:5:"plain";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:3:"rss";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}', 'text', 0, 0),
(2, 2, 'TextField', 'Body', '', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:13:"search-result";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:3:"rss";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}', 'text', 0, 1),
(3, 4, 'TextField', 'Body', 'Page content', 1, 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"400";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}', 'text', 0, 0),
(4, 3, 'TaxonomyField', 'Category', '', 0, 'a:4:{s:10:"vocabulary";s:1:"1";s:4:"type";s:6:"select";s:10:"max_values";s:1:"0";s:13:"error_message";s:0:"";}', 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:2;s:9:"formatter";s:5:"plain";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";}}', 'text', 0, 2);

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menu_links`
--

INSERT INTO `menu_links` (`id`, `menu_id`, `lft`, `rght`, `parent_id`, `url`, `description`, `title`, `target`, `expanded`, `active`, `activation`, `status`) VALUES
(1, 1, 1, 2, 0, '/admin/system/dashboard', NULL, 'Dashboard', '_self', 1, '/admin/system/dashboard\r\n/admin\r\n/admin/', 'any', 1),
(2, 1, 3, 10, 0, '/admin/system/structure', '', 'Structure', '_self', 1, '', 'auto', 1),
(3, 1, 11, 20, 0, '/admin/content/manage', '', 'Content', '_self', 1, '/admin/content/manage*\r\n/admin/content/comments*\r\n/admin/content/types*\r\n/admin/content/fields*', 'any', 1),
(4, 1, 23, 28, 0, '/admin/system/themes', '', 'Appearance', '_self', 1, 'admin/system/themes*', 'any', 1),
(5, 1, 29, 34, 0, '/admin/system/plugins', '', 'Extensions', '_self', 1, 'admin/system/plugins*', 'any', 1),
(6, 1, 35, 44, 0, '/admin/user/manage', '', 'Users & Security', '_self', 1, '/admin/user*', 'any', 1),
(7, 1, 51, 52, 0, '/admin/system/configuration', '', 'Configuration', '_self', 0, '/admin/system/configuration*', 'any', 1),
(8, 1, 53, 54, 0, '/admin/system/help', '', 'Help', '_self', 0, '/admin/system/help*', 'any', 1),
(9, 1, 4, 5, 2, '/admin/block/manage', 'Configure what block content appears in your site''s sidebars and other regions.', 'Blocks', '_self', 0, '/admin/block/*', 'any', 1),
(11, 1, 6, 7, 2, '/admin/menu/manage', 'Add new menus to your site, edit existing menus, and rename and reorganize menu links.', 'Menus', '_self', 0, NULL, NULL, 1),
(12, 1, 8, 9, 2, '/admin/taxonomy/vocabularies', 'Manage tagging, categorization, and classification of your content.', 'Taxonomy', '_self', 0, NULL, NULL, 1),
(13, 1, 45, 50, 0, '/admin/locale/', '', 'Languages', '_self', 1, '/admin/locale/*', 'any', 1),
(14, 2, 3, 4, 0, '/page/about.html', '', 'About', '_self', 0, NULL, NULL, 1),
(16, 2, 1, 2, 0, '/', '', 'Home', '_self', 0, NULL, NULL, 1),
(17, 2, 5, 6, 0, '/find/type:article', '', 'Blog', '_self', 0, '/article/*.html\r\n/find/*type:article*', 'any', 1),
(18, 1, 12, 13, 3, '/admin/content/manage/index', '', 'Contents List', '', 0, '', 'auto', 1),
(19, 1, 14, 15, 3, '/admin/content/manage/create', '', 'Create New Content', '', 0, '', 'auto', 1),
(20, 1, 16, 17, 3, '/admin/content/types', '', 'Content Types', '', 0, '/admin/content/types*\r\n/admin/content/fields*', 'any', 1),
(21, 1, 18, 19, 3, '/admin/content/comments/', '', 'Comments', '', 0, '/admin/content/comments/*', 'any', 1),
(22, 1, 24, 25, 4, '/admin/system/themes/index', '', 'Themes', '', 0, '/admin/system/themes\r\n/admin/system/themes/index', 'any', 1),
(23, 1, 26, 27, 4, '/admin/system/themes/install', '', 'Install New Theme', '', 0, '', 'auto', 1),
(24, 1, 30, 31, 5, '/admin/system/plugins/index', '', 'Plugins', '', 0, '', 'auto', 1),
(25, 1, 32, 33, 5, '/admin/system/plugins/install', '', 'Install New Plugin', '', 0, '', 'auto', 1),
(26, 1, 36, 37, 6, '/admin/user/manage/', '', 'Users List', '', 0, '/admin/user/manage/*', 'any', 1),
(27, 1, 38, 39, 6, '/admin/user/roles', '', 'User Roles', '', 0, '', 'auto', 1),
(28, 1, 40, 41, 6, '/admin/user/permissions', '', 'Permissions', '', 0, '', 'auto', 1),
(29, 1, 46, 47, 13, '/admin/locale/manage/index', '', 'Installed Languages', '', 0, '', 'auto', 1),
(30, 1, 48, 49, 13, '/admin/locale/manage/add', '', 'Add New Language', '', 0, '', 'auto', 1),
(31, 1, 42, 43, 6, '/admin/user/fields', '', 'Virtual Fields', '', 0, '/admin/user/fields*', 'any', 1),
(32, 1, 21, 22, 0, '/admin/media_manager/explorer', '', 'Media', '', 1, '/admin/media_manager/explorer*', 'any', 1);

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
(11, 'site_contents_home', '5', 1),
(12, 'site_maintenance', '0', 1);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
`id` int(11) NOT NULL,
  `aco_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `aco_id`, `role_id`) VALUES
(1, 141, 2),
(2, 141, 3),
(3, 143, 2),
(4, 142, 3),
(5, 133, 3),
(6, 134, 2),
(7, 134, 3),
(8, 135, 2),
(9, 135, 3),
(10, 136, 2),
(11, 136, 3),
(12, 137, 2),
(13, 137, 3),
(14, 138, 2),
(15, 138, 3),
(16, 139, 2),
(17, 139, 3),
(18, 140, 2),
(19, 11, 2),
(20, 11, 3),
(21, 12, 2),
(22, 12, 3),
(23, 13, 2),
(24, 13, 3),
(25, 14, 2),
(26, 14, 3),
(27, 15, 2),
(28, 15, 3);

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
('BackendTheme', 'quickapps-theme/backend-theme', '', 1, 1),
('Block', 'quickapps-plugin/block', '', 1, 2),
('Bootstrap', 'quickapps-plugin/bootstrap', '', 1, 3),
('Captcha', 'quickapps-plugin/captcha', '', 1, 4),
('Comment', 'quickapps-plugin/comment', 'a:13:{s:12:"auto_approve";s:1:"0";s:15:"allow_anonymous";s:1:"1";s:14:"anonymous_name";s:1:"1";s:23:"anonymous_name_required";s:1:"1";s:15:"anonymous_email";s:1:"1";s:24:"anonymous_email_required";s:1:"1";s:13:"anonymous_web";s:1:"1";s:22:"anonymous_web_required";s:1:"0";s:15:"text_processing";s:5:"plain";s:11:"use_captcha";s:1:"0";s:11:"use_akismet";s:1:"0";s:11:"akismet_key";s:1:"s";s:14:"akismet_action";s:6:"delete";}', 1, 5),
('Content', 'quickapps-plugin/content', '', 1, 6),
('Eav', 'quickapps-plugin/eav', '', 1, 7),
('Field', 'quickapps-plugin/field', '', 1, 8),
('FrontendTheme', 'quickapps-theme/frontend-theme', '', 1, 9),
('Installer', 'quickapps-plugin/installer', '', 1, 10),
('Jquery', 'quickapps-plugin/jquery', '', 1, 11),
('Locale', 'quickapps-plugin/locale', '', 1, 12),
('MediaManager', 'quickapps-plugin/media-manager', '', 1, 13),
('Menu', 'quickapps-plugin/menu', '', 1, 14),
('Search', 'quickapps-plugin/search', '', 1, 15),
('System', 'quickapps-plugin/system', '', 1, 16),
('Taxonomy', 'quickapps-plugin/taxonomy', '', 1, 17),
('User', 'quickapps-plugin/user', 'a:17:{s:21:"failed_login_attempts";s:0:"";s:35:"failed_login_attempts_block_seconds";s:0:"";s:23:"message_welcome_subject";s:50:"Account details for {{user:name}} at {{site:name}}";s:20:"message_welcome_body";s:462:"{{user:name}},\r\n\r\nThank you for registering at {{site:name}}. You may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{{user:one-time-login-url}}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {{site:login-url}} in the future using:\r\n\r\nusername: {{user:name}}\r\npassword: Your password\r\n\r\n--  {{site:name}} team";s:18:"message_activation";s:1:"1";s:26:"message_activation_subject";s:61:"Account details for {{user:name}} at {{site:name}} (approved)";s:23:"message_activation_body";s:473:"{{user:name}},\r\n\r\nYour account at {{site:name}} has been activated.\r\n\r\nYou may now log in by clicking this link or copying and pasting it into your browser:\r\n\r\n{{user:one-time-login-url}}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\nAfter setting your password, you will be able to log in at {{site:login-url}} in the future using:\r\n\r\nusername: {{user:name}}\r\npassword: Your password\r\n\r\n--  {{site:name}} team";s:15:"message_blocked";s:1:"1";s:23:"message_blocked_subject";s:60:"Account details for {{user:name}} at {{site:name}} (blocked)";s:20:"message_blocked_body";s:91:"{{user:name}},\r\n\r\nYour account on {{site:name}} has been blocked.\r\n\r\n--  {{site:name}} team";s:33:"message_password_recovery_subject";s:65:"Password recovery instructions for {{user:name}} at {{site:name}}";s:30:"message_password_recovery_body";s:348:"{{user:name}},\r\n\r\nA request to reset the password for your account has been made at {{site:name}}.\r\n\r\nYou may now log in by clicking this link or copying and pasting it to your browser:\r\n\r\n{{user:one-time-login-url}}\r\n\r\nThis link can only be used once to log in and will lead you to a page where you can set your password.\r\n\r\n--  {{site:name}} team";s:30:"message_cancel_request_subject";s:63:"Account cancellation request for {{user:name}} at {{site:name}}";s:27:"message_cancel_request_body";s:310:"{{user:name}},\r\n\r\nA request to cancel your account has been made at {{site:name}}.\r\n\r\nYou may now cancel your account on {{site:url}} by clicking this link or copying and pasting it into your browser:\r\n\r\n{{user:cancel-url}}\r\n\r\nNOTE: The cancellation of your account is not reversible.\r\n\r\n--  {{site:name}} team";s:16:"message_canceled";s:1:"1";s:24:"message_canceled_subject";s:61:"Account details for {{user:name}} at {{site:name}} (canceled)";s:21:"message_canceled_body";s:92:"{{user:name}},\r\n\r\nYour account on {{site:name}} has been canceled.\r\n\r\n--  {{site:name}} team";}', 1, 18),
('Wysiwyg', 'quickapps-plugin/wysiwyg', '', 1, 19);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `search_datasets`
--

INSERT INTO `search_datasets` (`id`, `entity_id`, `table_alias`, `words`) VALUES
(1, '1', 'contents', ' hello world hello world demo article welcome to quickappscms this is an example content lorem ipsum dolor sit amet consectetuer adipiscing elit aenean commodo ligula eget dolor aenean massa cum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus mus donec quam felis ultricies nec pellentesque eu pretium quis sem nulla consequat massa quis enim donec pede justo fringilla vel aliquet nec vulputate eget arcu in enim justo rhoncus ut imperdiet a venenatis vitae justo nullam dictum felis eu pede mollis pretium integer tincidunt cras dapibus vivamus elementum semper nisi aenean vulputate eleifend tellus aenean leo ligula porttitor eu consequat vitae eleifend ac enim aliquam lorem ante dapibus in viverra quis feugiat a tellus phasellus viverra nulla ut metus varius laoreet quisque rutrum aenean imperdiet etiam ultricies nisi vel augue curabitur ullamcorper ultricies nisi nam eget dui etiam rhoncus maecenas tempus tellus eget condimentum rhoncus sem quam semper libero sit amet adipiscing sem neque sed ipsum nam quam nunc blandit vel luctus pulvinar hendrerit id lorem maecenas nec odio et ante tincidunt tempus donec vitae sapien ut libero venenatis faucibus nullam quis ante etiam sit amet orci eget eros faucibus tincidunt duis leo sed fringilla mauris sit amet nibh donec sodales sagittis magna sed consequat leo eget bibendum sodales augue velit cursus nunc '),
(2, '2', 'contents', ' about about quickappscms demo page p duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi nbsp span style line height nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum span p p typi non habent claritatem insitam est usus legentis in iis qui facit eorum claritatem investigationes demonstraverunt lectores legere me lius quod ii legunt saepius claritas est etiam processus dynamicus qui sequitur mutationem consuetudium lectorum p p mirum est notare quam littera gothica quam nunc putamus parum claram anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima eodem modo typi qui nunc nobis videntur parum clari fiant sollemnes in futurum p ');

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
(1, 'QuickAppsCMS', 'admin', '$2y$10$EVI2DYmtDEGAqD0s9TbjL.wgbpKlSjLjeH70gXwKRhi6g5DpkR/Be', 'chris@quickapps.es', 'http://www.quickappscms.org/', 'en_US', 0, 0, '', NULL, 1, '2015-04-02 00:00:00', '2015-04-01 00:00:00');

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
-- Indexes for table `contents`
--
ALTER TABLE `contents`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents_roles`
--
ALTER TABLE `contents_roles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_revisions`
--
ALTER TABLE `content_revisions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_types`
--
ALTER TABLE `content_types`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `content_type_permissions`
--
ALTER TABLE `content_type_permissions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eav_attributes`
--
ALTER TABLE `eav_attributes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eav_values`
--
ALTER TABLE `eav_values`
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
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

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
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=180;
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
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `contents_roles`
--
ALTER TABLE `contents_roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_revisions`
--
ALTER TABLE `content_revisions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_types`
--
ALTER TABLE `content_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `content_type_permissions`
--
ALTER TABLE `content_type_permissions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `eav_attributes`
--
ALTER TABLE `eav_attributes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `eav_values`
--
ALTER TABLE `eav_values`
MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `entities_terms`
--
ALTER TABLE `entities_terms`
MODIFY `id` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `field_instances`
--
ALTER TABLE `field_instances`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
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
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `search_datasets`
--
ALTER TABLE `search_datasets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
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