<?php
$tSettings = array(
    'columns' => array(
        '<input type="checkbox" onclick="QuickApps.checkAll(this);">' => array(
            'value' => '<input type="checkbox" name="data[Items][id][]" value="{Comment.id}">',
            'thOptions' => array('align' => 'center'),
            'tdOptions' => array('width' => '25', 'align' => 'center')
        ),
        __t('Subject') => array(
            'value' => '<a href="' . $this->Html->url('/admin/comment/list/view/') .'{Comment.id}">{Comment.subject}</a>',
            'sort' => 'Comment.subject'
        ),
        __t('Author') => array(
            'value' => '{php} return ("{Comment.name}" != "" ? "{Comment.name}" : "{User.name}"); {/php}',
            'sort' => 'User.name'
        ),
        __t('Posted in') => array(
            'value' => '<a href="' . $this->Html->url('/admin/node/contents/edit/') . '{Node.slug}">{Node.title}</a>',
            'sort' => 'Node.title'
        ),
        __t('Posted on') => array(
            'value' => '{php} return date("' . __t('Y/m/d - H:i') . '", {Comment.created}); {/php}',
            'sort' => 'Comment.created'
        )
    ),
    'noItemsMessage' => __t('There are no comments to display'),
    'paginate' => true,
    'headerPosition' => 'top',
    'tableOptions' => array('width' => '100%')    # table attributes
);
?>

<?php echo $this->Form->create(null, array('onsubmit' => 'return confirm("' . __t('Are you sure ?') . '");')); ?>
    <!-- Update -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-update_fieldset" style="cursor:pointer;">' . __t('Update Options') . '</span>'); ?>
        <div id="update_fieldset" class="horizontalLayout" style="<?php echo isset($this->data['Comment']['update']) ? '' : 'display:none;'; ?>">
            <?php 
                $options = array(
                    'approve' => __t('Approve selected comments'),
                    'unapprove' => __t('Unapprove selected comments'),
                    'delete' => __t('Delete selected comments')
                );

                if ($status == 'published') {
                    unset($options['approve']);
                } else {
                    unset($options['unapprove']);
                }

                echo $this->Form->input('Comment.update',
                    array(
                        'type' => 'select',
                        'label' => false,
                        'options' => $options
                    )
                );
            ?>
            <?php echo $this->Form->input(__t('Update'), array('type' => 'submit', 'label' => false)); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Html->table($results, $tSettings); ?>
<?php echo $this->Form->end(); ?>


<script type="text/javascript">
    $("#toggle-update_fieldset").click(function () {
        $("#update_fieldset").toggle('fast', 'linear');
    });
</script>