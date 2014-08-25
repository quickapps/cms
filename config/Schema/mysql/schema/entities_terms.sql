CREATE TABLE `entities_terms` (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`entity_id` INTEGER(20) NOT NULL,
`term_id` INTEGER(20) NOT NULL,
`field_instance_id` INTEGER(11) NOT NULL,
`table_alias` VARCHAR(30) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB