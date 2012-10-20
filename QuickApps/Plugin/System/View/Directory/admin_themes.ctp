<?php
	$links = array(
		array(__t('Search'), '/admin/system/directory/themes'),
		array(__t('Newest'), '/admin/system/directory/themes/created'),
		array(__t('Recently Updated'), '/admin/system/directory/themes/updated')
	);

	echo $this->Menu->toolbar($links, array('class' => 'simple-toolbar'));
?>

<?php if (!$listing): ?>
	<?php echo $this->Form->create('Search'); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Search Themes')); ?>	
			<?php echo $this->Form->input('Search.keywords', array('label' => __t('Keywords'), 'helpBlock' => __t('Search for themes by keyword.'))); ?>
			<p><?php echo $this->Form->submit(__t('Search Themes')); ?></p>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>

<?php if (count($results)): ?>
<table class="table">
	<thead>
		<tr>
			<th><?php echo __t('Name'); ?></th>
			<th><?php echo __t('Description'); ?></th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($results as $repo): ?>
		<tr>
			<td width="250">
				<h4><?php echo str_replace('QACMS-', '', $repo->name); ?></h4>
				<div><p><?php echo $this->Html->image($repo->thumbnail, array('class' => 'theme_tn img-rounded', 'url' => "/admin/system/directory/theme_details/{$repo->name}")); ?></p></div>
				<p><?php echo $this->Html->link(__t('Details'), "/admin/system/directory/theme_details/{$repo->name}"); ?></p>
			</td>

			<td>
				<?php echo $this->Form->helpBlock(strip_tags($repo->description)); ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>