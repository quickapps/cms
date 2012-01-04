<?php
    echo $this->Form->input("Field.settings.vocabulary",
        array(
            'type' => 'select',
            'options' => ClassRegistry::init('Taxonomy.Vocabulary')->find('list'),
            'empty' => false,
            'label' => __d('field_terms', 'Vocabulary *')
        )
    );
?>
<em><?php echo __d('field_terms', 'The vocabulary which supplies the options for this field.'); ?></em>

<?php
    echo $this->Form->input("Field.settings.type",
        array(
            'type' => 'select',
            'options' => array(
                'checkbox' => __d('field_terms', 'Check boxes/radio buttons'),
                'select' => __d('field_terms', 'Select list'),
                'autocomplete' => __d('field_terms', 'Autocomplete term (tagging)')
            ),
            'empty' => false,
            'label' => __d('field_terms', 'Element Type')
        )
    );
?>
<em><?php echo __d('field_terms', 'The type of form element you would like to present to the user when creating this field.'); ?></em>

<?php
    echo $this->Form->input("Field.settings.max_values",
        array(
            'type' => 'select',
            'options' => array_merge(array(0 => __d('field_terms', 'Unlimited')), Set::combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), '{n}', '{n}')),
            'empty' => false,
            'label' => __d('field_terms', 'Number of values')
        )
    );
?>
<em><?php echo __d('field_terms', 'Maximum number of values users can enter for this field.'); ?></em>
