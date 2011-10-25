<?php
    $view_mode = isset($data['settings']['display'][$Layout['viewMode']]) ? $Layout['viewMode'] : 'default';

    if ($data['settings']['display'][$view_mode]['type'] != 'hidden') {
        $label = $data['settings']['display'][$view_mode]['label'];

        switch($label) {
            case 'hidden':
                default:
                    echo '';
            break;

            case 'inline':
                echo "<h4 class=\"field-label\" style=\"display:inline;\">{$data['label']}</h4> ";
            break;

            case 'above':
                echo "<h4 class=\"field-label\" style=\"display:block;\">{$data['label']}</h4> ";
            break;
        }

        $fieldData = isset($data['FieldData']['data']['files']) ? $data['FieldData']['data'] : array('files' => array());
        $data = array('content' => $fieldData, 'settings' => $data['settings'], 'format' => $data['settings']['display'][$view_mode]);

        $html = $this->Layout->hook('field_file_formatter', $data, array('collectReturn' => false));

        echo $html;
    }