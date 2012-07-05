<?php
/**
 * Default block rendering
 *
 * @package	 QuickApps.View.Elements
 * @author	 Christopher Castro <chris@quickapps.es>
 */
?>

<div id="block_<?php echo $block['id']; ?>" class="block block-<?php echo $block['module']; ?> delta-<?php echo $block['delta']; ?> <?php echo isset($block['params']['class']) ? $block['params']['class'] : ''; ?>">
	<?php if (isset($block['title']) && !empty($block['title'])): ?>
		<h2><?php echo $block['title']; ?></h2>
	<?php endif; ?>

	<?php if (isset($block['body']) && !empty($block['body'])): ?>
	<div class="content">
		<?php echo $block['body']; ?>
	</div>
	<?php endif; ?>
</div>