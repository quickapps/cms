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
        <?= $this->Form->create($term); ?>
            <fieldset>
                <legend><?= __d('taxonomy', "Editing Vocabulary's Term"); ?></legend>

                <?= $this->Form->label(__d('taxonomy', "Term's Name *")); ?>
                <div class="input-group">
                    <?= $this->Form->input('name', ['label' => false]); ?>
                    <span class="input-group-addon"><?= __d('taxonomy', 'Slug: {0}', $term->slug); ?></span>
                </div>
                <em class="help-block"><?= __d('taxonomy', 'The name for this term. e.g. "cat", "dog" or "bird" for the "animals" vocabulary.'); ?></em>

                <?= $this->Form->submit(__d('taxonomy', 'Save Changes')); ?>
            </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>