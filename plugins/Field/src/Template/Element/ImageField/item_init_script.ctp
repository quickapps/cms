FileField.init({
    instance: {
        id: <?php echo $field->metadata->field_instance_id; ?>,
        name: '<?php echo $field->name; ?>',
        showDescription: false,
        showThumbnail: <?php echo !empty($field->metadata->settings['preview']) ? 'true' : 'false'; ?>,
        showTitle: <?php echo !empty($field->metadata->settings['title_attr']) ? 'true' : 'false'; ?>,
        showAlt: <?php echo !empty($field->metadata->settings['alt_attr']) ? 'true' : 'false'; ?>,
        thumbnailSize: '<?php echo $field->metadata->settings['preview']; ?>',
        itemFormatter: 'imageFieldItemFormatter',
    },
    uploader: {
        buttonText: '<?php echo __d('field', 'Upload Image'); ?>',
        uploadLimit:  <?php echo $field->metadata->settings['multi'] - count((array)$field->raw); ?>,
        uploader: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'upload', 'prefix' => false, $field->name], true); ?>',
        remover: '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'delete', 'prefix' => false, $field->name], true); ?>',
        errorMessages: {
            502: 'The file {{file.name}} could not be uploaded: invalid image given.'
        }
    }
});
