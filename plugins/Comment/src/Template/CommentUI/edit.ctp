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
?>

<div class="row">
    <div class="col-md-12">
        <?php echo $this->Form->create($comment); ?>
            <fieldset>
                <legend><?php echo __d('comment', 'Editing Comment'); ?></legend>

                <?php if (!$comment->user_id): ?>
                    <?php
                        echo $this->Form->input('author_name', [
                            'label' => ($this->Comment->config('anonymous_name_required') ? __d('comment', 'Author Name *') : __d('comment', 'Author Name')),
                        ]);
                    ?>
                    <?php
                        echo $this->Form->input('author_email', [
                            'label' => ($this->Comment->config('anonymous_email_required') ? __d('comment', 'Author e-Mail *') : __d('comment', 'Author e-Mail')),
                        ]);
                    ?>
                    <?php
                        echo $this->Form->input('author_web', [
                            'label' => ($this->Comment->config('anonymous_web_required') ? __d('comment', 'Author Website *') : __d('comment', 'Author Website')),
                        ]);
                    ?>
                <?php else: ?>
                    <div class="media">
                        <?php echo $this->Html->image($comment->author->avatar, ['width' => 80, 'class' => 'media-object pull-left']); ?>
                        <div class="media-body">
                            <strong><?php echo $comment->author->name; ?></strong><br />
                            email: <?php echo $comment->author->email; ?><br />
                            web: <?php echo $comment->author->web; ?><br />
                            ip: <?php echo $comment->author->ip; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <hr />

                <?php echo $this->Form->input('subject', ['label' => __d('comment', 'Subject')]); ?>
                <?php
                    echo $this->Form->input('status', [
                        'type' => 'select',
                        'label' => __d('comment', 'Status'),
                        'options' => [
                            'approved' => __d('comment', 'Approved'),
                            'pending' => __d('comment', 'Pending'),
                            'spam' => __d('comment', 'Spam'),
                            'trash' => __d('comment', 'Trash'),
                        ]
                    ]);
                ?>
                <?php
                    echo $this->Form->input('body', [
                        'type' => 'textarea',
                        'label' => __d('comment', 'Message')
                    ]);
                ?>

                <?php echo $this->Form->submit(__d('comment', 'Save')); ?>
            </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
</div>