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
        <?php echo $this->element('Node.index_submenu'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <p>
            <?php echo $this->Form->create(null, ['type' => 'get']); ?>
            <div class="input-group">
                <?php echo $this->Form->input('filter', ['label' => false]) ?>
                <span class="input-group-btn">
                    <?php echo $this->Form->submit(__d('node', 'Search Contents')); ?>
                </span>
            </div>
            <?php echo $this->Form->end(); ?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th><?php echo __d('node', 'Title'); ?></th>
                    <th><?php echo __d('node', 'Type'); ?></th>
                    <th class="hidden-xs hidden-sm"><?php echo __d('node', 'Language'); ?></th>
                    <th class="hidden-xs"><?php echo __d('node', 'Created on'); ?></th>
                    <th class="hidden-xs"><?php echo __d('node', 'Modified on'); ?></th>
                    <th><?php echo __d('node', 'Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nodes as $node): ?>
                    <tr class="<?php echo $node->status == 0 ? 'warning' : ''; ?> ">
                        <td>
                           <?php
                                echo $this->Html->link(h($node->title), [
                                    'plugin' => 'Node',
                                    'controller' => 'manage',
                                    'action' => 'edit',
                                    $node->id,
                                ], [
                                    'title' => __d('node', 'Edit'),
                                ]);
                            ?>

                            <?php if ($node->promote): ?>
                            <span class="glyphicon glyphicon-home" title="<?php echo __d('node', 'Promote to home page'); ?>"></span>
                            <?php endif; ?>

                            <?php if ($node->sticky): ?>
                            <span class="glyphicon glyphicon-pushpin" title="<?php echo __d('node', 'Sticky at top of lists'); ?>"></span>
                            <?php endif; ?>

                            <?php if ((int)$node->comment_status === 1): ?>
                            <span class="glyphicon glyphicon-comment" title="<?php echo __d('node', 'Comments open'); ?>"></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $node->type; ?></td>
                        <td class="hidden-xs hidden-sm"><?php echo $node->language ? $node->language : __d('node', '---'); ?></td>
                        <td class="hidden-xs">
                            <?php
                                echo __d('node', '{on} <br /> by <em>{author}</em>', [
                                    'on' => $node->created->format(__d('node', 'Y-m-d H:i:s')),
                                    'author' => $node->author->name,
                                ]);
                            ?>
                        </td>
                        <td class="hidden-xs">
                            <?php
                                echo __d('node', '{on} <br /> by <em>{author}</em>', [
                                    'on' => $node->modified->format(__d('node', 'Y-m-d H:i:s')),
                                    'author' => is_object($node->get('modified_by')) ? $node->get('modified_by')->get('name') : __d('node', '-- unknown --'),
                                ]);
                            ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <?php
                                    echo $this->Html->link('', [
                                        'plugin' => 'Node',
                                        'controller' => 'manage',
                                        'action' => 'edit',
                                        $node->id,
                                    ], [
                                        'title' => __d('node', 'Edit'),
                                        'class' => 'btn btn-default btn-sm glyphicon glyphicon-pencil',
                                    ]);
                                ?>
                                <?php
                                    echo $this->Html->link('', $node->url, [
                                        'title' => __d('node', 'Visit published content'),
                                        'class' => 'btn btn-default btn-sm glyphicon glyphicon-eye-open',
                                        'target' => '_blank',
                                    ]);
                                ?>
                                <?php if ($node->language && !$node->translation_for): ?>
                                <?php
                                    echo $this->Html->link('', [
                                        'plugin' => 'Node',
                                        'controller' => 'manage',
                                        'action' => 'translate',
                                        $node->id
                                    ], [
                                        'title' => __d('node', 'Translate'),
                                        'class' => 'btn btn-default btn-sm glyphicon glyphicon-globe',
                                    ]);
                                ?>
                                <?php endif; ?>
                                <?php
                                    echo $this->Html->link('', [
                                        'plugin' => 'Node',
                                        'controller' => 'manage',
                                        'action' => 'delete',
                                        $node->id,
                                    ], [
                                        'title' => __d('node', 'Delete'),
                                        'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
                                        'confirm' => __d('node', 'You are about to delete: "{0}". Are you sure ?', $node->title),
                                    ]);
                                ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <ul class="pagination">
            <?php echo $this->Paginator->prev(); ?>
            <?php echo $this->Paginator->numbers(); ?>
            <?php echo $this->Paginator->next(); ?>
        </ul>
    </div>
</div>