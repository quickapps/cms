CREATE TABLE `nodes` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
`node_type_id` INTEGER(11) NOT NULL,
`node_type_slug` VARCHAR(100) NOT NULL,
`translation_for` INTEGER(11) DEFAULT NULL,
`slug` VARCHAR(100) NOT NULL,
`title` VARCHAR(250) NOT NULL,
`description` VARCHAR(200) DEFAULT NULL,
`promote` BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Show in front page?',
`sticky` BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Show at top of lists',
`comment_status` INTEGER(2) NOT NULL DEFAULT 0 COMMENT '0=closed, 1=open, 2=readonly',
`language` CHAR(10) DEFAULT NULL,
`status` BOOLEAN NOT NULL,
`created` DATETIME NOT NULL,
`modified` DATETIME NOT NULL,
`created_by` INTEGER(11) NOT NULL,
`modified_by` INTEGER(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB