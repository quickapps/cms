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
			<?php echo $this->Form->input('Search.keywords', array('label' => __t('Keywords'))); ?>
			<em><?php echo __t('Search for modules by keyword.'); ?></em>

			<p><?php echo $this->Form->submit(__t('Search Modules')); ?></p>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>

<?php if (count($results)): ?>
<table class="table table-bordered">
	<thead>
		<tr>
			<td><?php echo __t('Name'); ?></td>
			<td><?php echo __t('Description'); ?></td>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($results as $repo): ?>
		<tr>
			<td>
				<h4><?php echo str_replace('QACMS-', '', $repo->name); ?></h4>
				<?php echo $this->Html->link(__t('Details'), "/admin/system/directory/module_details/{$repo->name}"); ?>
			</td>

			<td width="65%">
				<em><?php echo strip_tags($repo->description); ?></em><br />
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>