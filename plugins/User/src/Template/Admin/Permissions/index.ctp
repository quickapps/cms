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
		<hr />
		<strong><?php echo __d('user', 'Maintenance Tasks'); ?></strong>
		<em class="help-block">
			<?php
				echo $this->Html->link(__d('user', 'Update Tree'), [
					'plugin' => 'User',
					'controller' => 'permissions',
					'action' => 'update'
				], ['class' => 'btn btn-success btn-sm']);
			?>
			<span><?php echo __d('user', 'Adds any missing entry to the tree'); ?></span>
		</em>
		<em class="help-block">
			<?php
				echo $this->Html->link(__d('user', 'Synchronize'), [
					'plugin' => 'User',
					'controller' => 'permissions',
					'action' => 'update',
					'sync' => 1,
				], ['class' => 'btn btn-success btn-sm']);
			?>
			<span><?php echo __d('user', 'Adds any missing entry to the tree, and removes invalid ones.'); ?></span>
		</em>
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
