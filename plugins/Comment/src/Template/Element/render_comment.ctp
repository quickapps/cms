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
 * Renders a single comment.
 *
 * It will renders its children if it has.
 */
?>

<article id="comment-<?php echo $comment->id; ?>" class="comment">
	<header>
		<h3><?php echo $comment->subject; ?></h3>
		<p class="date">
			<?php
				echo __d('comment',
					'Published at <time pubdate="pubdate">%s</time>',
					$comment->created->format(__d('comment', 'F jS, Y h:i A'))
				);
			?>
		</p>

		<address class="author">
			<?php echo $this->Html->image($comment->get('author')->avatar); ?>
			<?php echo __d('comment', 'By @{0}', $comment->get('author')->name); ?>
		</address>
	</header>

	<div class="message">
		<?php echo $comment->body; ?>

		<?php if ($comment->has('children') && !empty($comment->children)): ?>
			<?php foreach($comment->children as $child): ?>
				<?php echo $this->render($child); ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<footer>
		<?php if ($this->Comment->config('visibility') === 1): ?>
			<?php echo $this->Form->button(__d('comment', 'Reply'), ['class' => 'btn btn-default', 'onclick' => "CommentForm.replyTo({$comment->id});"]); ?>
		<?php endif; ?>
	</footer>
</article>