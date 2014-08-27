CREATE TABLE `users` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`name` VARCHAR(150) NOT NULL,
`username` VARCHAR(80) NOT NULL,
`password` VARCHAR(200) NOT NULL,
`email` VARCHAR(100) NOT NULL,
`web` VARCHAR(200) DEFAULT NULL,
`locale` VARCHAR(5) DEFAULT NULL,
`public_profile` BOOLEAN NOT NULL,
`public_email` BOOLEAN NOT NULL,
`token` VARCHAR(200) NOT NULL COMMENT 'random unique code, used for pass recovery',
`status` BOOLEAN NOT NULL DEFAULT 1,
`last_login` DATETIME NOT NULL,
`created` DATETIME NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `username` (`username`, `email`)
) ENGINE=InnoDB