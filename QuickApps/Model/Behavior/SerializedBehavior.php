<?php
/**
 * Serialized Behavior.
 *
 * Allows to store arrays of information on Model fields.
 *
 * PHP version 5
 *
 * @package	 QuickApps.Model.Behavior
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class SerializedBehavior extends ModelBehavior {
/**
 * List of fields to serialize.
 *
 * @var array
 */
	private $__fields = array();

/**
 * Initiate Serialized behavior
 *
 * @param object $Model instance of model
 * @param array $config array of configuration settings.
 * @return void
 */
	public function setup(Model $Model, $config = array()) {
		if (is_string($config)) {
			$config = array($config);
		}

		$this->__fields = array_merge($this->__fields, $config);
	}

	public function afterFind(Model $Model, $results, $primary) {
		$_results = $results;

		if (isset($_results[0][$Model->alias])) {
			foreach ($_results as $rkey => &$record) {
				foreach ($this->__fields as $field) {
					if (isset($record[$Model->alias][$field]) &&
						!empty($record[$Model->alias][$field]) &&
						is_string($record[$Model->alias][$field])
					) {
						$record[$Model->alias][$field] = $this->__unserialize($record[$Model->alias][$field]);
					}
				}
			}
		} else {
			foreach ($this->__fields as $field) {
				if (isset($_results[$Model->alias][$field]) &&
					!empty($_results[$Model->alias][$field]) &&
					is_string($_results[$Model->alias][$field])
				) {
					$_results[$Model->alias][$field] = $this->__unserialize($_results[$Model->alias][$field]);
				}
			}
		}

		return $_results;
	}

/**
 * Before save callback.
 *
 * @param Model $model Model using this behavior
 * @return boolean TRUE if the operation should continue, FALSE if it should abort
 */
	public function beforeSave(Model $Model) {
		if (isset($Model->data[$Model->alias][0])) {
			foreach ($Model->data[$Model->alias] as &$record) {
				foreach ($record as $field => &$data) {
					if (!in_array($field, $this->__fields)) {
						continue;
					}

					$data = $this->__serialize($data);
				}
			}
		} elseif (isset($Model->data[0])) {
			foreach ($Model->data as $key => &$row) {
				foreach ($row as $field => &$value) {
					if (!in_array($field, $this->__fields)) {
						continue;
					}

					$value = $this->__serialize($value);
				}
			}
		} else {
			foreach ($Model->data[$Model->alias] as $field => &$data) {
				if (!in_array($field, $this->__fields)) {
					continue;
				}

				$data = $this->__serialize($data);
			}
		}

		return true;
	}

/**
 * Unserializes the given string.
 *
 * @param string $serialized Serialized string to unserialize
 * @return array
 */
	private function __unserialize($serialized) {
		$serialized = (string)$serialized;

		return @unserialize($serialized);
	}

/**
 * Serializes the given string.
 *
 * @param array $data Array to serialize
 * @return string
 */
	private function __serialize($data) {
		$data = is_array($data) && empty($data) ? @serialize(array()) : @serialize($data);

		return $data;
	}
}