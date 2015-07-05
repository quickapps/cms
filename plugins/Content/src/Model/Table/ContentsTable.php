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
namespace Content\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use \ArrayObject;

/**
 * Represents "contents" database table.
 *
 * @property \Content\Model\Table\ContentTypesTable $ContentTypes
 * @property \Content\Model\Table\ContentsTable $TranslationOf
 * @property \User\Model\Table\RolesTable $Roles
 * @property \User\Model\Table\ContentRevisionsTable $ContentRevisions
 * @property \User\Model\Table\ContentsTable $Translations
 * @property \User\Model\Table\UsersTable $Author
 * @property \User\Model\Table\UsersTable $ModifiedBy
 * @method \Search\Engine\EngineInterface engine(\Search\Engine\EngineInterface $engine = null)
 * @method void bindComments()
 * @method void unbindComments()
 * @method void configureFieldable(array $config)
 * @method void bindFieldable()
 * @method void unbindFieldable()
 * @method \Cake\Datasource\EntityInterface attachFields(\Cake\Datasource\EntityInterface $entity)
 * @method \Cake\Datasource\ResultSetDecorator findComments(\Cake\ORM\Query $query, $options)
 * @method \Cake\ORM\Query search(string $criteria, \Cake\ORM\Query|null $query = null)
 */
class ContentsTable extends Table
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
        $this->belongsTo('ContentTypes', [
            'className' => 'Content.ContentTypes',
            'propertyName' => 'content_type',
            'fields' => ['slug', 'name', 'description'],
            'conditions' => ['Contents.content_type_slug = ContentTypes.slug']
        ]);

        $this->belongsTo('TranslationOf', [
            'className' => 'Content\Model\Table\ContentsTable',
            'foreignKey' => 'translation_for',
            'propertyName' => 'translation_of',
            'fields' => ['slug', 'title', 'description'],
        ]);

        $this->belongsToMany('Roles', [
            'className' => 'User.Roles',
            'propertyName' => 'roles',
            'through' => 'Content.ContentsRoles',
        ]);

        $this->hasMany('ContentRevisions', [
            'className' => 'Content.ContentRevisions',
            'dependent' => true,
        ]);

        $this->hasMany('Translations', [
            'className' => 'Content\Model\Table\ContentsTable',
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
                if ($entity->has('content_type_slug')) {
                    return $entity->content_type_slug;
                }

                if ($entity->has('id')) {
                    return $this
                        ->get($entity->id, [
                            'fields' => ['id', 'content_type_slug'],
                            'fieldable' => false,
                        ])
                        ->content_type_slug;
                }

                return '';
            }
        ]);

        $this->addBehavior('Search.Searchable', [
            'fields' => function ($content) {
                $words = '';
                if ($content->has('title')) {
                    $words .= " {$content->title}";
                }

                if ($content->has('description')) {
                    $words .= " {$content->description}";
                }

                if (!$content->has('_fields')) {
                    return $words;
                }

                foreach ($content->_fields as $virtualField) {
                    $words .= " {$virtualField}";
                }

                return $words;
            }
        ]);

        $this->engine()->addOperator('promote', 'operatorPromote');
        $this->engine()->addOperator('author', 'operatorAuthor');
        $this->engine()->addOperator('limit', 'Search.Limit');
        $this->engine()->addOperator('modified', 'Search.Date', ['field' => 'modified']);
        $this->engine()->addOperator('created', 'Search.Date', ['field' => 'created']);
        $this->engine()->addOperator('type', 'Search.Generic', ['field' => 'content_type_slug', 'conjunction' => 'auto']);
        $this->engine()->addOperator('language', 'Search.Generic', ['field' => 'language', 'conjunction' => 'auto']);
        $this->engine()->addOperator('order', 'Search.Order', ['fields' => ['slug', 'title', 'description', 'sticky', 'created', 'modified']]);
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
                'notBlank' => [
                    'rule' => 'notBlank',
                    'message' => __d('content', 'You need to provide a title.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('content', 'Title need to be at least 3 characters long.'),
                ],
            ]);

        return $validator;
    }

    /**
     * This callback performs two action, saving revisions and checking publishing
     * constraints.
     *
     * - Saves a revision version of each content being saved if it has changed.
     *
     * - Verifies the publishing status and forces to be "false" if use has no
     *   publishing permissions for this content type.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Content\Model\Entity\Content $entity The entity being saved
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
     * Tries to create a revision for the given content.
     *
     * @param \Cake\ORM\Entity $entity The content
     * @return void
     */
    protected function _saveRevision(Entity $entity)
    {
        if ($entity->isNew()) {
            return;
        }

        try {
            $prev = TableRegistry::get('Content.Contents')->get($entity->id);
            $hash = $this->_calculateHash($prev);
            $exists = $this->ContentRevisions->exists([
                'ContentRevisions.content_id' => $entity->id,
                'ContentRevisions.hash' => $hash,
            ]);

            if (!$exists) {
                $revision = $this->ContentRevisions->newEntity([
                    'content_id' => $prev->id,
                    'summary' => $entity->get('edit_summary'),
                    'data' => $prev,
                    'hash' => $hash,
                ]);

                if (!$this->ContentRevisions->hasBehavior('Timestamp')) {
                    $this->ContentRevisions->addBehavior('Timestamp');
                }
                $this->ContentRevisions->save($revision);
            }
        } catch (\Exception $ex) {
            // unable to create content's review
        }
    }

    /**
     * Ensures that content content has the correct publishing status based in content
     * type restrictions.
     *
     * If it's a new content it will set the correct status. However if it's an
     * existing content and user has no publishing permissions this method will not
     * change content's status, so it will remain published if it was already
     * published by an administrator.
     *
     * @param \Cake\ORM\Entity $entity The content
     * @return void
     */
    protected function _ensureStatus(Entity $entity)
    {
        if (!$entity->has('status')) {
            return;
        }

        if (!$entity->has('content_type') &&
            ($entity->has('content_type_id') || $entity->has('content_type_slug'))
        ) {
            if ($entity->has('content_type_id')) {
                $type = $this->ContentTypes->get($entity->get('content_type_id'));
            } else {
                $type = $this->ContentTypes
                    ->find()
                    ->where(['content_type_slug' => $entity->get('content_type_id')])
                    ->limit(1)
                    ->first();
            }
        } else {
            $type = $entity->get('content_type');
        }

        if ($type && !$type->userAllowed('publish')) {
            if ($entity->isNew()) {
                $entity->set('status', false);
            } else {
                $entity->unsetProperty('status');
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
            $conditions = ["Contents.promote {$conjunction}" => 1];
        } elseif ($value === 'false') {
            $conditions = ['Contents.promote {$conjunction}' => 0];
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
                $query->orWhere(['Contents.created_by IN' => $subQuery]);
            } elseif ($token->where() === 'and') {
                $query->andWhere(['Contents.created_by IN' => $subQuery]);
            } else {
                $query->where(['Contents.created_by IN' => $subQuery]);
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
