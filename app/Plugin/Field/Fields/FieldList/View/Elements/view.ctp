<?php
    if (isset($field['settings']['options']) &&
        !empty($field['settings']['options'])
    ) {
        switch($display['label']) {
            case 'inline':
                echo "<h4 class=\"field-label\" style=\"display:inline;\">{$field['label']}</h4> ";
            break;

            case 'above':
                echo "<h4 class=\"field-label\">{$field['label']}</h4> ";
            break;
        }

        $formatter_data = array(
            'content' => (isset($field['FieldData']['data']) ? $field['FieldData']['data'] : ''),
            'options' => $field['settings']['options'],
            'format' => $display
        );
        $html = $this->Layout->hook('field_list_formatter', $formatter_data, array('collectReturn' => false));

        echo $html;
    }