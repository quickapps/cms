<?php
class FieldTermsHookBehavior extends ModelBehavior {

/**
 * Create Entity's tags cache for search queries, 
 * this feature is available only for `Node` entity.
 *
 * Only Nodes (contents) are alowed to appear in search results with tag filter option,
 * Means that only Nodes will be listed in search queries like:
 *      http://www.domain.com/s/term:my-tag,other-tag
 *
 * @param array $info Fieldable array data
 * @return boolean true always
 */
    public function field_terms_beforeSave($info) {
        if (isset($info['Model']->data['FieldData']['field_terms']) && $info['Model']->name == 'Node') {
            $info['Model']->bindModel(
                array(
                    'hasAndBelongsToMany' => array(
                        'Term' => array(
                            'joinTable' => 'nodes_terms',
                            'className' => 'Taxonomy.Term',
                            'foreignKey' => 'node_id',
                            'associationForeignKey' => 'term_id',
                            'unique' => true,
                            'dependent' => false
                        )
                    )
                ), false
            );

            $terms_cache = array();
            $terms_ids = $_terms_ids = array();
            $_terms_ids = (array)Set::extract('FieldData.field_terms.{n}.data', $info['Model']->data);
            $_terms_ids = (array)Set::filter($_terms_ids);

            if (!empty($_terms_ids)) {
                foreach ($_terms_ids as $key => $ids) {
                    $terms_ids = array_merge($terms_ids, (array)$ids);
                }
            }

            $terms = ClassRegistry::init('Taxonomy.Term')->find('all', 
                array(
                    'fields' => array('slug', 'id'), 
                    'conditions' => array('Term.id' => $terms_ids)
                )
            );

            foreach ($terms as $term) {
                $info['Model']->data['Term']['Term'][] = array('field_id' => $info['field_id'], 'term_id' => $term['Term']['id']);
                $terms_cache[] = "{$term['Term']['id']}:{$term['Term']['slug']}";
            }

            $info['Model']->data['Node']['terms_cache'] = implode('|', $terms_cache);
        }    

        return true;
    }

    public function field_terms_afterSave($info) {
        if (empty($info)) {
            return true;
        }

        $info['id'] =  empty($info['id']) || !isset($info['id']) ? null : $info['id'];
        $data['FieldData'] = array(
            'id' => $info['id'],
            'field_id' => $info['field_id'],
            'data' => implode('|', (array)$info['data']),
            'belongsTo' => $info['Model']->name,
            'foreignKey' => $info['Model']->id
        );

        ClassRegistry::init('Field.FieldData')->save($data);

        return true;
    }

    public function field_terms_afterFind(&$data) {
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

    public function field_terms_beforeValidate($info) {
        $FieldInstance = ClassRegistry::init('Field.Field')->findById($info['field_id']);

        if (isset($FieldInstance['Field']['settings']['max_values']) && $FieldInstance['Field']['settings']['max_values'] != 0) {
            if (is_array($info['data']) && count($info['data']) > $FieldInstance['Field']['settings']['max_values']) {
                ClassRegistry::init('Field.FieldData')->invalidate(
                    "field_terms.{$info['field_id']}.data",
                    __d('field_terms', 'This field cannot hold more than 2 values')
                );

                return false;
            }
        }

        if ($FieldInstance['Field']['required'] == 1) {
            $info['data'] = is_array($info['data']) ? implode('', $info['data']) : $info['data'];
            $filtered = strip_tags($info['data']);
            if (empty($filtered)) {
                ClassRegistry::init('Field.FieldData')->invalidate(
                    "field_terms.{$info['field_id']}.data",
                    __d('field_terms', 'You must select at least on option')
                );

                return false;
            }
        }

        return true;
    }

    public function field_terms_beforeDelete($info) {
        return true;
    }

    public function field_terms_afterDelete($info) {
        ClassRegistry::init('Field.FieldData')->deleteAll(
            array(
                'FieldData.belongsTo' => $info['Model']->name,
                'FieldData.field_id' => $info['field_id'],
                'FieldData.foreignKey' => $info['Model']->id
            )
        );

        return true;
    }

    public function field_terms_afterDeleteInstance($FieldModel) {
        ClassRegistry::init('Field.FieldData')->deleteAll(
            array(
                'FieldData.field_id' => $FieldModel->data['Field']['id']
            )
        );

        ClassRegistry::init('NodesTerm')->deleteAll(
            array(
                'NodesTerm.field_id' => $FieldModel->data['Field']['id']
            )
        );
    }
}