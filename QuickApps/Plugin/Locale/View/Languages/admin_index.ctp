<?php
$tSettings = array(
	'columns' => array(
		'<input type="checkbox" onclick="QuickApps.checkAll(this);">' => array(
			'value' => '<input type="checkbox" name="data[Items][id][]" value="{Language.id}">',
			'thOptions' => array('align' => 'center'),
			'tdOptions' => array('width' => '25', 'align' => 'center')
		),
		__t('English name') => array(
			'value' => '
				{Language.name}
				{php}
					$icon = strpos("{Language.icon}", "://") !== false ? "{Language.icon}" : "/locale/img/flags/{Language.icon}";
					return ("{Language.icon}" != "" ? $this->_View->Html->image($icon, array("width" => 16, "class" => "flag-icon")) : "");
				{/php}
				{php} return ("{Language.code}" == "' . Configure::read('Variable.default_language') . '" ? $this->_View->Html->image("/locale/img/default.png", array("title" => "' . __t('Default language') . '")) : ""); {/php}
			'
		),
		__t('Native name') => array(
			'value' => '{Language.native}',
			'sort' => 'Language.native'
		),
		__t('Code') => array(
			'value' => '{Language.code}'
		),
		__t('Direction') => array(
			'value' => '{php} return ("{Language.direction}" == "ltr" ? "' . __t('Left to right') . '" : "' . __t('Right to left') . '");{/php}'
		),
		__t('Status') => array(
			'value' => '{php} return {Language.status} == 1 ? "' . __t('active') . '" : "' . __t('disabled') . '"; {/php}'
		),
		__t('Actions') => array(
			'value' => '
				<a href="{url}/admin/locale/languages/move/{Language.id}/up{/url}">' . __t('move up') . '</a> |
				<a href="{url}/admin/locale/languages/move/{Language.id}/down{/url}">' . __t('move down') . '</a> |
				{php} return "{Language.code}" != "' . Configure::read('Variable.default_language') . '" ? \'<a href="{url}/admin/locale/languages/set_default/{Language.id}{/url}">' . __t('set as default') . '</a> |\' : \'\'; {/php}
				<a href="{url}/admin/locale/languages/edit/{Language.id}{/url}">' . __t('edit') . '</a> |
				<a href="{url}/admin/locale/languages/delete/{Language.id}{/url}" onclick=\'return confirm("' . __t('Delete this language ?') . '");\'>' . __t('delete') . '</a>
			',
			'thOptions' => array('align' => 'center'),
			'tdOptions' => array('width' => '300', 'align' => 'right')
		)
	),
	'noItemsMessage' => __t('There are no languages to display. Critical error'),
	'paginate' => false,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')
);
?>
<!-- Add form -->
	<?php echo $this->Form->create('Language', array('class' => 'form-inline', 'url' => '/admin/locale/languages/add')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Add New Language') . '</span>'); ?>
			<div class="fieldset-toggle-container" style="display:none;">
				<div id="predefinedList">
					<?php echo $this->Form->input('code', array('type' => 'select', 'options' => $languages, 'label' => __t('Language name'))); ?>
					<p>
						<?php echo $this->Form->submit(__t('Add')); ?>
					</p>
				</div>
			<?php echo $this->Form->end(); ?>

			<?php echo $this->Form->create('Language', array('class' => 'form-vertical', 'url' => '/admin/locale/languages/add')); ?>
				<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle" onclick="$(\'#predefinedList\').toggle(\'fast\', \'linear\');">' . __t('Custom Language') . '</span>'); ?>
					<div class="fieldset-toggle-container" style="display:none;">
						<?php echo $this->Form->input('status', array('type' => 'hidden', 'value' => 1)); ?>
						<?php echo $this->Form->input('custom_code', array('required' => 'required', 'maxlength' => 3, 'style' => 'width:50px;', 'type' => 'text', 'label' => __t('Language code *'), 'helpBlock' => __t('<a href="%s" target="_blank">ISO 639-3</a> compliant language identifier.', 'http://www.sil.org/iso639-3/codes.asp'))); ?>
						<?php echo $this->Form->input('name', array('required' => 'required', 'type' => 'text', 'label' => __t('Language name in English *'))); ?>
						<?php echo $this->Form->input('native', array('required' => 'required', 'type' => 'text', 'label' => __t('Native language name *'), 'helpBlock' => __t('Name of the language in the language being added.'))); ?>
						<?php
							echo $this->Form->input('direction',
								array(
									'required' => 'required',
									'type' => 'radio',
									'separator' => '<br/>',
									'options' => array(
										'ltr' => __t('Left to Right'),
										'rtl' => __t('Right to Left')
									),
									'label' => true,
									'legend' => __t('Direction *'),
									'after' => __t('Direction that text in this language is presented.')
								)
							);
						?>
						<p>
							<?php echo $this->Form->input(__t('Add'), array('name' => 'data[Language][addCustom]', 'type' => 'submit', 'label' => false)); ?>
						</p>
					</div>
				<?php echo $this->Html->useTag('fieldsetend'); ?>
			</div>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(); ?>
	

<?php echo $this->Form->create('Language', array('class' => 'form-inline', 'onsubmit' => 'return confirm("' . __t('Are you sure ?') . '");')); ?>
	<!-- Update -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Update Options') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="<?php echo isset($this->data['Comment']['update']) ? '' : 'display:none;'; ?>">
			<?php echo $this->Form->input('Language.update',
					array(
						'type' => 'select',
						'label' => false,
						'options' => array(
							'enable' => __t('Enable selected languages'),
							'disable' => __t('Disable selected languages'),
							'delete' => __t('Delete selected languages')
						)
					)
				);
			?>
			<?php echo $this->Form->submit(__t('Update')); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->table($results, $tSettings); ?>
<?php echo $this->Form->end(); ?>