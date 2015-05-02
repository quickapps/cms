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
        <?php if (!empty($languages)): ?>
            <?php echo $this->Form->create($node); ?>
                <fieldset>
                    <legend><?php echo __d('node', 'Translating Content'); ?></legend>

                    <?php echo $this->Form->input('title', ['label' => $node->node_type->title_label . ' *']); ?>
                    <em class="help-block"><?php echo __d('node', 'New title for the translated version.'); ?></em>

                    <?php echo $this->Form->input('language', ['type' => 'select', 'label' => __d('node', 'Translate to'), 'options' => $languages]); ?>
                    <em class="help-block"><?php echo __d('node', 'Select the language to which you desire translate this content.'); ?></em>

                    <?php echo $this->Form->submit( __d('node', 'Continue')); ?>

                    <?php if ($translations->count()): ?>
                        <h3><?php echo __d('node', 'Available Translations'); ?></h3>
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
    </div>
</div>