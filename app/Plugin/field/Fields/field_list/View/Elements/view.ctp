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
                echo "<h4 class=\"field-label\">{$data['label']}</h4> "; 
            break;
        }

        $fieldData = isset($data['FieldData']['data']) ? $data['FieldData']['data'] : '';
        $data = array('content' => $fieldData, 'options' => $data['settings']['options'], 'format' => $data['settings']['display'][$view_mode]);
        $html = $this->Layout->hook('field_list_formatter', $data, array('collectReturn' => false));

        echo $html;
    }