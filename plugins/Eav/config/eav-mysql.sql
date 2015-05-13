-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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

-- --------------------------------------------------------

--
-- Table structure for table `eav_values`
--

CREATE TABLE IF NOT EXISTS `eav_values` (
`id` int(20) NOT NULL,
  `eav_attribute_id` int(11) NOT NULL,
  `entity_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'id of the entity in `table`',
  `value_datetime` datetime DEFAULT NULL,
  `value_binary` blob,
  `value_time` time DEFAULT NULL,
  `value_date` date DEFAULT NULL,
  `value_float` decimal(10,0) DEFAULT NULL,
  `value_integer` int(11) DEFAULT NULL,
  `value_biginteger` bigint(20) DEFAULT NULL,
  `value_text` text COLLATE utf8_unicode_ci,
  `value_string` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value_boolean` tinyint(1) DEFAULT NULL,
  `value_uuid` varchar(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra` text COLLATE utf8_unicode_ci COMMENT 'serialized additional information'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

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