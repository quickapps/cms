<?php
	$links = array(
		array(__t('Search'), '/admin/system/directory/modules'),
		array(__t('Newest'), '/admin/system/directory/modules/created'),
		array(__t('Recently Updated'), '/admin/system/directory/modules/updated')
	);

	echo $this->Menu->toolbar($links, array('class' => 'simple-toolbar'));
?>

<?php if (!$listing): ?>
	<?php echo $this->Form->create('Search'); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Search Modules')); ?>	
			<?php echo $this->Form->input('Search.keywords', array('label' => __t('Keywords'), 'helpBlock' => __t('Search for modules by keyword.'))); ?>
			<?php echo $this->Form->submit(__t('Search Modules')); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>

<?php if (count($results)): ?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th><?php echo __t('Name'); ?></th>
			<th><?php echo __t('Description'); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($results as $repo): ?>
		<?php
			$appName = str_replace('QACMS-', '', $repo->name);
			$installed = Configure::read("Modules.{$appName}") ? '<i class="icon-ok" title="' . __t('Installed') . '"></i>' : '';
		?>
		<tr>
			<td>
				<h4><?php echo $appName; ?> <?php echo $installed; ?></h4>
				<?php echo $this->Html->link(__t('Details'), "/admin/system/directory/module_details/{$repo->name}"); ?>
			</td>

			<td width="65%">
				<?php echo $this->Form->helpBlock(strip_tags($repo->description)); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>