<?php echo $this->Form->create('Node', array('url' => "/admin/node/contents/add/{$this->data['NodeType']['id']}")); ?>
	<!-- Content -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Add') . ' "' . $this->data['NodeType']['name'] . '"'); ?>
		<p class="muted"><?php echo !empty($this->data['NodeType']['description']) ? __t($this->data['NodeType']['description']) : ''; ?></p>
		<?php echo $this->Form->hidden('node_type_id', array('value' => $this->data['NodeType']['id'])); ?>
		<?php echo $this->Form->hidden('node_type_base', array('value' => $this->data['NodeType']['base'])); ?>
		<?php echo $this->Form->input('title', array('required' => 'required', 'label' => __t("%s *", $this->data['NodeType']['title_label']))); ?>
		<?php echo $this->Form->input('Node.description', array('type' => 'textarea', 'rows' => 2, 'label' => __t('Description'), 'helpBlock' => __t('A short description (255 chars. max.) about this content. Will be used as page meta-description when rendering this content node.'))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- NodeType Form -->
	<?php $refData = $this->data; ?>
	<?php echo $this->Layout->hook("{$this->data['NodeType']['base']}_form", $refData); ?>

	<!-- Settings -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Settings')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Comments')); ?>
			<?php echo $this->Form->input('comment', array('type' => 'radio', 'legend' => false, 'separator' => '<br>', 'options' => array(2 => __t('Open'), 0 => __t('Closed'), 1 => __t('Read Only')))); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Language')); ?>
			<?php echo $this->Form->input('language', array('empty' => __t('-- Any --'), 'type' => 'select', 'options' => $languages, 'label' => __t('Language'), 'helpBlock' => __t('If no language is selected (-- Any --), node will show regardless of language'))); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Publishing')); ?>
			<?php echo $this->Form->input('status', array('type' => 'checkbox', 'label' => __t('Published'), 'value' => 1)); ?>
			<?php echo $this->Form->input('promote', array('type' => 'checkbox', 'label' => __t('Promoted to front page'), 'value' => 1)); ?>
			<?php echo $this->Form->input('sticky', array('type' => 'checkbox', 'label' => __t('Sticky at top of lists'), 'value' => 1)); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Cache this node for')); ?>
			<?php
				echo $this->Form->input('cache',
					array(
						'type' => 'select',
						'label' => __t('Cache'),
						'options' => array(
							'' => __t('-- No Cache --'),
							'+1 hour' => __t('%s Hour', 1),
							'+2 hours' => __t('%s Hours', 2),
							'+4 hours' => __t('%s Hours', 3),
							'+7 hours' => __t('%s Hours', 7),
							'+11 hours' => __t('%s Hours', 11),
							'+16 hours' => __t('%s Hours', 16),
							'+22 hours' => __t('%s Hours', 22),
							'+1 day' => __t('%s Day', 1),
							'+3 day' => __t('%s Days', 3),
							'+5 day' => __t('%s Days', 5),
							'+1 week' => __t('%s Week', 1),
							'+2 weeks' => __t('%s Weeks', 2),
							'+3 weeks' => __t('%s Weeks', 3),
							'+1 month' => __t('%s Month', 1),
							'+3 months' => __t('%s Months', 3),
							'+6 months' => __t('%s Months', 6),
							'+9 months' => __t('%s Months', 9),
							'+1 year' => __t('%s Year', 1)
						)
					)
				);
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Roles')); ?>
			<?php echo $this->Form->input('Role', array('options' => $roles, 'type' => 'select', 'multiple' => true, 'label' => __t('Show content for specific roles'), 'helpBlock' => __t("Show this content only for the selected role(s). If you select no roles, the content will be visible to all users."))); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Menu Link')); ?>
			<?php $checked = (isset($this->data['MenuLink']) && !empty($this->data['MenuLink'])); ?>
			<?php echo $this->Form->input('Node.menu_link', array('onClick' => "$('#node_menu_link').toggle();", 'type' => 'checkbox', 'checked' => $checked,'label' => __t('Provide a menu link'))); ?>

			<div id="node_menu_link" style="<?php echo !$checked ? 'display:none;' : ''; ?>">
				<?php echo $this->Form->input('MenuLink.link_title', array('label' => __t('Menu link title'))); ?>
				<?php echo $this->Form->input('MenuLink.description', array('type' => 'textarea', 'label' => __t('Description'), 'helpBlock' =>  __t('Shown when hovering over the menu link.'))); ?>
				<?php echo $this->Form->input('MenuLink.parent_id', array('type' => 'select', 'options' => $menus, 'escape' => false, 'label' => __t('Parent item'))); ?>
			</div>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Advanced Options')); ?>
			<?php echo $this->Form->input('Node.params.class', array('label' => __t('Node container class suffix'), 'helpBlock' => __t('A suffix to be applied to the CSS class of the node container. This allows for individual node styling.'))); ?>
			<?php
				$data = $this->data;
				$params = $this->Layout->hook('node_form_params', $data, array('collectReturn' => true));

				echo implode("\n ", (array)$params);
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->submit(__t('Add content')); ?>
<?php echo $this->Form->end(); ?>