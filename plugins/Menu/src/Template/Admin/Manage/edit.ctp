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

<?php echo $this->Form->create($menu); ?>
	<fieldset>
		<legend><?php echo __d('menu', 'Editing Menu'); ?></legend>

		<?php echo $this->Form->input('title', ['label' => __d('menu', 'Title *')]); ?>
		<?php echo $this->Form->input('description', ['label' => __d('menu', 'Description')]); ?>

		<?php if ($menu->handler !== 'Menu'): ?>
			<?php $result = $this->trigger("Menu.{$menu->handler}.settings", $menu)->result; ?>
			<?php if (!empty($result)): ?>
			<hr />
				<?php echo $result; ?>
			<hr />
			<?php endif; ?>
		<?php endif; ?>

		<?php echo $this->Form->submit(__d('menu', 'Save Changes')); ?>
	</fieldset>
<?php echo $this->Form->end(); ?>