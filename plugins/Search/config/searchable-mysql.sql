-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
-- Indexes for dumped tables
--

--
-- Indexes for table `search_datasets`
--
ALTER TABLE `search_datasets`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `entity_id` (`entity_id`,`table_alias`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `search_datasets`
--
ALTER TABLE `search_datasets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;