<h1><?php echo __t('Installation'); ?>: <?php echo __t('Running server test...'); ?></h1>
<p>
	<?php if ($success): ?>
		<p class="success"><?php echo __t("Congratulations! Your server meets the basic software requirements."); ?> <?php echo $this->Html->image('/system/img/accept.png', array('align' => 'right')); ?></p>
		<form action="" method="post">
		<input type="hidden" name="data[Test]" value="1" />
			<fieldset class="install-button">
				<input class="submit" type="submit" value="<?php echo __t('Continue'); ?>">
			</fieldset>
		</form>
	<?php else: ?>
		<p><?php echo __t("Uh oh. There's a server compatibility issue. See below."); ?> <?php echo $this->Html->image('/system/img/error.png', array('align' => 'right')); ?></p>
		<p>
			<ul>
				<?php foreach ($tests as $name => $testNode): ?>
					<?php if (!$testNode['test']): ?>
						<li><em><?php echo $testNode['msg']; ?></em><br/></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</p>
	<?php endif; ?>
</p>