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

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
?>

<?php
	$count = intval(Configure::read('_taggerWidgetInstancesCount'));
	$tokenLimit = '';
	$prePopulate = [];
	$terms = TableRegistry::get('Taxonomy.Terms')
		->find()
		->select(['id', 'name'])
		->where(['id IN' => (array)$field->extra])
		->all();
	foreach ($terms as $term) {
		$prePopulate[] = "{id: {$term->id}, name: \"{$term->name}\"}";
	}

	$prePopulate = "\n " . implode(",\n ", $prePopulate) . "\n ";
	if (intval($field->metadata->settings['max_values'])) {
		$tokenLimit = "tokenLimit: {$field->metadata->settings['max_values']},";
	}
?>

<?php if (!$count): ?>
	<?php echo $this->Html->css('Taxonomy.token-input.css'); ?>
	<?php echo $this->Html->css('Taxonomy.token-input-facebook.css'); ?>
	<?php echo $this->Html->script('Taxonomy.jquery.tokeninput.js'); ?>
<?php endif; ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#<?php echo $fieldId; ?>').tokenInput('<?php echo $this->Url->build(['plugin' => 'Taxonomy', 'controller' => 'tagger', 'action' => 'search', $field->metadata->settings['vocabulary']], true); ?>', {
			allowNewItems: true,
			hintText: '<?php echo __d('taxonomy', 'Type in a search term'); ?>',
			noResultsText: '<?php echo __d('taxonomy', 'No results'); ?>',
			searchingText: '<?php echo __d('taxonomy', 'Searching...'); ?>',
			deleteText: '<?php echo __d('taxonomy', 'x'); ?>',
			<?php echo $tokenLimit; ?>
			theme: 'facebook',
			preventDuplicates: true,
			prePopulate: [<?php echo $prePopulate; ?>]
		});
	});
</script>
<?php Configure::write('_taggerWidgetInstancesCount', $count); ?>