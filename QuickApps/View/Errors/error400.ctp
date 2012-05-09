<!-- default error 400 -->
<h2><?php echo $name; ?></h2>
<p class="error">
	<strong><?php echo __('Error'); ?>: </strong>
	<?php echo __('The requested address %s was not found. <br/> There are no translations available or you don\'t have sufficient permissions.', "<strong>'{$url}'</strong>"); ?>
	<p><?php echo __('<a href="%s">Go to home page</a>', Router::url('/')); ?></p>

	<script type="text/javascript">
		var GOOG_FIXURL_LANG = '<?php echo Configure::read('Config.language'); ?>';
		var GOOG_FIXURL_SITE = '<?php echo Router::url('/', true); ?>'
	</script>

	<script type="text/javascript" src="http://linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js"></script>
</p>
<?php
if (Configure::read('debug')) {
	echo $this->element('exception_stack_trace');
}
?>
