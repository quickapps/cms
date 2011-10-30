<!-- List Formatter Form -->
<?php
    echo $this->Form->input("Field.settings.display.{$view_mode}.type",
        array(
            'label' => false,
            'type' => 'select',
            'options' => array(
                'default' => __d('field_list', 'Default'),
                'key' => __d('field_list', 'Key')
            ),
            'empty' => false
        )
    );