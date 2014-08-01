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

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Represents "nodes" database table.
 *
 */
class NodesTable extends Table {

/**
 * List of implemented events.
 *
 * @return array
 */
	public function implementedEvents() {
		$events = [
			'Model.beforeSave' => [
				'callable' => 'beforeSave',
				'priority' => -10
			]
		];

		return $events;
	}

/**
 * Initialize a table instance. Called after the constructor.
 *
 * @param array $config Configuration options passed to the constructor
 * @return void
 */
	public function initialize(array $config) {
		$this->belongsTo('NodeTypes', [
			'className' => 'Node.NodeTypes',
			'fields' => ['slug', 'name', 'description'],
		]);

		$this->hasMany('NodeRevisions', [
			'className' => 'Node.NodeRevisions',
			'dependent' => true,
		]);

		$this->hasMany('Translations', [
			'className' => 'Node.Nodes',
			'foreignKey' => 'translation_for',
			'dependent' => true,
		]);

		$this->belongsTo('Author', [
			'className' => 'User.Users',
			'foreignKey' => 'created_by',
			'fields' => ['id', 'name', 'username']
		]);

		$this->addBehavior('Timestamp');
		$this->addBehavior('Comment.Commentable');
		$this->addBehavior('System.Sluggable');
		$this->addBehavior('Field.Fieldable', ['polymorphic_table_alias' => 'node_type_slug']);
		$this->addBehavior('Search.Searchable', ['fields' => ['title', '_fields']]);

		// CRITERIA: author:<john,peter,...,username>
		$this->addScopeTag('author', 'scopeAuthor');

		// CRITERIA: promote:true
		$this->addScopeTag('promote', 'scopePromote');

		// CRITERIA: type:node-type-slug
		$this->addScopeTag('type', 'scopeType');
	}

/**
 * Default validation rules set.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('title', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('node', 'You need to provide a title.'),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('node', 'Title need to be at least 3 characters long.'),
				],
			]);

		return $validator;
	}

/**
 * Saves a revision version of each node being saved.
 *
 * @param \Cake\ORM\Query $query
 * @param \Node\Model\Entity\Node $entity
 * @return void
 */
	public function beforeSave($query, $entity) {
		if (!$entity->isNew()) {
			$serialized = @serialize(TableRegistry::get('Node.Nodes')->get($entity->id));
			$hash = md5($serialized);
			$exists = $this->NodeRevisions->find()
				->select(['id'])
				->where([
					'NodeRevisions.node_id' => $entity->id,
					'NodeRevisions.hash' => $hash,
				])
				->first();

			if (!$exists) {
				$revision = $this->NodeRevisions->newEntity([
					'node_id' => $entity->id,
					'data' => $serialized,
					'hash' => $hash,
				]);
				$this->NodeRevisions->addBehavior('Timestamp');
				$this->NodeRevisions->save($revision);
			}
		}
	}

/**
 * Applies "promote:" criteria scope the given query.
 *
 * @param \Cake\ORM\Query $query
 * @param string $value
 * @param boolean $negate
 * @param string $orAnd and|or
 * @return void
 */
	public function scopePromote($query, $value, $negate, $orAnd) {
		$value = strtolower($value);

		if ($value === 'true') {
			$query->where(['Nodes.promote' => 1]);
		} elseif ($value === 'false') {
			$query->where(['Nodes.promote' => 0]);
		}

		return $query;
	}

/**
 * Applies "type:" criteria scope the given query.
 *
 * @param \Cake\ORM\Query $query
 * @param string $value
 * @param boolean $negate
 * @param string $orAnd and|or
 * @return void
 */
	public function scopeType($query, $value, $negate, $orAnd) {
		$value = explode(',', strtolower($value));
		$conjunction = $negate ? 'NOT IN' : 'IN';

		if ($orAnd === 'or') {
			$query->orWhere(["Nodes.node_type_slug {$conjunction}" => $value]);
		} elseif ($orAnd === 'and') {
			$query->andWhere(["Nodes.node_type_slug {$conjunction}" => $value]);
		} else {
			$query->where(["Nodes.node_type_slug {$conjunction}" => $value]);
		}

		return $query;
	}

/**
 * Applies "author:" criteria scope the given query.
 *
 * Filter nodes by author's username.
 *
 * @param \Cake\ORM\Query $query
 * @param string $value
 * @param boolean $negate
 * @param string $orAnd and|or
 * @return void
 */
	public function scopeAuthor($query, $value, $negate, $orAnd) {
		$value = explode(',', $value);

		if (!empty($value)) {
			$conjunction = $negate ? 'NOT IN' : 'IN';
			$subQuery = TableRegistry::get('User.Users')->find()
				->select(['id'])
				->where(["Users.username {$conjunction}" => $value]);

			if ($orAnd === 'or') {
				$query->orWhere(['Nodes.created_by IN' => $subQuery]);
			} elseif ($orAnd === 'and') {
				$query->andWhere(['Nodes.created_by IN' => $subQuery]);
			} else {
				$query->where(['Nodes.created_by IN' => $subQuery]);
			}
		}

		return $query;
	}

}
