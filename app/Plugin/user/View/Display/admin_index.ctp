<?php
$tSettings = array(
    'columns' => array(
        __t('Name') => array(
            'value' => '{Field.label}',
            'tdOptions' => array('width' => '15%')
        ),
        __t('Label') => array(
            'value' => '{Field.settings.display.' . $view_mode . '.label}'
        ),
        __t('Format') => array(
            'value' => '{Field.settings.display.' . $view_mode . '.type}'
        ),
        __t('Actions') => array(
            'value' => "
                <a href='{url}/admin/user/display/field_formatter/{Field.id}/" . $view_mode . "{/url}'>" . __t('edit format') . "</a> |
                <a href='{url}/admin/field/handler/move/{Field.id}/up/" . $view_mode . "{/url}'>" . __t('move up') . "</a> |
                <a href='{url}/admin/field/handler/move/{Field.id}/down/" . $view_mode . "{/url}'>" . __t('move down') . "</a>",
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

<?php echo $this->Html->table(@Set::sort((array)$result, "{n}.Field.settings.display.{$view_mode}.ordering", 'asc'), $tSettings); ?>

<?php if ($view_mode === 'default' && count($result)): ?>
    <p>
    <?php echo $this->Form->create('User', array('url' => "/admin/user/display/")); ?>
        <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-viewModes_fieldset" style="cursor:pointer;">' . __t('View Modes') . '</span>'); ?>
            <div id="viewModes_fieldset" class="horizontalLayout" style="display:none;">
                <em><?php echo __t('Use custom display settings for the following view modes'); ?></em>
                <?php echo $this->Form->input('User.viewModes', array('type' => 'select', 'multiple' => 'checkbox', 'options' => array('user_account' => __t('User account')), 'label' => false)); ?>
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