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

<?= $this->Flash->render('activate'); ?>

<?php if ($activated): ?>
    <?=
        __d('user', 'Congratulations, your account has been successfully activated. You can now login in click <a href="{0}">here</a>.',
            $this->Url->build([
                'plugin' => 'User',
                'controller' => 'gateway',
                'action' => 'login',
            ])
        );
    ?>
<?php endif; ?>