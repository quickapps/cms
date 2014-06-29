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
 * Renders all comments for the given entity.
 * You must pass the `options` view-variable, available options are:
 *
 * - `entity`: The entity from where to get comments.
 * - `visibility`: 0: disabled, 1:open, 2:read-only
 *
 * Example:
 *
 *     $this->element('Comment.render_comments', [
 *         'entity' => $myEntity,
 *         'visibility' => 1, // 1: Open (read and write)
 *     ]);
 *
 * Your entity must use `Commentable` behavior, so `comments` key is defined as a property.
 */
?>

<?php if ($options['visibility'] > 0): ?>
	<section class="comments">
		<header>
			<h2><?php echo __d('comment', 'Comments'); ?></h2>
		</header>

		<?php if ($options['entity']->has('comments') && count($options['entity']->get('comments'))): ?>
			<?php foreach ($options['entity']->get('comments') as $comment): ?>
				<?php echo $this->render($comment, $options); ?>
			<?php endforeach; ?>
		<?php else: ?>
			<p><?php echo __d('comment', 'There are no comments yet.'); ?></p>
		<?php endif; ?>
	<section>

	<?php if ($options['visibility'] == 1): ?>
		<?php
			echo $this->element('Comment.render_comments_form', [
				'options' => [
					'entity' => $options['entity'],
				]
			]);
		?>
	<?php endif; ?>
<?php endif; ?>