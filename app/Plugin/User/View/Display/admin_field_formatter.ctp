<?php echo $this->Form->create('User'); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __t('Field display format (view mode: %s)', Inflector::camelize($viewMode))); ?>
        <?php echo $this->Form->hidden('Field.id'); ?>
        <?php
            echo $this->Form->input("Field.settings.display.{$viewMode}.label",
                array(
                    'type' => 'select',
                    'options' => array('hidden' => __t('Hidden'), 'above' => __t('Above'), 'inline' => __t('Inline')),
                    'empty' => false
                )
            );
        ?>
        <?php echo $this->element(Inflector::camelize($this->data['Field']['field_module']) . '.formatter_from'); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Form->input(__t('Save field'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>