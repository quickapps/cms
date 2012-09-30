<?php
$tSettings = array(
	'columns' => array(
		'<input type="checkbox" onclick="QuickApps.checkAll(this);">' => array(
			'value' => '<input type="checkbox" name="data[Items][id][]" value="{Node.id}">',
			'thOptions' => array('align' => 'center'),
			'tdOptions' => array('width' => '25', 'align' => 'center')
		),
		__t('Title') => array(
			'value' => '{Node.title}
				{php} return ({Node.sticky}) ? \'{img title="' . __t("Sticky at top") . '"}/node/img/sticky.png{/img}\' : ""; {/php}
				{php} return ({Node.promote}) ? \'{img title="' . __t("Promoted in front page") . '"}/node/img/promote.png{/img}\' : ""; {/php}
				{php} return (trim("{Node.cache}") != "") ? \'{img title="' . __t("Cache activated") . ': ' . '{Node.cache}"}/node/img/cache.png{/img}\' : ""; {/php}
				{php} return (trim("{Node.translation_of}") != "") ? \'{img title="' . __t("This node is a translation of other") . '"}/node/img/translation.png{/img}\' : ""; {/php}',
			'sort' => 'Node.title',
			'tdOptions' => array('width' => '40%', 'align' => 'left')
		),
		__t('Type') => array(
			'value' => '{php} return ("{NodeType.name}" != "") ? "{NodeType.name}" : "---"; {/php}',
			'sort' => 'NodeType.id'
		),
		__t('Author') => array(
			'value' => '{CreatedBy.name}',
			'sort' => 'CreatedBy.name'
		),
		__t('Status') => array(
			'value' => '{php} return ({Node.status} == 0 ? "' . __t('not published') . '" : "' . __t('published') . '"); {/php}',
			'sort' => 'Node.status'
		),
		__t('Updated') => array(
			'value' => '{php} return CakeTime::format("' . __t('Y/m/d - H:i') . '", {Node.modified}); {/php} {php} return ({Node.modified} != {Node.created} ? "<span style=\\"color:red;\\">' . __t('updated') . '</span>" : ""); {/php}',
			'sort' => 'Node.modified'
		),
		__t('Language') => array(
			'value' => '{php} return ("{Node.language}" == "" ? "' . __t('-- Any --') . '" : "{Node.language}"); {/php}',
			'sort' => 'Node.language'
		),
		__t('Actions') => array(
			'value' => "
				{php} return (!'{Node.translation_of}' && '{Node.language}') ? \"<a href='{url}/admin/node/contents/translate/{Node.slug}{/url}'>" . __t('translate') . "</a> |\" : ''; {/php}
				<a href='{url}/admin/node/contents/edit/{Node.slug}{/url}'>" . __t('edit') . "</a> |
				<a href='{url}/admin/node/contents/delete/{Node.slug}{/url}' onclick=\"return confirm('" . __t('Delete selected content ?') . "');\">" . __t('delete') . "</a>",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		)
	),
	'noItemsMessage' => __t('There are no nodes to display'),
	'paginate' => true,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);
?>

<?php echo $this->Form->create(null, array('class' => 'form-inline')); ?>
	<!-- Filter -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Filter Options') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Node']['filter']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Form->input('Node.filter.Node|title',
					array(
						'type' => 'text',
						'label' => __t('Title')
					)
				);
			?>

			<?php echo $this->Form->input('Node.filter.Node|status',
					array(
						'type' => 'select',
						'label' => __t('Status'),
						'empty' => true,
						'options' => array(
							1 => __t('published'),
							0 => __t('not published')
						)
					)
				);
			?>

			<?php echo $this->Form->input('Node.filter.Node|promote',
					array(
						'type' => 'select',
						'label' => __t('Front Page'),
						'empty' => true,
						'options' => array(
							1 => __t('promoted to front page'),
							0 => __t('not promoted to front page')
						)
					)
				);
			?>

			<?php echo $this->Form->input('Node.filter.Node|sticky',
					array(
						'type' => 'select',
						'label' => __t('Sticky'),
						'empty' => true,
						'options' => array(
							1 => __t('sticky at top'),
							0 => __t('not sticky at top')
						)
					)
				);
			?>

			<?php echo $this->Form->input('Node.filter.NodeType|id',
					array(
						'type' => 'select',
						'label' => __t('Type'),
						'empty' => true,
						'options' => $types
					)
				);
			?>

			<?php echo $this->Form->input('Node.filter.Node|language',
					array(
						'type' => 'select',
						'label' => __t('Language'),
						'empty' => true,
						'options' => $languages
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
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Node']['update']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Form->input('Node.update',
					array(
						'type' => 'select',
						'label' => false,
						'options' => array(
							'publish' => __t('Publish selected content'),
							'unpublish' => __t('Unpublish selected content'),
							'promote' => __t('Promote selected content to front page'),
							'demote' => __t('Demote selected content from front page'),
							'sticky' => __t('Make selected content sticky'),
							'unsticky' => __t('Make selected content not sticky'),
							'delete' => __t('Delete selected content'),
							'clear_cache' => __t('Clear cache')
						)
					)
				);
			?>
			<?php echo $this->Form->submit(__t('Update')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
	<!-- table results -->
	<?php echo $this->Html->table($results, $tSettings); ?>
	<!-- end: table results -->
<?php echo $this->Form->end(); ?>