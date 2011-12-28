<?php
    switch($display['label']) {
        case 'inline':
            echo "<h4 class=\"field-label\" style=\"display:inline;\">{$data['label']}</h4> ";
        break;

        case 'above':
            echo "<h4 class=\"field-label\" style=\"display:block;\">{$data['label']}</h4> ";
        break;
    }

    $fieldData = isset($data['FieldData']['data']) ? $data['FieldData']['data'] : '';
    $data = array(
        'content' => $fieldData,
        'settings' => $data['settings'],
        'format' => $display
    );
    $html = $this->Layout->hook('field_text_formatter', $data, array('collectReturn' => false));

    echo $html;