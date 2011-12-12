<?php echo $this->Form->create('Module'); ?>
    <?php echo $this->Form->input('name', array('type' => 'hidden')); ?>

    <?php echo $this->element('settings', array(), array('plugin' => $this->data['Module']['name'])); ?>

    <?php echo $this->Form->submit(__t('Save all')); ?>
<?php echo $this->Form->end(); ?>