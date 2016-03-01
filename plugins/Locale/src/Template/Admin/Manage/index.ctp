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
                $this->Html->link(__d('locale', 'Add new language'), [
                    'plugin' => 'Locale',
                    'controller' => 'manage',
                    'action' => 'add'
                ], [
                    'class' => 'btn btn-primary'
                ]);
            ?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><?= __d('locale', 'Name'); ?></th>
                    <th class="hidden-xs"><?= __d('locale', 'Path'); ?></th>
                    <th class="text-right"><?= __d('locale', 'Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $count = $languages->count(); ?>
                <?php $k = 0; ?>
                <?php foreach ($languages as $language): ?>
                    <tr>
                        <td>
                            <?php if ($language->icon): ?>
                                <?= $this->Html->image("Locale.flags/{$language->icon}"); ?>
                            <?php endif; ?>

                            <?php if ($language->status): ?>
                                <?= $language->name; ?>
                            <?php else: ?>
                                <s title="<?= __d('locale', 'Disabled'); ?>"><?= $language->name; ?></s>
                            <?php endif; ?>

                            <?php if (option('default_language') === $language->code): ?>
                            <span class="glyphicon glyphicon-star" title="<?= __d('locale', 'Default language'); ?>"></span>
                            <?php endif; ?>
                        </td>
                        <td class="hidden-xs">/<?= $language->code; ?></td>
                        <td>
                            <div class="btn-group pull-right">
                                <!-- move up -->
                                <?php if ($k > 0): ?>
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'Locale',
                                            'controller' => 'manage',
                                            'action' => 'move',
                                            $language->id,
                                            'up',
                                        ], [
                                            'title' => __d('locale', 'Move up'),
                                            'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-arrow-up',
                                        ]);
                                    ?>
                                <?php endif; ?>

                                <!-- move down -->
                                <?php if ($k < $count - 1): ?>
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'Locale',
                                            'controller' => 'manage',
                                            'action' => 'move',
                                            $language->id,
                                            'down',
                                        ], [
                                            'title' => __d('locale', 'Move down'),
                                            'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-arrow-down',
                                        ]);
                                    ?>
                                <?php endif; ?>

                                <!-- set default -->
                                <?php if ($language->status && option('default_language') !== $language->code): ?>
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'Locale',
                                            'controller' => 'manage',
                                            'action' => 'set_default',
                                            $language->id,
                                        ], [
                                            'title' => __d('locale', 'Set as default'),
                                            'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-star',
                                        ]);
                                    ?>
                                <?php endif; ?>

                                <!-- edit -->
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => 'Locale',
                                        'controller' => 'manage',
                                        'action' => 'edit',
                                        $language->id,
                                    ], [
                                        'title' => __d('locale', 'Edit'),
                                        'class' => 'btn btn-sm btn-default btn-sm glyphicon glyphicon-pencil',
                                    ]);
                                ?>

                                <!-- delete, enable, disable -->
                                <?php if (!in_array($language->code, [CORE_LOCALE, option('default_language')])): ?>
                                    <?php if ($language->status): ?>
                                        <?=
                                            $this->Html->link('', [
                                                'plugin' => 'Locale',
                                                'controller' => 'manage',
                                                'action' => 'disable',
                                                $language->id,
                                            ], [
                                                'title' => __d('locale', 'Disable'),
                                                'class' => 'btn btn-default btn-sm glyphicon glyphicon-remove-circle',
                                                'confirm' => __d('locale', 'You are about to disable: "{0}". Are you sure ?', $language->name),
                                            ]);
                                        ?>
                                    <?php else: ?>
                                        <?=
                                            $this->Html->link('', [
                                                'plugin' => 'Locale',
                                                'controller' => 'manage',
                                                'action' => 'enable',
                                                $language->id,
                                            ], [
                                                'title' => __d('locale', 'Enable'),
                                                'class' => 'btn btn-default btn-sm glyphicon glyphicon-ok-circle',
                                            ]);
                                        ?>
                                    <?php endif; ?>
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'Locale',
                                            'controller' => 'manage',
                                            'action' => 'delete',
                                            $language->id,
                                        ], [
                                            'title' => __d('locale', 'Delete'),
                                            'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
                                            'confirm' => __d('locale', 'You are about to delete: "{0}". Are you sure ?', $language->name),
                                        ]);
                                    ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php $k++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <em><?= __d('locale', 'Language\'s order dictates the position in which languages are rendered, for example in "Language Switcher" block.'); ?></em>
    </div>
</div>