CREATE TABLE `comments` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`entity_id` VARCHAR(50) NOT NULL,
`user_id` INTEGER(11) DEFAULT NULL,
`table_alias` VARCHAR(50) NOT NULL,
`subject` VARCHAR(200) NOT NULL,
`body` TEXT NOT NULL,
`author_name` VARCHAR(100) DEFAULT NULL,
`author_email` VARCHAR(100) DEFAULT NULL,
`author_web` VARCHAR(200) DEFAULT NULL,
`author_ip` VARCHAR(60) NOT NULL,
`parent_id` INTEGER(4) DEFAULT NULL,
`rght` INTEGER(4) NOT NULL,
`lft` INTEGER(4) NOT NULL,
`status` VARCHAR(20) NOT NULL COMMENT 'pending, approved, spam, trash',
`created` DATETIME NOT NULL,
PRIMARY KEY (`id`)
)