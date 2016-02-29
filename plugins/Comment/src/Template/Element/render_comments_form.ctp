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
 * Renders the "post new comment" form.
 */
?>

<?= $this->Html->script('Comment.comment_form.js'); ?>

<div class="comments-form-container">
    <section class="comments-form">
        <header>
            <h2><?= __d('comment', 'Post New Comment'); ?></h2>
            <p><?= $this->Form->button(__d('comment', 'Cancel Reply'), ['class' => 'cancel-reply btn btn-default btn-sm', 'onclick' => 'CommentForm.cancelReply();', 'style' => 'display:none;']); ?></p>
            <span class="comments-form-alerts"><?= $this->Flash->render('commentsForm'); ?></span>
        </header>

        <div class="comment-form">
            <?php if (!$this->request->is('userLoggedIn') && !$this->Comment->config('allow_anonymous')): ?>
                <h3><?= __d('comment', 'You must be logged in to post comments.'); ?></h3>
            <?php else: ?>
                <?= $this->Form->create($_commentFormContext, ['id' => 'comments-form', 'role' => 'form']); ?>
                    <?= $this->Form->hidden('comment.parent_id', ['id' => 'comment-parent-id']); ?>

                    <?php if ($this->request->is('userLoggedIn')): ?>
                        <?= $this->Html->image(user()->avatar); ?>
                        @<?= user()->username; ?> (<?= user()->name; ?>) &lt;<?= user()->email; ?>&gt;
                    <?php elseif ($this->Comment->config('allow_anonymous')): ?>
                        <?php if ($this->Comment->config('anonymous_name')): ?>
                            <?= $this->Form->input('comment.author_name', $this->Comment->optionsForInput('author_name')); ?>
                        <?php endif; ?>

                        <?php if ($this->Comment->config('anonymous_email')): ?>
                            <?= $this->Form->input('comment.author_email', $this->Comment->optionsForInput('author_email')); ?>
                            <em class="help-block"><?= __d('comment', 'Will not be published.'); ?></em>
                        <?php endif; ?>

                        <?php if ($this->Comment->config('anonymous_web')): ?>
                            <?= $this->Form->input('comment.author_web', $this->Comment->optionsForInput('author_web')); ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?= $this->Form->input('comment.subject', $this->Comment->optionsForInput('subject')); ?>
                    <?= $this->Form->input('comment.body', $this->Comment->optionsForInput('body')); ?>

                    <?php if ($this->Comment->config('text_processing') === 'plain'): ?>
                        <ul>
                            <li><?= __d('comment', 'No HTML tags allowed.'); ?></li>
                            <li><?= __d('comment', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
                            <li><?= __d('comment', 'Lines and paragraphs break automatically.'); ?></li>
                        </ul>
                    <?php elseif ($this->Comment->config('text_processing') === 'full'): ?>
                        <ul>
                            <li><?= __d('comment', 'All HTML tags allowed.'); ?></li>
                            <li><?= __d('comment', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
                        </ul>
                    <?php elseif ($this->Comment->config('text_processing') === 'filtered'): ?>
                        <ul>
                            <li><?= __d('comment', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
                            <li><?= __d('comment', 'Allowed HTML tags: &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;cite&gt; &lt;blockquote&gt; &lt;code&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt;'); ?></li>
                            <li><?= __d('comment', 'Lines and paragraphs break automatically.'); ?></li>
                        </ul>
                    <?php elseif ($this->Comment->config('text_processing') === 'markdown'): ?>
                        <ul>
                            <li><?= __d('comment', '<a href="{0}" target="_blank">Markdown</a> text format allowed only.', 'http://wikipedia.org/wiki/Markdown'); ?></li>
                        </ul>
                    <?php endif; ?>

                    <?= $this->Comment->captcha(); ?>
                    <?= $this->Form->submit(__d('comment', 'Publish')); ?>
                <?= $this->Form->end(); ?>
            <?php endif; ?>
        <div>
    </section>
</div>
