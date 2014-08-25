CREATE TABLE `search_datasets` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`entity_id` VARCHAR(50) NOT NULL,
`table_alias` VARCHAR(50) NOT NULL,
`words` TEXT DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `entity_id` (`entity_id`, `table_alias`)
) ENGINE=InnoDB