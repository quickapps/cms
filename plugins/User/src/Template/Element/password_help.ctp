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

use QuickApps\Core\Plugin;

$settings = Plugin::get('User')->settings();
?>

<em class="help-block">
    <ul>
        <li><?php echo __d('user', 'At least {0} characters length.', $settings['password_min_length']); ?></li>

        <?php if ($settings['password_uppercase']): ?>
            <li><?php echo __d('user', 'At least one uppercase character (A-Z).'); ?></li>
        <?php endif; ?>

        <?php if ($settings['password_lowercase']): ?>
            <li><?php echo __d('user', 'At least one lowercase character (a-z).'); ?></li>
        <?php endif; ?>

        <?php if ($settings['password_number']): ?>
            <li><?php echo __d('user', 'At least one numeric character (0-9).'); ?></li>
        <?php endif; ?>

        <?php if ($settings['password_non_alphanumeric']): ?>
            <li><?php echo __d('user', 'At least one non-alphanumeric character (e.g. <code>#$"</code>).'); ?></li>
        <?php endif; ?>
    </ul>
</em>