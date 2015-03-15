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

<?php echo $this->Form->create(null); ?>
    <h2>
        <?php echo __d('taxonomy', "{0}: Terms Tree", $vocabulary->name); ?>
        <?php
            echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> ' . __d('taxonomy', 'add term'), [
                'plugin' => 'Taxonomy',
                'controller' => 'terms',
                'action' => 'add',
                $vocabulary->id
            ], [
                'class' => 'btn btn-default',
                'escape' => false
            ]);
        ?>
    </h2>

    <?php if ($terms->count()): ?>
        <?php echo $this->Form->hidden('tree_order', ['id' => 'tree_order']); ?>
        <?php
            echo $this->Menu->render($terms, [
                'beautify' => false,
                'breadcrumbGuessing' => false,
                'id' => 'menu-links',
                'templates' => [
                    'root' => '<ul class="sortable">{{content}}</ul>',
                    'parent' => '<ul>{{content}}</ul>',
                ],
                'formatter' => function ($term, $info) {
                    return $this->element('Taxonomy.terms_tree_leaf', compact('term', 'info'));
                }
            ]);
        ?>
        <?php echo $this->Form->submit(__d('taxonomy', 'Save Order')); ?>
    <?php else: ?>
        <div class="alert alert-warning">
            <?php echo __d('taxonomy', 'There are not terms yet, use the "add term" button to start adding new terms to this vocabulary.'); ?>
        </div>
    <?php endif; ?>
<?php echo $this->Form->end(); ?>
<?php
    echo $this->Html->script([
        'Jquery.jquery-ui.min.js',
        'System.jquery.json.js',
        'System.jquery.mjs.nestedSortable.js',
        'Taxonomy.terms.tree.js',
    ]);
?>