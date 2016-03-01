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

<div class="alert alert-info">
    <strong><?= $message; ?>:</strong>

    <ol>
        <?php foreach ($params['plugins'] as $name => $path): ?>
        <li>
            <b><?= $name; ?></b>:
            <?php $controller = str_ends_with($name, 'Theme') ? 'themes' : 'plugins'; ?>
            <?=
                $this->Html->link($this->Text->truncate($path, 35, ['ellipsis' => ' ...']), [
                    'plugin' => 'System',
                    'controller' => $controller,
                    'action' => 'install',
                    'prefix' => 'admin',
                    'directory' => $path,
                ], [
                    'title' => __d('system', 'Run installation: {0}', $path),
                ]);
            ?>
        </li>
        <?php endforeach; ?>
    </ol>
</div>