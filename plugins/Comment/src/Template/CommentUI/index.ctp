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

<?= $this->fetch('beforeSubmenu'); ?>
<div class="row">
    <div class="col-md-12">
        <?= $this->element('Comment.CommentUI/index_submenu'); ?>
    </div>
</div>
<?= $this->fetch('afterSubmenu'); ?>

<div class="row">
    <div class="col-md-12">
        <p>
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-inline pull-right']); ?>
                <div class="input-group">
                    <?= $this->Form->input('search', ['type' => 'text', 'label' => false, 'value' => $search]); ?>
                    <span class="input-group-btn">
                        <?= $this->Form->submit(__d('comment', 'Search Comments')); ?>
                    </span>
                </div>
            <?= $this->Form->end(); ?>
        </p>
    </div>
</div>

<?= $this->fetch('beforeTable'); ?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="230"><?= __d('comment', 'Author'); ?></th>
                    <th><?= __d('comment', 'Comment'); ?></th>
                    <th><?= __d('comment', 'In Response To'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($comments->count()): ?>
                <?php foreach ($comments as $comment): ?>
                    <tr class="<?= $comment->status === 'pending' && $filterBy !== 'pending' ? 'warning' : ''?>">
                        <td>
                            <div class="media">
                                <?= $this->Html->image($comment->author->avatar, ['width' => 30, 'class' => 'media-object pull-left']); ?>
                                <div class="media-body">
                                    <strong><?= $comment->author->name; ?></strong><br />
                                    email: <?= $comment->author->email; ?><br />
                                    web: <?= $comment->author->web; ?><br />
                                    ip: <?= $comment->author->ip; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <h4><?= $this->Html->link($comment->subject, ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'edit', $comment->id]); ?></h4>
                            <p><?= $comment->body; ?></p>
                            <em class="help-block"><?= __d('comment', 'Submitted on {0}', $comment->created->format('Y/m/d \a\t H:i a')); ?></em>
                            <span><?= __d('comment', 'Move to'); ?>: </span>
                            <div class="btn-group btn-group-xs">
                                <?php if ($comment->status !== 'approved'): ?>
                                    <?= $this->Html->link(__d('comment', 'Approved'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'status', $comment->id, 'approved'], ['class' => 'btn btn-success']); ?>
                                <?php endif; ?>

                                <?php if ($comment->status !== 'pending'): ?>
                                    <?= $this->Html->link(__d('comment', 'Pending'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'status', $comment->id, 'pending'], ['class' => 'btn btn-info']); ?>
                                <?php endif; ?>

                                <?php if ($comment->status !== 'spam'): ?>
                                    <?= $this->Html->link(__d('comment', 'Spam'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'status', $comment->id, 'spam'], ['class' => 'btn btn-warning']); ?>
                                <?php endif; ?>

                                <?php if ($comment->status !== 'trash'): ?>
                                    <?= $this->Html->link(__d('comment', 'Trash'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'status', $comment->id, 'trash'], ['class' => 'btn btn-danger']); ?>
                                <?php else: ?>
                                    <?= $this->Html->link(__d('comment', 'Delete Permanently'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'delete', $comment->id, ], ['class' => 'btn btn-danger', 'confirm' => __d('comment', 'Delete this comment?')]); ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?= $comment->entity; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <?= $filterBy === 'pending' ? __d('comment', 'No comments awaiting moderation.') : __d('comment', 'No comments found.'); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->fetch('afterTable'); ?>

<?php if ($filterBy === 'trash'): ?>
    <p><?= $this->Html->link(__d('comment', 'Empty Trash'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'empty_trash'], ['class' => 'btn btn-default btn-sm', 'confirm' => __d('comment', 'Delete all comments in the trash? This operation can not be undone.')]); ?></p>
<?php endif; ?>

<?= $this->fetch('beforePagination'); ?>
<div class="row">
    <div class="col-md-12">
        <ul class="pagination">
            <?= $this->Paginator->prev(); ?>
            <?= $this->Paginator->numbers(); ?>
            <?= $this->Paginator->next(); ?>
        </ul>
    </div>
</div>
<?= $this->fetch('afterPagination'); ?>

<div class="row">
    <div class="col-md-12">
        <p class="text-center help-block">
            <?=
                $this->Paginator->counter(
                    __d('comment', 'Page {{page}} of {{pages}}, showing {{current}} comments out of {{count}} total.')
                );
            ?>
        </p>
    </div>
</div>