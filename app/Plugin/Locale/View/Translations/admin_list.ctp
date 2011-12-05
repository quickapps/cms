<?php
$tSettings = array(
    'columns' => array(
        __t('Text') => array(
            'value' => '{php} return $this->_View->Text->truncate("{Translation.original}"); {/php}',
            'sort' => 'Translation.original'
        ),
        __t('Actions') => array(
            'value' => "
                <a href='{url}/admin/locale/translations/edit/{Translation.id}{/url}'>" . __t('edit') . "</a> |
                <a href='{url}/admin/locale/translations/regenerate/{Translation.id}{/url}' title='" . __t('Regenerate translation cache') . "'>" . __t('regenerate') . "</a> |
                <a href='{url}/admin/locale/translations/delete/{Translation.id}{/url}' onclick='return confirm(\"" . __t('Delete this entry ?') . "\");'>" . __t('delete') . "</a>
            ",
            'thOptions' => array('align' => 'right'),
            'tdOptions' => array('align' => 'right')
        ),
    ),
    'noItemsMessage' => __t('There are no translations to display'),
    'paginate' => true,
    'headerPosition' => 'top',
    'tableOptions' => array('width' => '100%')
);
?>

<?php echo $this->Html->table($results, $tSettings);?>

<script>
    $("#toggle-filter_fieldset").click(function () {
        $("#filter_fieldset").toggle('fast', 'linear');
    });
</script>