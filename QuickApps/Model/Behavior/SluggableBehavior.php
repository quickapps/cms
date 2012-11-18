<?php
/**
 * Model behavior to support generation of slugs for models.
 *
 * PHP version 5
 *
 * @package	 QuickApps.Model.Behavior
 * @author	 Mariano Iglesias
 * @link	 http://cake-syrup.sourceforge.net/ingredients/sluggable-behavior/
 * @version	 $Revision: 36 $
 * @license	 http://www.opensource.org/licenses/mit-license.php The MIT License
 */ 
class SluggableBehavior extends ModelBehavior {
/**
 * Contain settings indexed by model name.
 *
 * @var array
 */
	private $__settings = array();

/**
 * Initiate behavior for the model using specified settings. Available settings:
 *
 * - label:	 (array | string, optional) set to the field name that contains the
 *				 string from where to generate the slug, or a set of field names to
 *				 concatenate for generating the slug. DEFAULTS TO: title
 *
 * - slug:		(string, optional) name of the field name that holds generated slugs.
 *				 DEFAULTS TO: slug
 *
 * - separator:	(string, optional) separator character / string to use for replacing
 *				 non alphabetic characters in generated slug. DEFAULTS TO: -
 *
 * - length:	(integer, optional) maximum length the generated slug can have.
 *				 DEFAULTS TO: 200
 *
 * - overwrite: (boolean, optional) set to true if slugs should be re-generated when
 *				 updating an existing record. DEFAULTS TO: true
 *
 * @param Model $Model Model using the behaviour
 * @param array $settings Settings to override for model.
 */
	public function setup(Model $Model, $settings = array()) {
		$default = array('label' => array('title'), 'slug' => 'slug', 'separator' => '-', 'length' => 200, 'overwrite' => true, 'translation' => null);

		if (!isset($this->__settings[$Model->alias])) {
			$this->__settings[$Model->alias] = $default;
		}

		$this->__settings[$Model->alias] = am($this->__settings[$Model->alias], (is_array($settings) ?  $settings : array()));
	}

/**
 * Run before a model is saved, used to set up slug for model.
 *
 * @param Model $Model Model about to be saved.
 * @return boolean true if save should proceed, false otherwise
 */
	public function beforeSave(Model $Model) {
		$return = parent::beforeSave($Model);

		if (!is_array($this->__settings[$Model->alias]['label'])) {
			$this->__settings[$Model->alias]['label'] = array($this->__settings[$Model->alias]['label']);
		}

		foreach ($this->__settings[$Model->alias]['label'] as $field) {
			if (!$Model->hasField($field)) {
				return $return;
			}
		}

		if ($Model->hasField($this->__settings[$Model->alias]['slug']) && ($this->__settings[$Model->alias]['overwrite'] || empty($Model->id))) {
			$label = '';

			foreach ($this->__settings[$Model->alias]['label'] as $field) {
				if (!empty($Model->data[$Model->alias][$field])) {
					$label .= (!empty($label) ?  ' ' : '') . $Model->data[$Model->alias][$field];
				}
			}

			if (!empty($label)) {
				$slug = $this->__slug($label, $this->__settings[$Model->alias]);
				$conditions = array($Model->alias . '.' . $this->__settings[$Model->alias]['slug'].' LIKE' => $slug.'%'); // Fix 2

				if (!empty($Model->id)) {
					$conditions['not'] = array(
						$Model->alias . '.' . $Model->primaryKey =>
							$Model->id
					);
				}

				$result = $Model->find('all', array('conditions' => $conditions, 'fields' => array($Model->primaryKey, $this->__settings[$Model->alias]['slug']), 'recursive' => -1));
				$sameUrls = null;

				if (!empty($result)) {
					$sameUrls = Hash::extract($result, '{n}.' . $Model->alias . '.' . $this->__settings[$Model->alias]['slug']);
				}

				if (!empty($sameUrls)) {
					$begginingSlug = $slug;
					$index = 1;

					while($index > 0) {
						if (!in_array($begginingSlug . $this->__settings[$Model->alias]['separator'] . $index, $sameUrls)) {
							$slug = $begginingSlug . $this->__settings[$Model->alias]['separator'] . $index;
							$index = -1;
						}

						$index++;
					}
				}

				if (!empty($Model->whitelist) && !in_array($this->__settings[$Model->alias]['slug'], $Model->whitelist)) {
					$Model->whitelist[] = $this->__settings[$Model->alias]['slug'];
				}

				$Model->data[$Model->alias][$this->__settings[$Model->alias]['slug']] = $slug;
			}
		}

		return $return;
	}

/**
 * Generate a slug for the given string using specified settings.
 *
 * @param string $string String from where to generate slug
 * @param array $settings Settings to use (looks for 'separator' and 'length')
 * @return string Slug for given string
 */
	private function __slug($string, $settings) {
		$string = Inflector::slug(strtolower($string), $settings['separator']);

		if (strlen($string) > $settings['length']) {
			$string = substr($string, 0, $settings['length']);
		}

		return $string;
	}
}