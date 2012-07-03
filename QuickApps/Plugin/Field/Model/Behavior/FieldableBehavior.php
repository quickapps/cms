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
 * Matches all 'N' Unicode character classes (numbers)
 */
define('PREG_CLASS_NUMBERS',
'\x{30}-\x{39}\x{b2}\x{b3}\x{b9}\x{bc}-\x{be}\x{660}-\x{669}\x{6f0}-\x{6f9}' .
'\x{966}-\x{96f}\x{9e6}-\x{9ef}\x{9f4}-\x{9f9}\x{a66}-\x{a6f}\x{ae6}-\x{aef}' .
'\x{b66}-\x{b6f}\x{be7}-\x{bf2}\x{c66}-\x{c6f}\x{ce6}-\x{cef}\x{d66}-\x{d6f}' .
'\x{e50}-\x{e59}\x{ed0}-\x{ed9}\x{f20}-\x{f33}\x{1040}-\x{1049}\x{1369}-' .
'\x{137c}\x{16ee}-\x{16f0}\x{17e0}-\x{17e9}\x{17f0}-\x{17f9}\x{1810}-\x{1819}' .
'\x{1946}-\x{194f}\x{2070}\x{2074}-\x{2079}\x{2080}-\x{2089}\x{2153}-\x{2183}' .
'\x{2460}-\x{249b}\x{24ea}-\x{24ff}\x{2776}-\x{2793}\x{3007}\x{3021}-\x{3029}' .
'\x{3038}-\x{303a}\x{3192}-\x{3195}\x{3220}-\x{3229}\x{3251}-\x{325f}\x{3280}-' .
'\x{3289}\x{32b1}-\x{32bf}\x{ff10}-\x{ff19}');

/**
 * Matches all 'P' Unicode character classes (punctuation)
 */
define('PREG_CLASS_PUNCTUATION',
'\x{21}-\x{23}\x{25}-\x{2a}\x{2c}-\x{2f}\x{3a}\x{3b}\x{3f}\x{40}\x{5b}-\x{5d}' .
'\x{5f}\x{7b}\x{7d}\x{a1}\x{ab}\x{b7}\x{bb}\x{bf}\x{37e}\x{387}\x{55a}-\x{55f}' .
'\x{589}\x{58a}\x{5be}\x{5c0}\x{5c3}\x{5f3}\x{5f4}\x{60c}\x{60d}\x{61b}\x{61f}' .
'\x{66a}-\x{66d}\x{6d4}\x{700}-\x{70d}\x{964}\x{965}\x{970}\x{df4}\x{e4f}' .
'\x{e5a}\x{e5b}\x{f04}-\x{f12}\x{f3a}-\x{f3d}\x{f85}\x{104a}-\x{104f}\x{10fb}' .
'\x{1361}-\x{1368}\x{166d}\x{166e}\x{169b}\x{169c}\x{16eb}-\x{16ed}\x{1735}' .
'\x{1736}\x{17d4}-\x{17d6}\x{17d8}-\x{17da}\x{1800}-\x{180a}\x{1944}\x{1945}' .
'\x{2010}-\x{2027}\x{2030}-\x{2043}\x{2045}-\x{2051}\x{2053}\x{2054}\x{2057}' .
'\x{207d}\x{207e}\x{208d}\x{208e}\x{2329}\x{232a}\x{23b4}-\x{23b6}\x{2768}-' .
'\x{2775}\x{27e6}-\x{27eb}\x{2983}-\x{2998}\x{29d8}-\x{29db}\x{29fc}\x{29fd}' .
'\x{3001}-\x{3003}\x{3008}-\x{3011}\x{3014}-\x{301f}\x{3030}\x{303d}\x{30a0}' .
'\x{30fb}\x{fd3e}\x{fd3f}\x{fe30}-\x{fe52}\x{fe54}-\x{fe61}\x{fe63}\x{fe68}' .
'\x{fe6a}\x{fe6b}\x{ff01}-\x{ff03}\x{ff05}-\x{ff0a}\x{ff0c}-\x{ff0f}\x{ff1a}' .
'\x{ff1b}\x{ff1f}\x{ff20}\x{ff3b}-\x{ff3d}\x{ff3f}\x{ff5b}\x{ff5d}\x{ff5f}-' .
'\x{ff65}');

