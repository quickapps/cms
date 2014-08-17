CREATE TABLE `field_instances` (
`id` INTEGER(10) NOT NULL AUTO_INCREMENT,
`slug` VARCHAR(200) NOT NULL COMMENT 'Machine name, must be unique',
`table_alias` VARCHAR(100) NOT NULL COMMENT 'Name of the table to which this field belongs to. eg: comment, node_article. Must be unique',
`handler` VARCHAR(80) NOT NULL COMMENT 'Name of event handler class under the `Field` namespace',
`label` VARCHAR(200) NOT NULL COMMENT 'Human readble name, used in views. eg: `First Name` (for a textbox)',
`description` VARCHAR(250) DEFAULT NULL COMMENT 'instructions to present to the user below this field on the editing form.',
`required` BOOLEAN NOT NULL DEFAULT 0,
`settings` TEXT DEFAULT NULL COMMENT 'Serialized information',
`view_modes` TEXT DEFAULT NULL,
`locked` BOOLEAN NOT NULL DEFAULT 0 COMMENT '0: (unlocked) users can edit this instance; 1: (locked) users can not modify this instance using web interface',
`ordering` INTEGER(3) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`),
UNIQUE KEY `slug` (`slug`),
KEY `entity` (`table_alias`),
KEY `id` (`id`)
)