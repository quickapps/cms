CREATE TABLE `blocks` (
`id` INTEGER(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key - Unique block ID.',
`copy_id` INTEGER(11) DEFAULT NULL COMMENT 'id of the block this block is a copy of',
`delta` VARCHAR(30) NOT NULL COMMENT 'unique ID within a handler',
`handler` VARCHAR(100) NOT NULL DEFAULT 'Block' COMMENT 'Name of the plugin that created this block. Used to generate event name, e.g. \"Menu\" triggers \"Block.Menu.display\" when rendering the block',
`title` VARCHAR(100) NOT NULL,
`description` VARCHAR(200) DEFAULT NULL,
`body` TEXT DEFAULT NULL,
`visibility` VARCHAR(8) NOT NULL DEFAULT 'except' COMMENT 'indicate how to show blocks on pages. (except = show on all pages except listed pages; only = show only on listed pages; php = use custom PHP code to determine visibility)',
`pages` TEXT DEFAULT NULL COMMENT 'Contents of the \"Pages\" block contains either a list of paths on which to include/exclude the block or PHP code, depending on \"visibility\" setting.',
`locale` TEXT DEFAULT NULL,
`settings` TEXT DEFAULT NULL COMMENT 'additional information used by this block, used by blocks handlers <> `Block`',
`status` BOOLEAN NOT NULL DEFAULT 0,
PRIMARY KEY (`id`),
UNIQUE KEY `delta` (`delta`, `handler`)
)