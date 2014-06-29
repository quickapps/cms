<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

/**
 * This view elements is capable of handling multiple view-modes.
 * If you want to create a separated view element for each view mode
 * take a look to `NodeHook::renderNode()` method.
 */
?>

<article class="node node-<?php echo $node->node_type_slug; ?> viewmode-<?php echo $this->getViewMode(); ?>">
	<header>
		<?php
			switch($this->getViewMode()):
			case 'full':
		?>
			<h1><?php echo $this->hooktags($node->title); ?></h1>
		<?php
			break;
			case 'search-result':
			default:
		?>
			<h2><?php echo $this->Html->link($this->hooktags($node->title), "/{$node->node_type_slug}/{$node->slug}.html"); ?></h2>
		<?php
			break;
			endswitch;
		?>
		<?php //TODO: set timezone to user's timezone (read from session) ?>
		<p><?php echo __d('node', 'Published'); ?>: <time pubdate="pubdate"><?php echo $node->created->timeAgoInWords(); ?></time></p>
	</header>

	<?php foreach ($node->_fields as $field): ?>
		<?php echo $this->hooktags($this->render($field)); ?>
	<?php endforeach; ?>

	<?php if ($this->getViewMode() === 'full'): ?>
		<?php
			echo $this->element('Comment.render_comments', [
				'options' => [
					'entity' => $node,
					'visibility' => $node->comment,
				]
			]);
		?>
	<?php endif; ?>
</article>