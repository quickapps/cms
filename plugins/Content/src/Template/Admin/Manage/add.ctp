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
        <?php echo $this->Form->create($content, ['id' => 'content-form']); ?>
            <fieldset>
                <legend><?php echo __d('content', 'Basic Information'); ?></legend>

                <?php echo $this->Form->input('title', ['label' => $content->content_type->title_label . ' *']); ?>
                <em class="help-block"><?php echo __d('content', 'Will be used as content title.'); ?></em>

                <?php echo $this->Form->input('description', ['label' => __d('content', 'Description')]); ?>
                <em class="help-block"><?php echo __d('content', 'A short description (200 chars. max.) about this content. Will be used as page meta-description when rendering this content.'); ?></em>
            </fieldset>

            <fieldset>
                <legend><?php echo __d('content', 'Publishing'); ?></legend>
                <?php echo $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('content', 'Published')]); ?>
                <?php echo $this->Form->input('promote', ['type' => 'checkbox', 'label' => __d('content', 'Promoted to front page')]); ?>
                <?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __d('content', 'Sticky at top of lists')]); ?>
            </fieldset>

            <?php if (isset($content->_fields) && $content->_fields->count()): ?>
            <fieldset>
                <legend><?php echo __d('content', 'Content'); ?></legend>
                <?php foreach ($content->_fields as $field): ?>
                    <?php echo $this->Form->input($field); ?>
                <?php endforeach; ?>
            </fieldset>
            <?php endif; ?>

            <fieldset>
                <legend><?php echo __d('content', 'Settings'); ?></legend>
                    <?php echo $this->Form->input('comment_status', ['label' => __d('content', 'Comments'), 'options' => [1 => __d('content', 'Open'), 0 => __d('content', 'Closed'), 2 => __d('content', 'Read Only')]]); ?>
                    <?php echo $this->Form->input('language', ['label' => __d('content', 'Language'), 'options' => $languages, 'empty' => __d('content', '-- ANY --')]); ?>
                    <?php echo $this->Form->input('roles._ids', ['type' => 'select', 'label' => __d('content', 'Show content for specific roles'), 'options' => $roles, 'multiple' => 'checkbox']); ?>
                    <em class="help-block"><?php echo __d('content', 'Show this content only for the selected role(s). If you select no roles, the content will be visible to all users.'); ?></em>
            </fieldset>

            <?php echo $this->Form->submit(__d('content', 'Create')); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>