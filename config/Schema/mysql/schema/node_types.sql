CREATE TABLE `node_types` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`slug` VARCHAR(100) NOT NULL,
`name` VARCHAR(200) NOT NULL COMMENT 'human-readable name',
`description` VARCHAR(255) NOT NULL,
`title_label` VARCHAR(80) NOT NULL COMMENT 'the label displayed for the title field on the edit form.',
`defaults` TEXT DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `slug` (`slug`)
)