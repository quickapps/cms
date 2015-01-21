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

<?php echo $this->Form->create($user, ['id' => 'user-form']); ?>
	<?php echo $this->Flash->render('user_profile'); ?>
	<fieldset>
		<legend><?php echo __d('user', "Editing User's Information"); ?></legend>
		<?php echo $this->Form->input('name', ['label' => __d('user', 'Real name')]); ?>
		<em class="help-block"><?php echo __d('user', "User's real name, e.g. John Locke"); ?></em>

		<?php echo $this->Form->input('username', ['label' => __d('user', 'User Name'), 'disabled']); ?>
		<em class="help-block"><?php echo __d('user', 'Username cannot be changed, it is used only for identification purposes.'); ?></em>

		<?php echo $this->Form->input('email', ['label' => __d('user', 'e-Mail')]); ?>
		<em class="help-block"><?php echo __d('user', 'Must be unique.'); ?></em>

		<?php echo $this->Form->input('public_email', ['type' => 'checkbox', 'label' => __d('user', 'Public e-mail')]); ?>
		<em class="help-block"><?php echo __d('user', 'Other users can see your email in <a href="{0}">your profile page</a>.', $this->Url->build(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'profile', $user->id])); ?></em>

		<?php echo $this->Form->input('public_profile', ['type' => 'checkbox', 'label' => __d('user', 'Public profile')]); ?>
		<em class="help-block"><?php echo __d('user', 'Restrict the access to <a href="{0}">your profile page</a>.', $this->Url->build(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'profile', $user->id])); ?></em>

		<?php echo $this->Form->input('web', ['label' => __d('user', 'Website')]); ?>
		<em class="help-block"><?php echo __d('user', "User's website, e.g. http://john-locke.about.me"); ?></em>

		<?php echo $this->Form->input('locale', ['type' => 'select', 'options' => $languages, 'label' => __d('user', 'Preferred Language'), 'empty' => __d('user', 'Default')]); ?>
		<em class="help-block"><?php echo __d('user', "Preferred user's language"); ?></em>

		<?php echo $this->Form->input('password', ['type' => 'password', 'label' => __d('user', 'Password'), 'value' => false]); ?>
		<em class="help-block"><?php echo __d('user', 'At least six characters long.'); ?></em>

		<?php echo $this->Form->input('password2', ['type' => 'password', 'label' => __d('user', 'Confirm Password')]); ?>
		<em class="help-block"><?php echo __d('user', "Leave both fields empty if you do not need to change User's password."); ?></em>

		<?php if (isset($user->_fields) && $user->_fields->count()): ?>
		<hr />

		<fieldset>
			<legend><?php echo __d('user', 'Additional Information'); ?></legend>
			<?php foreach ($user->_fields as $field): ?>
				<?php echo $this->Form->input($field); ?>
			<?php endforeach; ?>
		</fieldset>
		<?php endif; ?>

		<?php echo $this->Form->submit(__d('user', 'Save Changes')); ?>
		<?php echo $this->Html->link(__d('user', 'Cancel my account'), ['plugin' => 'User', 'controller' => 'gateway', 'action' => 'cancel_request']); ?>
	</fieldset>
<?php echo $this->Form->end(); ?>