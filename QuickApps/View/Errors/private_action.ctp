<!-- default error -->
<h2><?php echo __('Private Method in %s', $controller); ?></h2>
<p class="error">
	<strong><?php echo __('Error'); ?>: </strong>
	<?php echo __('%s%s cannot be accessed directly.', '<em>' . $controller . '::</em>', '<em>' . $action . '()</em>'); ?>
</p>

<p class="notice">
	<strong><?php echo __('Notice'); ?>: </strong>
	<?php echo __('If you want to customize this error message, create %s', APP_DIR . DS . 'View' . DS . 'Errors' . DS . 'private_action.ctp'); ?>
</p>

<p><?php echo __('<a href="%s">Go to home page</a>', Router::url('/')); ?></p>

<?php
if (Configure::read('debug')) {
	echo $this->element('exception_stack_trace');
}
?>