<h2><?php echo __t('Help topics'); ?></h2>
<em><?php echo __t('Help is available on the following items'); ?></em>
<p>
	<ol style="list-style-type:upper-roman;">
	<?php foreach (Configure::read('Modules') as $plugin => $data): ?>
		<?php if (file_exists(App::pluginPath(Inflector::camelize($plugin)) . 'View' . DS . 'Elements' . DS . 'help.ctp')): ?>
			<li><a href="<?php echo $this->Html->url("/admin/system/help/module/{$plugin}"); ?>"><?php echo $data['yaml']['name']; ?></a></li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ol>
</p>