<?php use Cake\Core\Configure; ?>
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __('Error'); ?></h3>
	</div>
	<div class="panel-body">
		<p><?php echo $message; ?></p>
		<p><?php echo __('The requested address %s was not found on this server.', "<strong>'{$url}'</strong>"); ?></p>

		<?php if (Configure::read('debug') > 0): ?>
			<p><?php echo $this->element('exception_stack_trace'); ?></p>
		<?php endif; ?>
	</div>
</div>