<?php
	$this->Layout->script("
	var _custom_icon_ = '{$this->data['Language']['icon']}';

	function showFlag(s) {
		if (s.value == '') {
			$('img.flag-icon').hide();
			$('#LanguageCustomIcon').val(_custom_icon_);
		} else {
			$('img.flag-icon').attr('src', QuickApps.settings.base_url + 'locale/img/flags/' + s.value);
			$('img.flag-icon').show();
			$('#LanguageCustomIcon').val('');
		}
	}
	", 'inline');
?>

<?php echo $this->Form->create('Language', array('url' => "/admin/locale/languages/edit/{$this->data['Language']['id']}")); ?>
	<?php echo $this->Html->useTag('fieldsetstart', __t('Editing language')); ?>
		<?php if (!in_array($this->data['Language']['code'], array('eng', Configure::read('Variable.default_language')))): ?>
			<?php echo $this->Form->input('status', array('type' => 'checkbox', 'label' => __t('Active'))); ?>
		<?php endif; ?>
		<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
		<?php echo $this->Form->input('code', array('type' => 'hidden')); ?>
		<?php echo $this->Form->input('name', array('required' => 'required', 'type' => 'text', 'label' => __t('Language name in English *'))); ?>
		<?php echo $this->Form->input('native', array('required' => 'required', 'type' => 'text', 'label' => __t('Native language name *'), 'helpBlock' => __t('Name of the language in the language being added.'))); ?>
		<?php
			if (strpos($this->data['Language']['icon'], '://') !== false) {
				$icon = $this->data['Language']['icon'];
			} else {
				$icon = "/locale/img/flags/{$this->data['Language']['icon']}";
			}

			echo $this->Form->input('icon',
				array(
					'type' => 'select',
					'label' => __t('Flag icon'),
					'options' => $flags,
					'empty' => __t('-- None --'),
					'onChange' => 'showFlag(this);',
					'after' => ' ' .
					$this->Html->image($icon,
						array(
							'class' => 'flag-icon',
							'style' => (empty($this->data['Language']['icon']) ? 'display:none;' : '')
						)
					)
				)
			);

			echo $this->Form->input('custom_icon',
				array(
					'type' => 'text',
					'label' => __t('Custom flag icon'),
					'value' => (!in_array($this->data['Language']['icon'], array_keys($flags)) ? $this->data['Language']['icon'] : ''),
					'helpBlock' => __t("Optional URL of your language flag icon, if your language flag isn't in the above list.")
				)
			);
		?>

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
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->submit(__t('Save language')); ?>
<?php echo $this->Form->end(); ?>