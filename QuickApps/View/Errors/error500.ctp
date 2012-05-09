<!-- default error 500 -->
<h2><?php echo $name; ?></h2>
<p class="error">
	<strong><?php echo __('Error'); ?>: </strong>
	<?php echo __('An Internal Error Has Occurred.'); ?>
	<p><?php echo __('<a href="%s">Go to home page</a>', Router::url('/')); ?></p>
</p>
<?php
if (Configure::read('debug')) {
	echo $this->element('exception_stack_trace');
}
?>