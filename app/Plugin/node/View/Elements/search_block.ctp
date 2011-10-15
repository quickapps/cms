<?php 
/**
 * Search form for block hook().
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */
?>

<?php echo $this->Form->create('Search', array('url' => '/s/', 'type' => 'GET')); ?>
    <?php echo $this->Form->input('criteria', array('required' => 'required', 'type' => 'text', 'label' => __d('node', 'Keywords'))); ?>
    <?php echo $this->Form->submit(__d('node', 'Search')); ?>
<?php echo $this->Form->end(); ?>