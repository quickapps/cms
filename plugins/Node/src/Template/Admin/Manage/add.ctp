<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<?php echo $this->Form->create($node, ['id' => 'node-form']); ?>
	<fieldset>
		<legend><?php echo __d('node', 'Basic Information'); ?></legend>
			<?php echo $this->Form->input('title', ['label' => $node->node_type->title_label]); ?>

			<?php echo $this->Form->input('description'); ?>
			<em class="help-block"><?php echo __d('node', 'A short description (200 chars. max.) about this content. Will be used as page meta-description when rendering this content node.'); ?></em>
	</fieldset>

	<fieldset>
		<legend><?php echo __d('node', 'Publishing'); ?></legend>
		<?php echo $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('node', 'Published')]); ?>
		<?php echo $this->Form->input('promote', ['type' => 'checkbox', 'label' => __d('node', 'Promoted to front page')]); ?>
		<?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __d('node', 'Sticky at top of lists')]); ?>
	</fieldset>

	<?php if (isset($node->_fields) && $node->_fields->count()): ?>
	<fieldset>
		<legend><?php echo __d('node', 'Content'); ?></legend>
		<?php foreach ($node->_fields as $field): ?>
			<?php echo $this->Form->input($field); ?>
		<?php endforeach; ?>
	</fieldset>
	<?php endif; ?>

	<fieldset>
		<legend><?php echo __d('node', 'Settings'); ?></legend>
			<?php echo $this->Form->input('comment_status', ['label' => __d('node', 'Comments'), 'options' => [1 => __d('node', 'Open'), 0 => __d('node', 'Closed'), 2 => __d('node', 'Read Only')]]); ?>
			<?php echo $this->Form->input('language', ['label' => __d('node', 'Language'), 'options' => $languages, 'empty' => __d('node', '-- ANY --')]); ?>
			<?php echo $this->Form->input('roles._ids', ['type' => 'select', 'label' => __d('node', 'Show content for specific roles'), 'options' => $roles, 'multiple' => 'checkbox']); ?>
			<em class="help-block"><?php echo __d('node', 'Show this content only for the selected role(s). If you select no roles, the content will be visible to all users.'); ?></em>
	</fieldset>

	<?php echo $this->Form->submit(__d('node', 'Create')); ?>
<?php echo $this->Form->end(); ?>