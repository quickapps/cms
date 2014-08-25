CREATE TABLE `blocks_roles` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`block_id` INTEGER(11) NOT NULL,
`role_id` INTEGER(10) NOT NULL COMMENT 'The user’s role ID from roles table',
PRIMARY KEY (`id`)
) ENGINE=InnoDB