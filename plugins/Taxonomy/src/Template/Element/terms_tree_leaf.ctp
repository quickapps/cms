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

<li id="vocabularyTerm_<?php echo $term->id; ?>">
    <div>
        <span style="cursor:move;"><?php echo $term->name; ?> (<?php echo $term->slug; ?>)&nbsp;&nbsp;&nbsp;</span>
        <div class="btn-group">
            <?php
                echo $this->Html->link('', [
                    'plugin' => 'Taxonomy',
                    'controller' => 'terms',
                    'action' => 'edit',
                    $term->id
                ], [
                    'title' => __d('menu', 'Edit term'),
                    'class' => 'btn btn-default btn-xs glyphicon glyphicon-pencil',
                ]);
            ?>
            <?php
                echo $this->Html->link('', [
                    'plugin' => 'Taxonomy',
                    'controller' => 'terms',
                    'action' => 'delete',
                    $term->id
                ], [
                    'title' => __d('menu', 'Delete this link'),
                    'confirm' => __d('menu', 'Remove this term? Children terms will be re-assigned to the immediately superior parent term.'),
                    'class' => 'btn btn-default btn-xs glyphicon glyphicon-trash'
                ]);
            ?>
        </div>
    </div>

    <p><?php echo $info['children']; ?></p>
</li>