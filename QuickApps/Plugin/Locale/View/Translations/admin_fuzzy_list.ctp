<?php
$tSettings = array(
	'columns' => array(
		'<input type="checkbox" onclick="QuickApps.checkAll(this);">' => array(
			'value' => '<input type="checkbox" name="data[Items][id][]" value="{Fuzzy.id}">',
			'thOptions' => array('align' => 'center'),
			'tdOptions' => array('width' => '25', 'align' => 'center')
		),
		__t('Text') => array(
			'value' => '
				<p style="text-decoration:{php} return ({Fuzzy.hidden}) ? "line-through" : "none"; {/php};">{php} return htmlentities("{Fuzzy.original}", ENT_QUOTES, "UTF-8"); {/php}</p>
				<em style="font-size:10px;">{php} return str_replace(ROOT, "", "{Fuzzy.file}"); {/php}:{Fuzzy.line}</em>
			'
		),
		__t('Actions') => array(
			'value' => "
				<a href='{url}/admin/locale/translations/add/fuzzy:{Fuzzy.id}{/url}'>" . __t('translate') . "</a> | 
				<a href='{url}/admin/locale/translations/fuzzy_list/{/url}{php} return (intval('{Fuzzy.hidden}')) ? 'unhide' : 'hide'; {/php}:{Fuzzy.id}'>{php} return (intval('{Fuzzy.hidden}')) ? '" . __t('unhide') ."' : '" . __t('hide') . "'; {/php}</a> | 
				<a href='{url}/admin/locale/translations/fuzzy_delete/{Fuzzy.id}{/url}' onclick='return confirm(\"" . __t('Delete this entry ?') . "\");'>" . __t('delete') . "</a>
			",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
	'noItemsMessage' => __t('There are no fuzzy entries to display'),
	'paginate' => true,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);
?>

<?php echo $this->Form->create('Fuzzy', array('class' => 'form-inline')); ?>
	<!-- Filter -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Filter Options') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Fuzzy']['filter']) ? '' : 'display:none;'; ?>">
			<?php
				echo $this->Form->input('Fuzzy.filter.original',
					array(
						'type' => 'text',
						'label' => __t('Original text')
					)
				);
			?>

			<?php
				echo $this->Form->input('Fuzzy.filter.file',
					array(
						'type' => 'text',
						'label' => __t('In file')
					)
				);
			?>

			<?php
				echo $this->Form->input('Fuzzy.filter.line',
					array(
						'type' => 'text',
						'label' => __t('Line number')
					)
				);
			?>

			<?php
				echo $this->Form->input('Fuzzy.filter.hidden',
					array(
						'type' => 'select',
						'label' => __t('Hidden'),
						'empty' => true,
						'options' => array(
							1 => __t('Yes'),
							0 => __t('No')
						),
						'empty' => __t('Both')
					)
				);
			?>
			<?php echo $this->Form->submit(__t('Filter')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Form->create(null, array('class' => 'form-inline', 'onsubmit' => 'return confirm("' . __t('Are you sure about this changes ?') . '");')); ?>
	<!-- Update -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Update Options') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Fuzzy']['update']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Form->input('Fuzzy.update',
					array(
						'type' => 'select',
						'label' => false,
						'options' => array(
							'hide' => __t('Hide'),
							'unhide' => __t('Unhide'),
							'delete' => __t('Delete'),
							'export' => __t('Export')
						)
					)
				);
			?>
			<?php echo $this->Form->submit(__t('Update')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<p>
		<?php echo $this->Html->link('<b>' . __t('Export all') . '</b>', '/admin/locale/translations/export/fuzzy', array('escape' => false)); ?>
		|
		<?php echo $this->Html->link('<b>' . __t('Clear all') . '</b>', '/admin/locale/translations/fuzzy_list/clear:all', array('escape' => false)); ?>
	</p>
	<?php echo $this->Html->table($results, $tSettings); ?>
<?php echo $this->Form->end(); ?>