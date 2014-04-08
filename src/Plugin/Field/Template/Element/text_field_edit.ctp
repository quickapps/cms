<?php $type = !empty($field->metadata->settings->type) ? $field->metadata->settings->type : 'text'; ?>
<?php $ckeditor = $field->metadata->settings->text_processing === 'full' && $type === 'textarea' ? 'ckeditor' : ''; ?>
<?php echo $this->Form->input($field, ['type'=> $type, 'class' => $ckeditor]); ?>