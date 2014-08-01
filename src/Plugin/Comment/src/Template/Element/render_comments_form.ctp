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
<div class="comment-form-container">
	<section class="comments-form">
		<header>
			<h2><?php echo __d('comment', 'Post New Comment'); ?></h2>
			<?php echo $this->Form->button(__d('comment', 'Cancel Reply'), ['class' => 'cancel-reply btn btn-default', 'onclick' => 'CommentForm.cancelReply();', 'style' => 'display:none;']); ?>
		</header>
		<?php echo $this->Form->create($_commentFormContext, ['role' => 'form']); ?>
			<?php echo $this->Form->hidden('_comment_parent_id', ['id' => 'comment-parent-id']); ?>
			<?php if ($this->is('user.logged')): ?>
			<?php echo $this->Html->image($this->user()->avatar); ?>
			@<?php echo $this->user()->username; ?> (<?php echo $this->user()->name; ?>) &lt;<?php echo $this->user()->email; ?>&gt;
			<?php echo $this->Form->hidden('_comment_user_id', ['value' => $this->user()->id]); ?>
			<?php else: ?>
			<?php echo $this->Form->input('_comment_author_name'); ?>
			<?php echo $this->Form->input('_comment_author_email', ['label' => __d('comment', 'E-Mail')]); ?>
			<?php echo $this->Form->input('_comment_author_web', ['label' => __d('comment', 'Website')]); ?>
			<?php endif; ?>
			<?php echo $this->Form->input('_comment_subject', ['label' => __d('comment', 'Subject')]); ?>
			<?php echo $this->Form->input('_comment_body', ['type' => 'textarea', 'label' => __d('comment', 'Message')]); ?>
			<?php echo $this->Form->submit(__d('comment', 'Publish')); ?>
		<?php echo $this->Form->end(); ?>
		<footer>
	</section>
</div>