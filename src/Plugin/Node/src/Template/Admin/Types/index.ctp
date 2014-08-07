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

<div class="text-right">
	<?php echo $this->Html->link(__d('node', 'Define new content type'), ['plugin' => 'Node', 'controller' => 'types', 'action' => 'add'], ['class' => 'btn btn-primary']); ?>
</div>

<p>
	<?php
		echo $this->Menu->render($types, [
			'class' => 'list-group',
			'formatter' => function ($item, $info) {
				return $this->element('Node.types_list_item', ['item' => $item, 'info' => $info]);
			}
		]);
	?>
</p>
