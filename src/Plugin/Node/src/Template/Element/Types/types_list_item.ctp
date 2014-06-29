<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<div class="clearfix">
	<p>
		<div class="btn-group pull-right">
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', ['plugin' => 'node', 'controller' => 'types', 'action' => 'edit', $item->slug], ['title' => __('Edit information'), 'class' => 'btn btn-default', 'escape' => false]); ?>
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-list-alt"></span>', ['plugin' => 'node', 'controller' => 'fields', 'action' => 'index', 'type' => $item->slug], ['title' => __('Manage fields'), 'class' => 'btn btn-default', 'escape' => false]); ?>
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', ['plugin' => 'node', 'controller' => 'types', 'action' => 'delete', $item->slug], ['title' => __('Delete'), 'class' => 'btn btn-default', 'escape' => false]); ?>
		</div>
		<h4><?php echo $item->name; ?> (id: <?php echo $item->slug; ?>)</h4>
		<p class="list-group-item-text"><em><?php echo $item->description; ?></em></p>
	</p>
</div>