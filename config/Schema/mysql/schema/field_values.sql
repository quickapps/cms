CREATE TABLE `field_values` (
`id` BIGINT NOT NULL AUTO_INCREMENT,
`field_instance_id` INTEGER(10) NOT NULL,
`field_instance_slug` VARCHAR(200) NOT NULL,
`entity_id` VARCHAR(50) NOT NULL COMMENT 'id of the entity in `table`',
`table_alias` VARCHAR(100) NOT NULL,
`value` TEXT DEFAULT NULL,
`extra` TEXT DEFAULT NULL COMMENT 'Extra information required by this field hadnler',
PRIMARY KEY (`id`)
) ENGINE=InnoDB