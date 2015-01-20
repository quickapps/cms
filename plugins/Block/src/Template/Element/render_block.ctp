<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

/**
 * General block render.
 *
 * This is the default render element used by \Block\Event\BlockHook::displayBlock().
 */
?>

<div class="qa-block" data-block-id="<?php echo $block->id; ?>" data-block-region="<?php echo $block->region->region; ?>">
	<h2><?php echo $block->title; ?></h2>
	<p><?php echo $block->body; ?></p>
</div>
