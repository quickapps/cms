<?php
/**
 * Field Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Field.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class Field extends FieldAppModel {
    public $name = 'Field';
    public $useTable = 'fields';
    public $order = array('Field.ordering' => 'ASC');
    public $actsAs = array('Serialized' => array('settings'));
    public $validate = array(
        'label' => array('required' => true, 'allowEmpty' => false, 'rule' => array('between', 1, 128), 'message' => 'Invalid field label.'),
        'name' => array(
            'alphaNumeric' => array('required' => true, 'allowEmpty' => false, 'rule' => array('custom', '/^[a-z0-9_]{3,32}$/i'), 'message' => "Field name must only contain letters and numbers. Between 3-32 characters are required (character '_' is allowed)."),
            'isUnique' => array('required' => true, 'allowEmpty' => false, 'rule' => 'checkUnique', 'message' => 'Field name already in use.')
        ),
        'field_module' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Select a field type.')
    );

    public function beforeValidate() {
        # merge settings (array treatment): formatter form post
        if (isset($this->data['Field']['id']) && isset($this->data['Field']['settings'])) {
            $this->validate = false;
            $settings = $this->field('settings', array('Field.id' => $this->data['Field']['id']));
            $this->data['Field']['settings'] = Set::merge($settings, $this->data['Field']['settings']);

            if (!isset($this->data['Field']['field_module']) || empty($this->data['Field']['field_module'])) {
                $this->data['Field']['field_module'] = $this->field('field_module', array('Field.id' => $this->data['Field']['id']));
            }
        } elseif (!isset($this->data['Field']['id'])) { # new field
            $default_settings = array(
                'display' => array(
                    'default' => array(
                        'label' => 'hidden',
                        'type' => '', #formatter name
                        'settings' => array(),
                        'ordering' => 0
                    )
                )
            );

            $this->data['Field']['settings'] = isset($this->data['Field']['settings']) ? Set::merge($this->data['Field']['settings'], $default_settings) : $default_settings;
        }

        $before = $this->hook("{$this->data['Field']['field_module']}_before_validate_instance", $this);

        if ($before === false) {
            return $before;
        }

        return true;
    }

    public function beforeSave() {
        if (isset($this->data['Field']['field_module'])) {
            $this->data['Field']['settings'] = @unserialize($this->data['Field']['settings']);
            $before = $this->hook("{$this->data['Field']['field_module']}_before_save_instance", $this);
            $this->data['Field']['settings'] = !is_array($this->data['Field']['settings']) ? array() : $this->data['Field']['settings'];
            $this->data['Field']['settings'] = @serialize($this->data['Field']['settings']);

            if ($before === false) {
                return $before;
            }
        }

        // field formatter
        if (isset($this->data['Field']['viewMode'])) {
            $viewMode = $this->data['Field']['viewMode'];

            if ($this->data['Field']['display_hidden']) {
                $data['Field']['settings']['display'][$viewMode]['type'] = 'hidden';
            } else {
                if (!isset($data['Field']['settings']['display'][$viewMode]['type'])) {
                    $data['Field']['settings']['display'][$viewMode]['type'] = false;
                }
            }
        }

        return true;
    }

    public function afterSave() {
        $field = $this->read();

        $this->hook("{$field['Field']['field_module']}_after_save_instance", $this);
    }

    public function beforeDelete() {
        $this->data = $this->read(); # tmp holder (before/afterDelete)
        $before = $this->hook("{$this->field['Field']['field_module']}_before_delete_instance", $this, array('collectReturn' => false));

        if ($before === false) {
            return $before;
        }

        return true;
    }

    public function afterDelete() {
        $this->hook("{$this->data['Field']['field_module']}_after_delete_instance", $this, array('collectReturn' => false));
    }

    public function checkUnique($check) {
        $value = array_shift($check);

        return $this->find('count',
            array(
                'conditions' => array(
                    'Field.belongsTo' => $this->data['Field']['belongsTo'],
                    'Field.name' => $value
                )
            )
        ) === 0;
    }

    public function move($id, $dir = 'up', $view_mode = false) {
        if (!($record = $this->findById($id))) {
            return false;
        }

        $_data = array('id' => $id, 'dir' => $dir, 'view_mode' => $view_mode);

        $this->hook("{$record['Field']['field_module']}_before_move_instance", $_data);
        extract($_data);

        # get brothers
        $nodes = $this->find('all',
            array(
                'conditions' => array(
                    'Field.belongsTo ' => $record['Field']['belongsTo']
                ),
                'order' => array("Field.ordering" => 'ASC'),
                'fields' => array('id', 'ordering', 'settings', 'label'),
                'recursive' => -1
            )
        );

        if (is_string($view_mode)) {
            foreach ($nodes as &$node) {
                if (!isset($node['Field']['settings']['display'][$view_mode])) {
                    $node['Field']['settings']['display'][$view_mode]['ordering'] = 0;
                }
            }

            $nodes = Set::sort($nodes, "{n}.Field.settings.display.{$view_mode}.ordering", 'asc');
        }

        $ids = Set::extract('/Field/id', $nodes);

        if (($dir == 'down' && $ids[count($ids)-1] == $record['Field']['id']) ||
            ($dir == 'up' && $ids[0] == $record['Field']['id'])
        ) { #edge -> cant go down/up
            return false;
        }

        $position = array_search($record['Field']['id'], $ids);
        $key = ($dir == 'up') ? $position-1 : $position+1;
        $tmp = $ids[$key];
        $ids[$key] = $ids[$position];
        $ids[$position] = $tmp;
        $i = 1;
        $prev_id = $this->id;

        foreach ($ids as $id) {
            $this->id = $id;

            if (is_string($view_mode)) {
                $node = Set::extract("/Field[id={$id}]", $nodes);

                if (isset($node[0]['Field']['settings']['display'][$view_mode])) {
                    $node[0]['Field']['settings']['display'][$view_mode]['ordering'] = $i;

                    $this->saveField('settings', $node[0]['Field']['settings'], false);
                }
            } else {
                $this->saveField('ordering', $i, false);
            }

            $i++;
        }

        $this->id = $prev_id;

        $this->hook("{$record['Field']['field_module']}_after_move_instance", $_data);

        return true;
    }

    public function setViewModes($modes, $conditions = false) {
        if (!is_array($modes) || empty($modes)) {
            $modes = array();
        }

        $conditions = (!$conditions || !is_array($conditions)) ? '1 = 1' : $conditions;
        $fields = $this->find('all', array('conditions' => $conditions));

        foreach ($fields as &$field) {
            $this->hook("{$field['Field']['field_module']}_before_set_view_modes", $field);

            $actual = array_keys($field['Field']['settings']['display']);

            foreach ($actual as $actual_mode) { # remove old modes
                if (!in_array($actual_mode, $modes) && $actual_mode !== 'default') {
                    unset($field['Field']['settings']['display'][$actual_mode]);
                }
            }

            if (!empty($modes)) {
                foreach ($modes as $new_mode) { # add if not set yet
                    if (!isset($field['Field']['settings']['display'][$new_mode])) {
                        $field['Field']['settings']['display'][$new_mode] = array(
                            'label' => 'hidden',
                            'type' => '', #formatter name
                            'settings' => array(),
                            'ordering' => 0
                        );
                    }
                }
            }

            $this->save($field, false);
            $this->hook("{$field['Field']['field_module']}_after_set_view_modes", $field);
        }
    }
}