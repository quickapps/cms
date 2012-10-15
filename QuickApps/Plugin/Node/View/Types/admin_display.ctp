<?php
$tSettings = array(
	'columns' => array(
		__t('Name') => array(
			'value' => '{Field.label}',
			'tdOptions' => array('width' => '15%')
		),
		__t('Label') => array(
			'value' => '{Field.settings.display.' . $display . '.label}'
		),
		__t('Format') => array(
			'value' => '{Field.settings.display.' . $display . '.type}'
		),
		__t('Actions') => array(
			'value' => "
				<a href='{url}/admin/node/types/field_formatter/{Field.id}/display:" . $display . "{/url}'>" . __t('edit format') . "</a> |
				<a href='{url}/admin/field/handler/move/{Field.id}/up/" . $display . "{/url}'>" . __t('move up') . "</a> |
				<a href='{url}/admin/field/handler/move/{Field.id}/down/" . $display . "{/url}'>" . __t('move down') . "</a>
			",
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

<p><?php echo __t('Content items can be displayed using different display-modes: Full, List, RSS, Print, etc. List is a short format that is typically used in lists of multiple content items. Full content is typically used when the content is displayed on its own page.'); ?></p>
<p><?php echo __t("Here, you can define which fields are shown and hidden when <em>%s</em> content is displayed in each display-mode, and define how the fields are displayed in each display-mode.", $this->data['NodeType']['name']); ?></p>

<?php echo $this->Html->table(@Hash::sort((array)$result, "{n}.Field.settings.display.{$display}.ordering", 'asc'), $tSettings); ?>

<?php if ($display === 'default' && count($result)): ?>
	<p>
		<?php echo $this->Form->create('NodeType', array('class' => 'form-inline', 'url' => "/admin/node/types/display/{$typeId}")); ?>
			<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Display Modes') . '</span>'); ?>
				<div class="fieldset-toggle-container" style="display:none;">
					<?php echo $this->Form->helpBlock(__t('Use custom display settings for the following display-modes')); ?>
					<?php
						$options = array();

						foreach (QuickApps::displayModes('Node') as $mn => $info) {
							if (!isset($info['locked']) || !$info['locked']) {
								$options[$mn] = $info['label'];
							}
						}

						echo $this->Form->input('NodeType.displayModes',
							array(
								'type' => 'select',
								'multiple' => 'checkbox',
								'options' => $options,
								'label' => false
							)
						);
					?>
				</div>
			<?php echo $this->Html->useTag('fieldsetend'); ?>
			<?php echo $this->Form->submit(__t('Save')); ?>
		<?php echo $this->Form->end(); ?>
	</p>
<?php endif; ?>