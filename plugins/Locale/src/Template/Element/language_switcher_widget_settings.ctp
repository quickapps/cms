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

<fieldset>
    <legend><?php echo __d('locale', 'Display Mode'); ?></legend>

    <div class="form-group">
        <?php
            echo $this->Form->input('languages', [
                'label' => 'Show languages',
                'type' => 'select',
                'multiple' => 'checkbox',
                'options' => $languagesList,
                'value' => !empty($block->settings['languages']) ? $block->settings['languages'] : [],
            ]);
        ?>
        <em class="help-block">
            <?echo __d('locale', 'Select which languages should be presented to users in the selector interface. <b>If none is selected</b>, all enabled languages will be used.'); ?>
        </em>
    </div>

    <?php
        echo $this->Form->radio('type', [
            'html' => __d('locale', 'HTML list'),
            'selectbox' => __d('locale', 'Selectbox'),
        ]);

        echo $this->Form->input('flags', [
            'type' => 'checkbox',
            'label' => __d('locale', 'Show flags when possible.'),
        ]);
    ?>
</fieldset>
