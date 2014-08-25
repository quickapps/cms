CREATE TABLE `plugins` (
`name` VARCHAR(80) NOT NULL,
`package` VARCHAR(100) NOT NULL COMMENT 'composer package. e.g. user_name/plugin_name',
`settings` TEXT NOT NULL COMMENT 'serialized array of options',
`status` BOOLEAN NOT NULL DEFAULT 0,
`ordering` INTEGER(3) NOT NULL DEFAULT 0,
PRIMARY KEY (`name`)
) ENGINE=InnoDB