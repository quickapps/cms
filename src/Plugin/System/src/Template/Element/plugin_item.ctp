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

<?php $status = ['-1' => 'danger', '0' => 'warning', '1' => 'success']; ?>
<div class="alert alert-<?php echo $status[$info['status']]; ?>" role="alert">
	<div class="btn-group pull-right">
		<?php
			echo $this->Html->link(
				'<span class="glyphicon glyphicon-lock"></span>',
				['plugin' => 'User', 'controller' => 'permissions', 'action' => 'index', 'prefix' => 'admin', 'expand' => $plugin],
				['class' => 'btn btn-default', 'title' => __d('system', 'Permissons'), 'escape' => false]
			);
		?>

		<?php if ($info['hasHelp']): ?>
			<?php
				echo $this->Html->link(
					'<span class="glyphicon glyphicon-question-sign"></span>',
					['plugin' => 'System', 'controller' => 'Help', 'action' => 'about', 'prefix' => 'admin', $plugin],
					['class' => 'btn btn-default', 'title' => __d('system', 'Help'), 'escape' => false]
				);
			?>
		<?php endif; ?>

		<?php if (!$info['isCore']): ?>
			<?php if ($info['hasSettings']): ?>
				<?php
					echo $this->Html->link(
						'<span class="glyphicon glyphicon-cog"></span>',
						['plugin' => 'System', 'controller' => 'Plugins', 'action' => 'settings', 'prefix' => 'admin', $plugin],
						['class' => 'btn btn-default', 'title' => __d('system', 'Settings'), 'escape' => false]
					);
				?>
			<?php endif; ?>

			<?php if ($info['status'] === 0): ?>
				<?php
					echo $this->Html->link(
						'<span class="glyphicon glyphicon-ok-circle"></span>',
						['plugin' => 'System', 'controller' => 'Plugins', 'action' => 'enable', 'prefix' => 'admin', $plugin],
						['class' => 'btn btn-default', 'title' => __d('system', 'Enable'), 'escape' => false]
					);
				?>
			<?php elseif ($info['status'] === 1): ?>
				<?php
					echo $this->Html->link(
						'<span class="glyphicon glyphicon-remove-circle"></span>',
						['plugin' => 'System', 'controller' => 'Plugins', 'action' => 'disable', 'prefix' => 'admin', $plugin],
						['class' => 'btn btn-default', 'title' => __d('system', 'Disable'), 'escape' => false]
					);
				?>
			<?php endif; ?>

			<?php
				echo $this->Html->link(
					'<span class="glyphicon glyphicon-trash"></span>',
					['plugin' => 'System', 'controller' => 'Plugins', 'action' => 'delete', 'prefix' => 'admin', $plugin],
					['class' => 'btn btn-default', 'title' => __d('system', 'Delete'), 'escape' => false]
				);
			?>
		<?php endif; ?>
	</div>

	<h2><?php echo $plugin; ?> (<?php echo $info['version']; ?>)</h2>
	<em class="description"><?php echo $info['description']; ?></em>

	<div class="extended-info" style="display:none;">
		<p class="details">
			<ul>
				<?php if (!empty($info['homepage'])): ?>
				<li><strong><?php echo __d('system', 'Homepage'); ?>:</strong> <?php echo $this->Html->link($info['homepage'], $info['homepage']); ?></li>
				<?php endif; ?>

				<?php if (!empty($info['support']['issues'])): ?>
				<li><strong><?php echo __d('system', 'Issues'); ?>:</strong> <?php echo $this->Html->link($info['support']['issues'], $info['support']['issues']); ?></li>
				<?php endif; ?>

				<?php if (!empty($info['support']['forum'])): ?>
				<li><strong><?php echo __d('system', 'Forum'); ?>:</strong> <?php echo $this->Html->link($info['support']['forum'], $info['support']['forum']); ?></li>
				<?php endif; ?>

				<?php if (!empty($info['support']['wiki'])): ?>
				<li><strong><?php echo __d('system', 'Wiki'); ?>:</strong> <?php echo $this->Html->link($info['support']['wiki'], $info['support']['wiki']); ?></li>
				<?php endif; ?>

				<?php if (!empty($info['support']['irc'])): ?>
				<li><strong><?php echo __d('system', 'IRC'); ?>:</strong> <?php echo $this->Html->link($info['support']['irc'], $info['support']['irc']); ?></li>
				<?php endif; ?>

				<?php if (!empty($info['support']['source'])): ?>
				<li><strong><?php echo __d('system', 'Source'); ?>:</strong> <?php echo $this->Html->link($info['support']['source'], $info['support']['source']); ?></li>
				<?php endif; ?>
			</ul>
		</p>

		<hr />

		<?php if (!empty($info['authors'])): ?>
			<h3><?php echo __d('system', 'Authors'); ?></h3>
			<ul>
			<?php foreach ($info['authors'] as $author): ?>
				<li>
					<?php if (!empty($author['homepage'])): ?>
						<?php echo $this->Html->link($author['name'], $author['homepage']); ?>
					<?php else: ?>
						<?php echo $author['name']; ?>
					<?php endif; ?>

					<?php if (!empty($author['email'])): ?>
						&lt;<?php echo $this->Html->link($author['email'], "mailto:{$author['email']}"); ?>&gt;
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
			</ul>

			<hr />
		<?php endif; ?>

		<div class="clearfix package-links container">
		<?php
			$trans = [
				'require' => __d('system', 'Requires'),
				'devRequire' => __d('system', 'Requires (Dev)'),
				'suggest' => __d('system', 'Suggests'),
				'provide' => __d('system', 'Provides'),
				'conflict' => __d('system', 'Conflicts'),
				'replace' => __d('system', 'Replaces'),
			];
		?>
		<?php foreach (["require", "devRequire", "suggest", "provide", "conflict", "replace"] as $type): ?>
		<p>
			<div class="<?php echo $type; ?>">
				<h4><?php echo $trans[$type]; ?></h4>

				<?php if (!empty($info[$type])): ?>
				<ul>
					<?php foreach ($info[$type] as $package => $version): ?>
						<li><?php echo $package; ?>: <?php echo $version; ?></li>
					<?php endforeach; ?>
				</ul>
				<?php else: ?>
					<?php echo __d('system', 'None'); ?>
				<?php endif; ?>
			</div>
		</p>
		<?php endforeach; ?>
		</div>

		<hr />

		<?php if (!empty($info['keywords'])): ?>
			<div class="clearfix text-left">
				<?php foreach($info['keywords'] as $tag): ?>
					<?php echo $this->Html->link($tag, 'https://packagist.org/search/?q=' . $tag, ['class' => 'label label-default', 'target' => '_blank']); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<hr />

	<p class="text-center"><a href="" class="btn btn-default glyphicon glyphicon-arrow-down toggler"></a></p>
</div>