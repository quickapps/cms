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
        <?php echo $this->Form->create($term); ?>
            <fieldset>
                <legend><?php echo __d('taxonomy', "Add New Vocabulary's Term"); ?></legend>
                <?php echo $this->Form->input('name', ['label' => __d('taxonomy', "Term's name *")]); ?>
                <em class="help-block"><?php echo __d('taxonomy', 'The name for this term. e.g. "cat", "dog" or "bird" for the "animals" vocabulary.'); ?></em>

                <?php
                    echo $this->Form->input('parent_id', [
                        'type' => 'select',
                        'label' => __d('taxonomy', 'Parent term'),
                        'options' => $parentsTree,
                        'empty' => __d('taxonomy', '-- NONE --')
                    ]);
                ?>

                <?php echo $this->Form->submit(__d('taxonomy', '&laquo; Save & go back to terms tree'), ['name' => 'action_vocabulary', 'escape' => false]); ?>
                <?php echo $this->Form->submit(__d('taxonomy', 'Save & add another &raquo;'), ['name' => 'action_add', 'escape' => false]); ?>
            </fieldset>
        <?php echo $this->Form->end(); ?>
    </div>
</div>