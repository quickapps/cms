<?php echo $this->Form->create('Node', array('url' => "/admin/node/types/field_formatter/{$this->data['Field']['id']}/{$view_mode}")); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __t('Field display format (view mode: %s)', Inflector::camelize($view_mode))); ?>
        <?php echo $this->Form->hidden('Field.id'); ?>
        <?php 
            echo $this->Form->input("Field.settings.display.{$view_mode}.label",
                array(
                    'type' => 'select',
                    'label' => __t('Label'),
                    'options' => array('hidden' => __t('Hidden'), 'above' => __t('Above'), 'inline' => __t('Inline')),
                    'empty' => false
                )
            );
        ?>
        <?php echo $this->element('formatter_from', array(), array('plugin' => Inflector::camelize($this->data['Field']['field_module']))); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Form->input(__t('Save field'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>