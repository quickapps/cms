<?php
/**
 * Fieldable Behavior
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Field.Model.Behavior
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */

/**
 * Fieldable behavior allows to custom data fields to be attached to Models and takes care of storing,
 * loading, editing, and rendering field data.
 * Any Model type (Node, User, etc.) can use this behavior to make itself `fieldable` and thus allow
 * fields to be attached to it.
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
 * Settings structure for this behavior.
 *
 * -	belongsTo (mixed): Used by polymorphic binding such as Nodes.
 * -	indexField (array): List of fields to add to the search index stack.
 *		e.g. `title`, `description` for `Node` entity. So Node entities can be located
 *		(search engine) by any of the words in their titles or description.
 * -	minimumWordSize: minimum word size to tokenize for search engine.
 * -	binded (boolean): Indicates if CCK fetching is enabled or not.
 *
 * @var array
 */
	public $settings = array(
		'belongsTo' => false,
		'indexFields' => array(),
		'minimumWordSize' => 2,
		'binded' => true
	);

/**
 * Holds specifict settings for each model.
 *
 * @var array
 */
	private $__settings = array();

/**
 * Temp holder.
 *
 * @var array
 */
	private $__tmp = array(
		'fieldData' => array()
	);

/**
 * Initiate Fieldable behavior.
 *
 * @param object $Model Instance of model
 * @param array $settings array of configuration settings
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		// keep a setings array for each model
		$this->__settings[$Model->alias] = $this->settings;
		$this->__settings[$Model->alias] = Hash::merge($this->__settings[$Model->alias], $settings);

		if (empty($this->__settings[$Model->alias]['belongsTo'])) {
			$this->__settings[$Model->alias]['belongsTo'] = $Model->alias;
		}
	}

/**
 * Check if field instances should be fetched or not to entity.
 * `before_find` hook is invoked on each field so they can alter
 * the find operation.
 *
 * @param object $Model Instance of model
 * @return array Modified query parameters
 */
	public function beforeFind(Model $Model, $query) {
		$recursive = isset($query['recursive']) ? $query['recursive'] : $Model->recursive;

		if (!$this->__settings[$Model->alias]['binded'] || $recursive <= 0) {
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
			$result['Field'] = Hash::extract($modelFields, '{n}.Field');

			foreach ($result['Field'] as $key => &$field) {
				$data['entity'] =& $Model;
				$data['query'] =& $query;
				$data['field'] = $field;
				$data['settings'] = $this->__settings[$Model->alias];

				if ($Model->hookDefined("{$field['field_module']}_before_find")) {
					$Model->hook("{$field['field_module']}_before_find", $data);
				}
			}
		}

		// build cck conditions
		if (
			$this->__settings[$Model->alias]['binded'] &&
			isset($query['conditions']) &&
			is_array($query['conditions'])
		) {
			$fields = $this->__buildCckConditions($Model, $query['conditions']);

			if (!empty($fields)) {
				$Model->bindModel(
					array(
						'hasMany' => array(
							'SearchData' => array(
								'className' => 'Field.SearchData',
								'foreignKey' => 'foreignKey',
								'fields' => array('id', 'field_name', 'data'),
								'conditions' => array(
									'SearchData.entity' => $Model->alias
								)
							)
						)
					)
				, true);
			}
		}

		return $query;
	}

