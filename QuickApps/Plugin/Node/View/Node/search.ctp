<?php
/**
 * Search results
 *
 * @package QuickApps.View.Elements
 * @author Christopher Castro
 */
?>

<?php echo $this->element('theme_search_form'); ?>

<?php
	foreach ($Layout['node'] as $node) {
		echo $this->Node->render($node);
	}

	if (count($Layout['node'])):
?>
	<div class="nodes-pagination paginator">
		<?php echo $this->Paginator->prev(__t('«'), null, null, array('class' => 'disabled')); ?>
		<?php echo $this->Paginator->numbers(array('separator' => ' ')); ?>
		<?php echo $this->Paginator->next(__t('»'), null, null, array('class' => 'disabled')); ?>
	</div>
<?php else: ?>
	<div id="node-no-search-results" class="node node-page node-list node-demote node-nosticky node-odd ">
		<h1><?php echo __t('Your search yielded no results'); ?></h1>

		<?php
			echo $this->Html->nestedList(
			array(
				__t('Check if your spelling is correct.'),
				__t('Remove quotes around phrases to search for each word individually. white cat will often show more results than "white cat".'),
				__t('Consider loosening your query with OR. white OR cat will often show more results than white cat.')
			),
			array('id' => 'no-search-results-suggestions-list'));
		?>
	</div>
<?php endif; ?>