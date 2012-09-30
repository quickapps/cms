<?php
	$t = Hash::extract($translations, '{n}.Node.language');

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
		<?php
			$li = array();

			foreach ($translations as $t) {
				$li[] =
					$this->Html->link($t['Node']['title'], "/admin/node/contents/edit/{$t['Node']['slug']}") . " [" . $t['Node']['language'] . "] | " .
					$this->Html->link(__t('delete'), "/admin/node/contents/delete/{$t['Node']['slug']}", array('onClick' => "return confirm('" . __t('Delete selected content ?') . "');"));
			}

			echo $this->Html->nestedList($li, array('id' => 'node-available-translations-list'));
		?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->submit(__t('Translate')); ?>
<?php echo $this->Form->end(); ?>
