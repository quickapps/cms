<?php
/**
 * Fieldable Behavior
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Field.Model.Behavior
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */

 /**
 * Fieldable behavior allows to custom data fields to be attached to Models and takes care of storing,
 * loading, editing, and rendering field data.
 * Any Model type (Node, User, etc.) can use this behavior to make itself `fieldable` and thus allow
 * fields to be attached to it.
 *
 * The Field API allows custom data fields to be attached to Models and takes care of storing, loading, editing,
 * and rendering field data. Any Model type (Node, User, etc.) can use the Field API to make itself "fieldable"
 * and thus allow fields to be attached to it.
 *
 * The Field API defines two primary data structures, Field and Instance.
 * A Field defines a particular type of data that can be attached to Models.
 * A Field Instance is a Field attached to a single Model.
 *
 * Internally, fields behave (functionally) like modules (cake's plugin), and they are responsible of manage the
 * storing proccess of specific data. As before, they behave -functionally- like modules,
 * this means they may have hooks and all what a regular module has.
 *
 * Fields belongs always to modules, modules are allowed to define an unlimeted number of fields by placing
 * them on the Fields folder.
 * e.g.: The core module Taxonomy has it own field TaxonomyTerms in QuickApps/Plugins/Taxonomy/Fields/TaxonomyTerms.
 * Most of the Fields included in the core of QuickApps belongs to the Fields module (QuickApps/Plugins/Field/Fields/).
 *
 * @link https://github.com/QuickAppsCMS/QuickApps-CMS/wiki/Field-API
 */
