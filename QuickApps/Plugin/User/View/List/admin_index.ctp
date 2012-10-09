<?php
$tSettings = array(
	'columns' => array(
		'<input type="checkbox" onclick="QuickApps.checkAll(this);">' => array(
			'value' => '<input type="checkbox" name="data[Items][id][]" value="{User.id}">',
			'thOptions' => array('align' => 'center'),
			'tdOptions' => array('width' => '25', 'align' => 'center')
		),
		__t('User Name') => array(
			'value' => '{User.username}',
			'sort' => 'User.name'
		),
		__t('Email') => array(
			'value' => '{User.email}',
			'tdOptions' => array('width' => '30%'),
			'sort' => 'User.email'
		),
		__t('Roles') => array(
			'value' => "{php} return implode(', ', Hash::extract(\$row_data, 'Role.{n}.name')); {/php}",
			'sort' => false
		),
		__t('Actions') => array(
			'value' => "<a href='{url}/admin/user/list/edit/{User.id}{/url}'>" . __t('edit') . "</a>",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
	'noItemsMessage' => __t('There are no users to display'),
	'paginate' => true,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);
?>

<?php echo $this->Form->create(null, array('class' => 'form-inline')); ?>
	<!-- Filter -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Filter Options') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['User']['filter']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Form->input('User.filter.User|status',
					array(
						'type' => 'select',
						'label' => __t('Status'),
						'options' => array(
							'' => '',
							1 => __t('active'),
							0 => __t('blocked')
						)
					)
				);
			?>
			<?php echo $this->Form->input('User.filter.User|name LIKE',
					array(
						'type' => 'text',
						'label' => __t('Name')
					)
				);
			?>

			<?php echo $this->Form->input('User.filter.User|email LIKE',
					array(
						'type' => 'text',
						'label' => __t('Email')
					)
				);
			?>
			<?php echo $this->Form->submit(__t('Filter')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Form->create('User', array('class' => 'form-inline', 'onsubmit' => 'return confirm("' . __t('Are you sure about this changes ?') . '");')); ?>
	<!-- Update -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Update Options') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['User']['update']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Form->input('User.update',
					array(
						'type' => 'select',
						'label' => false,
						'options' => array(
							'block' => __t('Block selected users'),
							'unblock' => __t('Unblock selected users'),
							'delete' => __t('Delete selected users')
						)
					)
				);
			?>
			<?php echo $this->Form->submit(__t('Update')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->table($results, $tSettings);?>
<?php echo $this->Form->end(); ?>