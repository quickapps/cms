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

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= __d('taxonomy', '{0}: Terms Tree', $vocabulary->name); ?>
                <?=
                    $this->Html->link('<span class="glyphicon glyphicon-plus"></span> ' . __d('taxonomy', 'add term'), [
                        'plugin' => 'Taxonomy',
                        'controller' => 'terms',
                        'action' => 'add',
                        $vocabulary->id
                    ], [
                        'class' => 'btn btn-default btn-xs',
                        'escape' => false
                    ]);
                ?>
            </div>

            <div class="panel-body">
                <?= $this->Form->create(null); ?>
                    <?php if (!$terms->isEmpty()): ?>
                        <?= $this->Form->hidden('tree_order', ['id' => 'tree_order']); ?>
                        <?=
                            $this->Menu->render($terms, [
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
                        <?= $this->Form->submit(__d('taxonomy', 'Save Order')); ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <?= __d('taxonomy', 'There are not terms yet, use the "add term" button to start adding new terms to this vocabulary.'); ?>
                        </div>
                    <?php endif; ?>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<?=
    $this->Html->script([
        'Jquery.jquery-ui.min.js',
        'System.jquery.json.js',
        'System.jquery.mjs.nestedSortable.js',
        'Taxonomy.terms.tree.js',
    ]);
?>