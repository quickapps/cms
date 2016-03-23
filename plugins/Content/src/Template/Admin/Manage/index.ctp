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
        <?= $this->element('Content.index_submenu'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 pull-right">
        <p>
            <?= $this->Form->create(null, ['type' => 'get']); ?>
            <div class="input-group">
                <?=
                    $this->Form->input('filter', [
                        'label' => false,
                        'value' => (!empty($this->request->query['filter']) ? $this->request->query['filter'] : '')
                    ]);
                ?>
                <span class="input-group-btn">
                    <?= $this->Form->submit(__d('content', 'Search Contents')); ?>
                </span>
            </div>
            <?= $this->Form->end(); ?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th><?= __d('content', 'Title'); ?></th>
                    <th><?= __d('content', 'Type'); ?></th>
                    <th class="hidden-xs hidden-sm"><?= __d('content', 'Language'); ?></th>
                    <th class="hidden-xs"><?= __d('content', 'Created on'); ?></th>
                    <th class="hidden-xs"><?= __d('content', 'Modified on'); ?></th>
                    <th class="text-right"><?= __d('content', 'Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contents as $content): ?>
                    <tr class="<?= $content->status == 0 ? 'warning' : ''; ?> ">
                        <td>
                           <?=
                                $this->Html->link(h($content->title), [
                                    'plugin' => 'Content',
                                    'controller' => 'manage',
                                    'action' => 'edit',
                                    $content->id,
                                ], [
                                    'title' => __d('content', 'Edit'),
                                ]);
                            ?>

                            <?php if ($content->promote): ?>
                            <span class="glyphicon glyphicon-home" title="<?= __d('content', 'Promote to home page'); ?>"></span>
                            <?php endif; ?>

                            <?php if ($content->sticky): ?>
                            <span class="glyphicon glyphicon-pushpin" title="<?= __d('content', 'Sticky at top of lists'); ?>"></span>
                            <?php endif; ?>

                            <?php if ((int)$content->comment_status === 1): ?>
                            <span class="glyphicon glyphicon-comment" title="<?= __d('content', 'Comments open'); ?>"></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $content->type; ?></td>
                        <td class="hidden-xs hidden-sm"><?= $content->language ? $content->language : __d('content', '---'); ?></td>
                        <td class="hidden-xs">
                            <?=
                                __d('content', '{0} <br /> by <em>{1}</em>',
                                    $content->created->format(__d('content', 'Y-m-d H:i:s')),
                                    $content->get('author')->name
                                );
                            ?>
                        </td>
                        <td class="hidden-xs">
                            <?=
                                __d('content', '{0} <br /> by <em>{1}</em>',
                                    $content->modified->format(__d('content', 'Y-m-d H:i:s')),
                                    is_object($content->get('modified_by')) ? $content->get('modified_by')->get('name') : __d('content', '-- unknown --')
                                );
                            ?>
                        </td>
                        <td>
                            <div class="btn-group pull-right">
                                <!-- edit -->
                                <?php if ($content->content_type->userAllowed('edit')): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'Content',
                                        'controller' => 'manage',
                                        'action' => 'edit',
                                        $content->id,
                                    ], [
                                        'title' => __d('content', 'Edit'),
                                        'class' => 'btn btn-default btn-sm glyphicon glyphicon-pencil',
                                    ]);
                                ?>
                                <?php endif; ?>

                                <!-- view -->
                                <?=
                                    $this->Html->link('', $content->url, [
                                        'title' => __d('content', 'Visit published content'),
                                        'class' => 'btn btn-default btn-sm glyphicon glyphicon-eye-open',
                                        'target' => '_blank',
                                    ]);
                                ?>

                                <!-- translate -->
                                <?php if ($content->content_type->userAllowed('translate') && $content->language && !$content->translation_for): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'Content',
                                        'controller' => 'manage',
                                        'action' => 'translate',
                                        $content->id
                                    ], [
                                        'title' => __d('content', 'Translate'),
                                        'class' => 'btn btn-default btn-sm glyphicon glyphicon-globe',
                                    ]);
                                ?>
                                <?php endif; ?>

                                <!-- delete -->
                                <?php if ($content->content_type->userAllowed('delete')): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'Content',
                                        'controller' => 'manage',
                                        'action' => 'delete',
                                        $content->id,
                                    ], [
                                        'title' => __d('content', 'Delete'),
                                        'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
                                        'confirm' => __d('content', 'You are about to delete: "{0}" ({1}). Are you sure ?', $content->title, $content->content_type->name),
                                    ]);
                                ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <ul class="pagination">
            <?= $this->Paginator->prev(); ?>
            <?= $this->Paginator->numbers(); ?>
            <?= $this->Paginator->next(); ?>
        </ul>
    </div>
</div>