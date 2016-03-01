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
        <?= $this->Form->create($vocabulary); ?>
            <fieldset>
                <legend><?= __d('taxonomy', 'Creating Vocabulary'); ?></legend>

                <?= $this->Form->input('name', ['label' => __d('taxonomy', 'Name *')]); ?>
                <em class="help-block"><?= __d('taxonomy', 'Name for this vocabulary. e.g. "Music".'); ?></em>

                <?= $this->Form->input('description', ['label' => __d('taxonomy', 'Description')]); ?>
                <?= $this->Form->submit(__d('taxonomy', 'Save & add terms &raquo;'), ['escape' => false]); ?>
            </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>