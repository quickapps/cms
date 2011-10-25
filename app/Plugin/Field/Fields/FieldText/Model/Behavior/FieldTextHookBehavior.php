<?php
class FieldTextHookBehavior extends ModelBehavior {

    public function field_text_beforeSaveInstance(&$Model) {
        if (!isset($Model->data['Field']['id']) || empty($Model->data['Field']['id'])) {
            $__default = array(
                'type' => 'text',
                'text_processing' => 'plain'
            );

            $Model->data['Field']['settings'] = Set::merge($__default, $Model->data['Field']['settings']);
        }

        return true;
    }

    public function field_text_beforeSave($info) {
        return true;
    }

    public function field_text_afterSave($info) {
        if (empty($info)) {
            return true;
        }
        
        $field = ClassRegistry::init('Field.Field')->findById($info['field_id']);
        
        if (isset($field['Field']['settings']['text_processing']) && !empty($field['Field']['settings']['text_processing'])) {
            $info['Model']->hook('text_processing_' . $field['Field']['settings']['text_processing'], $info['data']);
        }

        $info['id'] =  empty($info['id']) || !isset($info['id']) ? null : $info['id'];
        $data['FieldData'] = array(
            'id' => $info['id'], # update or create
            'field_id' => $info['field_id'],
            'data' => $info['data'],
            'belongsTo' => $info['Model']->name,
            'foreignKey' => $info['Model']->id
        );

        ClassRegistry::init('Field.FieldData')->save($data);

        return true;
    }

    public function field_text_afterFind(&$data) {
        $data['field']['FieldData'] = ClassRegistry::init('Field.FieldData')->find('first',
            array(
                'conditions' => array(
                    'FieldData.field_id' => $data['field']['id'],
                    'FieldData.belongsTo' => $data['belongsTo'],
                    'FieldData.foreignKey' => $data['foreignKey']
                )
            )
        );

        $data['field']['FieldData'] = Set::extract('/FieldData/.', $data['field']['FieldData']);
        $data['field']['FieldData'] = isset($data['field']['FieldData'][0]) ? $data['field']['FieldData'][0] : $data['field']['FieldData'];

        return;
    }

    public function field_text_beforeValidate($info) {
        $FieldInstance = ClassRegistry::init('Field.Field')->findById($info['field_id']);
        $errMsg = array();

        if (isset($FieldInstance['Field']['settings']['type']) &&
            $FieldInstance['Field']['settings']['type'] == 'text' &&
            isset($FieldInstance['Field']['settings']['max_len']) &&
            !empty($FieldInstance['Field']['settings']['max_len']) &&
            $FieldInstance['Field']['settings']['max_len'] > 0 &&
            strlen(trim($info['data'])) > $FieldInstance['Field']['settings']['max_len']
        ) {
            $errMsg[] = __d('field_text', 'Max. %s characters length', $FieldInstance['Field']['settings']['max_len']);
        }

        if ($FieldInstance['Field']['required'] == 1) {
            if (isset($FieldInstance['Field']['settings']['type']) && $FieldInstance['Field']['settings']['type'] == 'textarea') {
                $filtered = html_entity_decode(strip_tags($info['data']));
            } else {
                $filtered = strip_tags($info['data']);
            }

            if (empty($filtered)) {
                $errMsg[] = __d('field_text', 'Field required');
            }
        }

        if (isset($FieldInstance['Field']['settings']['validation_rule']) && !empty($FieldInstance['Field']['settings']['validation_rule'])) {
            if (!preg_match($FieldInstance['Field']['settings']['validation_rule'], $info['data'])) {
                if (isset($FieldInstance['Field']['settings']['validation_message']) && !empty($FieldInstance['Field']['settings']['validation_rule'])) {
                    $errMsg[] = __t($FieldInstance['Field']['settings']['validation_message']);
                } else {
                    $errMsg[] = __d('field_text', 'Invalid field');
                }
            }
        }

        if (!empty($errMsg)) {
            ClassRegistry::init('Field.FieldData')->invalidate(
                "field_text.{$info['field_id']}.data",
                implode(", ", $errMsg)
            );

            return false;
        }

        return true;
    }

    public function field_text_beforeDelete($info) {
        return true;
    }

    public function field_text_afterDelete($info) {
        ClassRegistry::init('Field.FieldData')->deleteAll(
            array(
                'FieldData.belongsTo' => $info['Model']->name,
                'FieldData.field_id' => $info['field_id'],
                'FieldData.foreignKey' => $info['Model']->id
            )
        );

        return true;
    }

    public function field_text_afterDeleteInstance($FieldModel) {
        ClassRegistry::init('Field.FieldData')->deleteAll(
            array(
                'FieldData.field_id' => $FieldModel->data['Field']['id']
            )
        );
    }

    // convert all to plain text
    public function text_processing_plain(&$text) {
        App::import('Lib', 'FieldText.Html2text');

        $h2t = new html2text($text);
        $text = $h2t->get_text();
    }
    
    // filter forbidden tags
    public function text_processing_filtered(&$text) {
        $text = strip_tags($text, '<a><em><strong><cite><blockquote><code><ul><ol><li><dl><dt><dd>');
    }

    // make safe for for View hook `text_processing_markdown()`
    public function text_processing_markdown(&$text) {
        App::import('Lib', 'FieldText.Html2text');

        $h2t = new html2text($text);
        $text = $h2t->get_text();
    }

    public function text_processing_full(&$text) {
        return;
    }
}