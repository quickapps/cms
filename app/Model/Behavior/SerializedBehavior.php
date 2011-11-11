<?php
/**
 * Serialized Behavior
 *
 * PHP version 5
 *
 * @package  QuickApps.Model.Behavior
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class SerializedBehavior extends ModelBehavior {

/**
 * Fields
 *
 * @var array
 * @access protected
 */
    private $fields = array();

/**
 * Initiate Serialized behavior
 *
 * @param object $Model instance of model
 * @param array $config array of configuration settings.
 * @return void
 * @access public
 */
    public function setup($Model, $config = array()) {
        if (is_string($config)) {
            $config = array($config);
        }

        $this->fields = array_merge($this->fields, $config);
    }

    public function afterFind(&$Model, $results, $primary) {
        $_results = $results;

        if (isset($_results[0][$Model->alias])) {
            foreach ($_results as $rkey => &$record) {
                foreach ($this->fields as $field) {
                    if (isset($record[$Model->alias][$field]) &&
                        !empty($record[$Model->alias][$field]) &&
                        is_string($record[$Model->alias][$field])
                    ) {
                        $record[$Model->alias][$field] = @unserialize($record[$Model->alias][$field]);
                    }
                }
            }
        } else {
            foreach ($this->fields as $field) {
                if (isset($_results[$Model->alias][$field]) &&
                    !empty($_results[$Model->alias][$field]) &&
                    is_string($_results[$Model->alias][$field])
                ) {
                    $_results[$Model->alias][$field] = @unserialize($_results[$Model->alias][$field]);
                }
            }
        }

        return $_results;
    }

    public function beforeSave($Model) {
        if (isset($Model->data[$Model->alias][0])) {
            foreach ($Model->data[$Model->alias] as &$record) {
                foreach ($record as $field => &$data) {
                    if (!in_array($field, $this->fields)) {
                        continue;
                    }

                    $data = $this->serialize($data);
                }
            }
        } elseif (isset($Model->data[0])) {
            foreach ($Model->data as $key => &$row) {
                foreach ($row as $field => &$value) {
                    if (!in_array($field, $this->fields)) {
                        continue;
                    }

                    $value = $this->serialize($value);
                }
            }
        } else {
            foreach ($Model->data[$Model->alias] as $field => &$data) {
                if (!in_array($field, $this->fields)) {
                    continue;
                }

                $data = $this->serialize($data);
            }
        }

        return true;
    }

    public function serialize($data) {
        return (is_array($data) && empty($data) ? @serialize(array()): @serialize($data));
    }
}