class FieldableBehavior extends ModelBehavior {
/**
 * Settings for this behavior.
 *
 * @var array
 */
    private $__settings = array(
        'belongsTo' => false
    );

/**
 * Temp holder for afterSave() proccessing.
 *
 * @var array
 */
    private $__fieldData = array();

/**
 * Initiate Fieldable behavior.
 *
 * @param object $Model instance of model
 * @param array $settings array of configuration settings
 * @return void
 */
    public function setup(&$Model, $settings = array()) {
        // keep a setings array for each model
        $this->__settings[$Model->alias] = array();
        $this->__settings[$Model->alias] = Set::merge($this->__settings[$Model->alias], $settings);

        if (empty($this->__settings[$Model->alias]['belongsTo'])) {
            $this->__settings[$Model->alias]['belongsTo'] = $Model->alias;
        }
    }

/**
 * Check if field instances should be fetch or not to the Model.
 *
 * @param object $Model instance of model
 * @return boolean true
 */
    public function beforeFind(&$Model, $query) {
        if ((isset($Model->fieldsNoFetch) && $Model->fieldsNoFetch) ||
            (isset($query['recursive']) && $query['recursive'] <= 0)
        ) {
            $Model->fieldsNoFetch = true;

            $Model->unbindModel(
                array(
                    'hasMany' => array('Field')
                )
            );
        } else {
            $modelFields = ClassRegistry::init('Field.Field')->find('all',
                array(
                    'order' => array('Field.ordering' => 'ASC'),
                    'conditions' => array(
                        'Field.belongsTo' => $this->__settings[$Model->alias]['belongsTo']
                    ),
                    'recursive' => -1
                )
            );
            $result['Field'] = Set::extract('/Field/.', $modelFields);

            foreach ($result['Field'] as $key => &$field) {
                $data['entity'] =& $Model;
                $data['query'] =& $query;
                $data['field'] = $field;
                $data['settings'] = $this->__settings[$Model->alias];

                $Model->hook("{$field['field_module']}_before_find", $data);
            }            
        }

        return true;
    }

/**
 * Fetch fields to Model results.
 *
 * @param object $Model instance of model
 * @param array $results The results of the Model's find operation
 * @param boolean $primary Whether Model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored
 */
    public function afterFind(&$Model, $results, $primary) {
        if (empty($results) ||
            !$primary ||
            (isset($Model->fieldsNoFetch) && $Model->fieldsNoFetch)
        ) {
            return $results;
        }

        // holds a list of fields attached to this model.
        $fieldsList = Configure::read('Fieldable.fieldsList');

        if (!isset($fieldsList[$Model->alias])) {
            $fieldsList[$Model->alias] = array();
        }

        // fetch model instance Fields
        foreach ($results as &$result) {
            if (!isset($result[$Model->alias])) {
                continue;
            }

            $belongsTo = $this->__parseBelongsTo($this->__settings[$Model->alias]['belongsTo'], $result);
            $result['Field'] = array();
            $modelFields = ClassRegistry::init('Field.Field')->find('all',
                array(
                    'order' => array('Field.ordering' => 'ASC'),
                    'conditions' => array(
                        'Field.belongsTo' => $belongsTo
                    )
                )
            );
            $result['Field'] = Set::extract('/Field/.', $modelFields);

            foreach ($result['Field'] as $key => &$field) {
                if (!in_array($field['field_module'], $fieldsList[$Model->alias])) {
                    $fieldsList[$Model->alias][] = $field['field_module'];
                }

                $field['FieldData'] = array();  // Field storage data must be set here
                $data['entity'] =& $Model; // Entity instance
                $data['field'] =& $field; // Field information
                $data['result'] =& $result; // Instance of current Entity record being fetched
                $data['settings'] = $this->__settings[$Model->alias]; // fieldable settings

                $Model->hook("{$field['field_module']}_after_find", $data);
            }
        }

        Configure::write('Fieldable.fieldsList', $fieldsList);

        return $results;
    }

/**
 * Invoke each field's beforeSave() event.
 * Return a FALSE result to halt the save.
 *
 * Fields data is stored in a temporaly variable (FieldableBehavior::$__fieldData) in order to save it
 * after the new Model record has been saved. That is, in afterSave() callback.
 * Remember: Field's storage process must always be executed after Model's save()
 *
 * @param object $Model instance of model
 * @return boolean FALSE if any of the fields has returned FALSE. TRUE otherwise
 */
    public function beforeSave(&$Model) {
        $r = array();

        if (isset($Model->data['FieldData'])) {
            foreach ($Model->data['FieldData'] as $field_module => $fields) {
                foreach ($fields as $field_id => $info) {
                    $info['entity'] =& $Model;
                    $info['field_id'] = $field_id;
                    $info['settings'] = $this->__settings[$Model->alias];

                    $r[] = $Model->hook("{$field_module}_before_save", $info, array('collectReturn' => false));
                }
            }
        }

        if (isset($Model->data['FieldData'])) {
            $this->__fieldData = $Model->data['FieldData'];
        }

        return !in_array(false, $r, true);
    }

/**
 * Save field data after Model record has been saved.
 *
 * @param object $Model instance of model
 * @param boolean $created which indicate if a new record has been inserted
 * @see $this::beforeSave()
 * @return void
 */
    public function afterSave(&$Model, $created) {
        if (!empty($this->__fieldData)) {
            foreach ($this->__fieldData as $field_module => $fields) {
                foreach ($fields as $field_id => $info) {
                    $info['entity'] =& $Model;
                    $info['field_id'] = $field_id;
                    $info['created'] = $created;
                    $info['settings'] = $this->__settings[$Model->alias];

                    $Model->hook("{$field_module}_after_save", $info);
                }
            }
        }

        return;
    }

/**
 * Call each Model's field instances callback
 *
 * @param object $Model instance of model
 * @return boolean False if any of the fields has returned false. True otherwise.
 */
    public function beforeDelete(&$Model) {
       return $this->__beforeAfterDelete($Model, 'before');
    }

/**
 * Call each Model's field instances callback
 *
 * @param object $Model instance of model
 * @return boolean False if any of the fields has returned false. True otherwise.
 */
    public function afterDelete(&$Model) {
        return $this->__beforeAfterDelete($Model, 'after');
    }

/**
 * Invoke each field's beforeValidate()
 * If any of the fields return 'false' then Model's save() proccess is interrupted
 *
 * Note:
 *  The hook chain does not stop if in chain any of the fields returns a false value.
 *  All fields response for the event are collected, this is so because fields
 *  may invalidate its field input in form.
 *
 * @param object $Model instance of model
 * @return boolean True if all the fields are valid, false otherwise
 */
    public function beforeValidate(&$Model) {
        if (!isset($Model->data['FieldData'])) {
            return true;
        }

        $r = array();

        foreach ($Model->data['FieldData'] as $field_module => $fields) {
            foreach ($fields as $field_id => $info) {
                $info['entity'] =& $Model;
                $info['field_id'] = $field_id;
                $info['settings'] = $this->__settings[$Model->alias];

                $r[] = $Model->hook("{$field_module}_before_validate", $info, array('collectReturn' => false));
            }
        }

        return !in_array(false, $r, true);
    }

/**
 * Attach a new field instance to Model.
 * (Would be like to add a new column to your table)
 *
 * @param object $Model instance of model
 * @param array $data Field instance information:
 *  - label: Field input label. e.g..: 'Article Body' for a textarea
 *  - name: Filed unique name. underscored and alphanumeric characters only. e.g.: 'field_article_body'
 *  - field_module: Name of the module that handle this instance. e.g.: `filed_text` or `FieldText`
 * @return mixed Return (int) Field instance ID if it was added correctly. FALSE otherwise.
 */
    public function attachFieldInstance(&$Model, $data) {
        $data = isset($data['Field']) ? $data['Field'] : $data;
        $data = array_merge(
            array(
                'label' => '',
                'name' => '',
                'field_module' => ''
            ),
            $data
        );

        extract($data);

        $field_info = $Model->hook('field_info', $field_module, array('collectReturn' => false));

        if (isset($field_info['max_instances']) &&
            $field_info['max_instances'] === 0
        ) {
            return false;
        }

        if (isset($field_info[$field_module])) {
            if (isset($field_info[$field_module]['max_instances']) && is_numeric($field_info[$field_module]['max_instances']) && $field_info[$field_module]['max_instances'] > 0) {
                $count = ClassRegistry::init('Field.Field')->find('count',
                    array(
                        'Field.belongsTo' => $this->__settings[$Model->alias]['belongsTo'],
                        'Field.field_module' => $field_module
                    )
                );

                if ($count > $field_info[$field_module]['max_instances']) {
                    return false;
                }
            }
        }

        $newField = array(
            'Field' => array(
                'belongsTo' => $this->__settings[$Model->alias]['belongsTo'],
                'label' => $label,
                'name' => $name,
                'field_module' => $field_module
            )
        );
        $Field = ClassRegistry::init('Field.Field');
        $before = $Model->hook("{$field_module}_before_attach_field_instance", $newField, array('collectReturn' => false));

        if ($before === false) {
            return false;
        }

        if ($Field->save($newField)) {
            $field = $Field->read();

            $Model->hook("{$field_module}_after_attach_field_instance", $field);

            return $Field->id;
        }

        return false;
    }

/**
 * Delete a field instance by id.
 * (Would be like to delete a column in your table)
 *
 * @see QuickApps.Plugin.Field.Controller.Handler::admin_delete()
 * @param object $Model instance of model
 * @param integer $field_id Field instance ID (stored in table `_fields`)
 * @return boolean False if the instance does not exists. True if was deleted correctly.
 */
    public function removeFieldInstance(&$Model, $field_id) {
        $field = ClassRegistry::init('Field.Field')->findById($field_id);

        if (!$field) {
            return false;
        }

        $deleted = $Model->hook("{$field['Field']['field_module']}_delete_instance", $field_id);

        if ($deleted === false) {
            return false;
        }

        return ClassRegistry::init('Field.Field')->delete($field_id);
    }

/**
 * Return all fields instantces attached to Model.
 * Useful when rendering forms.
 *
 * @param object $Model instance of model
 * @return array List array of all attached fields
 */
    public function fieldInstances(&$Model) {
        $results = ClassRegistry::init('Field.Field')->find('all',
            array(
                'conditions' => array(
                    'Field.belongsTo' => $this->__settings[$Model->alias]['belongsTo']
                ),
                'order' => array('Field.ordering' => 'ASC')
            )
        );

        return $results;
    }

/**
 * Makes a beforeDelete() or afterDelete().
 * Invoke each field before/afterDelte event.
 *
 * @param object $Model instance of model
 * @param string $type callback to execute, possible values: 'before' or 'after'
 * @return boolean False if any of the fields has returned false. True otherwise
 */
    private function __beforeAfterDelete(&$Model, $type = 'before') {
        $Model->id = $Model->id ? $Model->id : $Model->tmpData[$Model->alias][$Model->primaryKey];

        if ($type == 'before') {
            $result = $Model->find('first',
                array(
                    'conditions' => array(
                        "{$Model->alias}.{$Model->primaryKey}" => $Model->id
                    ),
                    'recursive' => -1
                )
            );

            $Model->tmpBelongsTo = $belongsTo = $this->__parseBelongsTo($this->__settings[$Model->alias]['belongsTo'], $result);
            $Model->tmpData = $result;
        } else {
            $belongsTo = $Model->tmpBelongsTo;
        }

        $fields = ClassRegistry::init('Field.Field')->find('all',
            array(
                'conditions' => array(
                    'belongsTo' => $belongsTo
                )
            )
        );

        $r = array();

        foreach ($fields as $field) {
            $info['field_id'] = $field['Field']['id'];
            $info['entity'] =& $Model;
            $info['settings'] = $this->__settings[$Model->alias];
            $r[] = $Model->hook("{$field['Field']['field_module']}_{$type}_delete", $info, array('collectReturn' => false));
        }

        return !in_array(false, $r, true);
    }

/**
 * Parses `belongsTo` setting parameter looking for array paths.
 * This is used by polymorphic objects such as `Node.
 * e.g.: Node objects may have diferent fields attached depending on its `NodeType`.
 *
 * ### Usage
 * {{{
 *  $actsAs = array(
 *      'Fieldable' => array(
 *          'belongsTo' => 'NodeType-{Node.node_type_id}'
 *      )
 *  );
 * }}}
 *
 * @param string $belongsTo string to parse
 * @param array $result a model row
 * @return string
 */
    private function __parseBelongsTo($belongsTo, $result = array()) {
        // look for dynamic belongsTo
        preg_match_all('/\{([\{\}0-9a-zA-Z_\.]+)\}/iUs', $belongsTo, $matches);
        if (isset($matches[1]) && !empty($matches[1])) {
            foreach ($matches[0] as $i => $m) {
                $belongsTo = str_replace($m, Set::extract(trim($matches[1][$i]), $result), $belongsTo);
            }
        }

        return $belongsTo;
    }

/**
 * Do not fetch fields instances on Model->find()
 *
 * @param object $Model instance of model
 * @return void
 */
    public function unbindFields(&$Model) {
        $Model->fieldsNoFetch = true;
    }

/**
 * Fetch all field instances on Model->find()
 *
 * @param object $Model instance of model
 * @return void
 */
    public function bindFields(&$Model) {
        $Model->fieldsNoFetch = false;
    }
}