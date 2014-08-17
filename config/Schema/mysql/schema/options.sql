CREATE TABLE `options` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(100) NOT NULL,
`value` TEXT DEFAULT NULL,
`autoload` BOOLEAN NOT NULL DEFAULT 0 COMMENT '1: true (autoload); 0:false',
PRIMARY KEY (`id`),
UNIQUE KEY `name` (`name`)
)