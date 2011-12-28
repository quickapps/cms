<?php
    switch($display['label']) {
        case 'inline':
            echo "<h4 class=\"field-label\" style=\"display:inline;\">{$data['label']}</h4> ";
        break;

        case 'above':
            echo "<h4 class=\"field-label\" style=\"display:block;\">{$data['label']}</h4> ";
        break;
    }

    $fieldData = isset($data['FieldData']['data']['files']) ? $data['FieldData']['data'] : array('files' => array());
    $data = array(
        'content' => $fieldData,
        'settings' => $data['settings'],
        'format' => $display
    );
    $html = $this->Layout->hook('field_file_formatter', $data, array('collectReturn' => false));

    echo $html;