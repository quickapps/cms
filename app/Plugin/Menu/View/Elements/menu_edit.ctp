<style>
    #menu-sortContainer ul {
        margin: 0;
        padding: 0;
        padding-left: 30px;
    }

    #menu-sortContainer ul.sortable, #menu-sortContainer ul.sortable ul {
        margin: 0 0 0 25px;
        padding: 0;
        list-style-type: none;
    }

    #menu-sortContainer ul.sortable {
        margin: 0 0 2em 0;
    }

    #menu-sortContainer .sortable li {
        margin: 7px 0 0 0;
        padding: 0;
    }

    #menu-sortContainer .sortable li div  {
        border: 1px solid #ccc;
        padding: 3px;
        margin: 0;
        cursor: move;
    }

    #menu-sortContainer .placeholder {
        background-color: #cfcfcf;
        padding:15px;
    }
</style>
<?php //echo $this->Html->script('nestedSortable/jquery-1.5.2.min.js'); ?>
<?php echo $this->Html->script('/menu/js/nestedSortable/jquery-ui-1.8.11.custom.min.js'); ?>
<?php echo $this->Html->script('/menu/js/nestedSortable/jquery.ui.nestedSortable'); ?>
<?php echo $this->Html->script('/system/js/json.js'); ?>

<div id="menu-sortContainer">
    <?php echo $this->Tree->generate($links, array('class' => 'sortable', 'plugin' => 'menu', 'element' => 'menu_link_node', 'id' => 'menuLinks', 'model' => 'MenuLink', 'alias' => 'link_title')); ?>
</div>

<?php echo $this->Form->submit(__t('Save changes'), array('id' => 'saveChanges')); ?>
<span id="saveStatus">&nbsp;</span>

<script>
    $(document).ready(function() {
        $('ul.sortable').nestedSortable({
            listType: 'ul',
            disableNesting: 'no-nest',
            forcePlaceholderSize: true,
            handle: 'div',
            helper:    'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div'
        });

        $('#saveChanges').click(function(e) {
            $('#saveStatus').text('<?php echo __t('Saving...'); ?>');
            arraied = $('ul.sortable').nestedSortable('toArray', {startDepthCount: 0});
            $.ajax({
                type: 'POST',
                url: QuickApps.settings.url,
                data: 'data[MenuLink]=' + $.toJSON(arraied),
                success: function() {
                    $('#saveStatus').text('<?php echo __t('Saved!'); ?>');
                }
            });
        });
    });

</script>