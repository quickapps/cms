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
        <?= $this->element('User.index_submenu'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <p>
            <?=
                $this->Html->link(__d('user','{0} New User', '<span class="glyphicon glyphicon-plus"></span>'), [
                    'plugin' => 'User',
                    'controller' => 'manage',
                    'action' => 'add'
                ], [
                    'class' => 'btn btn-primary',
                    'escape' => false,
                ]);
            ?>
        </p>
    </div>

    <div class="col-md-4">
        <?= $this->Form->create(null, ['type' => 'get']); ?>
        <div class="input-group">
            <?=
                $this->Form->input('filter', [
                    'label' => false,
                    'value' => (!empty($this->request->query['filter']) ? $this->request->query['filter'] : '')
                ]);
            ?>
            <span class="input-group-btn"><?= $this->Form->submit(__d('user', 'Search Users')); ?></span>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th><?= __d('user', 'Name'); ?></th>
                    <th><?= __d('user', 'e-Mail'); ?></th>
                    <th class="hidden-xs"><?= __d('user', 'Roles'); ?></th>
                    <th class="text-right"><?= __d('user', 'Actions'); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user->name; ?> <small>(<?= $user->username; ?>)</small></td>
                        <td><?= $user->email; ?></td>
                        <td class="hidden-xs">
                            <?= implode(', ', $user->role_names); ?>
                        </td>
                        <td>
                            <div class="btn-group pull-right">
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'User',
                                        'controller' => 'manage',
                                        'action' => 'edit',
                                        $user->id,
                                    ], [
                                        'title' => __d('user', 'Set as default'),
                                        'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-pencil',
                                    ]);
                                ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'User',
                                        'controller' => 'manage',
                                        'action' => 'password_instructions',
                                        $user->id,
                                    ], [
                                        'title' => __d('user', 'Send password recovery instructions'),
                                        'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-qrcode',
                                        'confirm' => __d('user', 'You are about to send password recovery instructions to "{0}". Are you sure ?', $user->name),
                                    ]);
                                ?>
                                <?php if (!in_array(ROLE_ID_ADMINISTRATOR, $user->role_ids)): ?>
                                    <?php if ($user->status): ?>
                                        <?=
                                            $this->Html->link('', [
                                                'plugin' => 'User',
                                                'controller' => 'manage',
                                                'action' => 'block',
                                                $user->id,
                                            ], [
                                                'title' => __d('user', 'Block account'),
                                                'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-remove-circle',
                                                'confirm' => __d('user', 'You are about to block: "{0}". Are you sure ?', $user->name),
                                            ]);
                                        ?>
                                    <?php else: ?>
                                        <?=
                                            $this->Html->link('', [
                                                'plugin' => 'User',
                                                'controller' => 'manage',
                                                'action' => 'activate',
                                                $user->id,
                                            ], [
                                                'title' => __d('user', 'Activate account'),
                                                'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-ok-circle',
                                                'confirm' => __d('user', 'You are about to activate: "{0}". Are you sure ?', $user->name),
                                            ]);
                                        ?>
                                    <?php endif; ?>
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'User',
                                            'controller' => 'manage',
                                            'action' => 'delete',
                                            $user->id,
                                        ], [
                                            'title' => __d('user', 'Delete'),
                                            'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-trash',
                                            'confirm' => __d('user', 'You are about to delete: "{0}". Are you sure ?', $user->name),
                                        ]);
                                    ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <ul class="pagination">
            <?= $this->Paginator->prev(); ?>
            <?= $this->Paginator->numbers(); ?>
            <?= $this->Paginator->next(); ?>
        </ul>
    </div>
</div>