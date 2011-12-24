<!-- File Formatter Form -->
<?php
    echo $this->Form->input("Field.settings.display.{$viewMode}.type",
        array(
            'label' => false,
            'type' => 'select',
            'options' => array(
                'link' => __d('field_file', 'Link to file'),
                'table' => __d('field_file', 'Table of Files'),
                'url' => __d('field_file', 'File URL')
            ),
            'empty' => false
        )
    );