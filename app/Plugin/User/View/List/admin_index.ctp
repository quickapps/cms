<?php
$tSettings = array(
    'columns' => array(
        '<input type="checkbox" onclick="QuickApps.checkAll(this);">' => array(
            'value' => '<input type="checkbox" name="data[Items][id][]" value="{User.id}">',
            'thOptions' => array('align' => 'center'),
            'tdOptions' => array('width' => '25', 'align' => 'center')
        ),
        __t('User Name') => array(
            'value' => '{User.username}',
            'sort' => 'User.name'
        ),
        __t('Email') => array(
            'value' => '{User.email}',
            'tdOptions' => array('width' => '30%'),
            'sort' => 'User.email'
        ),
        __t('Roles') => array(
            'value' => '{php} return implode(", ", Set::extract("/Role/name", $row_data)); {/php}',
            'sort' => false
        ),
        __t('Actions') => array(
            'value' => "<a href='{url}/admin/user/list/edit/{User.id}{/url}'>" . __t('edit') . "</a>",
            'thOptions' => array('align' => 'right'),
            'tdOptions' => array('align' => 'right')
        ),
    ),
    'noItemsMessage' => __t('There are no users to display'),
    'paginate' => true,
    'headerPosition' => 'top',
    'tableOptions' => array('width' => '100%')    # table attributes
);
?>

<?php echo $this->Form->create(); ?>
    <!-- Filter -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-filter_fieldset" style="cursor:pointer;">' . __t('Filter Options') . '</span>'); ?>
        <div id="filter_fieldset" class="horizontalLayout" style="<?php echo isset($this->data['User']['filter']) ? '' : 'display:none;'; ?>">
            <?php echo $this->Form->input('User.filter.User|status',
                    array(
                        'type' => 'select',
                        'label' => __t('Status'),
                        'options' => array(
                            '' => '',
                            1 => __t('active'),
                            0 => __t('blocked')
                        )
                    )
                );
            ?>
            <?php echo $this->Form->input('User.filter.User|name LIKE',
                    array(
                        'type' => 'text',
                        'label' => __t('Name')
                    )
                );
            ?>

            <?php echo $this->Form->input('User.filter.User|email LIKE',
                    array(
                        'type' => 'text',
                        'label' => __t('Email')
                    )
                );
            ?>
            <?php echo $this->Form->input(__t('Filter'), array('type' => 'submit', 'label' => false)); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Form->create('User', array('onsubmit' => 'return confirm("' . __t('Are you sure about this changes ?') . '");')); ?>
    <!-- Update -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-update_fieldset" style="cursor:pointer;">' . __t('Update Options') . '</span>'); ?>
        <div id="update_fieldset" class="horizontalLayout" style="<?php echo isset($this->data['User']['update']) ? '' : 'display:none;'; ?>">
            <?php echo $this->Form->input('User.update',
                    array(
                        'type' => 'select',
                        'label' => false,
                        'options' => array(
                            'block' => __t('Block selected users'),
                            'unblock' => __t('Unblock selected users'),
                            'delete' => __t('Delete selected users')
                        )
                    )
                );
            ?>
            <?php echo $this->Form->input(__t('Update'), array('type' => 'submit', 'label' => false)); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Html->table($results, $tSettings);?>
<?php echo $this->Form->end(); ?>

<script>
    $("#toggle-update_fieldset").click(function () {
        $("#update_fieldset").toggle('fast', 'linear');
    });

    $("#toggle-filter_fieldset").click(function () {
        $("#filter_fieldset").toggle('fast', 'linear');
    });
</script>