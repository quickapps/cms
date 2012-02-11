<!-- List Formatter Form -->
<?php
    $viewMode = $this->data['Field']['viewMode'];

    echo $this->Form->input("Field.settings.display.{$viewMode}.type",
        array(
            'label' => false,
            'type' => 'select',
            'options' => array(
                'default' => __t('Default'),
                'key' => __t('Key')
            ),
            'empty' => false
        )
    );