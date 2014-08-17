CREATE TABLE `menu_links` (
`id` INTEGER(10) NOT NULL AUTO_INCREMENT,
`menu_id` INTEGER(11) NOT NULL COMMENT 'All links with the same menu ID are part of the same menu.',
`lft` INTEGER(11) NOT NULL,
`rght` INTEGER(11) NOT NULL,
`parent_id` INTEGER(10) NOT NULL DEFAULT 0 COMMENT 'The parent link ID (plid) is the mlid of the link above in the hierarchy, or zero if the link is at the top level in its menu.',
`url` VARCHAR(255) DEFAULT NULL COMMENT 'the url',
`description` VARCHAR(200) DEFAULT NULL,
`title` VARCHAR(255) DEFAULT NULL COMMENT 'The text displayed for the link, which may be modified by a title callback stored in menu_router.',
`target` VARCHAR(15) NOT NULL DEFAULT '_self',
`expanded` INTEGER(1) NOT NULL DEFAULT 1 COMMENT 'Flag for whether this link should be rendered as expanded in menus - expanded links always have their child links displayed, instead of only when the link is in the active trail (1 = expanded, 0 = not expanded)',
`active` TEXT DEFAULT NULL COMMENT 'php code, or regular expression. based on active_on_type',
`activation` VARCHAR(5) DEFAULT NULL COMMENT 'php: on php return TRUE. auto: auto-detect; any: request\'s URL matches any in \"active\" column; none: request\'s URL matches none of listed in \"active\" column',
`status` BOOLEAN NOT NULL DEFAULT 1,
PRIMARY KEY (`id`),
KEY `router_path` (`url`)
)