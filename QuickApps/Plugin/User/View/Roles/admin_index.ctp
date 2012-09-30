<?php echo $this->Form->create('Role',array('class' => 'form-inline')); ?>
	<!-- Filter -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Add New Role') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Role']['name']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Form->input('name',
					array(
						'type' => 'text',
						'label' => __t('Name')
					)
				);
			?>
			<?php echo $this->Form->submit(__t('Add')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php
$tSettings = array(
	'columns' => array(
		__t('Name') => array(
			'value' => '{Role.name}',
			'sort' => 'Role.name'
		),
		__t('Actions') => array(
			'value' => "
				<a href='{url}/admin/user/roles/edit/{Role.id}{/url}'>" . __t('edit') . "</a>
				{php} return !in_array({Role.id}, array(1, 2, 3)) ? \" | <a href='{url}/admin/user/roles/delete/{Role.id}{/url}' onClick='return confirm(\\\"" . __t('Are you sure?') . "\\\");'>" . __t('delete') . "</a>\" : \"\"; {/php}",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
	'noItemsMessage' => __t('There are no roles to display'),
	'paginate' => false,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);

echo $this->Html->table($results, $tSettings);
?>