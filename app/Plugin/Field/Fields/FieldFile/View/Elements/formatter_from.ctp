<!-- File Formatter Form -->
<?php
    echo $this->Form->input("Field.settings.display.{$view_mode}.type",
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