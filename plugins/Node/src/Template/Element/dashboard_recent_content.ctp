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

use Cake\ORM\TableRegistry;
?>

<?php
	$nodes = TableRegistry::get('Node.Nodes')
		->find()
		->order(['created' => 'DESC'])
		->limit(10)
		->all();
?>

<div class="panel panel-default">
	<div class="panel-heading"><?php echo __d('node', 'Recent Content'); ?></div>
	<div class="panel-body">
		<table class="table">
			<?php foreach ($nodes as $node): ?>
				<tr>
					<td>
						<?php echo $this->Html->link($node->title, ['plugin' => 'Node', 'controller' => 'manage', 'action' => 'edit', $node->id]); ?>
						<em class="help-block"><?php echo $node->description; ?></em>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>