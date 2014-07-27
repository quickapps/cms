<?php echo $this->Form->create($type); ?>
	<fieldset>
		<legend><?php echo __d('node', 'Content Type Information'); ?></legend>
		<?php echo $this->Form->input('name', ['label' => __d('node', 'Name')]); ?>
		<em class="help-block"><?php echo __d('node', 'This text will be displayed as part of the list on the "Add New Content" page.'); ?></em>

		<?php echo $this->Form->input('slug', ['label' => __d('node', 'Machine name')]); ?>
		<em class="help-block"><?php echo __d('node', 'A unique name for this content type. It must only contain lowercase letters, numbers, and minus symbol (a-z, 0-9, -).'); ?></em>

		<?php echo $this->Form->input('title_label', ['label' => __d('node', 'Title field label')]); ?>
		<em class="help-block"><?php echo __d('node', 'Label name for the "Title" field. e.g. "Product name", "Author name", etc.'); ?></em>

		<?php echo $this->Form->input('description', ['label' => __d('node', 'Description'), 'type' => 'textarea']); ?>
		<em class="help-block"><?php echo __d('node', 'Describe this content type. The text will be displayed on the Add new content page.'); ?></em>
	</fieldset>

	<hr />

	<fieldset>
		<legend><?php echo __d('node', 'Author & Publish Date'); ?></legend>

	</fieldset>

	<fieldset>
		<legend><?php echo __d('node', 'Comments'); ?></legend>

	</fieldset>

	<fieldset>
		<legend><?php echo __d('node', 'Language'); ?></legend>

	</fieldset>

	<fieldset>
		<legend><?php echo __d('node', 'Publishing'); ?></legend>

	</fieldset>
<?php echo $this->Form->end(); ?>