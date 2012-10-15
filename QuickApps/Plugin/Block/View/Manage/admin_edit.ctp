<?php echo $this->Form->create('Block', array('url' => "/admin/block/manage/edit/{$this->data['Block']['id']}")); ?>
	<!-- Content -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Content')); ?>
		<?php echo $this->Form->hidden('id'); ?>
		<?php echo $this->Form->input('status', array('type' => 'checkbox', 'label' => __t('Active'))) . "\n"; ?>
		<?php echo $this->Form->input('Block.title', array('label' => __t('Block title'), 'helpBlock' => __t('The title of the block as shown to the user.'))); ?>

		<?php if ($this->data['Block']['module'] === 'Block'): // custom data only for custom blocks ?>
			<?php echo $this->Form->input('BlockCustom.description', array('required' => 'required', 'label' => __t('Block description *'), 'helpBlock' => __t('A brief description of your block. Used on the Blocks administration page.'))); ?>
			<?php echo $this->Form->input('BlockCustom.body', array('required' => 'required', 'type' => 'textarea', 'class' => 'full', 'label' => __t('Block body *'))); ?>
		<?php endif; ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php if ($this->data['Block']['module'] !== 'Block'): ?>
		<?php $this->Layout->attachModuleHooks($this->data['Block']['module']); ?>
		<?php
			if ($this->Layout->elementExists("{$this->data['Block']['module']}.{$this->data['Block']['delta']}_block_settings")) {
				$settings = $this->element("{$this->data['Block']['module']}.{$this->data['Block']['delta']}_block_settings", array('block' => $this->data));
			} else {
				$data = $this->data;
				$settings = $this->Layout->hook("{$this->data['Block']['module']}_{$this->data['Block']['delta']}_settings", $data, array('collectReturn' => false));
			}
		?>
		<?php if ($settings): ?>
			<?php echo $this->Html->useTag('fieldsetstart', 'Widget settings'); ?>
				<?php echo $settings; ?>
			<?php echo $this->Html->useTag('fieldsetend'); ?>
		<?php endif; ?>
		<?php $this->Layout->detachModuleHooks($this->data['Block']['module']); ?>
	<?php endif; ?>

	<!-- Language -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Language')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Translations')); ?>
			<?php
				$langs = array();

				foreach (Configure::read('Variable.languages') as $lang) {
					$langs[$lang['Language']['code']] = $lang['Language']['name'];
				}
			?>
			<?php echo $this->Form->input('locale', array('options' => $langs, 'type' => 'select', 'selected' => Hash::extract($this->data, '{n}.Block.locale'), 'multiple' => true, 'label' => __t('Show this block for these languages'), 'helpBlock' => __t('If no language is selected, block will show regardless of language.'))); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Visibility settings -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Visibility settings')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Theme Region')); ?>
			<?php echo $this->Form->helpBlock(__t('Specify in which themes and regions this block is displayed.')); ?>
			<?php $i = 0; ?>
			<?php foreach ($regions as $theme => $_regions): ?>
				<?php list($theme_name, $theme_app) = explode('::', $theme); ?>

				<label><?php echo $theme_name; ?></label>
				<?php
					$_selected = Hash::extract($this->data, "BlockRegion.{n}[theme={$theme_app}]");
					$selected = isset($_selected[0]['region']) && !empty($_selected[0]['region']) ? $_selected[0]['region'] : null;
				?>

				<?php echo $this->Form->select("BlockRegion.{$i}.region", $_regions, array('value' => $selected, 'empty' => __t('--None--'))) . "\n"; ?>
				<?php echo $this->Form->hidden("BlockRegion.{$i}.theme", array('value' => $theme_app)) . "\n"; ?>
				<?php echo $this->Form->hidden("BlockRegion.{$i}.block_id", array('value' => $this->data['Block']['id'])) . "\n"; ?>

				<?php
					if (isset($_selected[0]['id'])) {
						echo $this->Form->hidden("BlockRegion.{$i}.id", array('value' => $_selected[0]['id'])) . "\n";
					}

					$i++;
				?>
			<?php endforeach; ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Pages')); ?>
			<?php
				echo $this->Form->input('visibility',
					array(
						'type' => 'radio',
						'legend' => false,
						'separator' => '<br>',
						'options' => array(
							0 => __t('All pages except those listed'),
							1 => __t('Only the listed pages'),
							2 => __t('Pages on which this PHP code returns TRUE (experts only)')
						)
					)
				);
			?>

			<?php
				echo $this->Form->input('pages',
					array(
						'type' => 'textarea',
						'label' => false,
						'helpBlock' => 
							__t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are blog for the blog page and blog/* for every blog entry. '/' is the front page.") .
							__t('If the PHP option is chosen, enter PHP code between &lt;?php ?&gt;. Note that executing incorrect PHP code can break your QuickApps site.')
					)
				);
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Roles')); ?>
			<?php echo $this->Form->input('Role', array('options' => $roles, 'type' => 'select', 'multiple' => true, 'label' => __t('Show block for specific roles'), 'helpBlock' => __t("Show this block only for the selected role(s). If you select no roles, the block will be visible to all users."))); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Advanced Options')); ?>
			<?php echo $this->Form->input('Block.params.class', array('label' => __t('Block class suffix'), 'helpBlock' => __t('A suffix to be applied to the CSS class of the block. This allows for individual block styling.'))); ?>
			<?php
				$data = $this->data['Block'];
				$params =  $this->Layout->hook('block_form_params', $data, array('collectReturn' => true));

				echo implode("\n ", (array)$params);
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->submit(__t('Save block')); ?>
<?php echo $this->Form->end(); ?>