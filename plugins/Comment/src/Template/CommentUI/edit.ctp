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
        <?= $this->Form->create($comment); ?>
            <fieldset>
                <legend><?= __d('comment', 'Editing Comment'); ?></legend>

                <?php if (!$comment->user_id): ?>
                    <?=
                        $this->Form->input('author_name', [
                            'label' => ($this->Comment->config('anonymous_name_required') ? __d('comment', 'Author Name *') : __d('comment', 'Author Name')),
                        ]);
                    ?>
                    <?=
                        $this->Form->input('author_email', [
                            'label' => ($this->Comment->config('anonymous_email_required') ? __d('comment', 'Author e-Mail *') : __d('comment', 'Author e-Mail')),
                        ]);
                    ?>
                    <?=
                        $this->Form->input('author_web', [
                            'label' => ($this->Comment->config('anonymous_web_required') ? __d('comment', 'Author Website *') : __d('comment', 'Author Website')),
                        ]);
                    ?>
                <?php else: ?>
                    <div class="media">
                        <?= $this->Html->image($comment->author->avatar, ['width' => 80, 'class' => 'media-object pull-left']); ?>
                        <div class="media-body">
                            <strong><?= $comment->author->name; ?></strong><br />
                            email: <?= $comment->author->email; ?><br />
                            web: <?= $comment->author->web; ?><br />
                            ip: <?= $comment->author->ip; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <hr />

                <?= $this->Form->input('subject', ['label' => __d('comment', 'Subject')]); ?>
                <?=
                    $this->Form->input('status', [
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
                <?=
                    $this->Form->input('body', [
                        'type' => 'textarea',
                        'label' => __d('comment', 'Message')
                    ]);
                ?>

                <?= $this->Form->submit(__d('comment', 'Save')); ?>
            </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>