<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Node\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Represents "node_types" database table.
 *
 */
class NodeTypesTable extends Table {

/**
 * Used to update all existing contents when content type changes its slug.
 *
 * @var boolean
 */
	protected $_oldSlug = false;

/**
 * Alter the schema used by this table.
 *
 * @param \Cake\Database\Schema\Table $table The table definition fetched from database
 * @return \Cake\Database\Schema\Table the altered schema
 */
	protected function _initializeSchema(Schema $table) {
		$table->columnType('settings', 'serialized');
		$table->columnType('defaults', 'serialized');
		return $table;
	}

/**
 * Initialize a table instance. Called after the constructor.
 *
 * @param array $config Configuration options passed to the constructor
 * @return void
 */
	public function initialize(array $config) {
		$this->addBehavior('System.Sluggable', [
			'label' => 'name',
			'slug' => 'slug',
			'on' => 'insert',
		]);
	}

	public function beforeSave(Event $event, Entity $entity, $options) {
		if (!$entity->isNew() && $entity->has('slug') && $entity->has('id')) {
			$oldSlug = $this->find()
				->select(['NodeTypes.slug'])
				->where(['NodeTypes.id' => $entity->id])
				->first();

			if ($oldSlug && $oldSlug->slug != $entity->slug) {
				$this->_oldSlug = $oldSlug->slug;
			}
		}
	}

	public function afterSave(Event $event, Entity $entity, $options) {
		if ($this->_oldSlug && !$entity->isNew() && $entity->has('slug')) {
			// update existing contents
			TableRegistry::get('Node.Nodes')->updateAll(
				['node_type_slug' => $entity->slug],
				['node_type_slug' => $this->_oldSlug]
			);

			// fix field instances references
			TableRegistry::get('Field.FieldInstances')->updateAll(
				['table_alias' => "nodes_{$entity->slug}"],
				['table_alias' => "nodes_{$this->_oldSlug}"]
			);

			// fix stored values for each field instance
			TableRegistry::get('Field.FieldValues')->updateAll(
				['table_alias' => "nodes_{$entity->slug}"],
				['table_alias' => "nodes_{$this->_oldSlug}"]
			);

			// try to fix existing urls
			$links = TableRegistry::get('Menu.MenuLinks')->find('all')->where(['MenuLinks.url LIKE' => "/{$this->_oldSlug}/%"]);
			foreach ($links as $link) {
				$link->set('url', str_replace_once("/{$this->_oldSlug}/", "/{$entity->slug}/", $link->url));
				TableRegistry::get('Menu.MenuLinks')->save($link, ['validate' => false]);
			}

			// regenerate snapshot cache
			snapshot();
		}

		$this->_oldSlug = false;
	}

}
