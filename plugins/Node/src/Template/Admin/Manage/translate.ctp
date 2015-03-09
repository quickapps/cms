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

<?php if (!empty($languages)): ?>
    <?php echo $this->Form->create($node); ?>
    <fieldset>
        <legend><?php echo __d('system', 'Translating Content'); ?></legend>

        <div class="form-group">
            <?php echo $this->Form->input('title', ['label' => $node->node_type->title_label . ' *']); ?>
            <em class="help-block"><?php echo __d('system', 'New title for the translated version.'); ?></em>
        </div>

        <div class="form-group">
            <?php echo $this->Form->input('language', ['type' => 'select', 'label' => __d('node', 'Translate to'), 'options' => $languages]); ?>
            <em class="help-block"><?php echo __d('system', 'Select the language to which you desire translate this content.'); ?></em>
        </div>

        <div class="form-group">
            <?php echo $this->Form->submit( __d('node', 'Continue')); ?>
        </div>

        <?php if ($translations->count()): ?>
        <h3><?php echo __d('system', 'Available Translations'); ?></h3>
        <ul>
            <?php foreach ($translations as $t): ?>
            <li>
                <?php echo $this->Html->link($t->title, ['plugin' => 'Node', 'controller' => 'manage', 'action' => 'edit', $t->id]); ?> (<?php echo $t->language; ?>)
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>
<?php else: ?>
    <div class="alert alert-danger">
        <?php echo __d('node', 'This content cannot be translated because there are no more available languages into which this content could be translated to.'); ?>
    </div>
<?php endif; ?>