/**
 * Matches Unicode characters that are word boundaries.
 *
 * @see http://unicode.org/glossary
 *
 * Characters with the following General_category (gc) property values are used
 * as word boundaries. While this does not fully conform to the Word Boundaries
 * algorithm described in http://unicode.org/reports/tr29, as PCRE does not
 * contain the Word_Break property table, this simpler algorithm has to do.
 * - Cc, Cf, Cn, Co, Cs: Other.
 * - Pc, Pd, Pe, Pf, Pi, Po, Ps: Punctuation.
 * - Sc, Sk, Sm, So: Symbols.
 * - Zl, Zp, Zs: Separators.
 *
 * Non-boundary characters include the following General_category (gc) property
 * values:
 * - Ll, Lm, Lo, Lt, Lu: Letters.
 * - Mc, Me, Mn: Combining Marks.
 * - Nd, Nl, No: Numbers.
 *
 * Note that the PCRE property matcher is not used because we wanted to be
 * compatible with Unicode 5.2.0 regardless of the PCRE version used (and any
 * bugs in PCRE property tables).
 */
define('PREG_CLASS_UNICODE_WORD_BOUNDARY',
  '\x{0}-\x{2F}\x{3A}-\x{40}\x{5B}-\x{60}\x{7B}-\x{A9}\x{AB}-\x{B1}\x{B4}' .
  '\x{B6}-\x{B8}\x{BB}\x{BF}\x{D7}\x{F7}\x{2C2}-\x{2C5}\x{2D2}-\x{2DF}' .
  '\x{2E5}-\x{2EB}\x{2ED}\x{2EF}-\x{2FF}\x{375}\x{37E}-\x{385}\x{387}\x{3F6}' .
  '\x{482}\x{55A}-\x{55F}\x{589}-\x{58A}\x{5BE}\x{5C0}\x{5C3}\x{5C6}' .
  '\x{5F3}-\x{60F}\x{61B}-\x{61F}\x{66A}-\x{66D}\x{6D4}\x{6DD}\x{6E9}' .
  '\x{6FD}-\x{6FE}\x{700}-\x{70F}\x{7F6}-\x{7F9}\x{830}-\x{83E}' .
  '\x{964}-\x{965}\x{970}\x{9F2}-\x{9F3}\x{9FA}-\x{9FB}\x{AF1}\x{B70}' .
  '\x{BF3}-\x{BFA}\x{C7F}\x{CF1}-\x{CF2}\x{D79}\x{DF4}\x{E3F}\x{E4F}' .
  '\x{E5A}-\x{E5B}\x{F01}-\x{F17}\x{F1A}-\x{F1F}\x{F34}\x{F36}\x{F38}' .
  '\x{F3A}-\x{F3D}\x{F85}\x{FBE}-\x{FC5}\x{FC7}-\x{FD8}\x{104A}-\x{104F}' .
  '\x{109E}-\x{109F}\x{10FB}\x{1360}-\x{1368}\x{1390}-\x{1399}\x{1400}' .
  '\x{166D}-\x{166E}\x{1680}\x{169B}-\x{169C}\x{16EB}-\x{16ED}' .
  '\x{1735}-\x{1736}\x{17B4}-\x{17B5}\x{17D4}-\x{17D6}\x{17D8}-\x{17DB}' .
  '\x{1800}-\x{180A}\x{180E}\x{1940}-\x{1945}\x{19DE}-\x{19FF}' .
  '\x{1A1E}-\x{1A1F}\x{1AA0}-\x{1AA6}\x{1AA8}-\x{1AAD}\x{1B5A}-\x{1B6A}' .
  '\x{1B74}-\x{1B7C}\x{1C3B}-\x{1C3F}\x{1C7E}-\x{1C7F}\x{1CD3}\x{1FBD}' .
  '\x{1FBF}-\x{1FC1}\x{1FCD}-\x{1FCF}\x{1FDD}-\x{1FDF}\x{1FED}-\x{1FEF}' .
  '\x{1FFD}-\x{206F}\x{207A}-\x{207E}\x{208A}-\x{208E}\x{20A0}-\x{20B8}' .
  '\x{2100}-\x{2101}\x{2103}-\x{2106}\x{2108}-\x{2109}\x{2114}' .
  '\x{2116}-\x{2118}\x{211E}-\x{2123}\x{2125}\x{2127}\x{2129}\x{212E}' .
  '\x{213A}-\x{213B}\x{2140}-\x{2144}\x{214A}-\x{214D}\x{214F}' .
  '\x{2190}-\x{244A}\x{249C}-\x{24E9}\x{2500}-\x{2775}\x{2794}-\x{2B59}' .
  '\x{2CE5}-\x{2CEA}\x{2CF9}-\x{2CFC}\x{2CFE}-\x{2CFF}\x{2E00}-\x{2E2E}' .
  '\x{2E30}-\x{3004}\x{3008}-\x{3020}\x{3030}\x{3036}-\x{3037}' .
  '\x{303D}-\x{303F}\x{309B}-\x{309C}\x{30A0}\x{30FB}\x{3190}-\x{3191}' .
  '\x{3196}-\x{319F}\x{31C0}-\x{31E3}\x{3200}-\x{321E}\x{322A}-\x{3250}' .
  '\x{3260}-\x{327F}\x{328A}-\x{32B0}\x{32C0}-\x{33FF}\x{4DC0}-\x{4DFF}' .
  '\x{A490}-\x{A4C6}\x{A4FE}-\x{A4FF}\x{A60D}-\x{A60F}\x{A673}\x{A67E}' .
  '\x{A6F2}-\x{A716}\x{A720}-\x{A721}\x{A789}-\x{A78A}\x{A828}-\x{A82B}' .
  '\x{A836}-\x{A839}\x{A874}-\x{A877}\x{A8CE}-\x{A8CF}\x{A8F8}-\x{A8FA}' .
  '\x{A92E}-\x{A92F}\x{A95F}\x{A9C1}-\x{A9CD}\x{A9DE}-\x{A9DF}' .
  '\x{AA5C}-\x{AA5F}\x{AA77}-\x{AA79}\x{AADE}-\x{AADF}\x{ABEB}' .
  '\x{D800}-\x{F8FF}\x{FB29}\x{FD3E}-\x{FD3F}\x{FDFC}-\x{FDFD}' .
  '\x{FE10}-\x{FE19}\x{FE30}-\x{FE6B}\x{FEFF}-\x{FF0F}\x{FF1A}-\x{FF20}' .
  '\x{FF3B}-\x{FF40}\x{FF5B}-\x{FF65}\x{FFE0}-\x{FFFD}');

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
 * Settings for this behavior.
 *
 * @var array
 */
	private $__settings = array(
		'belongsTo' => false
	);

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
		$this->__settings[$Model->alias] = array();
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
 * @param string $field_content Field's text (content) to index
 * @return boolean TRUE on sucess, FALSE otherwise
 * @see FieldableBehavior::__processSearchIndex()
 */
	public function indexField(Model $Model, $field_content) {
		if ($Model->alias != 'Node' || !$Model->id || !is_string($field_content)) {
			return false;
		}

		if (!isset($this->__tmp['NodeSearchData'])) {
			$this->__tmp['NodeSearchData'] = array();
		}

		$this->__tmp['NodeSearchData'][] = $field_content;

		return true;
	}

