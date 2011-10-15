<?php 
    $options = ClassRegistry::init('Taxonomy.Vocabulary')->find('list', array('recursive' => -1));

    echo $this->Form->input('Block.settings.vocabularies',
        array(
            'type' => 'select',
            'multiple' => 'checkbox',
            'options' => $options,
            'label' => __d('taxonomy', 'Vocabularies')
        )
    );
?>

<p>&nbsp;</p>

<?php
    echo $this->Form->input('Block.settings.content_counter',
        array(
            'type' => 'checkbox',
            'options' => array(0 => __d('taxonomy', 'No'), 1 => __d('taxonomy', 'Yes')),
            'label' => __d('taxonomy', 'Show content count')
        )
    );

    echo $this->Form->input('Block.settings.show_vocabulary',
        array(
            'type' => 'checkbox',
            'options' => array(0 => __d('taxonomy', 'No'), 1 => __d('taxonomy', 'Yes')),
            'label' => __d('taxonomy', 'Show vocabulary and its terms as tree')
        )
    );