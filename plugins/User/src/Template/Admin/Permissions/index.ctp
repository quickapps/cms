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

<p><?php echo $this->element('User.index_submenu'); ?></p>

<div class="row">
	<div class="col-md-5">
		<div id="acos-tree" style="display:none;">
			<?php
				echo $this->Menu->render($tree, function ($item, $info) {
					$options = [];
					if (!$info['depth']) {
						$options['templates']['child'] = '<li{{attrs}}><strong>{{content}}</strong>{{children}}</li>';
						$options['childAttrs']['id'] = 'node-' . $item->title;
					}
					if (!$info['hasChildren']) {
						$options['linkAttrs']['class'] = 'leaf-aco';
					}
					$options['linkAttrs']['data-aco-id'] = $item->id;
					return $this->Menu->formatter($item, $info, $options);
				});
			?>
		</div>
	</div>

	<div class="col-md-7 permissions-table"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<p><h2><?php echo __d('user', 'Maintenance Tasks'); ?></h2></p>

		<hr />

		<p>
			<?php
				echo $this->Html->link('', [
					'plugin' => 'User',
					'controller' => 'permissions',
					'action' => 'update'
				], ['class' => 'btn btn-success btn-sm glyphicon glyphicon-refresh']);
			?>
			<?php echo __d('user', '<strong>Update Tree</strong>: Adds any missing entry to the tree'); ?>
		</p>

		<p>
			<?php
				echo $this->Html->link('', [
					'plugin' => 'User',
					'controller' => 'permissions',
					'action' => 'update',
					'sync' => 1,
				], ['class' => 'btn btn-success btn-sm glyphicon glyphicon-sort-by-attributes']);
			?>
			<?php echo __d('user', '<strong>Synchronize</strong>: Adds any missing entry to the tree, and removes invalid ones.'); ?>
		</p>

		<p>
			<?php
				echo $this->Html->link('', [
					'plugin' => 'User',
					'controller' => 'permissions',
					'action' => 'export',
				], ['class' => 'btn btn-success btn-sm glyphicon glyphicon-export']);
			?>
			<?php echo __d('user', '<strong>Export</strong>: Backups permissions in an external file that can be imported later.'); ?>
		</p>

		<p>
			<?php
				echo $this->Form->create(null, [
					'url' => [
						'plugin' => 'User',
						'controller' => 'permissions',
						'action' => 'import',
					],
					'type' => 'file',
					'class' => 'form-inline',
				]);
			?>
			<button class="btn btn-success btn-sm glyphicon glyphicon-import" type="submit"></button>
			<?php echo __d('user', '<strong>Import</strong>: Creates permissions using a backup file.'); ?>
			<?php echo $this->Form->input('json', ['type' => 'file', 'label' => false, 'class' => 'input-sm']); ?>
			<?php echo $this->Form->end(); ?>
		</p>
	</div>
</div>

<script>
	var baseURL = '<?php echo $this->Url->build(['plugin' => 'User', 'controller' => 'permissions', 'action' => 'aco'], true); ?>/';
	var expandPlugin = '<?php echo !empty($this->request->query['expand']) ? $this->request->query['expand'] : ''; ?>';
</script>
<?php echo $this->Html->script('User.jstree.min.js'); ?>
<?php echo $this->Html->css('User.jstree-themes/default/style.min.css'); ?>
<?php echo $this->Html->css('User.acos.tree.css'); ?>
<?php echo $this->Html->script('User.acos.tree.js'); ?>
