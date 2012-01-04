<!-- List Formatter Form -->
<?php
    echo $this->Form->input("Field.settings.display.{$viewMode}.type",
        array(
            'label' => false,
            'type' => 'select',
            'options' => array(
                'plain' => __d('field_terms', 'Plain'),
                'link-localized' => __d('field_terms', 'Link (localized)'),
                'plain-localized' => __d('field_terms', 'Plain (localized)')
            ),
            'empty' => false
        )
    );

    echo $this->Form->input("Field.settings.display.{$viewMode}.url_prefix",
        array(
            'label' => __d('field_terms', 'URL prefix'),
            'type' => 'text'
        )
    );
?>
<em><?php echo __d('field_terms', 'Valid only when format is "Link (localized)". Adds a prefix to each term link url.'); ?></em>