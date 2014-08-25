CREATE TABLE `vocabularies` (
`id` INTEGER(10) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(255) NOT NULL,
`slug` VARCHAR(255) NOT NULL,
`description` TEXT DEFAULT NULL,
`ordering` INTEGER(11) DEFAULT NULL,
`locked` BOOLEAN NOT NULL DEFAULT 0 COMMENT 'if set to 1 users can not delete this vocabulary',
`modified` DATETIME NOT NULL,
`created` DATETIME NOT NULL,
PRIMARY KEY (`id`),
KEY `slug` (`slug`)
) ENGINE=InnoDB