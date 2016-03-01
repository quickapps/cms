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

<em class="help-block">
    <ul>
        <li><?= __d('user', 'At least {0} characters length.', plugin('User')->settings['password_min_length']); ?></li>

        <?php if (plugin('User')->settings['password_uppercase']): ?>
            <li><?= __d('user', 'At least one uppercase character (A-Z).'); ?></li>
        <?php endif; ?>

        <?php if (plugin('User')->settings['password_lowercase']): ?>
            <li><?= __d('user', 'At least one lowercase character (a-z).'); ?></li>
        <?php endif; ?>

        <?php if (plugin('User')->settings['password_number']): ?>
            <li><?= __d('user', 'At least one numeric character (0-9).'); ?></li>
        <?php endif; ?>

        <?php if (plugin('User')->settings['password_non_alphanumeric']): ?>
            <li><?= __d('user', 'At least one non-alphanumeric character (e.g. <code>#$"</code>).'); ?></li>
        <?php endif; ?>
    </ul>
</em>