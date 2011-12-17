<?php 
/**
 * Search results
 *
 * @package QuickApps.Plugin.Node.View
 * @author Christopher Castro
 */ 
?>

<?php echo $this->element('search_form'); ?>

<?php
    foreach ($Layout['node'] as $node) {
        echo $this->Layout->renderNode($node);
    }

    if (count($Layout['node'])):
?>
    <div class="nodes-pagination paginator">
        <?php $this->Paginator->options(array('url' => array('criteria' => $criteria))); ?>
        <?php echo $this->Paginator->prev(__d('node', '«'), null, null, array('class' => 'disabled')); ?>
        <?php echo $this->Paginator->numbers(array('separator' => ' ')); ?>
        <?php echo $this->Paginator->next(__d('node', '»'), null, null, array('class' => 'disabled')); ?>
    </div>
<?php else: ?>
    <div id="node-no-search-results" class="node node-page node-list node-demote node-nosticky node-odd ">
        <h1><?php echo __t('Your search yielded no results'); ?></h1>
        
        <ul>
            <li><?php echo __t('Check if your spelling is correct.'); ?></li>
            <li><?php echo __t('Remove quotes around phrases to search for each word individually. white cat will often show more results than "white cat".'); ?></li>
            <li><?php echo __t('Consider loosening your query with OR. white OR cat will often show more results than white cat.'); ?></li>
        </ul>
    </div>
<?php endif; ?>