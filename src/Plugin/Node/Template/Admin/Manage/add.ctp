<?php echo $this->Form->create($node, ['type' => 'file']); ?>
	<fieldset>
		<legend><?php echo __('Basic Information'); ?></legend>
			<?php echo $this->Form->input('title'); ?>

			<?php echo $this->Form->input('description'); ?>
			<em class="help-block"><?php echo __('A short description (200 chars. max.) about this content. Will be used as page meta-description when rendering this content node.'); ?></em>
	</fieldset>

	<fieldset>
		<legend><?php echo __('Publishing'); ?></legend>
		<?php echo $this->Form->input('status', ['type' => 'checkbox', 'label' => __('Published')]); ?>
		<?php echo $this->Form->input('promote', ['type' => 'checkbox', 'label' => __('Promoted to front page')]); ?>
		<?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky at top of lists')]); ?>
	</fieldset>

	<fieldset>
		<legend><?php echo __('Content'); ?></legend>
		<?php foreach ($node->_fields as $field): ?>
			<?php echo $this->Form->input($field); ?>
		<?php endforeach; ?>
	</fieldset>

	<fieldset>
		<legend><?php echo __('Settings'); ?></legend>
			<?php echo $this->Form->input('comment', ['label' => __('Comments'), 'options' => [1 => __('Open'), 0 => __('Closed'), 2 => __('Read Only')]]); ?>
			<?php echo $this->Form->input('language', ['label' => __('Language'), 'options' => $languages, 'empty' => __('-- ANY --')]); ?>
	</fieldset>

	<?php echo $this->Form->submit(__('Create')); ?>
<?php echo $this->Form->end(); ?>