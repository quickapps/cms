<?php
$tSettings = array(
	'columns' => array(
		__t('Text') => array(
			'value' => '{php} return String::truncate(htmlentities("{Translation.original}", ENT_QUOTES, "UTF-8"), 150); {/php}',
			'sort' => 'Translation.original'
		),
		__t('Actions') => array(
			'value' => "
				<a href='{url}/admin/locale/translations/edit/{Translation.id}{/url}'>" . __t('edit') . "</a> |
				<a href='{url}/admin/locale/translations/regenerate/{Translation.id}{/url}' title='" . __t('Regenerate translation cache') . "'>" . __t('regenerate') . "</a> |
				<a href='{url}/admin/locale/translations/delete/{Translation.id}{/url}' onclick='return confirm(\"" . __t('Delete this entry ?') . "\");'>" . __t('delete') . "</a>
			",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
	'noItemsMessage' => __t('There are no translations to display'),
	'paginate' => true,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);
?>

<?php echo $this->Form->create('Translation'); ?>
	<!-- Filter -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Search') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="display:none;">
			<?php echo $this->Form->input('Translation.filter.original', array('type' => 'text', 'label' => __t('Original text'))); ?>
			<?php echo $this->Form->submit(__t('Search')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<p>
	<?php echo $this->Html->link('<b>' . __t('Export all') . '</b>', '/admin/locale/translations/export/', array('escape' => false)); ?>
	&nbsp;
	<?php echo $this->Html->link('<b>' . __t('Import') . '</b>', '/admin/locale/translations/import/', array('escape' => false)); ?>
</p>

<?php echo $this->Html->table($results, $tSettings); ?>