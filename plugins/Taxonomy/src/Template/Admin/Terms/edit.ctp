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

<?php echo $this->Form->create($term); ?>
	<fieldset>
		<legend><?php echo __d('taxonomy', "Editing Vocabulary's Term"); ?></legend>

		<?php echo $this->Form->label(__d('taxonomy', "Term's Name *")); ?>
		<div class="input-group">
			<?php echo $this->Form->input('name', ['label' => false]); ?>
			<span class="input-group-addon"><?php echo __d('taxonomy', 'Slug: {0}', $term->slug); ?></span>
		</div>
		<em class="help-block"><?php echo __d('taxonomy', 'The name for this term. e.g. "cat", "dog" or "bird" for the "animals" vocabulary.'); ?></em>

		<?php echo $this->Form->submit(__d('taxonomy', 'Save Changes')); ?>
	</fieldset>
<?php echo $this->Form->end(); ?>