/**
 * Fetch fields to Model results.
 *
 * @param object $Model Instance of model
 * @param array $results Array of results from Model's find operation
 * @param boolean $primary Whether Model is being queried directly (vs. being queried as an association)
 * @return array Modified results array
 */
	public function afterFind(Model $Model, $results, $primary) {
		if (empty($results) ||
			!$primary ||
			!$this->__settings[$Model->alias]['binded']
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
			if (!isset($result[$Model->alias]) || !isset($result[$Model->alias][$Model->primaryKey])) {
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
			$result['Field'] = Hash::extract($modelFields, '{n}.Field');

			foreach ($result['Field'] as $key => &$field) {
				if (!in_array($field['field_module'], $fieldsList[$Model->alias])) {
					$fieldsList[$Model->alias][] = $field['field_module'];
				}

				$field['FieldData'] = array(); // Field storage data must be set here
				$data['entity'] =& $Model; // Entity instance
				$data['entity_id'] = $result[$Model->alias][$Model->primaryKey];
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
 * Invokes each field's `before_save()` hook.
 * Return a non-true result to halt the save.
 *
 * Fields data is stored in a temporaly variable (FieldableBehavior::$__tmp['fieldData']) in order to save it
 * after the new Model record has been saved. That is, in afterSave() callback.
 * Remember: Field's storage process must always be executed after Model's save()
 *
 * @param object $Model Instance of model
 * @return boolean FALSE if any of the fields has returned FALSE. TRUE otherwise
 */
	public function beforeSave(Model $Model) {
		$r = array();

		if (isset($Model->data['FieldData'])) {
			foreach ($Model->data['FieldData'] as $field_module => $fields) {
				foreach ($fields as $field_id => $info) {
					$info['entity'] =& $Model;
					$info['field_id'] = $field_id;
					$info['settings'] = $this->__settings[$Model->alias];

					if ($Model->hookDefined("{$field_module}_before_save")) {
						$r[] = $Model->hook("{$field_module}_before_save", $info, array('collectReturn' => false));
					}
				}
			}
		}

		if (isset($Model->data['FieldData'])) {
			$this->__tmp['fieldData'] = $Model->data['FieldData'];
		}

		$r = array_unique($r);

		return empty($r) || (count($r) === 1 && $r[0] === true);
	}

/**
 * Save field data after Model record has been saved.
 *
 * @param object $Model Instance of model
 * @param boolean $created which indicate if a new record has been inserted
 * @see $this::beforeSave()
 * @return void
 */
	public function afterSave(Model $Model, $created) {
		if (!empty($this->__tmp['fieldData'])) {
			foreach ($this->__tmp['fieldData'] as $field_module => $fields) {
				foreach ($fields as $field_id => $info) {
					$info['entity'] =& $Model;
					$info['field_id'] = $field_id;
					$info['created'] = $created;
					$info['settings'] = $this->__settings[$Model->alias];

					$Model->hook("{$field_module}_after_save", $info);
				}
			}
		}

		$this->__processSearchIndex($Model);
	}

/**
 * Call each Model's field instances callback
 *
 * @param object $Model Instance of model
 * @return boolean FALSE if any of the fields has returned a non-true value. TRUE otherwise.
 */
	public function beforeDelete(Model $Model, $cascade = true) {
	   return $this->__beforeAfterDelete($Model, 'before');
	}

/**
 * Call each Model's field instances callback
 *
 * @param object $Model Instance of model
 * @return void
 */
	public function afterDelete(Model $Model) {
		$this->__beforeAfterDelete($Model, 'after');
	}

/**
 * Invoke each field's beforeValidate()
 * If any of the fields returns FALSE then Model's save() proccess is interrupted
 *
 * Note:
 *  The **hook chain** will no stop if in chain some of the fields returns a FALSE value.
 *  All fields response for the callback are collected, this is so because fields
 *  may invalidate its field input in form.
 *
 * @param object $Model Instance of model
 * @return boolean TRUE if all the fields are valid, FALSE otherwise
 */
	public function beforeValidate(Model $Model) {
		if (!isset($Model->data['FieldData'])) {
			return true;
		}

		$r = array();

		foreach ($Model->data['FieldData'] as $field_module => $fields) {
			foreach ($fields as $field_id => $info) {
				$info['entity'] =& $Model;
				$info['field_id'] = $field_id;
				$info['settings'] = $this->__settings[$Model->alias];

				if ($Model->hookDefined("{$field_module}_before_validate")) {
					$r[] = $Model->hook("{$field_module}_before_validate", $info, array('collectReturn' => false));
				}
			}
		}

		$r = array_unique($r);

		return empty($r) || (count($r) === 1 && $r[0] === true);
	}

/**
 * Attach a new field instance to entity.
 * (Would be like to add a new column to your table)
 *
 * @param object $Model Instance of model
 * @param array $data Field instance information:
 *  - label: Field input label. e.g..: 'Article Body' for a textarea
 *  - name: Filed unique name. underscored and alphanumeric characters only. e.g.: 'field_article_body'
 *  - field_module: Name of the module that handle this instance. e.g.: `filed_text` or `FieldText`
 * @return mixed Return (int) Field instance ID if it was added correctly. FALSE otherwise.
 */
	public function attachFieldInstance(Model $Model, $data) {
		$data = isset($data['Field']) ? $data['Field'] : $data;
		$data = array_merge(
			array(
				'label' => '',
				'name' => '',
				'field_module' => '',
				'description' => '',
				'required' => 0,
				'settings' => array(),
				'locked' => 0
			),
			$data
		);

		extract($data);

		$field_info = QuickApps::field_info($field_module);

		if (empty($field_info)) {
			return false;
		}

		if (isset($field_info['max_instances']) &&
			$field_info['max_instances'] === 0
		) {
			return false;
		}

		if (isset($field_info['entity_types']) &&
			!empty($field_info['entity_types']) &&
			!in_array(
				Inflector::underscore($Model->alias),
				array_map('Inflector::underscore', (array)$field_info['entity_types'])
			)
		) {
			return false;
		}

		if (isset($field_info[$field_module])) {
			if (isset($field_info[$field_module]['max_instances']) &&
				is_numeric($field_info[$field_module]['max_instances']) &&
				$field_info[$field_module]['max_instances'] > 0
			) {
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

		$data['belongsTo'] = $this->__settings[$Model->alias]['belongsTo'];
		$Field = ClassRegistry::init('Field.Field');

		if ($Model->hookDefined("{$field_module}_before_attach_field_instance")) {
			$before = $Model->hook("{$field_module}_before_attach_field_instance", $data, array('collectReturn' => false));

			if ($before !== true) {
				return false;
			}
		}

		if ($Field->save($data)) {
			$field = $Field->read();

			$Model->hook("{$field_module}_after_attach_field_instance", $field);

			return $Field->id;
		}

		return false;
	}

/**
 * Delete a field instance by instance ID.
 * This is a simple wrapper method to `Field::delete()`.
 * Both `before_delete_instance` and `after_delete_instance` hooks are invoked
 * by `Field` model on on deletion process.
 *
 * @param object $Model Instance of model
 * @param integer $field_id Field instance ID (in `fields` table)
 * @return boolean TRUE on success
 */
	public function detachFieldInstance(Model $Model, $field_id) {
		return ClassRegistry::init('Field.Field')->delete($field_id);
	}

/**
 * Return all fields instantces attached to Model.
 * Useful when rendering forms.
 *
 * @param object $Model Instance of model
 * @return array List array of all attached fields
 */
	public function fieldInstances(Model $Model) {
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
 * For `Node` entity only.
 * Indexes Field's content, so nodes can be searched by any of
 * the words in any of its fields.
 * This method simply adds the given text to a stack to be processed
 * later by `FieldableBehavior::__processSearchIndex()`.
 *
 * @param object $Model Instance of model
 * @param string $search_data Field's text (content) to index
 * @param integer $instance_id Field instance ID within the `fields` table.
 * @return boolean TRUE on sucess or FALSE otherwise
 * @see FieldableBehavior::__processSearchIndex()
 */
	public function indexField(Model $Model, $search_data, $instance_id) {
		if (empty($search_data)) {
			return false;
		}

		$Field = ClassRegistry::init('Field.Field')->find('first',
			array(
				'conditions' => array('Field.id' => $instance_id),
				'recursive' => -1,
				'fields' => array('id', 'name')
			)
		);

		if (!$Field) {
			return false;
		}

		if (!isset($this->__tmp['SearchData'])) {
			$this->__tmp['SearchData'] = array();
		}

		$search_data = (string)$search_data;
		$this->__tmp['SearchData'][$Field['Field']['name']] = $search_data;

		return true;
	}

/**
 * Do not fetch fields instances on Model->find()
 *
 * @param object $Model Instance of model
 * @return void
 */
	public function unbindFieldable(Model $Model) {
		$this->__settings[$Model->alias]['binded'] = false;
	}

/**
 * Fetch all field instances on Model->find()
 *
 * @param object $Model Instance of model
 * @return void
 */
	public function bindFieldable(Model $Model) {
		$this->__settings[$Model->alias]['binded'] = true;
	}

/**
 * Lock the specified field, so users can't modify its settings.
 *
 * @param integer $instance_id Instance ID of the field to lock
 * @return boolean TRUE on success, FALSE on failure
 * @see Field::lockField()
 */
	public function lockField(Model $Model, $instance_id) {
		return ClassRegistry::init('Field.Field')->lockField($instance_id);
	}

/**
 * Lock the specified field, so users can't modify its settings.
 *
 * @param integer $instance_id Instance ID of the field to unlock
 * @return boolean TRUE on success, FALSE on failure
 * @see Field::unlockField()
 */
	public function unlockField(Model $Model, $instance_id) {
		return ClassRegistry::init('Field.Field')->unlockField($instance_id);
	}

/**
 * Allows to modify the `belongsTo` parameter on the fly.
 *
 * Useful for polymorphic entities such as `Node`, allows to
 * quickly change the `belongsTo` value whithout detaching and attaching again
 * the `Fielable` behavior.
 *
 * ### Example of usage
 *
 *    $node = $this->Node->findById(1);
 *    $this->Node->fieldsBelongsTo('NodeType-' . $node['Node']['node_type_id']);
 *
 * @param integer $instance_id Instance ID of the field to unlock
 * @return boolean TRUE on success, FALSE on failure
 * @see Field::unlockField()
 */
	public function fieldsBelongsTo(Model $Model, $belongs_to) {
		$this->__settings[$Model->alias]['belongsTo'] = $belongs_to;
	}

/**
 * Look for CCK Field conditions for beforeFind().
 *
 *     ...
 *     'conditions' => array(
 *         ':cck_field_name1' => 'Exact Match',
 *         'Entity.:cck_field_name2 LIKE' => '%Containts this phrase%',
 *         'Entity.:FieldText LIKE' => '%where any of its FieldText instances contains this words%',
 *         'Entity.: LIKE' => '%look on any CCK Field%'
 *     )
 *     ... 
 *
 * @param $conditions array Original conditions from find() to alter
 * @return array List of all CCK fields found
 */
	private function __buildCckConditions(Model $Model, &$conditions) {
		$bool = array('and', 'or', 'not', 'and not', 'or not', 'xor', '||', '&&');
		$fields = array();

		foreach ($conditions as $key => $value) {
			if ((is_numeric($key) && is_array($value)) || in_array(strtolower(trim($key)), $bool)) {
				$fields = array_merge($fields, $this->__buildCckConditions($Model, $conditions[$key]));
			} else {
				list($entity, $field_name) = pluginSplit($key);

				if (!$field_name) {
					$field_name = $entity;
				}

				$field_name = preg_replace('/ {2,}/', ' ', $field_name);
				$parts = explode(' ', $field_name);
				$field_name = $_fName = trim($parts[0]);

				if ($Model->hasField($field_name) || $Model->isVirtualField($field_name)) {
					continue;
				}

				if (count($parts) > 1) {
					array_shift($parts);

					$parts = ' ' . strtoupper(implode(' ', $parts));
				} else {
					$parts = ' = ';
				}

				unset($conditions[$key]);

				if ($d = substr_count($field_name, ':')) {
					if ($d === 2) {
						$field_name = null;
					} else {
						$field_name = str_replace(':', '', $field_name);
					}
				}

				$db = $Model->getDataSource();
				$alias = 'a' . substr(md5(uniqid(rand(), true)), 0, 8);
				$subQuery = $db->buildStatement(
					array(
						'limit' => 1,
						'alias' => $alias,
						'fields' => array("{$alias}.foreignKey"),
						'table' => $db->fullTableName('search_data'),
						'conditions' => array(
							"{$alias}.foreignKey" => $db->identifier("{$Model->alias}.{$Model->primaryKey}"),
							"{$alias}.field_name" => $field_name,
							"{$alias}.entity" => "{$Model->alias}",
							"{$alias}.data{$parts}" => $value
						)
					), $Model
				);

				$subQuery = $db->expression($subQuery);
				$conditions["{$Model->alias}.{$Model->primaryKey}"] = array($subQuery);
				$fields[] = $_fName;
			}
		}

		return $fields;
	}

/**
 * Save all the texts added to the stack by FieldableBehavior::indexField()
 *
 * @return boolean TRUE on sucess, FALSE otherwise.
 * @see FieldableBehavior::indexField()
 */
	private function __processSearchIndex(Model $Model) {
		if (!isset($this->__tmp['SearchData']) || !$Model->id) {
			return false;
		}

		$ALL = array();

		if (!empty($this->__settings[$Model->alias]['indexFields']) && $Model->id) {
			if (is_string($this->__settings[$Model->alias]['indexFields'])) {
				$this->__settings[$Model->alias]['indexFields'] = array($this->__settings[$Model->alias]['indexFields']);
			}

			$entity = $Model->read();

			if ($entity) {
				foreach ($this->__settings[$Model->alias]['indexFields'] as $f) {
					if (isset($entity[$Model->alias][$f]) && !empty($entity[$Model->alias][$f])) {
						$ALL[] = $this->__processSearchText($entity[$Model->alias][$f], $this->__settings[$Model->alias]['minimumWordSize']);
					}
				}
			}
		}

		$SearchData = ClassRegistry::init('Field.SearchData');

		foreach ($this->__tmp['SearchData'] as $field_name => $content) {
			$content = $this->__processSearchText($content, $this->__settings[$Model->alias]['minimumWordSize']);
			$ALL[] = $content;
			$exists = $SearchData->find('first',
				array(
					'conditions' => array(
						'SearchData.field_name' => $field_name,
						'SearchData.entity' => $Model->alias,
						'SearchData.foreignKey' => $Model->id
					)
				)
			);

			if ($exists) {
				$exists['SearchData']['data'] = $content;
			} else {
				$exists = array(
					'SearchData' => array(
						'field_name' => $field_name,
						'foreignKey' => $Model->id,
						'entity' => $Model->alias,
						'data' => $content
					)
				);

				$SearchData->create();
			}

			$SearchData->save($exists);
		}

		$ALL = implode(' ', $ALL); // merge all
		$ALL = explode(' ', $ALL); // split in words
		$ALL = array_unique($ALL); // remove duplicated words

		if (!empty($ALL)) {
			$ALL = ' ' . implode(' ', $ALL) . ' ';
			$exists = $SearchData->find('first',
				array(
					'conditions' => array(
						'SearchData.field_name' => null,
						'SearchData.entity' => $Model->alias,
						'SearchData.foreignKey' => $Model->id
					)
				)
			);
			
			if ($exists) {
				$exists['SearchData']['data'] = $ALL;
			} else {
				$exists = array(
					'SearchData' => array(
						'field_name' => null,
						'foreignKey' => $Model->id,
						'entity' => $Model->alias,
						'data' => $ALL
					)
				);

				$SearchData->create();
			}

			$SearchData->save($exists);
		}

		return true;
	}

/**
 * Process the given text to be stored in `search_data` table.
 *
 * @param $text string Text to sanitize
 * @return string Sanitized text
 */
	private function __processSearchText($text, $minimumWordSize = 3) {
		App::uses('String', 'Utility');

		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		$text = mb_strtolower($text);

		// To improve searching for numerical data such as dates, IP addresses
		// or version numbers, we consider a group of numerical characters
		// separated only by punctuation characters to be one piece.
		// This also means that searching for e.g. '20/03/1984' also returns
		// results with '20-03-1984' in them.
		// Readable regexp: ([number]+)[punctuation]+(?=[number])
		$text = preg_replace('/([' . PREG_CLASS_NUMBERS . ']+)[' . PREG_CLASS_PUNCTUATION . ']+(?=[' . PREG_CLASS_NUMBERS . '])/u', '\1', $text);

		// Multiple dot and dash groups are word boundaries and replaced with space.
		// No need to use the unicode modifer here because 0-127 ASCII characters
		// can't match higher UTF-8 characters as the leftmost bit of those are 1.
		$text = preg_replace('/[.-]{2,}/', ' ', $text);

		// The dot, underscore and dash are simply removed. This allows meaningful
		// search behavior with acronyms and URLs. See unicode note directly above.
		$text = preg_replace('/[._-]+/', '', $text);

		// With the exception of the rules above, we consider all punctuation,
		// marks, spacers, etc, to be a word boundary.
		$text = preg_replace('/[' . PREG_CLASS_UNICODE_WORD_BOUNDARY . ']+/u', ' ', $text);

		// Truncate everything to 50 characters.
		$words = explode(' ', $text);

		foreach ($words as $i => $word) {
			if (strlen($word) < $minimumWordSize) {
				unset($words[$i]);
			} else {
				$words[$i] = String::truncate($word, 50);
			}
		}

		$words = array_unique($words);
		$text = implode(' ', $words);

		return trim($text);
	}

/**
 * Makes a beforeDelete() or afterDelete().
 * Invoke each field before/afterDelte event.
 *
 * @param object $Model Instance of model
 * @param string $type callback to execute, possible values: 'before' or 'after'
 * @return mixed
 *  `before_delete`: FALSE if any of the fields has returned a non-true value. TRUE otherwise
 *  `after_delete`: void
 */
	private function __beforeAfterDelete(Model $Model, $type = 'before') {
		// make Model->id available even after deletion
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

			// useful data to be passed to hooks handlers
			$Model->tmpBelongsTo = $belongsTo = $this->__parseBelongsTo($this->__settings[$Model->alias]['belongsTo'], $result);
			$Model->tmpData = $result;
		} else {
			$belongsTo = $Model->tmpBelongsTo;

			// delete search information
			ClassRegistry::init('Field.SearchData')->deleteAll(array(
				'SearchData.entity' => $Model->alias,
				'SearchData.foreignKey' => $Model->id
			), false);
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

			if ($Model->hookDefined("{$field['Field']['field_module']}_{$type}_delete")) {
				$r[] = $Model->hook("{$field['Field']['field_module']}_{$type}_delete", $info, array('collectReturn' => false));
			}
		}

		if ($type == 'before') {
			$r = array_unique($r);

			return empty($r) || (count($r) === 1 && $r[0] === true);
		}

		return;
	}

/**
 * Parses `belongsTo` setting parameter looking for array paths.
 * This is used by polymorphic entities such as `Node`.
 * e.g.: Node objects may have different fields attached depending on its `NodeType`.
 *
 * ### Usage
 *
 *     $actsAs = array(
 *         'Fieldable' => array(
 *             'belongsTo' => 'NodeType-{Node.node_type_id}'
 *         )
 *     );
 *
 * @param string $belongsTo String to parse
 * @param array $result Model record where to get array paths
 * @return string
 */
	private function __parseBelongsTo($belongsTo, $result = array()) {
		// look for dynamic belongsTo
		preg_match_all('/\{([\{\}0-9a-zA-Z_\.]+)\}/iUs', $belongsTo, $matches);
		if (isset($matches[1]) && !empty($matches[1])) {
			foreach ($matches[0] as $i => $m) {
				$belongsTo = str_replace(
					$m, 
					array_pop(Hash::extract($result, trim($matches[1][$i]))),
					$belongsTo
				);
			}
		}

		return $belongsTo;
	}
}