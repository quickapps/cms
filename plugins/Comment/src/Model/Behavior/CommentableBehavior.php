<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Comment\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Allow entities to be commented.
 *
 */
class CommentableBehavior extends Behavior
{

    /**
     * The table this behavior is attached to.
     *
     * @var Table
     */
    protected $_table;

    /**
     * Enable/Diable this behavior.
     *
     * @var bool
     */
    protected $_enabled = false;

    /**
     * Default configuration.
     *
     * These are merged with user-provided configuration when the behavior is used.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'implementedFinders' => [
            'comments' => 'findComments',
        ],
        'implementedMethods' => [
            'bindComments' => 'bindComments',
            'unbindComments' => 'unbindComments',
        ],
        'count' => false,
        'order' => ['Comments.created' => 'DESC'],
    ];

    /**
     * Constructor.
     *
     * Here we associate `Comments` table with the table this behavior is attached to.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to.
     * @param array $config The config for this behavior.
     */
    public function __construct(Table $table, array $config = [])
    {
        $this->_table = $table;
        $this->_table->hasMany('Comments', [
            'className' => 'Comment.Comments',
            'foreignKey' => 'entity_id',
            'conditions' => [
                'table_alias' => Inflector::underscore($this->_table->alias()),
                'status' => 'approved',
            ],
            'joinType' => 'LEFT',
            'dependent' => true,
        ]);

        parent::__construct($table, $config);
    }

    /**
     * Attaches comments to each entity on find operation.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Query $query The query object
     * @param array $options Additional options as an array
     * @param bool $primary Whether is find is a primary query or not
     * @return void
     */
    public function beforeFind(Event $event, $query, $options, $primary)
    {
        if ($this->_enabled && $query->count() > 0) {
            $pk = $this->_table->primaryKey();
            $tableAlias = Inflector::underscore($this->_table->alias());

            $query->contain([
                'Comments' => function ($query) {
                    return $query->find('threaded')
                        ->contain(['Users'])
                        ->order($this->config('order'));
                },
            ]);

            if ($this->config('count') ||
                (isset($options['comments_count']) && $options['comments_count'] === true)
            ) {
                $query->formatResults(function ($results) use ($pk, $tableAlias) {
                    return $results->map(function ($entity) use ($pk, $tableAlias) {
                        $entityId = $entity->{$pk};
                        $count = TableRegistry::get('Comment.Comments')->find()
                            ->where(['entity_id' => $entityId, 'table_alias' => $tableAlias])
                            ->count();
                        $entity->set('comments_count', $count);
                        return $entity;
                    });
                });
            }
        }
    }

    /**
     * Get comments for the given entity.
     *
     * Allows you to get all comments even when this behavior was disabled
     * using `unbindComments()`.
     *
     * ### Usage:
     *
     *     // in your controller, gets comments for post which id equals 2
     *     $postComments = $this->Posts->find('comments', ['for' => 2]);
     *
     * @param \Cake\ORM\Query $query The query object
     * @param array $options Additional options as an array
     * @return \Cake\Datasource\ResultSetDecorator Comments collection
     * @throws \InvalidArgumentException When the 'for' key is not passed in $options
     */
    public function findComments(Query $query, $options)
    {
        $tableAlias = Inflector::underscore($this->_table->alias());

        if (empty($options['for'])) {
            throw new \InvalidArgumentException("The 'for' key is required for find('children')");
        }

        $comments = $this->_table->Comments
            ->find('threaded')
            ->where(['table_alias' => $tableAlias, 'entity_id' => $options['for']])
            ->order($this->config('order'))
            ->all();

        return $comments;
    }

    /**
     * Enables this behavior.
     *
     * Comments will be attached to entities.
     *
     * @return void
     */
    public function bindComments()
    {
        $this->_enabled = true;
    }

    /**
     * Disables this behavior.
     *
     * Comments won't be attached to entities.
     *
     * @return void
     */
    public function unbindComments()
    {
        $this->_enabled = false;
    }
}
