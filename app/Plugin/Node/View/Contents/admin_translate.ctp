<?php
    $t = Set::extract('/Node/language', $translations);

    foreach ($languages as $code => $name) {
        if ($code == $this->data['Node']['language'] || in_array($code, $t)) {
            unset($languages[$code]);
        }
    }
?>
<?php echo $this->Form->create('Node', array('url' => "/admin/node/contents/translate/{$this->data['Node']['slug']}")); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __t('Translating Content')); ?>
        <?php echo $this->Form->input('Node.title', array('required' => 'required', 'label' => __t($this->data['NodeType']['title_label']) . ' *')); ?>
        <?php echo $this->Form->input('Node.language', array('empty' => false, 'type' => 'select', 'label' => __t('Translate to'), 'options' => $languages)); ?>
        <?php echo $this->Html->useTag('fieldsetstart', __t('Available Translations')); ?>
        <ul>
        <?php foreach ($translations as $t): ?>
            <li>
                <?php echo $this->Html->link($t['Node']['title'], "/admin/node/contents/edit/{$t['Node']['slug']}"); ?> [<?php echo $t['Node']['language']; ?>] |
                <?php echo $this->Html->link(__t('delete'), "/admin/node/contents/delete/{$t['Node']['slug']}", array('onClick' => "return confirm('" . __t('Delete selected content ?') . "');")); ?>
            </li>
        <?php endforeach; ?>
        </ul>
        <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Form->input(__t('Translate'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>
