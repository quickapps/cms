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
namespace Comment\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Allow entities to be commented.
 *
 * @author Christopher Castro <chris@quickapps.es>
 */
class CommentableBehavior extends Behavior {
/**
 * The table this behavior is attached to.
 *
 * @var Table
 */
	protected $_table;

/**
 * Enable/Diable this behavior.
 *
 * @var boolean
 */
	protected $_enabled = true;

/**
 * Default config
 *
 * These are merged with user-provided configuration when the behavior is used.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'implementedFinders' => [
			'comments' => 'findComments',
		],
		'post_validator' => 'default'
	];

/**
 * Constructor.
 *
 * Here we associate `Comments` table to the
 * table this behavior is attached to.
 *
 * @param Table $table The table this behavior is attached to.
 * @param array $config The config for this behavior.
 */
	public function __construct(Table $table, array $config = []) {
		$this->_table = $table;
		$this->_table->hasMany('Comments', [
			'className' => 'Comment\\Model\\Table\\CommentsTable',
			'foreignKey' => 'entity_id',
			'conditions' => [
				'table_alias' => strtolower($this->_table->alias()),
				'status >' => 0
			],
			'joinType' => 'LEFT',
			'dependent' => true
		]);

		parent::__construct($table, $config);
	}

/**
 * Attaches comments to each entity on find operation.
 *
 * @param \Cake\Event\Event $event
 * @param \Cake\ORM\Query $query
 * @param array $options
 * @param boolean $primary
 * @return void
 */
	public function beforeFind(Event $event, $query, $options, $primary) {
		if ($this->_enabled) {
			if ($query->count() > 0) {
				$pk = $this->_table->primaryKey();
				$tableAlias = $this->_table->alias();

				$query->contain([
					'Comments' => function ($query) {
						return $query->find('threaded')
							->order(['created' => 'DESC']);
					}
				]);

				// TODO: try to move this to CounterCacheBehavior to reduce DB queries
				$query->mapReduce(function ($entity, $key, $mapReduce) use ($pk, $tableAlias) {
					$entityId = $entity->{$pk};
					$entity->set('comment_count',
						TableRegistry::get('Comment.Comments')->find()
							->where(['entity_id' => $entityId, 'table_alias' => $tableAlias])
							->count()
					);
					$mapReduce->emit($entity, $key);
				});
			}
		}
	}

/**
 * Get comments for the given entity.
 *
 * @param Query $query
 * @param array $options
 * @return \Cake\ORM\Query
 * @throws \InvalidArgumentException When the 'for' key is not passed in $options
 */
	public function findComments(Query $query, $options) {
		if ($this->_enabled) {
			$table_alias = strtolower($this->_table->alias());
			$options += ['for' => null];

			if (empty($options['for'])) {
				throw new \InvalidArgumentException("The 'for' key is required for find('children')");
			}

			$query->contain(['Comments.Users']);
		}

		return $query;
	}

/**
 * Enables this behavior.
 *
 * Comments will be attached to entities.
 *
 * @return void
 */
	public function bindComments() {
		$this->_enabled = true;
	}

/**
 * Disables this behavior.
 *
 * Comments won't be attached to entities.
 *
 * @return void
 */
	public function unbindComments() {
		$this->_enabled = false;
	}

}
