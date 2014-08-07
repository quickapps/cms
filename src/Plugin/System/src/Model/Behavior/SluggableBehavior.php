<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace System\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

/**
 * Sluggable Behavior.
 *
 * Allows entities to have a unique `slug`.
 */
class SluggableBehavior extends Behavior {

/**
 * Table which this behavior is attached to.
 *
 * @var \Cake\ORM\Table
 */
	protected $_table;

	protected $_enabled = true;

/**
 * Default configuration.
 *
 * - `label`: Set to the field name that contains the string from where to generate the slug,
 * or a set of field names to concatenate for generating the slug. `title` by default.
 * - `slug`: Name of the field name that holds generated slugs. `slug` by default.
 * - `separator`: Separator char. `-` by default. e.g.: `one-two-three`
 * - `on`: When to generate new slugs. `insert`, `update` or `both` (by default).
 * - `length`: Maximum length the generated slug can have. default to 200.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'label' => 'title',
		'slug' => 'slug',
		'separator' => '-',
		'on' => 'both',
		'length' => 200,
		'implementedMethods' => [
			'bindSluggable' => 'bindSluggable',
			'unbindSluggable' => 'unbindSluggable',
		],
	];

/**
 * Constructor.
 *
 * @param \Cake\ORM\Table $table The table this behavior is attached to
 * @param array $config Configuration array for this behavior
 */
	public function __construct(Table $table, array $config = []) {
		$this->_table = $table;
		parent::__construct($table, $config);
	}

/**
 * Run before a model is saved, used to set up slug for model.
 *
 * @param \Cake\Event\Event $event
 * @param \Cake\ORM\Entity $entity
 * @param array $options
 * @return boolean True if save should proceed, false otherwise
 */
	public function beforeSave(Event $event, $entity, $options) {
		if (!$this->_enabled) {
			return true;
		}

		$config = $this->config();

		if (!is_array($config['label'])) {
			$config['label'] = [$config['label']];
		}

		foreach ($config['label'] as $field) {
			if (!$entity->has($field)) {
				return false;
			}
		}

		$isNew = $entity->isNew();

		if (
			($isNew && in_array($config['on'], ['insert', 'both'])) ||
			(!$isNew && in_array($config['on'], ['update', 'both']))
		) {
			$label = '';

			foreach ($config['label'] as $field) {
				$val = $entity->get($field);
				$label .= !empty($val) ?  " {$val}" : '';
			}

			if (!empty($label)) {
				$slug = $this->_slug($label, $entity);
				$entity->set($config['slug'], $slug);
			}
		}

		return true;
	}

	public function bindSluggable() {
		$this->_enabled = true;
	}

	public function unbindSluggable() {
		$this->_enabled = false;
	}

/**
 * Generate a slug for the given string and entity.
 *
 * The generated slug is unique on the whole table.
 *
 * @param string $string string from where to generate slug
 * @param \Cake\ORM\Entity $entity
 * @return string Slug for given string
 */
	protected function _slug($string, $entity) {
		$config = $this->config();
		$slug = Inflector::slug(strtolower($string), $config['separator']);
		$pk = $this->_table->primaryKey();

		if (strlen($slug) > $config['length']) {
			$slug = substr($slug, 0, $config['length']);
		}

		$conditions = [
			"{$config['slug']} LIKE" => "{$slug}%",
		];

		if ($entity->has($pk)) {
			$conditions["{$pk} NOT IN"] = [$entity->{$pk}];
		}

		$same = $this->_table->find()
			->where($conditions)
			->all()
			->extract($config['slug'])
			->toArray();

		if (!empty($same)) {
			$initialSlug = $slug;
			$index = 1;

			while ($index > 0) {
				$nextSlug = "{$initialSlug}{$config['separator']}{$index}";

				if (!in_array($nextSlug, $same)) {
					$slug = $nextSlug;
					$index = -1;
				}

				$index++;
			}
		}

		return $slug;
	}

}
