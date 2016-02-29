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
    <div class="col-md-12">
        <?= $this->Form->create($type); ?>
            <fieldset>
                <legend><?= __d('content', 'Content Type Information'); ?></legend>
                <?= $this->Form->input('name', ['label' => __d('content', 'Name *')]); ?>
                <em class="help-block"><?= __d('content', 'This text will be displayed as part of the list on the "Add New Content" page.'); ?></em>

                <?= $this->Form->input('slug', ['label' => __d('content', 'Machine name *'), 'readonly']); ?>
                <em class="help-block">
                    <?= __d('content', 'A unique name for this content type. This value can not be changed after content type is created.'); ?>
                </em>

                <?= $this->Form->input('title_label', ['label' => __d('content', 'Title field label *')]); ?>
                <em class="help-block"><?= __d('content', 'Label name for the "Title" field. e.g. "Product name", "Author name", etc.'); ?></em>

                <?= $this->Form->input('description', ['label' => __d('content', 'Description'), 'type' => 'textarea']); ?>
                <em class="help-block"><?= __d('content', 'Describe this content type. The text will be displayed on the Add new content page.'); ?></em>
            </fieldset>

            <hr />

            <fieldset>
                <legend><?= __d('content', 'Permissions'); ?></legend>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?= __d('content', 'Role'); ?></th>
                                <th><?= __d('content', 'Create'); ?></th>
                                <th><?= __d('content', 'Edit'); ?></th>
                                <th><?= __d('content', 'Translate'); ?></th>
                                <th><?= __d('content', 'Delete'); ?></th>
                                <th><?= __d('content', 'Publish'); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($roles as $id => $role): ?>
                            <tr>
                                <td><?= $role; ?></td>
                                <?php foreach (['create', 'edit', 'translate', 'delete', 'publish'] as $action): ?>
                                    <td>
                                        <?php if ($id == ROLE_ID_ADMINISTRATOR): ?>
                                            <?= __d('content', 'yes'); ?>
                                        <?php else: ?>
                                            <?=
                                                $this->Form->input('_dummy', [
                                                    'type' => 'checkbox',
                                                    'name' => "permissions[{$action}][]",
                                                    'label' => false,
                                                    'value' => $id,
                                                    ($type->checkPermission($id, $action) ? 'checked' : '')
                                                ]);
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                             </tr>
                            <?php endforeach; ?>
                        <tbody>
                    </table>
                </div>
            </fieldset>

            <hr />

            <fieldset>
                <legend><?= __d('content', 'Content Type Settings'); ?></legend>

                <?= $this->Form->input('defaults.status', ['type' => 'checkbox', 'label' => __d('content', 'Mark as published')]); ?>
                <?= $this->Form->input('defaults.promote', ['type' => 'checkbox', 'label' => __d('content', 'Promoted to front page')]); ?>
                <?= $this->Form->input('defaults.sticky', ['type' => 'checkbox', 'label' => __d('content', 'Sticky at top of lists')]); ?>

                <hr />

                <?= $this->Form->input('defaults.author_name', ['type' => 'checkbox', 'label' => __d('content', "Show author's name")]); ?>
                <em class="help-block"><?= __d('content', "Author's username will be displayed."); ?></em>

                <?= $this->Form->input('defaults.show_date', ['type' => 'checkbox', 'label' => __d('content', 'Show date')]); ?>
                <em class="help-block"><?= __d('content', 'Publish date will be displayed'); ?></em>

                <?=
                    $this->Form->input('defaults.comment_status', [
                        'type' => 'select',
                        'label' => __d('content', 'Comments default status'),
                        'options' => [
                            0 => __d('content', 'Closed'),
                            1 => __d('content', 'Open'),
                            2 => __d('content', 'Read only'),
                        ],
                    ]);
                ?>
                <em class="help-block"><?= __d('content', 'Default comment setting for new content.'); ?></em>

                <?=
                    $this->Form->input('defaults.language', [
                        'type' => 'select',
                        'label' => __d('content', 'Language'),
                        'options' => $languages,
                        'empty' => __d('content', '-- ANY --'),
                    ]);
                ?>
                <em class="help-block"><?= __d('content', 'Default language for new contents.'); ?></em>
            </fieldset>

            <?= $this->Form->submit(__d('content', 'Save changes')); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>