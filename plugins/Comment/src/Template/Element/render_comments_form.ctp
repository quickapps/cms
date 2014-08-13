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

<div class="comments-form-container">
	<section class="comments-form">
		<header>
			<h2><?php echo __d('comment', 'Post New Comment'); ?></h2>
			<?php echo $this->Form->button(__d('comment', 'Cancel Reply'), ['class' => 'cancel-reply btn btn-default', 'onclick' => 'CommentForm.cancelReply();', 'style' => 'display:none;']); ?>
			<span class="comments-form-alerts"><?php echo $this->alerts(null, 'commentsForm'); ?></span>
		</header>

		<div class="comment-form">
			<?php if (!$this->request->is('userLoggedIn') && !$this->Comment->config('allow_anonymous')): ?>
				<h3><?php echo __d('comment', 'You must be logged in to post comments.'); ?></h3>
			<?php else: ?>
				<?php echo $this->Form->create($_commentFormContext, ['id' => 'comments-form', 'role' => 'form']); ?>
					<?php echo $this->Form->hidden('comment.parent_id', ['id' => 'comment-parent-id']); ?>

					<?php if ($this->request->is('userLoggedIn')): ?>
						<?php echo $this->Html->image(user()->avatar); ?>
						@<?php echo user()->username; ?> (<?php echo user()->name; ?>) &lt;<?php echo user()->email; ?>&gt;
					<?php elseif ($this->Comment->config('allow_anonymous')): ?>
						<?php if ($this->Comment->config('anonymous_name')): ?>
							<?php echo $this->Form->input('comment.author_name', $this->Comment->optionsForInput('author_name')); ?>
						<?php endif; ?>

						<?php if ($this->Comment->config('anonymous_email')): ?>
							<?php echo $this->Form->input('comment.author_email', $this->Comment->optionsForInput('author_email')); ?>
							<em class="help-block"><?php echo __d('comment', 'Will not be published.'); ?></em>
						<?php endif; ?>

						<?php if ($this->Comment->config('anonymous_web')): ?>
							<?php echo $this->Form->input('comment.author_web', $this->Comment->optionsForInput('author_web')); ?>
						<?php endif; ?>
					<?php endif; ?>

					<?php echo $this->Form->input('comment.subject', $this->Comment->optionsForInput('subject')); ?>
					<?php echo $this->Form->input('comment.body', $this->Comment->optionsForInput('body')); ?>

					<?php if ($this->Comment->config('text_processing') === 'plain'): ?>
						<ul>
							<li><?php echo __d('field', 'No HTML tags allowed.'); ?></li>
							<li><?php echo __d('field', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
							<li><?php echo __d('field', 'Lines and paragraphs break automatically.'); ?></li>
						</ul>
					<?php elseif ($this->Comment->config('text_processing') === 'full'): ?>
						<ul>
							<li><?php echo __d('field', 'All HTML tags allowed.'); ?></li>
							<li><?php echo __d('field', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
						</ul>
					<?php elseif ($this->Comment->config('text_processing') === 'filtered'): ?>
						<ul>
							<li><?php echo __d('field', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
							<li><?php echo __d('field', 'Allowed HTML tags: &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;cite&gt; &lt;blockquote&gt; &lt;code&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt;'); ?></li>
							<li><?php echo __d('field', 'Lines and paragraphs break automatically.'); ?></li>
						</ul>
					<?php elseif ($this->Comment->config('text_processing') === 'markdown'): ?>
						<ul>
							<li><?php echo __d('field', '<a href="{0}" target="_blank">Markdown</a> text format allowed only.', 'http://wikipedia.org/wiki/Markdown'); ?></li>
						</ul>
					<?php endif; ?>

					<?php echo $this->Comment->captcha(); ?>
					<?php echo $this->Form->submit(__d('comment', 'Publish')); ?>
				<?php echo $this->Form->end(); ?>
			<?php endif; ?>
		<div>
	</section>
</div>
