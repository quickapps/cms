<?php echo $this->Form->create('Block', array('url' => "/admin/block/manage/add")); ?>
	<!-- Content -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Content')); ?>
		<?php echo $this->Form->hidden('status', array('value' => 1)); ?>
		<?php echo $this->Form->input('Block.title', array('label' => __t('Block title'))); ?>
		<em><?php echo __t('The title of the block as shown to the user.'); ?></em>

		<?php echo $this->Form->input('BlockCustom.description', array('required' => 'required', 'label' => __t('Block description *'))); ?>
		<em><?php echo __t('A brief description of your block. Used on the Blocks administration page.'); ?></em>

		<?php echo $this->Form->input('BlockCustom.body', array('required' => 'required', 'label' => __t('Block body *'), 'class' => 'full', 'type' => 'textarea', 'after' => '')); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

   <!-- Language -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Language')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Translations')); ?>
			<?php
				$langs = array();
				foreach (Configure::read('Variable.languages') as $lang) $langs[$lang['Language']['code']] = $lang['Language']['name'];
			?>
			<?php echo $this->Form->input('locale', array('options' => $langs, 'type' => 'select', 'selected' => Hash::extract($this->data, '{n}.Block.locale'), 'multiple' => true, 'label' => __t('Show this block for these languages'))); ?>
			<em><?php echo __t('If no language is selected, block will show regardless of language.'); ?></em>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Visibility settings -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Visibility settings')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Theme Region')); ?>
			<em><?php echo __t('Specify in which themes and regions this block is displayed.'); ?></em><br/>
			<?php $i = 0; foreach ($regions as $theme => $_regions): ?>
				<?php $theme = explode('@|@', $theme); // name, folder ?>
				<label><?php echo $theme[0]; ?></label>
				<?php echo $this->Form->select("BlockRegion.{$i}.region", $_regions, array('empty' => __t('--None--'))) . "\n"; ?>
				<?php echo $this->Form->hidden("BlockRegion.{$i}.theme", array('value' => $theme[1])) . "\n"; ?>
			<?php $i++; endforeach; ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Pages')); ?>
			<?php
				echo $this->Form->input('visibility',
					array(
						'type' => 'radio',
						'legend' => false,
						'value' => 0,
						'separator' => '<br>',
						'options' => array(
							0 => __t('All pages except those listed'),
							1 => __t('Only the listed pages'),
							2 => __t('Pages on which this PHP code returns TRUE (experts only)')
						)
					)
				);
			?>

			<?php echo $this->Form->input('pages', array('type' => 'textarea', 'label' => false, 'after' => '')); ?>
			<em>
				<?php echo __t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are blog for the blog page and blog/* for every blog entry. '/' is the front page."); ?>
				<?php echo __t('If the PHP option is chosen, enter PHP code between &lt;?php ?&gt;. Note that executing incorrect PHP code can break your QuickApps site.'); ?>
			</em>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Roles')); ?>
			<?php echo $this->Form->input('Role', array('options' => $roles, 'type' => 'select', 'multiple' => true, 'label' => __t('Show block for specific roles'))); ?>
			<em><?php echo __t("Show this block only for the selected role(s). If you select no roles, the block will be visible to all users."); ?></em>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Advanced Options')); ?>
			<?php echo $this->Form->input('Block.params.class', array('label' => __t('Block class suffix'))); ?>
			<em><?php echo __t('A suffix to be applied to the CSS class of the block. This allows for individual block styling.'); ?></em>

			<?php
				$params =  $this->Layout->hook('block_form_params', $data = null, array('collectReturn' => true));

				echo implode("\n ", (array)$params);
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->input(__t('Save block'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>