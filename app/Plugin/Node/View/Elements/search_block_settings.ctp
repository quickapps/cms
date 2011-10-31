<?php
    echo $this->Form->input('Block.settings.url_prefix',
        array(
            'between' => $this->Html->url('/', true) . 's/',
            'after' => ' my-search-criteria',
            'type' => 'text',
            'label' => __d('node', 'URL prefix')
        )
    );
?>
<em><?php echo __d('node', 'Adds a prefix to each search URL query.'); ?></em>