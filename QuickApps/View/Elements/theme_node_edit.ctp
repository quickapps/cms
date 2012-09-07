<?php
/**
 * Edit form for NodeType.
 * This element is rendered by NodeHookHelper::node_form().
 *
 * @package	 QuickApps.View.Elements
 * @author	 Christopher Castro <chris@quickapps.es>
 */
?>

<?php echo $this->Html->useTag('fieldsetstart', __t('Content')); ?>
	<?php foreach ($data['Field'] as $field): ?>
		<?php echo $this->Node->renderField($field, true); ?>
	<?php endforeach; ?>
<?php echo $this->Html->useTag('fieldsetend'); ?>