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
 * Renders the `post new comment` form.
 */
?>

<?php echo $this->Html->script('Comment.comment_form.js'); ?>

<section class="comments-form">
	<header>
		<h2><?php echo __d('comment', 'Post New Comment'); ?></h2>
		<?php echo $this->Form->button(__d('comment', 'Cancel Reply'), ['class' => 'cancel-reply btn btn-default', 'onclick' => 'CommentForm.cancelReply();', 'style' => 'display:none;']); ?>
	</header>

	<div class="comment-form">
		<?php if (!$this->is('user.logged') && !$this->Comment->config('allow_anonymous')): ?>
			<h3><?php echo __d('comment', 'You must be logged in to post comments.'); ?></h3>
		<?php else: ?>
			<?php echo $this->Form->create($_commentFormContext, ['role' => 'form']); ?>
				<?php echo $this->Form->hidden('_comment_parent_id', ['id' => 'comment-parent-id']); ?>

				<?php if ($this->is('user.logged')): ?>
					<?php echo $this->Html->image($this->user()->avatar); ?>
					@<?php echo $this->user()->username; ?> (<?php echo $this->user()->name; ?>) &lt;<?php echo $this->user()->email; ?>&gt;
					<?php echo $this->Form->hidden('_comment_user_id', ['value' => $this->user()->id]); ?>
				<?php elseif ($this->Comment->config('allow_anonymous')): ?>
					<?php if ($this->Comment->config('anonymous_name')): ?>
						<?php echo $this->Form->input('_comment_author_name', $this->Comment->optionsForInput('author_name')); ?>
					<?php endif; ?>

					<?php if ($this->Comment->config('anonymous_email')): ?>
						<?php echo $this->Form->input('_comment_author_email', $this->Comment->optionsForInput('author_email')); ?>
					<?php endif; ?>

					<?php if ($this->Comment->config('anonymous_web')): ?>
						<?php echo $this->Form->input('_comment_author_web', $this->Comment->optionsForInput('author_web')); ?>
					<?php endif; ?>
				<?php endif; ?>

				<?php echo $this->Form->input('_comment_subject', $this->Comment->optionsForInput('subject')); ?>
				<?php echo $this->Form->input('_comment_body', $this->Comment->optionsForInput('body')); ?>
				<?php echo $this->Comment->captcha(); ?>
				<?php echo $this->Form->submit(__d('comment', 'Publish')); ?>
			<?php echo $this->Form->end(); ?>
		<?php endif; ?>
	<div>
</section>
