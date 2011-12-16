<!-- Field File -->
<?php
    # attach js & css files only if there is no file field instances yet
    if (!isset($this->__fileFieldCount) || $this->__fileFieldCount < 1) {
?>
    <link href="<?php echo $this->Html->url('/field_file/js/uploadify/uploadify.css'); ?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo $this->Html->url('/field_file/css/field_file.css'); ?>" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo $this->Html->url('/js/ui/jquery-ui.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo $this->Html->url('/js/json.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo $this->Html->url('/field_file/js/field_file.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo $this->Html->url('/field_file/js/locale.' . Configure::read('Variable.language.code') . '.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo $this->Html->url('/field_file/js/uploadify/swfobject.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo $this->Html->url('/field_file/js/uploadify/jquery.uploadify.v2.1.4.min.js'); ?>"></script>

    <script type="text/javascript">
        QuickApps.field_file.uploader = '<?php echo $this->Html->url('/field_file/js/uploadify/uploadify.swf'); ?>';
        QuickApps.field_file.session_id = '<?php echo CakeSession::id(); ?>';
        QuickApps.field_file.cancelImg = '<?php echo $this->Html->url('/field_file/js/uploadify/cancel.png'); ?>';
    </script>

<?php
        $this->__fileFieldCount++;
    }

    $multi = (isset($data['settings']['multi']) && $data['settings']['multi'] > 1);
?>

<div id="FieldDataFieldFile<?php echo $data['id']; ?>">
<?php
    echo $this->Html->useTag('fieldsetstart', ($data['required'] ? $data['label'] . ' *' : $data['label']));

    if ($data['required']) {
        $options['required'] = 'required';
    }

    $data['FieldData'] = Set::merge(
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
        ), @$data['FieldData']
    );

    $data['FieldData']['data'] = Set::merge(
        array(
            'files' => array(),
            'description' => ''
        ),
        (array)$data['FieldData']['data']
    );

    $uploaded_path = '[]';

    # form realoaded by validation error
    if (isset($this->data['FieldData']['FieldFile'][$data['id']]['data']['files'])) {
        $uploaded_path = $this->data['FieldData']['FieldFile'][$data['id']]['uploaded_path'];
        $data['FieldData']['data']['files'] = $this->data['FieldData']['FieldFile'][$data['id']]['data']['files'];
    }

    echo '<ul class="files-list">';

    foreach ($data['FieldData']['data']['files'] as $key => $file) {
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

    <div class="snippet" id="FieldDataFieldFile<?php echo $data['id']; ?>_<?php echo $uid; ?>">
        <img class="file-icon" src="<?php echo isset($file['mime_icon']) && !empty($file['mime_icon']) ? $this->Html->url('/field_file/img/icons/' . $file['mime_icon']) : ''; ?>" />
        <span class="file-name">
            <a href="<?php echo $this->Html->url("/files/{$data['settings']['upload_folder']}/{$file['file_name']}"); ?>" target="_blank">
                <?php echo @$file['file_name']; ?>
            </a>
        </span>
        <span class="file-size">(<?php echo @$file['file_size']; ?>)</span>
        <div class="submit"><input type="button" value="<?php echo __d('field_file', 'Remove'); ?>" onClick="QuickApps.field_file.remove('FieldDataFieldFile<?php echo $data['id']; ?>_<?php echo $uid; ?>'); return false;" /></div>

        <?php
            echo $this->Form->hidden("FieldData.FieldFile.{$data['id']}.data.files.{$key}.mime_icon", array('class' => 'mime_icon', 'value' => $file['mime_icon'])) . "\n";
            echo "\t" . $this->Form->hidden("FieldData.FieldFile.{$data['id']}.data.files.{$key}.file_name", array('class' => 'file_name', 'value' => $file['file_name'])) . "\n";
            echo "\t" . $this->Form->hidden("FieldData.FieldFile.{$data['id']}.data.files.{$key}.file_size", array('class' => 'file_size', 'value' => $file['file_size'])) . "\n";
        ?>

    </div>

<?php
        if (isset($data['settings']['description']) && $data['settings']['description']) {
            echo $this->Form->input("FieldData.FieldFile.{$data['id']}.data.files.{$key}.description", array('value' => @$file['description']));
        }

        echo '</li>';
    } // end foreach

    echo '</ul>';

    echo $this->Form->hidden("FieldData.FieldFile.{$data['id']}.id", array('value' => @$data['FieldData']['id']));
    echo $this->Form->hidden("FieldData.FieldFile.{$data['id']}.uploaded_path", array('value' => $uploaded_path));

    $show_uploader = (
        !count($data['FieldData']['data']['files']) || 
        ($multi && count($data['FieldData']['data']['files']) < $data['settings']['multi'])
    );
?>

    <div class="uploader <?php echo $multi ? 'multi-upload' : 'single-upload'; ?>" style="<?php echo $show_uploader ? '' : 'display:none;'; ?>">
        <?php echo $this->Form->input("FieldData.FieldFile.{$data['id']}.uploader", array('type' => 'file', 'label' => false)); ?>
        <em><?php echo __d('field_file', 'Files must be less than <b>%sB</b>.', ini_get('upload_max_filesize')) ; ?></em>
        <br />
        <em><?php echo __d('field_file', 'Allowed file types: <b>%s</b>.', str_replace(',', ', ', $data['settings']['extensions'])); ?></em>

        <div id="FieldQueue<?php echo $data['id']; ?>" class="field-queue"></div>
    </div>

<?php if (!empty($data['description'])): ?>
    <em><?php echo $this->Layout->hooktags($data['description']); ?></em>
<?php endif; ?>

<?php echo $this->Html->useTag('fieldsetend'); ?>

</div>

<script type="text/javascript">
    $("ul.files-list").sortable({opacity: 0.6, cursor: 'move'});

    var Settings = new Array();
    Settings['fileExt'] = '*.<?php echo str_replace(',', ';*.', $data['settings']['extensions']); ?>';
    Settings['fileDesc'] = '<?php echo $data['label']; ?>';
    Settings['queueID'] = 'FieldQueue<?php echo $data['id']; ?>';
    Settings['upload_folder'] = '<?php echo @$data['settings']['upload_folder']; ?>';
    Settings['description'] = <?php echo isset($data['settings']['description']) && $data['settings']['description'] ? 'true' : 'false'; ?>;
    Settings['instance_id'] = <?php echo $data['id']; ?>;
    Settings['can_upload'] = <?php echo $data['settings']['multi'] - count($data['FieldData']['data']['files']); ?>;
    <?php if ($multi): ?>

    Settings['multi'] = true;
    Settings['queueSizeLimit'] = <?php echo $data['settings']['multi']; ?>;
    <?php else: ?>
    
    Settings['multi'] = false;
    Settings['queueSizeLimit'] = 1;
    <?php endif; ?>

    QuickApps.field_file.setupField('FieldDataFieldFile<?php echo $data['id']; ?>', Settings);
</script>