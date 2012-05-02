<?php
    echo $this->Form->input('Field.settings.extensions',
        array(
            'type' => 'text',
            'label' => __t('Allowed extensions')
        )
    );
?>
<em><?php echo __t('Comma separated. e.g.: jpg,gif,png'); ?></em>

<?php
    echo $this->Form->input('Field.settings.multi',
        array(
            'type' => 'select',
            'options' => Hash::combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), '{n}', '{n}'),
            'label' => __t('Number of files')
        )
    );
?>
<em><?php echo __t('Maximum number of files users can upload for this field.'); ?></em>

<?php
    echo $this->Form->input('Field.settings.upload_folder',
        array(
            'type' => 'text',
            'label' => __t('Upload folder')
        )
    );
?>
<em><?php echo __t('Optional subdirectory within the upload destination where files will be stored. Do not include preceding or trailing slashes.'); ?></em>

<?php
    echo $this->Form->input('Field.settings.description',
        array(
            'type' => 'checkbox',
            'label' => __t('Enable Description field')
        )
    );
?>
<em><?php echo __t('The description field allows users to enter a description about the uploaded file.'); ?></em>