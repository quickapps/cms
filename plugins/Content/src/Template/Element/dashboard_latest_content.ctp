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

<div class="panel panel-default">
    <div class="panel-heading"><?= __d('content', 'Recent Content'); ?></div>
    <div class="panel-body">
        <table class="table">
            <?php foreach ($contents as $content): ?>
                <tr>
                    <td>
                        <?= $this->Html->link($content->title, ['plugin' => 'Content', 'controller' => 'manage', 'action' => 'edit', $content->id]); ?>
                        <em class="help-block"><?= $content->description; ?></em>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>