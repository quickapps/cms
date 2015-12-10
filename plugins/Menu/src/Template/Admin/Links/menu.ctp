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
                <?php echo __d('menu', "{0}: Links Tree", $menu->title); ?>
                <?php
                    echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> ' . __d('menu', 'add link'), [
                        'plugin' => 'Menu',
                        'controller' => 'links',
                        'action' => 'add',
                        $menu->id
                    ], [
                        'class' => 'btn btn-default btn-xs',
                        'escape' => false
                    ]);
                ?>
            </div>

            <div class="panel-body">
                <?php echo $this->Form->create(null); ?>
                    <?php if (!$links->isEmpty()): ?>
                        <?php echo $this->Form->hidden('tree_order', ['id' => 'tree_order']); ?>
                        <?php
                            echo $this->Menu->render($links, [
                                'beautify' => false,
                                'breadcrumbGuessing' => false,
                                'id' => 'menu-links',
                                'templates' => [
                                    'root' => '<ul class="sortable">{{content}}</ul>',
                                    'parent' => '<ul>{{content}}</ul>',
                                ],
                                'formatter' => function ($link, $info) {
                                    return $this->element('Menu.menu_tree_leaf', compact('link', 'info'));
                                }
                            ]);
                        ?>
                        <?php echo $this->Form->submit(__d('menu', 'Save Order')); ?>
                        <em class="help-block"><?php echo __d('menu', 'Drag and drop to reorder the links tree, then click on "Save Order".'); ?></em>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <?php echo __d('menu', 'There are not links yet, use the "add link" button to start adding new links to this menu.'); ?>
                        </div>
                    <?php endif; ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
    echo $this->Html->script([
        'Jquery.jquery-ui.min.js',
        'System.jquery.json.js',
        'System.jquery.mjs.nestedSortable.js',
        'Menu.links.tree.js'
    ]);
?>

<script type="text/javascript">
    $('form input[type=submit]').on('click', function() {
        linksChanged = false;
    });

    $(window).bind('beforeunload', function() {
        if (linksChanged) {
            return "<?php echo __d('menu', 'Changes will be lost, are you sure you want to exit this page ?'); ?>";
        }
    });
</script>