/**
 * Do not fetch fields instances on Model->find()
 *
 * @param object $Model Instance of model
 * @return void
 */
	public function unbindFields(Model $Model) {
		$Model->fieldsNoFetch = true;
	}

/**
 * Fetch all field instances on Model->find()
 *
 * @param object $Model Instance of model
 * @return void
 */
	public function bindFields(Model $Model) {
		$Model->fieldsNoFetch = false;
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
 * Save all the texts added to the stack by FieldableBehavior::indexField()
 *
 * @return boolean TRUE on sucess, FALSE otherwise.
 * @see FieldableBehavior::indexField()
 */
	private function __processSearchIndex(Model $Model) {
		if (!isset($this->__tmp['NodeSearchData'])) {
			return false;
		}

		App::uses('String', 'Utility');

		$node = $Model->read();
		$this->__tmp['NodeSearchData'][] = $node['Node']['slug'];
		$this->__tmp['NodeSearchData'][] = $node['Node']['title'];
		$this->__tmp['NodeSearchData'][] = $node['Node']['description'];
		$text = implode(' ', $this->__tmp['NodeSearchData']);
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
			if (strlen($word) < 2) {
				unset($words[$i]);
			} else {
				$words[$i] = String::truncate($word, 50);
			}
		}

		$text = implode(' ', $words);

		if (empty($text)) {
			return false;
		}

		$text = ' ' . $text . ' ';
		$NodeSearch = ClassRegistry::init('Node.NodeSearch');
		$save = $NodeSearch->findByNodeId($Model->id);

		if (!$save) {
			$save = array(
				'NodeSearch' => array(
					'node_id' => $Model->id,
					'data' => $text
				)
			);
		} else {
			$save['NodeSearch']['data'] = $text;
		}

		return $NodeSearch->save($save);
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
 *    $actsAs = array(
 *        'Fieldable' => array(
 *            'belongsTo' => 'NodeType-{Node.node_type_id}'
 *        )
 *    );
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