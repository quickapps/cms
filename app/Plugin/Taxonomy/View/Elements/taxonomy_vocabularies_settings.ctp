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

    echo $this->Form->input('Block.settings.terms_cache_duration',
        array(
            'type' => 'select',
            'label' => __d('taxonomy', 'Cache terms counters for'),
            'options' => array(
                '+10 minutes' => __d('node', '%s Minutes', 10),
                '+20 minutes' => __d('node', '%s Minutes', 20),
                '+40 minutes' => __d('node', '%s Minutes', 40),
                '+1 hour' => __d('node', '%s Hour', 1),
                '+2 hours' => __d('node', '%s Hours', 2),
                '+4 hours' => __d('node', '%s Hours', 3),
                '+7 hours' => __d('node', '%s Hours', 7),
                '+11 hours' => __d('node', '%s Hours', 11),
                '+16 hours' => __d('node', '%s Hours', 16),
                '+22 hours' => __d('node', '%s Hours', 22),
                '+1 day' => __d('node', '%s Days', 1),
                '+3 day' => __d('node', '%s Days', 3),
                '+5 day' => __d('node', '%s Days', 5),
                '+1 week' => __d('node', '%s Weeks', 1)
            )
        )
    );

    echo $this->Form->input('Block.settings.url_prefix',
        array(
            'between' => $this->Html->url('/', true) . 's/',
            'after' => ' term:my-term-slug',
            'type' => 'text',
            'label' => __d('taxonomy', 'URL prefix')
        )
    );