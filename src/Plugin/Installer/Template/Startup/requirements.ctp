<?php if ($success): ?>
	<div class="alert alert-success clearfix">
		<p><?php echo __("<strong>Congratulations!</strong> Your server meets the basic software requirements."); ?></p>
		<p><?php echo $this->Html->link(__('Continue'), ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'license'], ['class' => 'btn btn-primary pull-right']); ?></p>
	</div>
<?php else: ?>
	<div class="alert alert-danger">
		<p><?php echo __("<strong>Uh oh.</strong> There's a server compatibility issue. See below."); ?></p>
		<p>
			<ul>
				<?php foreach ($tests as $name => $testNode): ?>
					<?php if (!$testNode['test']): ?>
						<li><em><?php echo $testNode['msg']; ?></em></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</p>
	</div>
<?php endif; ?>