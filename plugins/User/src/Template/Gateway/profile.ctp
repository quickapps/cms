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

<div class="media">
    <span class="pull-left"><?= $this->Html->image($user->avatar(['s' => 150]), ['class' => 'media-object']); ?></span>
    <div class="media-body">
        <h2 class="media-heading"><?= __d('user', 'User Profile'); ?></h2>
        <p><strong><?= __d('user', 'Name'); ?>:</strong> <?= $user->name; ?></p>
        <p><strong><?= __d('user', 'Username'); ?>:</strong> @<?= $user->username; ?></p>
        <p><strong><?= __d('user', 'Registered on'); ?>:</strong> <?= $user->created->format(__d('user', 'Y-m-d H:i:s')); ?></p>
        <p><strong><?= __d('user', 'Last Connection'); ?>:</strong> <?= $user->last_login->format(__d('user', 'Y-m-d H:i:s')); ?></p>

        <?php if ($user->public_email): ?>
            <p><strong><?= __d('user', 'Email'); ?>:</strong> <?= $user->email; ?></p>
        <?php endif; ?>

        <?php foreach ($user->_fields as $field): ?>
            <p><?= $this->render($field); ?></p>
        <?php endforeach; ?>
    </div>
</div>
