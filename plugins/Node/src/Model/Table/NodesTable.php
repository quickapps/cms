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
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use \ArrayObject;

/**
 * Represents "nodes" database table.
 *
 * @property \Node\Model\Table\NodeTypesTable $NodeTypes
 * @property \Node\Model\Table\NodesTable $TranslationOf
 * @property \User\Model\Table\RolesTable $Roles
 * @property \User\Model\Table\NodeRevisionsTable $NodeRevisions
 * @property \User\Model\Table\NodesTable $Translations
 * @property \User\Model\Table\UsersTable $Author
 * @method void addSearchOperator(string $name, mixed $handler, array $options = [])
 * @method void enableSearchOperator(string $name)
 * @method void disableSearchOperator(string $name)
 * @method void bindComments()
 * @method void unbindComments()
 * @method void configureFieldable(array $config)
 * @method void bindFieldable()
 * @method void unbindFieldable()
 * @method \Cake\Datasource\ResultSetDecorator findComments(\Cake\ORM\Query $query, $options)
 * @method \Cake\ORM\Entity attachFields(\Cake\ORM\Entity $entity)
 * @method \Cake\ORM\Query search(string $criteria, \Cake\ORM\Query|null $query = null)
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
                'priority' => 16 // after Fieldable Behavior
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
            'className' => 'Node\Model\Table\NodesTable',
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
            'className' => 'Node\Model\Table\NodesTable',
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
        $this->addBehavior('User.WhoDidIt', [
            'idCallable' => function () {
                return user()->get('id');
            }
        ]);
        $this->addBehavior('Field.Fieldable', [
            'bundle' => function ($entity) {
                if ($entity->has('node_type_slug')) {
                    return $entity->node_type_slug;
                }

                if ($entity->has('id')) {
                    return $this
                        ->get($entity->id, [
                            'fields' => ['id', 'node_type_slug'],
                            'fieldable' => false,
                        ])
                        ->node_type_slug;
                }

                return '';
            }
        ]);

        $this->addBehavior('Search.Searchable', [
            'fields' => function ($node) {
                $words = '';
                if ($node->has('title')) {
                    $words .= " {$node->title}";
                }

                if ($node->has('description')) {
                    $words .= " {$node->description}";
                }

                if (!$node->has('_fields')) {
                    return $words;
                }

                foreach ($node->_fields as $virtualField) {
                    $words .= " {$virtualField}";
                }

                return $words;
            }
        ]);

        $this->addSearchOperator('promote', 'operatorPromote');
        $this->addSearchOperator('author', 'operatorAuthor');
        $this->addSearchOperator('limit', 'Search.Limit');
        $this->addSearchOperator('modified', 'Search.Date', ['field' => 'modified']);
        $this->addSearchOperator('created', 'Search.Date', ['field' => 'created']);
        $this->addSearchOperator('type', 'Search.Generic', ['field' => 'node_type_slug', 'conjunction' => 'auto']);
        $this->addSearchOperator('language', 'Search.Generic', ['field' => 'language', 'conjunction' => 'auto']);
        $this->addSearchOperator('order', 'Search.Order', ['fields' => ['slug', 'title', 'description', 'sticky', 'created', 'modified']]);
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
     * This callback performs two action, saving revisions and checking publishing
     * constraints.
     *
     * - Saves a revision version of each node being saved if it has changed.
     *
     * - Verifies the publishing status and forces to be "false" if use has no
     *   publishing permissions for this content type.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Node\Model\Entity\Node $entity The entity being saved
     * @param \ArrayObject $options Array of options
     * @return bool True on success
     */
    public function beforeSave(Event $event, Entity $entity, ArrayObject $options = null)
    {
        $this->_saveRevision($entity);
        $this->_ensureStatus($entity);
        return true;
    }

    /**
     * Tries to create a revision for the given content node.
     *
     * @param \Cake\ORM\Entity $node The node
     * @return void
     */
    protected function _saveRevision(Entity $entity)
    {
        if ($entity->isNew()) {
            return true;
        }

        try {
            $prev = TableRegistry::get('Node.Nodes')->get($entity->id);
            $hash = $this->_calculateHash($prev);
            $exists = $this->NodeRevisions->exists([
                'NodeRevisions.node_id' => $entity->id,
                'NodeRevisions.hash' => $hash,
            ]);

            if (!$exists) {
                $revision = $this->NodeRevisions->newEntity([
                    'node_id' => $prev->id,
                    'summary' => $entity->get('edit_summary'),
                    'data' => $prev,
                    'hash' => $hash,
                ]);

                if (!$this->NodeRevisions->hasBehavior('Timestamp')) {
                    $this->NodeRevisions->addBehavior('Timestamp');
                }
                $this->NodeRevisions->save($revision);
            }
        } catch (\Exception $ex) {
            // unable to create node's review
        }
    }

    /**
     * Ensures that content node has the correct publishing status based in content
     * type restrictions.
     *
     * If it's a new node it will set the correct status. However if it's an
     * existing node and user has no publishing permissions this method will not
     * change node's status, so it will remain published if it was already published
     * by an administrator.
     *
     * @param \Cake\ORM\Entity $entity The node
     * @return void
     */
    protected function _ensureStatus(Entity $entity)
    {
        if (!$entity->has('status')) {
            return;
        }

        $type = null;
        if (!$entity->has('node_type') &&
            ($entity->has('node_type_id') || $entity->has('node_type_slug'))
        ) {
            if ($entity->has('node_type_id')) {
                $type = $this->NodeTypes->get($entity->get('node_type_id'));
            } else {
                $type = $this->NodeTypes
                    ->find()
                    ->where(['node_type_slug' => $entity->get('node_type_id')])
                    ->limit(1)
                    ->first();
            }
        } else {
            $type = $entity->get('node_type');
        }

        if ($type && !$type->userAllowed('publish')) {
            if ($entity->isNew()) {
                $entity->set('status', false);
            } else {
                $entity->unsetPropery('status');
            }
        }
    }

    /**
     * Handles "promote" search operator.
     *
     *     promote:<true|false>
     *
     * @param \Cake\ORM\Query $query The query object
     * @param \Search\Token $token Operator token
     * @return \Cake\ORM\Query
     */
    public function operatorPromote(Query $query, $token)
    {
        $value = strtolower($token->value());
        $conjunction = $token->negated() ? '<>' : '';
        $conditions = [];

        if ($value === 'true') {
            $conditions = ["Nodes.promote {$conjunction}" => 1];
        } elseif ($value === 'false') {
            $conditions = ['Nodes.promote {$conjunction}' => 0];
        }

        if (!empty($conditions)) {
            if ($token->where() === 'or') {
                $query->orWhere($conditions);
            } elseif ($token->where() === 'and') {
                $query->andWhere($conditions);
            } else {
                $query->where($conditions);
            }
        }

        return $query;
    }

    /**
     * Handles "author" search operator.
     *
     *     author:<username1>,<username2>, ...
     *
     * @param \Cake\ORM\Query $query The query object
     * @param \Search\Token $token Operator token
     * @return \Cake\ORM\Query
     */
    public function operatorAuthor(Query $query, $token)
    {
        $value = explode(',', $token->value());

        if (!empty($value)) {
            $conjunction = $token->negated() ? 'NOT IN' : 'IN';
            $subQuery = TableRegistry::get('User.Users')->find()
                ->select(['id'])
                ->where(["Users.username {$conjunction}" => $value]);

            if ($token->where() === 'or') {
                $query->orWhere(['Nodes.created_by IN' => $subQuery]);
            } elseif ($token->where() === 'and') {
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
     * @param \Cake\Datasource\EntityInterface $entity The entity for which calculate its hash
     * @return string MD5 hash for this particular entity
     */
    protected function _calculateHash($entity)
    {
        $hash = [];
        foreach ($entity->visibleProperties() as $property) {
            if (strpos($property, 'created') === false &&
                strpos($property, 'created_by') === false &&
                strpos($property, 'modified') === false &&
                strpos($property, 'modified_by') === false
            ) {
                if ($property == '_fields') {
                    foreach ($entity->get('_fields') as $field) {
                        if ($field instanceof \Field\Model\Entity\Field) {
                            $hash[] = is_object($field->value) || is_array($field->value) ? md5(serialize($field->value)) : md5($field->value);
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
