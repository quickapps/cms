<?php 
/**
 * Search form for block hook().
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */
?>

<?php 
    $prefix = '';

    if (isset($data['Block']['settings']['url_prefix']) && !empty($data['Block']['settings']['url_prefix'])) {
        $prefix = trim($data['Block']['settings']['url_prefix']) . ' ';
    }

    echo $this->Form->create('Search', 
        array(
            'url' => '/s/', 
            'onSubmit' => "$(location).attr('href', QuickApps.settings.base_url + 's/{$prefix}' + decodeURIComponent($('#SearchCriteria').val())); return false;"
        )
            
    );
?>
    <?php echo $this->Form->input('criteria', array('required' => 'required', 'type' => 'text', 'label' => __d('node', 'Keywords'))); ?>
    <?php echo $this->Form->submit(__d('node', 'Search')); ?>
<?php echo $this->Form->end(); ?>