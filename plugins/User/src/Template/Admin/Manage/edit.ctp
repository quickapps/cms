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

<p><?php echo $this->element('User.index_submenu'); ?></p>

<?php echo $this->Form->create($user, ['id' => 'user-form']); ?>
	<fieldset>
		<legend><?php echo __d('user', "Editing User's Information"); ?></legend>
		<?php echo $this->Form->input('name', ['label' => __d('user', 'Real name')]); ?>
		<em class="help-block"><?php echo __d('user', "User's real name, e.g. John Locke"); ?></em>

		<?php echo $this->Form->input('username', ['label' => __d('user', 'User Name'), 'disabled']); ?>
		<em class="help-block"><?php echo __d('user', 'Username cannot be changed, it is used only for identification purposes.'); ?></em>

		<?php echo $this->Form->input('email', ['label' => __d('user', 'e-Mail')]); ?>
		<em class="help-block"><?php echo __d('user', 'Must be unique.'); ?></em>

		<?php echo $this->Form->input('web', ['label' => __d('user', 'Website')]); ?>
		<em class="help-block"><?php echo __d('user', "User's website, e.g. http://john-locke.about.me"); ?></em>

		<?php echo $this->Form->input('locale', ['type' => 'select', 'options' => $languages, 'label' => __d('user', 'Preferred Language'), 'empty' => __d('user', 'Default')]); ?>
		<em class="help-block"><?php echo __d('user', "Preferred user's language"); ?></em>

		<?php echo $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('user', 'Active')]); ?>
		<em class="help-block"><?php echo __d('user', 'Inactive users cannot log-in. Administrator users cannot be disabled.'); ?></em>

		<?php echo $this->Form->input('password', ['type' => 'password', 'label' => __d('user', 'Password'), 'value' => false]); ?>
		<em class="help-block"><?php echo __d('user', 'At least six characters long.'); ?></em>

		<?php echo $this->Form->input('password2', ['type' => 'password', 'label' => __d('user', 'Confirm Password')]); ?>
		<em class="help-block"><?php echo __d('user', "Leave both fields empty if you do not need to change User's password."); ?></em>

		<?php echo $this->Form->input('roles._ids', ['type' => 'select', 'options' => $roles, 'label' => __d('user', 'Roles'), 'multiple' => 'checkbox']); ?>

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
	</fieldset>
<?php echo $this->Form->end(); ?>