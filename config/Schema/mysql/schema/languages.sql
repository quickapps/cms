CREATE TABLE `languages` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`code` VARCHAR(12) NOT NULL COMMENT 'Language code, e.g. ’eng’',
`name` VARCHAR(64) NOT NULL COMMENT 'Language name in English.',
`direction` VARCHAR(3) NOT NULL DEFAULT 'ltr' COMMENT 'Direction of language (Left-to-Right , Right-to-Left ).',
`icon` VARCHAR(255) DEFAULT NULL,
`status` INTEGER(11) NOT NULL DEFAULT 0 COMMENT 'Enabled flag (1 = Enabled, 0 = Disabled).',
`ordering` INTEGER(11) NOT NULL DEFAULT 0 COMMENT 'Weight, used in lists of languages.',
PRIMARY KEY (`id`),
UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB