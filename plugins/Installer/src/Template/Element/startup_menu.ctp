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

<p>
    <ul class="nav nav-pills startup-menu">
        <?php foreach ($menu as $label => $link): ?>
        <li class="<?php echo $link['active'] ? 'active' : 'disabled'; ?>">
            <?php echo $this->Html->link($label, ($link['active'] ? $link['url'] : '#')); ?>
        </li>
        <?php endforeach; ?>
    </ul>
</p>

<hr />