<?php
    $hidden = (
        isset($this->data['Field']['settings']['display'][$viewMode]['type']) &&
        $this->data['Field']['settings']['display'][$viewMode]['type'] == 'hidden'
    );
?>
<?php echo $this->Form->create('Field', array('url' => "/admin/node/types/field_formatter/{$this->data['Field']['id']}/{$viewMode}")); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __t('Field display format (view mode: %s)', Inflector::camelize($viewMode))); ?>
        <?php echo $this->Form->hidden('Field.id'); ?>

        <?php echo $this->Html->useTag('fieldsetstart', __t('Label')); ?>
            <?php
                echo $this->Form->input("Field.settings.display.{$viewMode}.label",
                    array(
                        'type' => 'select',
                        'label' => false,
                        'options' => array('hidden' => __t('Hidden'), 'above' => __t('Above'), 'inline' => __t('Inline')),
                        'empty' => false
                    )
                );
            ?>
        <?php echo $this->Html->useTag('fieldsetend'); ?>

        <?php echo $this->Html->useTag('fieldsetstart', __t('Format')); ?>
            <?php 
                echo $this->Form->input('display_hidden', 
                    array(
                        'type' => 'checkbox',
                        'label' => __t('Hidden'),
                        'onClick' => "$('#field-formatter-form').toggle();",
                        'value' => 1,
                        'checked' => $hidden
                    )
                );
            ?>
            <div id="field-formatter-form" style="<?php echo $hidden ? 'display:none;' : ''; ?>">
            <?php
                echo $this->element('formatter_from',
                    array(),
                    array(
                        'plugin' => Inflector::camelize($this->data['Field']['field_module'])
                    )
                );
            ?>
            </div>
        <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Form->input(__t('Save field'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>