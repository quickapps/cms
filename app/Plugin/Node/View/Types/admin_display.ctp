<?php
$tSettings = array(
    'columns' => array(
        __t('Name') => array(
            'value' => '{Field.label}',
            'tdOptions' => array('width' => '15%')
        ),
        __t('Label') => array(
            'value' => '{Field.settings.display.' . $viewMode . '.label}'
        ),
        __t('Format') => array(
            'value' => '{Field.settings.display.' . $viewMode . '.type}'
        ),
        __t('Actions') => array(
            'value' => "
                <a href='{url}/admin/node/types/field_formatter/{Field.id}/" . $viewMode . "{/url}'>" . __t('edit format') . "</a> |
                <a href='{url}/admin/field/handler/move/{Field.id}/up/" . $viewMode . "{/url}'>" . __t('move up') . "</a> |
                <a href='{url}/admin/field/handler/move/{Field.id}/down/" . $viewMode . "{/url}'>" . __t('move down') . "</a>",
            'thOptions' => array('align' => 'right'),
            'tdOptions' => array('align' => 'right')
        ),
    ),
    'noItemsMessage' => __t('There are no fields to display'),
    'paginate' => false,
    'headerPosition' => 'top',
    'tableOptions' => array('width' => '100%')    # table attributes
);
?>

<p><?php echo __t('Content items can be displayed using different view modes: Full, List, RSS, Print, etc. List is a short format that is typically used in lists of multiple content items. Full content is typically used when the content is displayed on its own page.'); ?></p>
<p><?php echo __t("Here, you can define which fields are shown and hidden when <em>%s</em> content is displayed in each view mode, and define how the fields are displayed in each view mode.", $this->data['NodeType']['name']); ?></p>

<?php echo $this->Html->table(@Set::sort((array)$result, "{n}.Field.settings.display.{$viewMode}.ordering", 'asc'), $tSettings); ?>

<?php if ($viewMode === 'default' && count($result)): ?>
    <p>
        <?php echo $this->Form->create('NodeType', array('url' => "/admin/node/types/display/{$typeId}")); ?>
            <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-viewModes_fieldset" style="cursor:pointer;">' . __t('View Modes') . '</span>'); ?>
                <div id="viewModes_fieldset" class="horizontalLayout" style="display:none;">
                    <em><?php echo __t('Use custom display settings for the following view modes'); ?></em>
                    <?php echo $this->Form->input('NodeType.viewModes', array('type' => 'select', 'multiple' => 'checkbox', 'options' => array('full' => __t('full'), 'list' => __t('list'), 'rss' => __t('rss'), 'print' => __t('print')), 'label' => false)); ?>
                </div>
            <?php echo $this->Html->useTag('fieldsetend'); ?>
            <?php echo $this->Form->input(__t('Save'), array('type' => 'submit')); ?>
        <?php echo $this->Form->end(); ?>
    </p>

    <script type="text/javascript">
        $("#toggle-viewModes_fieldset").click(function () {
            $("#viewModes_fieldset").toggle('fast', 'linear');
        });
    </script>
<?php endif; ?>