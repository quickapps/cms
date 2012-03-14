<!-- File Formatter Form -->
<?php
    $viewMode = $this->data['Field']['viewMode'];

    echo $this->Form->input("Field.settings.display.{$viewMode}.type",
        array(
            'label' => false,
            'type' => 'select',
            'options' => array(
                'link' => __t('Link to file'),
                'table' => __t('Table of Files'),
                'url' => __t('File URL')
            ),
            'empty' => false
        )
    );