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

<?php if ($success): ?>
	<div class="alert alert-success clearfix">
		<p><?php echo __d('installer', '<strong>Congratulations!</strong> Your server meets the basic software requirements.'); ?></p>
		<p><?php echo $this->Html->link(__d('installer', 'Continue'), ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'license'], ['class' => 'btn btn-primary pull-right']); ?></p>
	</div>
<?php else: ?>
	<div class="alert alert-danger">
		<p><?php echo __d('installer', "<strong>Uh oh.</strong> There's a server compatibility issue. See below."); ?></p>
		<p>
			<ul>
				<?php foreach ($tests as $name => $testNode): ?>
					<?php if (!$testNode['assertTrue']): ?>
						<li><em><?php echo $testNode['message']; ?></em></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</p>
	</div>
<?php endif; ?>