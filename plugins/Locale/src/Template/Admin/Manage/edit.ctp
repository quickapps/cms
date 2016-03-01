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
        <?= $this->Form->create($language); ?>
            <fieldset>
                <legend><?= __d('locale', 'Editing Language'); ?></legend>

                <?= $this->Form->input('name', ['label' => __d('locale', 'Language Name')]); ?>
                <?=
                    $this->Form->input('direction', [
                        'type' => 'select',
                        'options' => [
                            'ltr' => __d('locale', 'Left to Right'),
                            'rtl' => __d('locale', 'Right to Left'),
                        ],
                        'label' => __d('locale', 'Writing Direction'),
                    ]);
                ?>

                <div class="input-group">
                    <span class="input-group-addon"><?= __d('locale', 'Language Icon'); ?>: <span class="flag"></span></span>
                    <?=
                        $this->Form->input('icon', [
                            'id' => 'flag-icons',
                            'label' => false,
                            'type' => 'select',
                            'options' => $icons,
                            'onchange' => "changeFlag('#flag-icons', '" . $this->Url->build('/Locale/img/flags/', true) . "');",
                        ]);
                    ?>
                </div>

                <p>&nbsp;</p>

                <?= $this->Form->submit(__d('locale', 'Save')); ?>
            </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>

<?= $this->Html->script('Locale.language.form.js'); ?>

<script type="text/javascript">
    $(document).ready(function () {
        changeFlag('#flag-icons', '<?= $this->Url->build('/Locale/img/flags/', true); ?>')
    });
</script>