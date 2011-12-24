<?php
    switch($display['label']) {
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
    $html = $fieldData;

    echo $html;