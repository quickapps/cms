<?php
$tSettings = array(
    'columns' => array(
        __t('Text') => array(
            'value' => '{Translation.original}',
            'sort' => 'Translation.original'
        ),
        __t('Actions') => array(
            'value' => "
                <a href='{url}/admin/locale/translations/edit/{Translation.id}{/url}'>" . __t('edit') . "</a>
                <a href='{url}/admin/locale/translations/delete/{Translation.id}{/url}'>" . __t('delete') . "</a>
            ",
            'thOptions' => array('align' => 'right'),
            'tdOptions' => array('align' => 'right')
        ),
    ),
    'noItemsMessage' => __t('There are no translations to display'),
    'paginate' => true,
    'headerPosition' => 'top',
    'tableOptions' => array('width' => '100%')    # table attributes
);
?>


<?php echo $this->Html->table($results, $tSettings);?>


<script>
    $("#toggle-filter_fieldset").click(function () {
        $("#filter_fieldset").toggle('fast', 'linear');
    });
</script>