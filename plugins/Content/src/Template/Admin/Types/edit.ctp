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
        <?php echo $this->element('Content.index_submenu'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php echo $this->Form->create($type); ?>
            <fieldset>
                <legend><?php echo __d('content', 'Content Type Information'); ?></legend>
                <?php echo $this->Form->input('name', ['label' => __d('content', 'Name *')]); ?>
                <em class="help-block"><?php echo __d('content', 'This text will be displayed as part of the list on the "Add New Content" page.'); ?></em>

                <?php echo $this->Form->input('slug', ['label' => __d('content', 'Machine name *'), 'readonly']); ?>
                <em class="help-block">
                    <?php echo __d('content', 'A unique name for this content type. This value can not be changed after content type is created.'); ?>
                </em>

                <?php echo $this->Form->input('title_label', ['label' => __d('content', 'Title field label *')]); ?>
                <em class="help-block"><?php echo __d('content', 'Label name for the "Title" field. e.g. "Product name", "Author name", etc.'); ?></em>

                <?php echo $this->Form->input('description', ['label' => __d('content', 'Description'), 'type' => 'textarea']); ?>
                <em class="help-block"><?php echo __d('content', 'Describe this content type. The text will be displayed on the Add new content page.'); ?></em>
            </fieldset>

            <hr />

            <fieldset>
                <legend><?php echo __d('content', 'Permissions'); ?></legend>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo __d('content', 'Role'); ?></th>
                                <th><?php echo __d('content', 'Create'); ?></th>
                                <th><?php echo __d('content', 'Edit'); ?></th>
                                <th><?php echo __d('content', 'Translate'); ?></th>
                                <th><?php echo __d('content', 'Delete'); ?></th>
                                <th><?php echo __d('content', 'Publish'); ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($roles as $id => $role): ?>
                            <tr>
                                <td><?php echo $role; ?></td>
                                <?php foreach (['create', 'edit', 'translate', 'delete', 'publish'] as $action): ?>
                                    <td>
                                        <?php if ($id == ROLE_ID_ADMINISTRATOR): ?>
                                            <?php echo __d('content', 'yes'); ?>
                                        <?php else: ?>
                                            <?php
                                                echo $this->Form->input('_dummy', [
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
                <legend><?php echo __d('content', 'Content Type Settings'); ?></legend>

                <?php echo $this->Form->input('defaults.status', ['type' => 'checkbox', 'label' => __d('content', 'Mark as published')]); ?>
                <?php echo $this->Form->input('defaults.promote', ['type' => 'checkbox', 'label' => __d('content', 'Promoted to front page')]); ?>
                <?php echo $this->Form->input('defaults.sticky', ['type' => 'checkbox', 'label' => __d('content', 'Sticky at top of lists')]); ?>

                <hr />

                <?php echo $this->Form->input('defaults.author_name', ['type' => 'checkbox', 'label' => __d('content', "Show author's name")]); ?>
                <em class="help-block"><?php echo __d('content', "Author's username will be displayed."); ?></em>

                <?php echo $this->Form->input('defaults.show_date', ['type' => 'checkbox', 'label' => __d('content', 'Show date')]); ?>
                <em class="help-block"><?php echo __d('content', 'Publish date will be displayed'); ?></em>

                <?php
                    echo $this->Form->input('defaults.comment_status', [
                        'type' => 'select',
                        'label' => __d('content', 'Comments default status'),
                        'options' => [
                            0 => __d('content', 'Closed'),
                            1 => __d('content', 'Open'),
                            2 => __d('content', 'Read only'),
                        ],
                    ]);
                ?>
                <em class="help-block"><?php echo __d('content', 'Default comment setting for new content.'); ?></em>

                <?php
                    echo $this->Form->input('defaults.language', [
                        'type' => 'select',
                        'label' => __d('content', 'Language'),
                        'options' => $languages,
                        'empty' => __d('content', '-- ANY --'),
                    ]);
                ?>
                <em class="help-block"><?php echo __d('content', 'Default language for new contents.'); ?></em>
            </fieldset>

            <?php echo $this->Form->submit(__d('content', 'Save changes')); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>