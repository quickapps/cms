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

<div class="text-right"><?php echo $this->Html->link(__d('block', 'Create New Block'), ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'add'], ['class' => 'btn btn-primary']); ?></div>

<p>
	<div class="panel-group" id="accordion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#front-theme"><?php echo __d('block', 'Theme: {0}', $frontThemeName); ?></a></h4>
			</div>
			<div id="front-theme" class="panel-collapse collapse">
				<div class="panel-body">
					<?php echo $this->Form->create(); ?>
						<?php foreach ($front as $region => $blocks): ?>
							<hr />
							<h3><?php echo $region; ?></h3>

							<?php if (count($blocks->toArray())): ?>
								<ul class="sortable list-group">
									<?php foreach ($blocks as $block): ?>
										<li class="list-group-item clearfix">
											<div class="pull-left">
												<strong><?php echo $block->title; ?></strong>
												<em class="help-block"><?php echo $block->description; ?></em>
											</div>
											<div class="btn-group pull-right">
												<?php
													echo $this->Html->link('', [
														'plugin' => 'Block',
														'controller' => 'manage',
														'action' => 'edit',
														$block->id
													], [
														'title' => __d('block', 'Edit'),
														'class' => 'btn btn-default btn-sm glyphicon glyphicon-pencil'
													]);
												?>
												<?php
													echo $this->Html->link('', [
														'plugin' => 'Block',
														'controller' => 'manage',
														'action' => 'duplicate',
														$block->id
													], [
														'title' => __d('block', 'Duplicate'),
														'class' => 'btn btn-default btn-sm glyphicon glyphicon-copy',
														'confirm' => __d('block', 'Duplicate this block, are you sure?'),
													]);
												?>
												<?php if ($block->handler === 'Block'): ?>
													<?php
														echo $this->Html->link('', [
															'plugin' => 'Block',
															'controller' => 'manage',
															'action' => 'delete',
															$block->id
														], [
															'title' => __d('block', 'Delete'),
															'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
															'confirm' => __d('block', 'Delete this block, are you sure?'),
														]);
													?>
												<?php endif; ?>
											</div>
											<?php echo $this->Form->hidden('regions.' . option('front_theme') . ".{$block->region->region}.", ['value' => $block->region->id]); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php else: ?>
								<div class="alert alert-warning"><?php echo __d('block', 'There are no blocks in this region yet.'); ?></div>
							<?php endif; ?>
						<?php endforeach; ?>

						<hr />

						<em class="help-block">(<?php echo __d('block', 'Drag and drop blocks to reorder within a region. To move a block to a different region use block editing form by clicking in the <span class="btn btn-default btn-xs glyphicon glyphicon-pencil"></span> button.'); ?>)</em>
						<?php echo $this->Form->submit(__d('block', 'Save Order')); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#back-theme"><?php echo __d('block', 'Theme: {0}', $backThemeName); ?></a></h4>
			</div>
			<div id="back-theme" class="panel-collapse collapse">
				<div class="panel-body">
					<?php echo $this->Form->create(); ?>
						<?php foreach ($back as $region => $blocks): ?>
							<hr />
							<h3><?php echo $region; ?></h3>

							<?php if (count($blocks->toArray())): ?>
								<ul class="sortable list-group">
									<?php foreach ($blocks as $block): ?>
										<li class="list-group-item clearfix">
											<div class="pull-left">
												<strong><?php echo $block->title; ?></strong>
												<em class="help-block"><?php echo $block->description; ?></em>
											</div>
											<div class="btn-group pull-right">
												<?php echo $this->Html->link('', ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'edit', $block->id], ['title' => __d('block', 'Edit'), 'class' => 'btn btn-default btn-sm glyphicon glyphicon-pencil']); ?>
												<?php echo $this->Html->link('', ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'duplicate', $block->id], ['title' => __d('block', 'Duplicate'), 'class' => 'btn btn-default btn-sm glyphicon glyphicon-th-large']); ?>
												<?php if ($block->handler === 'Block'): ?>
													<?php echo $this->Html->link('', ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'delete', $block->id], ['title' => __d('block', 'Delete'), 'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash', 'confirm' => __d('block', 'Delete this block, are you sure?')]); ?>
												<?php endif; ?>
											</div>
											<?php echo $this->Form->hidden('regions.' . option('back_theme') . ".{$block->region->region}.", ['value' => $block->region->id]); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php else: ?>
								<div class="alert alert-warning"><?php echo __d('block', 'There are no blocks in this region yet.'); ?></div>
							<?php endif; ?>
						<?php endforeach; ?>

						<hr />

						<em class="help-block">(<?php echo __d('block', 'Drag and drop blocks to reorder within a region. To move a block to a different region use block editing form by clicking in the "Edit" button.'); ?>)</em>
						<?php echo $this->Form->submit(__d('block', 'Save Order')); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#unused-blocks"><?php echo __d('block', 'Unused or Unassigned Blocks'); ?></a></h4>
			</div>
			<div id="unused-blocks" class="panel-collapse collapse">
				<div class="panel-body">
					<ul class="list-group">
						<?php foreach ($unused as $block): ?>
							<li class="list-group-item clearfix">
								<div class="pull-left">
									<strong><?php echo $block->title; ?></strong>
									<em class="help-block"><?php echo $block->description; ?></em>
								</div>
								<div class="btn-group pull-right">
									<?php echo $this->Html->link('', ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'edit', $block->id], ['title' => __d('block', 'Edit'), 'class' => 'btn btn-default btn-sm glyphicon glyphicon-pencil']); ?>
									<?php echo $this->Html->link('', ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'duplicate', $block->id], ['title' => __d('block', 'Duplicate'), 'class' => 'btn btn-default btn-sm glyphicon glyphicon-th-large']); ?>
									<?php if ($block->handler === 'Block'): ?>
										<?php echo $this->Html->link('', ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'delete', $block->id], ['title' => __d('block', 'Delete'), 'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash', 'confirm' => __d('block', 'Delete this block, are you sure?')]); ?>
									<?php endif; ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</p>

<?php echo $this->Html->script(['System.bootstrap.js', 'Jquery.jquery-ui.min.js', 'System.jquery.cookie.js', 'Block.collapse-ui.js']); ?>