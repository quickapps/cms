<h1><?php echo __t('Finish'); ?>: <?php echo __t('Finishing Installation'); ?></h1>
<p>
	<?php if ($error = $this->Layout->sessionFlash()): ?>
	<div class="content-box content-box-error">
		<?php echo $error; ?>
	</div>
	<?php endif; ?>
</p>