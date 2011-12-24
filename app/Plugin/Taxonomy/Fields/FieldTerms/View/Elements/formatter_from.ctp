<!-- List Formatter Form -->
<?php
    echo $this->Form->input("Field.settings.display.{$viewMode}.type",
        array(
            'label' => false,
            'type' => 'select',
            'options' => array(
                'plain' => __d('field_terms', 'Plain'),
                'link-localized' => __d('field_terms',
                'Link (localized)'),
                'plain-localized' => __d('field_terms', 'Plain (localized)')
            ),
            'empty' => false
        )
    );