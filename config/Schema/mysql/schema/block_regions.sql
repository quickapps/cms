CREATE TABLE `block_regions` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`block_id` INTEGER(11) NOT NULL,
`theme` VARCHAR(200) NOT NULL,
`region` VARCHAR(200) DEFAULT NULL,
`ordering` INTEGER(11) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`),
UNIQUE KEY `block_id` (`block_id`, `theme`)
)