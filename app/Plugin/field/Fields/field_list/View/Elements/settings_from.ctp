<?php
    echo $this->Form->input("Field.settings.type",
        array(
            'type' => 'select',
            'options' => array('radio' => __d('field_list', 'Radio buttons'), 'checkbox' => __d('field_list', 'Checkboxes')),
            'empty' => false,
            'label' => __d('field_list', 'List Type')
        )
    );

    echo $this->Form->input("Field.settings.options",
        array(
            'type' => 'textarea',
            'label' => __d('field_list', 'Options')
        )
    );
?>

<ul>
    <li><em><?php echo __d('field_list', 'The possible values this field can contain. Enter one value per line, in the format <b>key|label</b>.'); ?></em></li>
    <li><em><?php echo __d('field_list', 'The key is the stored value. The label will be used in displayed values and edit forms.'); ?></em></li>
    <li><em><?php echo __d('field_list', 'The label is optional: if a line contains a single string, it will be used as key and label.'); ?></em></li>
</ul>