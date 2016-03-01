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
        <p class="text-right">
            <?=
                $this->Html->link(__d('taxonomy', 'Define new vocabulary'), [
                    'plugin' => 'Taxonomy',
                    'controller' => 'vocabularies',
                    'action' => 'add'
                ], [
                    'class' => 'btn btn-primary',
                ]);
            ?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= __d('taxonomy', 'Name'); ?></th>
                    <th><?= __d('taxonomy', 'Description'); ?></th>
                    <th><?= __d('taxonomy', 'Actions'); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php $count = $vocabularies->count(); ?>
                <?php if ($count > 0): ?>
                    <?php $k = 0; ?>
                    <?php foreach ($vocabularies as $vocabulary): ?>
                    <tr>
                        <td><?= $vocabulary->name; ?> (<?= $vocabulary->slug; ?>)</td>
                        <td><em><?= $vocabulary->brief_description; ?></em></td>
                        <td>
                            <div class="btn-group">
                                <?php if ($k > 0): ?>
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'Taxonomy',
                                            'controller' => 'vocabularies',
                                            'action' => 'move',
                                            $vocabulary->id,
                                            'up'
                                        ], [
                                            'title' => __d('taxonomy', 'Move Up'),
                                            'class' => 'btn btn-default glyphicon glyphicon-arrow-up',
                                        ]);
                                    ?>
                                <?php endif; ?>

                                <?php if ($k < $count - 1): ?>
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'Taxonomy',
                                            'controller' => 'vocabularies',
                                            'action' => 'move',
                                            $vocabulary->id,
                                            'down'
                                        ], [
                                            'title' => __d('taxonomy', 'Move down'),
                                            'class' => 'btn btn-default glyphicon glyphicon-arrow-down',
                                        ]);
                                    ?>
                                <?php endif; ?>

                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'Taxonomy',
                                        'controller' => 'vocabularies',
                                        'action' => 'edit',
                                        $vocabulary->id
                                    ],[
                                        'title' => __d('taxonomy', 'Edit information'),
                                        'class' => 'btn btn-default glyphicon glyphicon-pencil',
                                    ]);
                                ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'Taxonomy',
                                        'controller' => 'terms',
                                        'action' => 'vocabulary',
                                        $vocabulary->id
                                    ], [
                                        'title' => __d('taxonomy', "Manage vocabulary's terms"),
                                        'class' => 'btn btn-default glyphicon glyphicon-tags',
                                    ]);
                                ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'Taxonomy',
                                        'controller' => 'terms',
                                        'action' => 'add',
                                        $vocabulary->id
                                    ], [
                                        'title' => __d('taxonomy', 'Add term'),
                                        'class' => 'btn btn-default glyphicon glyphicon-plus',
                                    ]);
                                ?>
                                <?php if (!$vocabulary->locked): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'Taxonomy',
                                        'controller' => 'vocabularies',
                                        'action' => 'delete',
                                        $vocabulary->id
                                    ], [
                                        'title' => __d('taxonomy', 'Delete vocabulary'),
                                        'class' => 'btn btn-default glyphicon glyphicon-trash',
                                        'confirm' => __d('taxonomy', 'Delete this vocabulary? All terms within this vocabulary will be lost.')
                                    ]);
                                ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php $k++; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="3">
                        <div class="alert alert-warning">
                            <?= __d('taxonomy', 'There are no vocabularies yet, click on "Define new vocabulary" button to add one.'); ?>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
