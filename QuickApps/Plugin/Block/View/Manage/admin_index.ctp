<?php echo $this->Form->create('BlockRegion'); ?>
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . $themes[Configure::read('Variable.site_theme')]['info']['name'] . '</span>'); ?>
	<div class="fieldset-toggle-container" style="display:none;">
		<?php
			echo $this->element('regions',
				array(
					'theme' => Configure::read('Variable.site_theme'),
					'blocks_in_theme' => $site_theme
				)
			);
		?>
	</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Backend theme -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . $themes[Configure::read('Variable.admin_theme')]['info']['name'] . '</span>'); ?>
	<div class="fieldset-toggle-container" style="display:none;">
		<?php
			echo $this->element('regions',
				array(
					'theme' => Configure::read('Variable.admin_theme'),
					'blocks_in_theme' => $admin_theme
				)
			);
		?>
	</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Unassigned blocks -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Unassigned or Disabled') . '</span>'); ?>
	<div class="fieldset-toggle-container" style="display:none;">
		<?php
			$notAssigned = array();

			foreach ($unassigned as $key => $b) {
				if (
					strpos($b['Block']['module'], 'Theme') === 0 &&
					!in_array($b['Block']['module'],
						array(
							'Theme' . Configure::read('Variable.admin_theme'),
							'Theme' . Configure::read('Variable.site_theme')
						)
					)
				) {
					continue;
				}

				$notAssigned[] = $b;
			}
		?>
		<ul class="not-sortable">
			<?php foreach ($notAssigned as $block): ?>
			<li class="ui-state-default">
				<div class="pull-left">
					<span class="ui-icon"></span>
				</div>

				<div class="pull-left" style="width:60%;">
				<?php
					if ($block['Block']['title'] == '') {
						if ($block['Menu']['title'] != '') {
							echo $block['Menu']['title'];
						} else {
							echo "{$block['Block']['module']}_{$block['Block']['delta']}";
						}
					} else {
						echo "{$block['Block']['title']}";
					}

					echo !empty($block['BlockCustom']['description']) ? " (<em>{$block['BlockCustom']['description']}</em>)" : '';
				?>
				</div>

				<div class="pull-left">
					---
				</div>

				<div class="pull-right">
					<a href="<?php echo $this->Html->url("/admin/block/manage/clone/{$block['Block']['id']}"); ?>" onClick="return confirm('<?php echo __t('Duplicate this block?'); ?>');"><?php echo __t('clone') ?></a> |
					<a href="<?php echo $this->Html->url("/admin/block/manage/edit/{$block['Block']['id']}"); ?>"><?php echo __t('configure'); ?></a> |
					<?php if ($block['Block']['module'] == 'Block' || $block['Block']['clone_of'] != 0) { ?>
						<a href="<?php echo $this->Html->url("/admin/block/manage/delete/{$block['Block']['id']}"); ?>" onclick="return confirm('<?php echo __t('Delete selected block ?'); ?>');"><?php echo __t('delete'); ?></a> |
					<?php } ?>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Form->submit(__t('Save all')); ?>
<?php echo $this->Form->end(); ?>
<script>
	$(".sortable").sortable().disableSelection();
</script>