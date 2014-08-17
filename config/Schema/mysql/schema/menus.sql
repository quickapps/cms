CREATE TABLE `menus` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`title` VARCHAR(255) NOT NULL COMMENT 'Menu title, displayed at top of block.',
`description` TEXT DEFAULT NULL COMMENT 'Menu description.',
`handler` VARCHAR(100) NOT NULL COMMENT 'Name of the plugin that created this menu.',
`settings` TEXT DEFAULT NULL,
PRIMARY KEY (`id`)
)