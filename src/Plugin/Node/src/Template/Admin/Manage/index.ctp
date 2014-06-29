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

<?php
	function renderNodeRow($view, $node, $deph = 0) {
		return $view->element('node_row', ['node' => $node, 'deph' => $deph]);
	}

	echo $this->element('Node.index_pills');
?>

<table class="table table-hover">
	<thead>
		<tr>
			<th><?php echo __('Title'); ?></th>
			<th><?php echo __('Type'); ?></th>
			<th><?php echo __('Author'); ?></th>
			<th><?php echo __('Language'); ?></th>
			<th><?php echo __('Created on'); ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($nodes as $node): ?>
			<?php echo renderNodeRow($this, $node); ?>
		<?php endforeach; ?>
	</tbody>
</table>