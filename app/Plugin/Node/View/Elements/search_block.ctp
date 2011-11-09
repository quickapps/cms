<?php 
/**
 * This element is rendered by NodeHookHelper::node_search().
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
            'onSubmit' => "QuickApps.doSearch(); return false;"
        )
            
    );
?>
    <?php echo $this->Form->input('criteria', array('required' => 'required', 'type' => 'text', 'label' => __d('node', 'Keywords'))); ?>
    <?php echo $this->Form->submit(__d('node', 'Search')); ?>
<?php echo $this->Form->end(); ?>

<script type="text/javascript">
    QuickApps.doSearch = function () {
        $(location).attr('href', 
            QuickApps.settings.base_url + 's/<?php echo $prefix; ?>' + decodeURIComponent($('#SearchCriteria').val())
        );
    };

    QuickApps.__searchCriteria = '<?php echo @$criteria; ?>';

    $(document).ready(function (){
        $('#SearchCriteria').focus(function () {
            if ($(this).val() == QuickApps.__searchCriteria) {
                $(this).val('');
            }
        });

        $('#SearchCriteria').blur(function () {
            if ($.trim($(this).val()) == '') {
                $(this).val(QuickApps.__searchCriteria);
            }
        });
    });
</script>