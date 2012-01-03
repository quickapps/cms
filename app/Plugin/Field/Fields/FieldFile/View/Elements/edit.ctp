<!-- Field File -->
<?php echo $this->Layout->hook('field_file_libs'); ?>
<?php  $multi = (isset($field['settings']['multi']) && $field['settings']['multi'] > 1); ?>

<div id="FieldDataFieldFile<?php echo $field['id']; ?>">
<?php
    echo $this->Html->useTag('fieldsetstart', $field['label']);

    if ($field['required']) {
        $options['required'] = 'required';
    }

    $field['FieldData'] = Set::merge(
        array(
            'id' => null,
            'field_id' => null,
            'foreignKey' => null,
            'belongsTo' => null,
            'data' => array(
                'file_name' => '',
                'file_path' => '',
                'description' => ''
            )
        ), @$field['FieldData']
    );

    $field['FieldData']['data'] = Set::merge(
        array(
            'files' => array(),
            'description' => ''
        ),
        (array)$field['FieldData']['data']
    );

    $uploaded_path = '[]';

    # form realoaded by validation error
    if (isset($this->data['FieldData']['FieldFile'][$field['id']]['data']['files'])) {
        $uploaded_path = $this->data['FieldData']['FieldFile'][$field['id']]['uploaded_path'];
        $field['FieldData']['data']['files'] = $this->data['FieldData']['FieldFile'][$field['id']]['data']['files'];
    }

    echo '<ul class="files-list">';

    foreach ($field['FieldData']['data']['files'] as $key => $file) {
        $file = Set::merge(
            array(
                'mime_icon' => '',
                'file_path' => '',
                'file_name' => '',
                'file_size' => ''
            ), (array)$file
        );

        echo "<li>";

        $uid = strtoupper(substr(md5($key . time()), 0, 8));
?>

    <div class="snippet" id="FieldDataFieldFile<?php echo $field['id']; ?>_<?php echo $uid; ?>">
        <img class="file-icon" src="<?php echo isset($file['mime_icon']) && !empty($file['mime_icon']) ? $this->Html->url('/field_file/img/icons/' . $file['mime_icon']) : ''; ?>" />
        <span class="file-name">
            <a href="<?php echo $this->Html->url("/files/{$field['settings']['upload_folder']}/{$file['file_name']}"); ?>" target="_blank">
                <?php echo @$file['file_name']; ?>
            </a>
        </span>
        <span class="file-size">(<?php echo @$file['file_size']; ?>)</span>
        <div class="submit"><input type="button" value="<?php echo __d('field_file', 'Remove'); ?>" onClick="QuickApps.field_file.remove('FieldDataFieldFile<?php echo $field['id']; ?>_<?php echo $uid; ?>'); return false;" /></div>

        <?php
            echo $this->Form->hidden("FieldData.FieldFile.{$field['id']}.data.files.{$key}.mime_icon", array('class' => 'mime_icon', 'value' => $file['mime_icon'])) . "\n";
            echo "\t" . $this->Form->hidden("FieldData.FieldFile.{$field['id']}.data.files.{$key}.file_name", array('class' => 'file_name', 'value' => $file['file_name'])) . "\n";
            echo "\t" . $this->Form->hidden("FieldData.FieldFile.{$field['id']}.data.files.{$key}.file_size", array('class' => 'file_size', 'value' => $file['file_size'])) . "\n";
        ?>

    </div>

<?php
        if (isset($field['settings']['description']) && $field['settings']['description']) {
            echo $this->Form->input("FieldData.FieldFile.{$field['id']}.data.files.{$key}.description", array('value' => @$file['description']));
        }

        echo '</li>';
    } // end foreach

    echo '</ul>';

    echo $this->Form->hidden("FieldData.FieldFile.{$field['id']}.id", array('value' => @$field['FieldData']['id']));
    echo $this->Form->hidden("FieldData.FieldFile.{$field['id']}.uploaded_path", array('value' => $uploaded_path));

    $show_uploader = (
        !count($field['FieldData']['data']['files']) ||
        ($multi && count($field['FieldData']['data']['files']) < $field['settings']['multi'])
    );
?>

    <div class="uploader <?php echo $multi ? 'multi-upload' : 'single-upload'; ?>" style="<?php echo $show_uploader ? '' : 'display:none;'; ?>">
        <?php echo $this->Form->input("FieldData.FieldFile.{$field['id']}.uploader", array('type' => 'file', 'label' => false)); ?>
        <em><?php echo __d('field_file', 'Files must be less than <b>%sB</b>.', ini_get('upload_max_filesize')) ; ?></em>
        <br />
        <em><?php echo __d('field_file', 'Allowed file types: <b>%s</b>.', str_replace(',', ', ', $field['settings']['extensions'])); ?></em>

        <div id="FieldQueue<?php echo $field['id']; ?>" class="field-queue"></div>
    </div>

<?php if (!empty($field['description'])): ?>
    <em><?php echo $field['description']; ?></em>
<?php endif; ?>

<?php echo $this->Html->useTag('fieldsetend'); ?>

</div>

<script type="text/javascript">
    $("ul.files-list").sortable({opacity: 0.6, cursor: 'move'});

    var Settings = new Array();
    Settings['fileExt'] = '*.<?php echo str_replace(',', ';*.', $field['settings']['extensions']); ?>';
    Settings['fileDesc'] = '<?php echo $field['label']; ?>';
    Settings['queueID'] = 'FieldQueue<?php echo $field['id']; ?>';
    Settings['upload_folder'] = '<?php echo @$field['settings']['upload_folder']; ?>';
    Settings['description'] = <?php echo isset($field['settings']['description']) && $field['settings']['description'] ? 'true' : 'false'; ?>;
    Settings['instance_id'] = <?php echo $field['id']; ?>;
    Settings['can_upload'] = <?php echo $field['settings']['multi'] - count($field['FieldData']['data']['files']); ?>;
    <?php if ($multi): ?>

    Settings['multi'] = true;
    Settings['queueSizeLimit'] = <?php echo $field['settings']['multi']; ?>;
    <?php else: ?>

    Settings['multi'] = false;
    Settings['queueSizeLimit'] = 1;
    <?php endif; ?>

    QuickApps.field_file.setupField('FieldDataFieldFile<?php echo $field['id']; ?>', Settings);
</script>