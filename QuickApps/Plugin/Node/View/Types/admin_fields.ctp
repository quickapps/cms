<?php
$tSettings = array(
	'columns' => array(
		__t('Label') => array(
			'value' => '{label}',
			'tdOptions' => array('width' => '15%')
		),
		__t('Name') => array(
			'value' => '{name}',
			'tdOptions' => array('width' => '15%')
		),
		__t('Type') => array(
			'value' => '{field_module}',
			'tdOptions' => array('width' => '15%')
		),
		__t('Required') => array(
			'value' => '{php} return ("{required}" == "1") ? "' . __t('Yes') . '" : "' . __t('No') . '";  {/php}'
		),
		__t('Actions') => array(
			'value' => "
				{php} return ('{locked}' != '1') ? '<a href=\"{url}/admin/node/types/field_settings/{id}{/url}\">" . __t('configure') . "</a> |' : ''; {/php}
				<a href='{url}/admin/field/handler/move/{id}/up{/url}'>" . __t('move up') . "</a> |
				<a href='{url}/admin/field/handler/move/{id}/down{/url}'>" . __t('move down') . "</a> |
				<a href='{url}/admin/field/handler/delete/{id}{/url}' onclick=\"return confirm('" . __t('Delete selected field and all related data, this can not be undone ?') . "');\">" . __t('delete') . "</a>",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
	'noItemsMessage' => __t('There are no fields to display'),
	'paginate' => false,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);
?>

<?php echo $this->Form->create(null, array('class' => 'form-inline')); ?>
	<!-- Add -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Add field') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Field']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Form->input('Field.label',
					array(
						'required' => 'required',
						'type' => 'text',
						'size' => 15,
						'style' => 'width:140px;',
						'label' => __t('Label *')
					)
				);
			?>

			<?php echo $this->Form->input('Field.name',
					array(
						'required' => 'required',
						'type' => 'text',
						'label' => __t('Name *'),
						'between' => 'field_',
						'size' => 15,
						'style' => 'width:140px;',
						'after' => ' <em>(a-z, 0-9, _)</em>'
					)
				);
			?>

			<?php
				$fieldsOptions = array();

				foreach($field_modules as $plugin => $data) {
					$fieldsOptions['list'][$plugin] = $data['name'];
					$fieldsOptions['description'][$plugin] = $data['description'];
				}

				echo $this->Form->input('Field.field_module',
					array(
						'type' => 'select',
						'label' => __t('Type'),
						'empty' => true,
						'options' => $fieldsOptions['list'],
						'onChange' => 'showDescription(this.value);'
					)
				);
			?>

			<p>
				<em id="field_description"></em>
			</p>

			<?php echo $this->Form->submit(__t('Add')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>


<?php echo $this->Html->table($result['Field'], $tSettings); ?>

<script type="text/javascript">
	var field_descriptions = new Array();

	<?php foreach($fieldsOptions['description'] as $plugin => $desc): ?>
		field_descriptions['<?php echo $plugin; ?>'] = '<?php echo QuickApps::is('module.core', $plugin) ? __t($desc) : __d($plugin, $desc); ?>';
	<?php endforeach; ?>

	function showDescription(field) {
		desc = field ? field_descriptions[field] : '';
		$('em#field_description').html(desc);
	}
</script>