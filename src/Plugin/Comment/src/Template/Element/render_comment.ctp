<?php
/**
 * Renders a single comment.
 * You must pass the `options` view-variable, available options are:
 *
 * - `visibility`: 0: disabled, 1:open, 2:read-only
 * - `author`: true show author name
 * - `date`: true show publish date
 */
	$options += ['visibility' => 0, 'author' => true, 'date' => true];
?>
<article id="comment-<?php echo $comment->id; ?>" class="comment">
	<header>
		<h3><?php echo $comment->subject; ?></h3>
		<p class="date"><?php echo $options['date'] ? __d('comment', 'Published at <time pubdate="pubdate">%s</time>', $this->Time->format(__d('node', 'F jS, Y h:i A'), $comment->created)) : ''; ?></p>
		<address class="author">
			<?php echo $options['author'] ? $this->Html->image($comment->get('author')->avatar) : ''; ?>
			<?php echo $options['author'] ? __d('comment', 'By %s', $comment->get('author')->name) : ''; ?>
		</address>
	</header>

	<div class="message">
		<?php echo $comment->body; ?>
		<?php if ($comment->has('children') && !empty($comment->children)): ?>
			<?php foreach($comment->children as $child): ?>
				<?php echo $this->render($child, $options); ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<footer>
		<?php if ($options['visibility'] === 1): ?>
			<?php echo $this->Form->button(__d('comment', 'Reply'), ['class' => 'btn btn-default', 'onclick' => "CommentForm.replyTo({$comment->id});"]); ?>
		<?php endif; ?>
	</footer>
</article>