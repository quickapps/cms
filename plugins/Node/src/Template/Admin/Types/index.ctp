<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<div class="text-right">
	<?php echo $this->Html->link(__d('node', 'Define new content type'), ['plugin' => 'Node', 'controller' => 'types', 'action' => 'add'], ['class' => 'btn btn-primary']); ?>
</div>

<p>
	<?php foreach ($types as $type): ?>
		<div class="clearfix">
			<p>
				<div class="btn-group pull-right">
					<?php
						echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', [
							'plugin' => 'Node',
							'controller' => 'types',
							'action' => 'edit',
							$type->slug
						],[
							'title' => __d('node', 'Edit information'),
							'class' => 'btn btn-default',
							'escape' => false
						]);
					?>
					<?php
						echo $this->Html->link('<span class="glyphicon glyphicon-list-alt"></span>', [
							'plugin' => 'Node',
							'controller' => 'fields',
							'action' => 'index',
							'type' => $type->slug
						], [
								'title' => __d('node', 'Manage fields'),
								'class' => 'btn btn-default',
								'escape' => false
						]);
					?>
					<?php
						echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', [
							'plugin' => 'Node',
							'controller' => 'types',
							'action' => 'delete',
							$type->slug
						], [
							'title' => __d('node', 'Delete'),
							'class' => 'btn btn-default',
							'escape' => false,
							'confirm' => __d('node', 'Delete this content type? This operation can not be undone.')
						]);
					?>
				</div>
				<h4><?php echo $type->name; ?> (id: <?php echo $type->slug; ?>)</h4>
				<p class="list-group-item-text"><em><?php echo $type->description; ?></em></p>
			</p>
		</div>
	<?php endforeach; ?>
</p>
