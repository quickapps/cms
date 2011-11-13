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
 * Basically this behavior allows to:
 * Expand your table columns by attaching extra fields to any Model.
 *
 * ### What a Field is
 *
 * Internaly Fields are actually modules (cake's plugin), which manage the storing proccess of specific data. 
 * They behave like modules, this means they may have hooks and all what a common plugin has.
 * Data is usually stored in DB tables, QuickApps provides a basic storage table called `field_data`.
 * Though, each field is able to define its own storage system (usually extra tables). 
 * Also, each field's data element must have an unique ID in that storage system, 
 * and such data is associated to an unique Model record.
 *
 * ### Understanding Model->Field relations
 *
 * - Model -> hasMany -> Field Instances:
 *      Models may have multiple instances of the same field, i.e.:
 *      User model may define extra fields: 'last name' and 'age', both represented
 *      by a textbox, means that each field ('last name' & 'age') is an instance of the same Field handler: 'field_textbox'.
 *
 *
 * - FieldInstance -> hasMany -> Field Data:
 *      Obviously each instance may have multiple data records in its storage system, BUT each of
 *      this records belongs to diferent Model records. i.e.: the instance 'last name' for the
 *      User model may have many records of data but each 'last name' actually belong to diferent Users.
 *
 * - Model -> Field Instance -> hasOne -> Field Data:
 *      When retrieving a Model record, all its extra fields are captured (instances).
 *      Therefore each of this instances has ONLY ONE related data for this Model record.
 *      i.e.: When editing a User, its 'last name' field must have only one value, even though the
 *      field instance has many data records in its storage system. (explanation above)
 *
 * ### Field posting strucure:
 *
 * Each field MUST always send its information following this structure,
 *
 *      data[FieldData][{field_module}][{field_instance_id}][data]
 *      data[FieldData][{field_module}][{field_instance_id}][id]
 *
 *      - (string) {field_module}: name of the field handler in CamelCase, i.e.: 'FieldTextarea', 'FieldMyField'.
 *                                 Note: 'Field' prefix is not required but highly recommended.
 *
 *      - (int) {field_instance_id}: ID of the field instance attached to the current Model.
 *                                   (field instances are stored in '{prefix}_fields' table)
 *
 *      - (mixed) data: Field data. It may be from a simple text to complex arrays of mixed data
 *                      i.e.: 'FieldAlbumImage' could define data as an array of images.
 *
 *      - (int) id: Storage ID. Unique ID for the data in the storage system implemented by the field.
 *                  null ID means that there is no data stored yet for this Model record and this field instance.
 *
 *
 *  // debug($this->data) should looks:
 *
 *          array(
 *              .... // Other Model's native fields (table columns)
 *              'FieldData' => array(
 *                  'FieldHandler1' => array(
 *                      41 => array(
 *                          'id' => 153,
 *                          'data' => 'This data has an id = 153 and instance id 41'
 *                      ),
 *                      95 => array(
 *                          'id' => 181,
 *                          'data' => 'This is other instance (95) of FieldHandler1'
 *                      )...
 *                  ),
 *                  'FieldHandler2' => array(
 *                      60 => array(
 *                          id => null,
 *                          'data' => 'null storage ID means that there is no data stored yet for this field instance (60) and Model record'
 *                      )
 *                  )
 *              )
 *          )
 *
 * ### Capturing POST and saving data
 * Capturing field's data and saving process are performed by using Model hooks callbacks (Behaviors Hooks).
 * In this process there are two diferent callbacks types, 
 * `Entity callbacks`, related to Model entities (User, Node, etc).
 * And `Instance callbacks`, related to Field attachment process.
 *
 *
 * ####Entity callbacks
 * This hooks callbacks are fired after each `fieldable` Model's callbacks.
 *
 *  * `{field_module}_before_save($info)` [optional]: before Entity record is saved
 *
 *  * `{field_module}_after_save($info)` [required]: after Entity record has been saved
 *
 *  * `{field_module}_before_validate($info)` [optional]: before validate Entity record
 *
 *  * `{field_module}_before_delete($info)` [optional]: before Entity record delete
 *
 *  * `{field_module}_after_delete($info)` [requited]: after Entity record has been deleted.
 *      (Here is where field should remove data from storage system.)
 * 
 * **_$info_ structure:**
 *
 *      $info = array(
 *          [data] => ...,
 *          [id] => ...,
 *          [created] => ...,
 *          [Model] => ...
 *     );
 *
 * (mixed) **[data]**: Field Data. data[FieldData][{field_module}][{field_instance_id}][data]
 * (int) **[id]**: Storage ID. data[FieldData][{field_module}][{field_instance_id}][id]
 * (bool) **[created]**: Set ONLY on afterSave() callback.
 * (object) **[Model]**: Instance of Model entity that Field is attached to.
 *
 *
 * ####Instance callbacks
 * This hooks callbacks are fired when Fields are being attached to Entities, or when
 * Field is being unattached, etc.
 *
 *  * {field_module}_before_delete_instance(&$FieldModel) [required/optional]: (at least one of (before/after) must be defined).
 *
 *  * {field_module}_after_delete_instance(&$FieldModel) [required/optional]: (at least one of (before/after) must be defined).
 *
 *  * {field_module}_before_validate_instance(&$FieldModel) [optional]: before validate the field instance being saved (attached to entity).
 *
 *  * {field_module}_before_save_instance(&$FieldModel) [optional]: before field is attached to entity.
 *
 *  * {field_module}_after_save_instance(&$FieldModel) [optional]: after field has been attached to entity.
 *
 *  * {field_module}_before_move_instance(&$move_parametters) [optional]: before field instance is moved (reordered) within an entity.
 *    $move_parametters = array('id', 'dir', 'view_mode')
 *
 *  * {field_module}_after_move_instance(&$move_parametters) [optional]: after field instance was moved (reordered) within an entity.
 *
 *  * {field_module}_before_set_view_modes(&$field_record) [optional]: before `view modes` are modified within an entity.
 *
 *  * {field_module}_after_set_view_modes(&$field_record) [optional]: after `view modes` were modified within an entity.
 *
 *
 * ####IMPORTANT
 * Field data **MUST** always be **saved after Entity** record has been saved, that is on afterSave() callback.
 * i.e.: When updating/creating a new User, all field's data must be saved after the User native data has been updated/created
 */
class FieldableBehavior extends ModelBehavior {
/**
 * belongsTo: Name of the object that field belongs to. (Commonly Model Name)
 * If no information is given then Model name is used as default.
 */
    private $__settings = array();

/**
 * Temp holder for afterSave() proccessing
 */
    private $__fieldData = array();

/**
 * Initiate Fieldable behavior
 *
 * @param object $Model instance of model
 * @param array $settings array of configuration settings.
 * @return void
 */
    public function setup(&$Model, $settings = array()) {
        # keep a setings array for each model
        $this->__settings[$Model->alias] = array();
        $this->__settings[$Model->alias] = Set::merge($this->__settings[$Model->alias], $settings);

        if (empty($this->__settings[$Model->alias]['belongsTo'])) {
            $this->__settings[$Model->alias]['belongsTo'] = $Model->alias;
        }
    }

/**
 * Check if field instances should be fetch or not to the Model
 *
 * @param object $Model instance of model
 * @return boolean true
 */
    public function beforeFind(&$Model, $query) {
        if ((isset($Model->fieldsNoFetch) && $Model->fieldsNoFetch) ||
            (isset($query['recursive']) && $query['recursive'] <= 0)
        ) {
            $Model->unbindModel(
                array(
                    'hasMany' => array('Field')
                )
            );
        }

        return true;
    }

/**
 * Invoke each field's beforeSave() event and proceed with the Model's save proccess
 * if all the fields has returned 'true'.
 *
 * Fields data is stored in a temporaly variable ($__fieldData) in order to save it
 * after the new Model record has been saved. That is, in afterSave() callback.
 * Remember: Field's storage process must always be executed after Model's save()
 *
 * @param object $Model instance of model
 * @return boolean False if any of the fields has returned false. True otherwise
 */
    public function beforeSave(&$Model) {
        $r = array();

        if (isset($Model->data['FieldData'])) {
            foreach ($Model->data['FieldData'] as $field_module => $fields) {
                foreach ($fields as $field_id => $info) {
                    $info['field_id'] = $field_id;
                    $info['Model'] =& $Model;
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
                    $info['field_id'] = $field_id;
                    $info['Model'] =& $Model;
                    $info['created'] = $created;

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
                $info['field_id'] = $field_id;
                $info['Model'] =& $Model;
                $r[] = $Model->hook("{$field_module}_before_validate", $info, array('collectReturn' => false));
            }
        }

        return !in_array(false, $r, true);
    }

/**
 * Fecth fields to Model results
 *
 * @param object $Model instance of model
 * @param array $results The results of the Model's find operation
 * @param boolean $primary Whether Model is being queried directly (vs. being queried as an association)
 * @return mixed An array value will replace the value of $results - any other value will be ignored.
 */
    public function afterFind(&$Model, $results, $primary) {
        if (empty($results) ||
            !$primary ||
            (isset($Model->fieldsNoFetch) && $Model->fieldsNoFetch)
        ) {
            return $results;
        }

        # fetch model instance Fields
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
                $field['FieldData'] = array();  # Field storage data must be set here
                $data['field'] =& $field; # Field instance information
                $data['belongsTo'] = $Model->alias; # Field belongsTo
                $data['foreignKey'] = @$result[$Model->alias][$Model->primaryKey]; # Model unique ID
                $data['result'] =& $result; # Instance of current Entity record being fetched

                $Model->hook("{$field['field_module']}_after_find", $data);
            }
        }

        return $results;
    }

/**
 * Attach a new field instance to Model.
 * (Would be like to add a new column to your table)
 *
 * @param object $Model instance of model
 * @param array $data Field instance information:
 *  - label: Field input label. i.e.: 'Article Body' for a textarea
 *  - name: Filed unique name. underscored and alphanumeric characters only. i.e.: 'field_article_body'
 *  - field_module: Name of the module that handle this instance. i.e.: 'filed_textarea'
 * @return mixed Return (int) Field instance ID if it was added correctly. False otherwise.
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
 * Insted this method Field Controller Handler should be used.
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
            $info['Model'] =& $Model;
            $r[] = $Model->hook("{$field['Field']['field_module']}_{$type}_delete", $info, array('collectReturn' => false));
        }

        return !in_array(false, $r, true);
    }

/**
 * Parses 'belongsTo' parameter looking for array paths.
 * This functionality is used only (and should be used only) by Nodes. That is so because,
 * Nodes may have diferent fields attached depending in NodeType (bridge association), 
 * Nodes's 'belongsTo' looks: 'NodeType-{Node.node_type_id}'
 *
 * @param string $belongsTo string to parse
 * @param array $result a Node model row
 * @return string
 */
    private function __parseBelongsTo($belongsTo, $result = array()){
         # look for dynamic belongsTo
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