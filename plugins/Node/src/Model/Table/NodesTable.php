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
namespace Node\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use \ArrayObject;

/**
 * Represents "nodes" database table.
 *
 */
class NodesTable extends Table
{

    /**
     * List of implemented events.
     *
     * @return array
     */
    public function implementedEvents()
    {
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
    public function initialize(array $config)
    {
        $this->belongsTo('NodeTypes', [
            'className' => 'Node.NodeTypes',
            'propertyName' => 'node_type',
            'fields' => ['slug', 'name', 'description'],
            'conditions' => ['Nodes.node_type_slug = NodeTypes.slug']
        ]);

        $this->belongsTo('TranslationOf', [
            'className' => 'Node.Nodes',
            'foreignKey' => 'translation_for',
            'propertyName' => 'translation_of',
            'fields' => ['slug', 'title', 'description'],
        ]);

        $this->belongsToMany('Roles', [
            'className' => 'User.Roles',
            'propertyName' => 'roles',
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
        $this->addBehavior('Sluggable');
        $this->addBehavior('Field.Fieldable', [
            'bundle' => function ($entity, $table) {
                $nodeTypeSlug = '';
                if ($entity->has('node_type_slug')) {
                    $nodeTypeSlug = $entity->node_type_slug;
                } elseif ($entity->has('id')) {
                    $nodeTypeSlug = $table->get($entity->id, [
                        'fields' => ['id', 'node_type_slug'],
                        'fieldable' => false,
                    ])
                    ->node_type_slug;
                }

                return $nodeTypeSlug;
            }
        ]);
        $this->addBehavior('Search.Searchable', [
            'fields' => function ($node) {
                $words = "{$node->title} {$node->description}";
                if (!empty($node->_fields)) {
                    foreach ($node->_fields as $vf) {
                        $words .= ' ' . trim($vf->value);
                    }
                }

                return $words;
            }
        ]);

        $this->addSearchOperator('created', 'operatorCreated');
        $this->addSearchOperator('limit', 'operatorLimit');
        $this->addSearchOperator('order', 'operatorOrder');
        $this->addSearchOperator('author', 'operatorAuthor');
        $this->addSearchOperator('promote', 'operatorPromote');
        $this->addSearchOperator('type', 'operatorType');
        $this->addSearchOperator('language', 'operatorLanguage');
    }

    /**
     * Default validation rules set.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
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
     * Saves a revision version of each node being saved if it has changed.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Node\Model\Entity\Node $entity The entity being saved
     * @param \ArrayObject $options Array of options
     * @return void
     */
    public function beforeSave(Event $event, $entity, ArrayObject $options = null)
    {
        if (!$entity->isNew()) {
            $prev = TableRegistry::get('Node.Nodes')->get($entity->id);
            $hash = $this->_calculateHash($prev);
            $exists = $this->NodeRevisions
                ->exists([
                    'NodeRevisions.node_id' => $entity->id,
                    'NodeRevisions.hash' => $hash,
                ]);

            if (!$exists) {
                $revision = $this->NodeRevisions->newEntity([
                    'node_id' => $prev->id,
                    'data' => $prev,
                    'hash' => $hash,
                ]);

                if (!$this->NodeRevisions->hasBehavior('Timestamp')) {
                    $this->NodeRevisions->addBehavior('Timestamp');
                }
                $this->NodeRevisions->save($revision);
            }
        }

        return true;
    }

    /**
     * Handles "created" search operator.
     *
     *     created:<date>
     *     created:<date1>..<date2>
     *
     * Dates must be in YEAR-MONTH-DATE format. e.g. `2014-12-30`
     *
     * @param \Cake\ORM\Query $query The query object
     * @param string $value Operator's arguments
     * @param bool $negate Whether this operator was negated or not
     * @param string $orAnd and|or
     * @return void
     */
    public function operatorCreated(Query $query, $value, $negate, $orAnd)
    {
        if (strpos($value, '..') !== false) {
            list($dateLeft, $dateRight) = explode('..', $value);
        } else {
            $dateLeft = $dateRight = $value;
        }

        $dateLeft = preg_replace('/[^0-9\-]/', '', $dateLeft);
        $dateRight = preg_replace('/[^0-9\-]/', '', $dateRight);
        $range = [$dateLeft, $dateRight];
        foreach ($range as &$date) {
            $parts = explode('-', $date);
            $year = !empty($parts[0]) ? intval($parts[0]) : date('Y');
            $month = !empty($parts[1]) ? intval($parts[1]) : 1;
            $day = !empty($parts[2]) ? intval($parts[2]) : 1;

            $year = (1 <= $year && $year <= 32767) ? $year : date('Y');
            $month = (1 <= $month && $month <= 12) ? $month : 1;
            $day = (1 <= $month && $month <= 31) ? $day : 1;

            $date = date('Y-m-d', strtotime("{$year}-{$month}-{$day}"));
        }

        list($dateLeft, $dateRight) = $range;
        if (strtotime($dateLeft) > strtotime($dateRight)) {
            $tmp = $dateLeft;
            $dateLeft = $dateRight;
            $dateRight = $tmp;
        }

        if ($dateLeft !== $dateRight) {
            $not = $negate ? ' NOT' : '';
            $conditions = [
                "AND{$not}" => [
                    'Nodes.created >=' => $dateLeft,
                    'Nodes.created <=' => $dateRight,
                ]
            ];
        } else {
            $cmp = $negate ? '<=' : '>=';
            $conditions = ["Nodes.created {$cmp}" => $dateLeft];
        }

        if ($orAnd === 'or') {
            $query->orWhere($conditions);
        } elseif ($orAnd === 'and') {
            $query->andWhere($conditions);
        } else {
            $query->where($conditions);
        }

        return $query;
    }

    /**
     * Handles "limit" search operator.
     *
     *     limit:<number>
     *
     * @param \Cake\ORM\Query $query The query object
     * @param string $value Operator's arguments
     * @param bool $negate Whether this operator was negated or not
     * @param string $orAnd and|or
     * @return void
     */
    public function operatorLimit(Query $query, $value, $negate, $orAnd)
    {
        if ($negate) {
            return $query;
        }

        $value = intval($value);

        if ($value > 0) {
            $query->limit($value);
        }

        return $query;
    }

    /**
     * Handles "order" search operator.
     *
     *     order:<field1>,<asc|desc>;<field2>,<asc,desc>; ...
     *
     * @param \Cake\ORM\Query $query The query object
     * @param string $value Operator's arguments
     * @param bool $negate Whether this operator was negated or not
     * @param string $orAnd and|or
     * @return void
     */
    public function operatorOrder(Query $query, $value, $negate, $orAnd)
    {
        if ($negate) {
            return $query;
        }

        $value = strtolower($value);
        $split = explode(';', $value);

        foreach ($split as $segment) {
            $parts = explode(',', $segment);
            if (count($parts) === 2 &&
                in_array($parts[1], ['asc', 'desc']) &&
                in_array($parts[0], ['slug', 'title', 'description', 'sticky', 'created', 'modified'])
            ) {
                $field = $parts[0];
                $dir = $parts[1];
                $query->order(["Nodes.{$field}" => $dir]);
            }
        }

        return $query;
    }

    /**
     * Handles "promote" search operator.
     *
     *     promote:<true|false>
     *
     * @param \Cake\ORM\Query $query The query object
     * @param string $value Operator's arguments
     * @param bool $negate Whether this operator was negated or not
     * @param string $orAnd and|or
     * @return void
     */
    public function operatorPromote(Query $query, $value, $negate, $orAnd)
    {
        $value = strtolower($value);
        $conjunction = $negate ? '<>' : '';
        $conditions = [];

        if ($value === 'true') {
            $conditions = ["Nodes.promote {$conjunction}" => 1];
        } elseif ($value === 'false') {
            $conditions = ['Nodes.promote {$conjunction}' => 0];
        }

        if (!empty($conditions)) {
            if ($orAnd === 'or') {
                $query->orWhere($conditions);
            } elseif ($orAnd === 'and') {
                $query->andWhere($conditions);
            } else {
                $query->where($conditions);
            }
        }

        return $query;
    }

    /**
     * Handles "type" search operator.
     *
     *     type:<slug1>,<slug2>, ...
     *
     * @param \Cake\ORM\Query $query The query object
     * @param string $value Operator's arguments
     * @param bool $negate Whether this operator was negated or not
     * @param string $orAnd and|or
     * @return void
     */
    public function operatorType(Query $query, $value, $negate, $orAnd)
    {
        $value = explode(',', strtolower($value));
        $conjunction = $negate ? 'NOT IN' : 'IN';
        $conditions = ["Nodes.node_type_slug {$conjunction}" => $value];

        if ($orAnd === 'or') {
            $query->orWhere($conditions);
        } elseif ($orAnd === 'and') {
            $query->andWhere($conditions);
        } else {
            $query->where($conditions);
        }

        return $query;
    }

    /**
     * Handles "author" search operator.
     *
     *     author:<username1>,<username2>, ...
     *
     * @param \Cake\ORM\Query $query The query object
     * @param string $value Operator's arguments
     * @param bool $negate Whether this operator was negated or not
     * @param string $orAnd and|or
     * @return void
     */
    public function operatorAuthor(Query $query, $value, $negate, $orAnd)
    {
        $value = explode(',', $value);

        if (!empty($value)) {
            $conjunction = $negate ? 'NOT IN' : 'IN';
            $conditions = ["Nodes.language {$conjunction}" => $value];

            if ($orAnd === 'or') {
                $query->orWhere($conditions);
            } elseif ($orAnd === 'and') {
                $query->andWhere($conditions);
            } else {
                $query->where($conditions);
            }
        }

        return $query;
    }

    /**
     * Handles "language" search operator.
     *
     *     language:<lang1>,<lang2>, ...
     *
     * @param \Cake\ORM\Query $query The query object
     * @param string $value Operator's arguments
     * @param bool $negate Whether this operator was negated or not
     * @param string $orAnd and|or
     * @return void
     */
    public function operatorLanguage(Query $query, $value, $negate, $orAnd)
    {
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

    /**
     * Generates a unique hash for the given entity.
     *
     * Used by revision system to detect if an entity has changed or not.
     *
     * @param \Cake\ORM\Entity $entity The entity for which calculate its hash
     * @return string MD5 hash
     */
    protected function _calculateHash($entity)
    {
        $hash = [];
        foreach ($entity->visibleProperties() as $property) {
            if (strpos($property, 'created') === false && strpos($property, 'modified') === false) {
                if ($property == '_fields') {
                    foreach ($entity->get('_fields') as $field) {
                        if ($field instanceof \Field\Model\Entity\Field) {
                            $raw = is_object($field->raw) || is_array($field->raw) ? serialize($field->raw) : $field->raw;
                            $hash[] = $field->value . $raw;
                        }
                    }
                } else {
                    $hash[] = $entity->get($property);
                }
            }
        }

        return md5(serialize($hash));
    }
}
