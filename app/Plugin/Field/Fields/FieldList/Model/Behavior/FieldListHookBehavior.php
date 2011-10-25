<?php
class FieldListHookBehavior extends ModelBehavior {

    function field_list_beforeSave($info) {
        return true;
    }

    function field_list_afterSave($info) {
        if (empty($info)) {
            return true;
        }

        $info['id'] =  empty($info['id']) || !isset($info['id']) ? null : $info['id'];
        $data['FieldData'] = array(
            'id' => $info['id'], # update or create
            'field_id' => $info['field_id'],
            'data' => implode('|', (array)$info['data']),
            'belongsTo' => $info['Model']->name,
            'foreignKey' => $info['Model']->id
        );

        ClassRegistry::init('Field.FieldData')->save($data);

        return true;
    }

    function field_list_afterFind(&$data) {
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

    function field_list_beforeValidate($info) {
        $FieldInstance = ClassRegistry::init('Field.Field')->findById($info['field_id']);

        if ($FieldInstance['Field']['required'] == 1) {
            $info['data'] = is_array($info['data']) ? implode('', $info['data']) : $info['data'];
            $filtered = strip_tags($info['data']);

            if (empty($filtered)) {
                ClassRegistry::init('Field.FieldData')->invalidate(
                    "field_list.{$info['field_id']}.data",
                    __d('field_list', 'You must select at least on option')
                );

                return false;
            }
        }

        return true;
    }

    function field_list_beforeDelete($info) {
        return true;
    }

    function field_list_afterDelete($info) {
        ClassRegistry::init('Field.FieldData')->deleteAll(
            array(
                'FieldData.belongsTo' => $info['Model']->name,
                'FieldData.field_id' => $info['field_id'],
                'FieldData.foreignKey' => $info['Model']->id
            )
        );

        return true;
    }

    public function field_list_afterDeleteInstance($FieldModel) {
        ClassRegistry::init('Field.FieldData')->deleteAll(
            array(
                'FieldData.field_id' => $FieldModel->data['Field']['id']
            )
        );
    }
}