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
        <?= $this->Form->create($content, ['id' => 'content-form']); ?>
            <fieldset>
                <legend><?= __d('content', 'Basic Information'); ?></legend>

                <?= $this->Form->input('title', ['label' => $content->content_type->title_label . ' *']); ?>
                <em class="help-block">
                    <b><?= __d('content', 'Permalink'); ?>:</b> <?= $this->Html->link(stripLanguagePrefix($content->url), $content->url, ['target' => '_blank']); ?>
                </em>

                <?= $this->Form->input('regenerate_slug', ['type' => 'checkbox', 'label' => __d('content', 'Regenerate Slug (actual: {0})', $content->slug)]); ?>
                <em class="help-block"><?= __d('content', 'Check this to generate a new slug from title.'); ?></em>

                <?php if ($content->translation_of): ?>
                    <em class="help-block">
                        <strong><?= __d('content', 'This content is a translation of'); ?>: </strong>
                        <?= $this->Html->link($content->translation_of->title, ['plugin' => 'Content', 'controller' => 'manage', 'action' => 'edit', $content->translation_of->id]); ?>
                    </em>
                <?php endif; ?>

                <?= $this->Form->input('description', ['label' => __d('content', 'Description')]); ?>
                <em class="help-block"><?= __d('content', 'A short description (200 chars. max.) about this content. Will be used as page meta-description when rendering this content.'); ?></em>

                <?= $this->Form->input('edit_summary', ['label' => __d('content', 'Edit Summary')]); ?>
                <em class="help-block"><?= __d('content', 'Briefly describe your changes.'); ?></em>
            </fieldset>

            <fieldset>
                <legend><?= __d('content', 'Publishing'); ?></legend>
                <?php if ($content->content_type->userAllowed('publish')): ?>
                    <?= $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('content', 'Published')]); ?>
                <?php else: ?>
                    <?= $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('content', 'Published'), 'disabled']); ?>
                    <em class="help-block"><?= __d('content', 'This content must reviewed by an administrator before publishing it.'); ?></em>
                <?php endif; ?>
                <?= $this->Form->input('promote', ['type' => 'checkbox', 'label' => __d('content', 'Promoted to front page')]); ?>
                <?= $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __d('content', 'Sticky at top of lists')]); ?>
            </fieldset>

            <?php if (isset($content->_fields) && $content->_fields->count()): ?>
            <fieldset>
                <legend><?= __d('content', 'Content'); ?></legend>
                <?php foreach ($content->_fields as $field): ?>
                    <?= $this->escapeShortcodes($this->Form->input($field)); ?>
                <?php endforeach; ?>
            </fieldset>
            <?php endif; ?>

            <fieldset>
                <legend><?= __d('content', 'Settings'); ?></legend>
                    <?= $this->Form->input('comment_status', ['label' => __d('content', 'Comments'), 'options' => [1 => __d('content', 'Open'), 0 => __d('content', 'Closed'), 2 => __d('content', 'Read Only')]]); ?>

                    <?php if (!$content->translation_for): ?>
                        <?= $this->Form->input('language', ['label' => __d('content', 'Language'), 'options' => $languages, 'empty' => __d('content', '-- ANY --')]); ?>
                    <?php else: ?>
                        <?= $this->Form->label(null, __d('content', 'Language')); ?>
                        <em class="help-block"><?= __d('content', 'This content is the ({0}) translation of an existing content originally in ({1}).', $content->language, $content->translation_of->language); ?></em>
                    <?php endif; ?>

                    <?= $this->Form->input('roles._ids', ['type' => 'select', 'label' => __d('content', 'Show content for specific roles'), 'options' => $roles, 'multiple' => 'checkbox']); ?>
                    <em class="help-block"><?= __d('content', 'Show this content only for the selected role(s). If you select no roles, the content will be visible to all users.'); ?></em>
            </fieldset>

            <?php if ($content->has('content_revisions') && count($content->content_revisions)): ?>

            <hr />

            <fieldset>
                <legend><?= __d('content', 'Revisions'); ?></legend>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?= __d('content', 'Title'); ?></th>
                            <th><?= __d('content', 'Summary'); ?></th>
                            <th><?= __d('content', 'Language'); ?></th>
                            <th><?= __d('content', 'Revision date'); ?></th>
                            <th><?= __d('content', 'Actions'); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($content->content_revisions as $revision): ?>
                        <tr>
                            <td>
                                <?= $revision->data->title; ?><br />
                                <em>
                                    <small>
                                        (<?= !empty($revision->data->description) ? $this->Text->truncate($revision->data->description, 30) : '---'; ?>)
                                    </small>
                                </em>
                            </td>
                            <td><?= !empty($revision->summary) ? $revision->summary : __d('content', '--not provided--'); ?></td>
                            <td><?= !empty($revision->data->language) ? $revision->data->language : __d('content', '--any--'); ?></td>
                            <td><?= $revision->created->format(__d('content', 'Y-m-d H:i:s')); ?></td>
                            <td>
                                <div class="btn-group">
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'Content',
                                            'controller' => 'manage',
                                            'action' => 'edit',
                                            $content->id, $revision->id
                                        ], [
                                            'title' => __d('content', 'Load revision'),
                                            'class' => 'btn btn-default btn-sm glyphicon glyphicon-edit',
                                        ]);
                                    ?>
                                    <?=
                                        $this->Html->link('', [
                                            'plugin' => 'Content',
                                            'controller' => 'manage',
                                            'action' => 'delete_revision',
                                            $content->id,
                                            $revision->id
                                        ], [
                                            'title' => __d('content', 'Delete revision'),
                                            'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
                                            'confirm' => __d('content', 'You are about to delete: "{0}". Are you sure ?', $revision->data->title),
                                        ]);
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tbody>
                </table>
            </fieldset>
            <?php endif; ?>

            <?php if (!$content->translation_for & $content->has('translations') && count($content->translations)): ?>
            <fieldset>
                <legend><?= __d('content', 'Translations'); ?></legend>
                <ul>
                    <?php foreach ($content->translations as $translation): ?>
                    <li><?= $this->Html->link($translation->title, ['plugin' => 'Content', 'controller' => 'manage', 'action' => 'edit', $translation->id]); ?> (<?= $translation->language; ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </fieldset>
            <?php endif; ?>

            <?= $this->Form->submit(__d('content', 'Save All')); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>