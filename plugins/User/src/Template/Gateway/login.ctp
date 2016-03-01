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

<?= $this->Form->create($user); ?>
    <?= $this->Form->input('username', ['label' => __d('user', 'Username')]); ?>
    <?= $this->Form->input('password', ['label' => __d('user', 'Password')]); ?>
    <?= $this->Form->input('remember', ['type' => 'checkbox', 'label' => __d('user', 'Remember me')]); ?>
    <?= $this->Form->submit(__d('user', 'Login')); ?>
<?= $this->Form->end(); ?>
