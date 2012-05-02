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
                <a href='{url}/admin/user/display/field_formatter/{Field.id}/" . $viewMode . "{/url}'>" . __t('edit format') . "</a> |
                <a href='{url}/admin/field/handler/move/{Field.id}/up/" . $viewMode . "{/url}'>" . __t('move up') . "</a> |
                <a href='{url}/admin/field/handler/move/{Field.id}/down/" . $viewMode . "{/url}'>" . __t('move down') . "</a>",
            'thOptions' => array('align' => 'right'),
            'tdOptions' => array('align' => 'right')
        ),
    ),
    'noItemsMessage' => __t('There are no fields to display'),
    'paginate' => false,
    'headerPosition' => 'top',
    'tableOptions' => array('width' => '100%')
);
?>

<?php echo $this->Html->table(@Hash::sort((array)$result, "{n}.Field.settings.display.{$viewMode}.ordering", 'asc'), $tSettings); ?>

<?php if ($viewMode === 'default' && count($result)): ?>
    <p>
    <?php echo $this->Form->create('User', array('url' => "/admin/user/display/")); ?>
        <?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('View Modes') . '</span>'); ?>
            <div class="fieldset-toggle-container horizontalLayout" style="display:none;">
                <em><?php echo __t('Use custom display settings for the following view modes'); ?></em>
                <?php echo $this->Form->input('User.viewModes', array('type' => 'select', 'multiple' => 'checkbox', 'options' => array('user_profile' => __t('User profile')), 'label' => false)); ?>
            </div>
        <?php echo $this->Html->useTag('fieldsetend'); ?>
        <?php echo $this->Form->input(__t('Save'), array('type' => 'submit')); ?>
    <?php echo $this->Form->end(); ?>
    </p>
<?php endif; ?>