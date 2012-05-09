<?php echo $this->Form->create('MenuLink'); ?>
	<?php echo $this->Html->useTag('fieldsetstart', __t('Add menu link')); ?>
		<?php echo $this->Form->input('menu_id', array('type' => 'hidden', 'value' => $menu_id)); ?>

		<?php echo $this->Form->input('link_title', array('required' => 'required', 'type' => 'text', 'label' => __t('Menu link title *'))); ?>
		<em><?php echo __t('The text to be used for this link in the menu.'); ?></em>

		<?php echo $this->Form->input('router_path', array('required' => 'required', 'type' => 'text', 'label' => __t('Path *'))); ?>
		<em><?php echo __t("The path for this menu link. This can be an internal QuickApps path such as /type-of-content/my-post.html or an external URL such as http://quickapps.es. Enter '/' to link to the front page."); ?></em>

		<?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => __t('Description'), 'class' => 'plain')); ?>
		<em><?php echo __t('Shown when hovering over the menu link.'); ?></em>

		<?php echo $this->Form->input('target', array('type' => 'select', 'label' => __t('Target window'), 'options' => array('_self' => __t('Same window'), '_blank' => __t('New window')))); ?>
		<em><?php echo __t('Target browser window when the link is clicked.'); ?></em>

		<p>&nbsp;</p>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Show as selected:')); ?>
			<?php
				echo $this->Form->input('selected_on_type',
					array(
						'legend' => false,
						'type' => 'radio',
						'options' => array(
							'php' => __t('When following PHP code returns TRUE (experts only)'),
							'reg' => __t('On URL matching (regular expression)')
						),
						'separator' => '<br />'
					)
				);
			?>

			<?php echo $this->Form->input('selected_on', array('type' => 'textarea', 'label' => false)); ?>
			<em>
				<?php echo __t('If the PHP option is chosen, enter PHP code between &lt;?php ?&gt;. Note that executing incorrect PHP code can break your QuickApps site.'); ?>
			</em>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Form->input('parent_id', array('escape' => false, 'empty' => true, 'type' => 'select', 'options' => $links, 'label' => __t('Parent link'))); ?>
		<p>&nbsp;</p>
		<?php echo $this->Form->input('expanded', array('type' => 'checkbox', 'label' => __t('Show as expanded'))); ?>
		<em><?php echo __t('If selected and this menu link has children, the menu will always appear expanded.'); ?></em>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->submit(__t('Save')); ?>
<?php echo $this->Form->end(); ?>