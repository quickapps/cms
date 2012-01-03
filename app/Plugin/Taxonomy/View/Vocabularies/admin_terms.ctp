<?php echo $this->Form->create(); ?>
    <!-- New Term -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-add_new_term_fieldset" style="cursor:pointer;">' . __t('Add New Term') . '</span>'); ?>
        <div id="add_new_term_fieldset" class="horizontalLayout" style="display:none;">
            <?php echo $this->Form->input('Term.name', array('required' => 'required', 'type' => 'text', 'label' => __t('Name *'))); ?>
            <?php echo $this->Form->input('Term.parent_id', array('type' => 'select', 'label' => __t('Parent term'), 'options' => $parents, 'escape' => false, 'empty' => __t('-- None --'))); ?>
            <?php echo $this->Form->input(__t('Save'), array('type' => 'submit', 'label' => false)); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>


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

<?php if (!empty($results)): ?>
    <?php //echo $this->Html->script('nestedSortable/jquery-1.5.2.min.js'); ?>
    <?php echo $this->Html->script('/menu/js/nestedSortable/jquery-ui-1.8.11.custom.min.js'); ?>
    <?php echo $this->Html->script('/menu/js/nestedSortable/jquery.ui.nestedSortable'); ?>
    <?php echo $this->Html->script('/system/js/json.js'); ?>

    <div id="menu-sortContainer">
        <?php echo $this->Tree->generate($results, array('class' => 'sortable', 'plugin' => 'taxonomy', 'element' => 'term_node', 'id' => 'termsList', 'model' => 'Term', 'alias' => 'title')); ?>
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
                    data: 'data[Term][sorting]=' + $.toJSON(arraied),
                    success: function() {
                        $('#saveStatus').text('<?php echo __t('Saved!'); ?>');
                    }
                });
            });
        });
    </script>
<?php endif; ?>
<script>
    $("#toggle-add_new_term_fieldset").click(function () {
        $("#add_new_term_fieldset").toggle('fast', 'linear');
    });
</script>