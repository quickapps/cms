<?php $ckeditor = $field->metadata->settings->text_processing === 'full' ? 'ckeditor' : ''; ?>
<?php echo $this->Form->input($field, ['type'=> 'textarea', 'class' => $ckeditor]); ?>