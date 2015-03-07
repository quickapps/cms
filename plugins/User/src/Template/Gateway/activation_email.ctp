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

<?php echo $this->Flash->render('activation_email'); ?>

<?php if (!$sent): ?>
	<?php echo $this->Form->create(null); ?>
		<?php echo $this->Form->input('username', ['label' => __d('user', 'Username or e-mail')]); ?>
		<?php echo $this->Form->submit(__d('user', 'Send Instructions')); ?>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>