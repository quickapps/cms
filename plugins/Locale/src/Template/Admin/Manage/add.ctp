<?php echo $this->Form->create($language); ?>
    <fieldset>
        <legend><?php echo __d('locale', 'Adding New Language'); ?></legend>

        <?php echo $this->Form->input('code', ['type' => 'select', 'label' => __d('locale', 'Choose Language'), 'options' => $languages]); ?>

        <div class="input-group">
            <span class="input-group-addon"><?php echo __d('locale', 'Language Icon'); ?>: <span class="flag"></span></span>
            <?php echo $this->Form->input('icon', ['type' => 'select', 'options' => $icons, 'label' => false, 'onchange' => 'changeFlag();', 'id' => 'flag-icons']); ?>
        </div>

        <?php echo $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('locale', 'Active')]); ?>

        <p>&nbsp;</p>

        <?php echo $this->Form->submit(__d('locale', 'Save')); ?>
    </fieldset>
<?php echo $this->Form->end(); ?>

<script> var baseURL = '<?php echo $this->Url->build('/Locale/img/flags/', true); ?>'; </script>
<?php echo $this->Html->script('Locale.language.form.js'); ?>