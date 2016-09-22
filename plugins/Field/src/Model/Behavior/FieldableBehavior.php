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
namespace Field\Model\Behavior;

use Cake\Collection\CollectionInterface;
use Cake\Datasource\EntityInterface;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Eav\Model\Behavior\EavBehavior;
use Field\Collection\FieldCollection;
use Field\Model\Entity\Field;
use \ArrayObject;

/**
 * Fieldable Behavior.
 *
 * A more flexible EAV approach. Allows additional fields to be attached to Tables.
 * Any Table (Contents, Users, etc.) can use this behavior to make itself `fieldable`
 * and thus allow fields to be attached to it.
 *
 * The Field API defines two primary data structures, FieldInstance and FieldValue:
 *
 * - FieldInstance: is a Field attached to a single Table. (Schema equivalent: column)
 * - FieldValue: is the stored data for a particular [FieldInstance, Entity]
 *   tuple of your Table. (Schema equivalent: cell value)
 *
 * **This behavior allows you to add _virtual columns_ to your table schema.**
 *
 * @link https://github.com/quickapps/docs/blob/2.x/en/developers/field-api.rst
 */
class FieldableBehavior extends EavBehavior
{

    /**
     * Used for reduce BD queries and allow inter-method communication.
     * Example, it allows to pass some information from beforeDelete() to
     * afterDelete().
     *
     * @var array
     */
    protected $_cache = [];

    /**
     * Default configuration.
     *
     * These are merged with user-provided configuration when the behavior is used.
     * Available options are:
     *
     * - `bundle`: Bundle within this the table. Can be a string or a callable
     *   method that must return a string to use as bundle. Default null. If set to
     *   a callable function, it will receive the entity being saved as first
     *   argument, so you can calculate a bundle name for each particular entity.
     *
     * - `enabled`: True enables this behavior or false for disable. Default to
     *   true.
     *
     * - `cache`: Column-based cache. See EAV plugin's documentation.
     *
     * Bundles are usually set to dynamic values. For example, for the "contents"
     * table we have "content" entities, but we may have "article contents", "page
     * contents", etc. depending on the "type of content" they are; is said that
     * "article" and "page" **are bundles** of "contents" table.
     *
     * @var array
     */
    protected $_fieldableDefaultConfig = [
        'bundle' => null,
        'implementedMethods' => [
            'configureFieldable' => 'configureFieldable',
            'attachFields' => 'attachEntityFields',
            'unbindFieldable' => 'unbindFieldable',
            'bindFieldable' => 'bindFieldable',
        ],
    ];

    /**
     * Instance of EavAttributes table.
     *
     * @var \Eav\Model\Table\EavAttributesTable
     */
    public $Attributes = null;

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to
     * @param array $config Configuration array for this behavior
     */
    public function __construct(Table $table, array $config = [])
    {
        $this->_defaultConfig = array_merge($this->_defaultConfig, $this->_fieldableDefaultConfig);
        $this->Attributes = TableRegistry::get('Eav.EavAttributes');
        $this->Attributes->hasOne('Instance', [
            'className' => 'Field.FieldInstances',
            'foreignKey' => 'eav_attribute_id',
            'propertyName' => 'instance',
        ]);
        parent::__construct($table, $config);
    }

    /**
     * Returns a list of events this class is implementing. When the class is
     * registered in an event manager, each individual method will be associated
     * with the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        $events = [
            'Model.beforeFind' => ['callable' => 'beforeFind', 'priority' => 15],
            'Model.beforeSave' => ['callable' => 'beforeSave', 'priority' => 15],
            'Model.afterSave' => ['callable' => 'afterSave', 'priority' => 15],
            'Model.beforeDelete' => ['callable' => 'beforeDelete', 'priority' => 15],
            'Model.afterDelete' => ['callable' => 'afterDelete', 'priority' => 15],
        ];

        return $events;
    }

    /**
     * Modifies the query object in order to merge custom fields records
     * into each entity under the `_fields` property.
     *
     * ### Events Triggered:
     *
     * - `Field.<FieldHandler>.Entity.beforeFind`: This event is triggered for each
     *    entity in the resulting collection and for each field attached to these
     *    entities. It receives three arguments, a field entity representing the
     *    field being processed, an options array and boolean value indicating
     *    whether the query that initialized the event is part of a primary find
     *    operation or not. Returning false will cause the entity to be removed from
     *    the resulting collection, also will stop event propagation, so other
     *    fields won't be able to listen this event. If the event is stopped using
     *    the event API, will halt the entire find operation.
     *
     * You can enable or disable this behavior for a single `find()` or `get()`
     * operation by setting `fieldable` or `eav` to false in the options array for
     * find method. e.g.:
     *
     * ```php
     * $contents = $this->Contents->find('all', ['fieldable' => false]);
     * $content = $this->Contents->get($id, ['fieldable' => false]);
     * ```
     *
     * It also looks for custom fields in WHERE clause. This will search entities in
     * all bundles this table may have, if you need to restrict the search to an
     * specific bundle you must use the `bundle` key in find()'s options:
     *
     * ```php
     * $this->Contents
     *     ->find('all', ['bundle' => 'articles'])
     *     ->where(['article-title' => 'My first article!']);
     * ```
     *
     * The `bundle` option has no effects if no custom fields are given in the
     * WHERE clause.
     *
     * @param \Cake\Event\Event $event The beforeFind event that was triggered
     * @param \Cake\ORM\Query $query The original query to modify
     * @param \ArrayObject $options Additional options given as an array
     * @param bool $primary Whether this find is a primary query or not
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if ((isset($options['fieldable']) && $options['fieldable'] === false) ||
            !$this->config('enabled')
        ) {
            return true;
        }

        if (array_key_exists('eav', $options)) {
            unset($options['eav']);
        }

        return parent::beforeFind($event, $query, $options, $primary);
    }


    /**
     * {@inheritDoc}
     */
    protected function _hydrateEntities(CollectionInterface $entities, array $args)
    {
        return $entities->map(function ($entity) use ($args) {
            if ($entity instanceof EntityInterface) {
                $entity = $this->_prepareCachedColumns($entity);
                $entity = $this->_attachEntityFields($entity, $args);

                if ($entity === null) {
                    return self::NULL_ENTITY;
                }
            }

            return $entity;
        })
        ->filter(function ($entity) {
            return $entity !== self::NULL_ENTITY;
        });
    }

    /**
     * Attaches entity's field under the `_fields` property, this method is invoked
     * by `beforeFind()` when iterating results sets.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity being altered
     * @param array $args Arguments given to the originating `beforeFind()`
     */
    protected function _attachEntityFields(EntityInterface $entity, array $args)
    {
        $entity = $this->attachEntityFields($entity);
        foreach ($entity->get('_fields') as $field) {
            $result = $field->beforeFind((array)$args['options'], $args['primary']);
            if ($result === null) {
                return null; // remove entity from collection
            } elseif ($result === false) {
                return false; // abort find() operation
            }
        }

        return $entity;
    }

    /**
     * Before an entity is saved.
     *
     * Here is where we dispatch each custom field's `$_POST` information to its
     * corresponding Field Handler, so they can operate over their values.
     *
     * Fields Handler's `beforeSave()` method is automatically invoked for each
     * attached field for the entity being processed, your field handler should look
     * as follow:
     *
     * ```php
     * use Field\Handler;
     *
     * class TextField extends Handler
     * {
     *     public function beforeSave(Field $field, $post)
     *     {
     *          // alter $field, and do nifty things with $post
     *          // return FALSE; will halt the operation
     *     }
     * }
     * ```
     *
     * Field Handlers should **alter** `$field->value` and `$field->extra` according
     * to its needs using the provided **$post** argument.
     *
     * **NOTE:** Returning boolean FALSE will halt the whole Entity's save operation.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity being saved
     * @param \ArrayObject $options Additional options given as an array
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     * @return bool True if save operation should continue
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$this->config('enabled')) {
            return true;
        }

        if (!$options['atomic']) {
            throw new FatalErrorException(__d('field', 'Entities in fieldable tables can only be saved using transaction. Set [atomic = true]'));
        }

        if (!$this->_validation($entity)) {
            return false;
        }

        $this->_cache['createValues'] = [];
        foreach ($this->_attributesForEntity($entity) as $attr) {
            if (!$this->_toolbox->propertyExists($entity, $attr->get('name'))) {
                continue;
            }

            $field = $this->_prepareMockField($entity, $attr);
            $result = $field->beforeSave($this->_fetchPost($field));

            if ($result === false) {
                $this->attachEntityFields($entity);

                return false;
            }

            $data = [
                'eav_attribute_id' => $field->get('metadata')->get('attribute_id'),
                'entity_id' => $this->_toolbox->getEntityId($entity),
                "value_{$field->metadata['type']}" => $field->get('value'),
                'extra' => $field->get('extra'),
            ];

            if ($field->get('metadata')->get('value_id')) {
                $valueEntity = TableRegistry::get('Eav.EavValues')->get($field->get('metadata')->get('value_id'));
                $valueEntity = TableRegistry::get('Eav.EavValues')->patchEntity($valueEntity, $data, ['validate' => false]);
            } else {
                $valueEntity = TableRegistry::get('Eav.EavValues')->newEntity($data, ['validate' => false]);
            }

            if ($entity->isNew() || $valueEntity->isNew()) {
                $this->_cache['createValues'][] = $valueEntity;
            } elseif (!TableRegistry::get('Eav.EavValues')->save($valueEntity)) {
                $this->attachEntityFields($entity);
                $event->stopPropagation();

                return false;
            }
        }

        $this->attachEntityFields($entity);

        return true;
    }

    /**
     * After an entity is saved.
     *
     * ### Events Triggered:
     *
     * - `Field.<FieldHandler>.Entity.afterSave`: Will be triggered after a
     *   successful insert or save, listeners will receive two arguments, the field
     *   entity and the options array. The type of operation performed (insert or
     *   update) can be infer by checking the field entity's method `isNew`, true
     *   meaning an insert and false an update.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param \ArrayObject $options Additional options given as an array
     * @return bool True always
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$this->config('enabled')) {
            return true;
        }

        // as we don't know entity's ID on beforeSave, we must delay values storage;
        // all this occurs inside a transaction so we are safe
        if (!empty($this->_cache['createValues'])) {
            foreach ($this->_cache['createValues'] as $valueEntity) {
                $valueEntity->set('entity_id', $this->_toolbox->getEntityId($entity));
                $valueEntity->unsetProperty('id');
                TableRegistry::get('Eav.EavValues')->save($valueEntity);
            }
            $this->_cache['createValues'] = [];
        }

        foreach ($this->_attributesForEntity($entity) as $attr) {
            $field = $this->_prepareMockField($entity, $attr);
            $field->afterSave();
        }

        if ($this->config('cacheMap')) {
            $this->updateEavCache($entity);
        }

        return true;
    }

    /**
     * Deletes an entity from a fieldable table.
     *
     * ### Events Triggered:
     *
     * - `Field.<FieldHandler>.Entity.beforeDelete`: Fired before the delete occurs.
     *    If stopped the delete will be aborted. Receives as arguments the field
     *    entity and options array.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity being deleted
     * @param \ArrayObject $options Additional options given as an array
     * @return bool
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     */
    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$this->config('enabled')) {
            return true;
        }

        if (!$options['atomic']) {
            throw new FatalErrorException(__d('field', 'Entities in fieldable tables can only be deleted using transaction. Set [atomic = true]'));
        }

        foreach ($this->_attributesForEntity($entity) as $attr) {
            $field = $this->_prepareMockField($entity, $attr);
            $result = $field->beforeDelete();

            if ($result === false) {
                $event->stopPropagation();

                return false;
            }

            // holds in cache field mocks, so we can catch them on afterDelete
            $this->_cache['afterDelete'][] = $field;
        }

        return true;
    }

    /**
     * After an entity was removed from database.
     *
     * ### Events Triggered:
     *
     * - `Field.<FieldHandler>.Entity.afterDelete`: Fired after the delete has been
     *    successful. Receives as arguments the field entity and options array.
     *
     * **NOTE:** This method automatically removes all field values from
     * `eav_values` database table for each entity.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was deleted
     * @param \ArrayObject $options Additional options given as an array
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     * @return void
     */
    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$this->config('enabled')) {
            return;
        }

        if (!$options['atomic']) {
            throw new FatalErrorException(__d('field', 'Entities in fieldable tables can only be deleted using transactions. Set [atomic = true]'));
        }

        if (!empty($this->_cache['afterDelete'])) {
            foreach ((array)$this->_cache['afterDelete'] as $field) {
                $field->afterDelete();
            }
            $this->_cache['afterDelete'] = [];
        }

        parent::afterDelete($event, $entity, $options);
    }

    /**
     * Changes behavior's configuration parameters on the fly.
     *
     * @param array $config Configuration parameters as `key` => `value`
     * @return void
     */
    public function configureFieldable($config)
    {
        $this->config($config);
    }

    /**
     * Enables this behavior.
     *
     * @return void
     */
    public function bindFieldable()
    {
        $this->config('enabled', true);
    }

    /**
     * Disables this behavior.
     *
     * @return void
     */
    public function unbindFieldable()
    {
        $this->config('enabled', false);
    }

    /**
     * The method which actually fetches custom fields.
     *
     * Fetches all Entity's fields under the `_fields` property.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity where to fetch fields
     * @return \Cake\Datasource\EntityInterface
     */
    public function attachEntityFields(EntityInterface $entity)
    {
        $_fields = [];
        foreach ($this->_attributesForEntity($entity) as $attr) {
            $field = $this->_prepareMockField($entity, $attr);
            if ($entity->has($field->get('name'))) {
                $this->_fetchPost($field);
            }

            $field->fieldAttached();
            $_fields[] = $field;
        }

        $entity->set('_fields', new FieldCollection($_fields));

        return $entity;
    }

    /**
     * Triggers before/after validate events.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity being validated
     * @return bool True if save operation should continue, false otherwise
     */
    protected function _validation(EntityInterface $entity)
    {
        $validator = new Validator();
        $hasErrors = false;

        foreach ($this->_attributesForEntity($entity) as $attr) {
            $field = $this->_prepareMockField($entity, $attr);
            $result = $field->validate($validator);

            if ($result === false) {
                $this->attachEntityFields($entity);

                return false;
            }

            $errors = $validator->errors($entity->toArray(), $entity->isNew());
            $entity->errors($errors);

            if (!empty($errors)) {
                $hasErrors = true;
                if ($entity->has('_fields')) {
                    $entityErrors = $entity->errors();
                    foreach ($entity->get('_fields') as $field) {
                        $postData = $entity->get($field->name);
                        if (!empty($entityErrors[$field->name])) {
                            $field->set('value', $postData);
                            $field->metadata->set('errors', (array)$entityErrors[$field->name]);
                        }
                    }
                }
            }
        }

        return !$hasErrors;
    }

    /**
     * Alters the given $field and fetches incoming POST data, both "value" and
     * "extra" property will be automatically filled for the given $field entity.
     *
     * @param \Field\Model\Entity\Field $field The field entity for which
     *  fetch POST information
     * @return mixed Raw POST information
     */
    protected function _fetchPost(Field $field)
    {
        $post = $field
            ->get('metadata')
            ->get('entity')
            ->get($field->get('name'));

        // auto-magic
        if (is_array($post)) {
            $field->set('extra', $post);
            $field->set('value', null);
        } else {
            $field->set('extra', null);
            $field->set('value', $post);
        }

        return $post;
    }

    /**
     * Gets all attributes that should be attached to the given entity, this entity
     * will be used as context to calculate the proper bundle.
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity context
     * @return array
     */
    protected function _attributesForEntity(EntityInterface $entity)
    {
        $bundle = $this->_resolveBundle($entity);
        $attrs = $this->_toolbox->attributes($bundle);
        $attrByIds = []; // attrs indexed by id
        $attrByNames = []; // attrs indexed by name

        foreach ($attrs as $name => $attr) {
            $attrByNames[$name] = $attr;
            $attrByIds[$attr->get('id')] = $attr;
            $attr->set(':value', null);
        }

        if (!empty($attrByIds)) {
            $instances = $this->Attributes->Instance
                ->find()
                ->where(['eav_attribute_id IN' => array_keys($attrByIds)])
                ->all();
            foreach ($instances as $instance) {
                if (!empty($attrByIds[$instance->get('eav_attribute_id')])) {
                    $attr = $attrByIds[$instance->get('eav_attribute_id')];
                    if (!$attr->has('instance')) {
                        $attr->set('instance', $instance);
                    }
                }
            }
        }

        $values = $this->_fetchValues($entity, array_keys($attrByNames));
        foreach ($values as $value) {
            if (!empty($attrByNames[$value->get('eav_attribute')->get('name')])) {
                $attrByNames[$value->get('eav_attribute')->get('name')]->set(':value', $value);
            }
        }

        return $this->_toolbox->attributes($bundle);
    }

    /**
     * Retrives stored values for all virtual properties by name. This gets all
     * values at once.
     *
     * This method is used to reduce the number of SQl queries, so we get all
     * values at once in a single Select instead of creating a select for every
     * field attached to the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entuity for which
     *  get related values
     * @param array $attrNames List of attribute names for which get their
     *  values
     * @return \Cake\Datasource\ResultSetInterface
     */
    protected function _fetchValues(EntityInterface $entity, array $attrNames = [])
    {
        $bundle = $this->_resolveBundle($entity);
        $conditions = [
            'EavAttribute.table_alias' => $this->_table->table(),
            'EavValues.entity_id' => $entity->get((string)$this->_table->primaryKey()),
        ];

        if ($bundle) {
            $conditions['EavAttribute.bundle'] = $bundle;
        }

        if (!empty($attrNames)) {
            $conditions['EavAttribute.name IN'] = $attrNames;
        }

        $storedValues = TableRegistry::get('Eav.EavValues')
            ->find()
            ->contain(['EavAttribute'])
            ->where($conditions)
            ->all();

        return $storedValues;
    }

    /**
     * Creates a new Virtual "Field" to be attached to the given entity.
     *
     * This mock Field represents a new property (table column) of the entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity where the
     *  generated virtual field will be attached
     * @param \Cake\Datasource\EntityInterface $attribute The attribute where to get
     *  the information when creating the mock field.
     * @return \Field\Model\Entity\Field
     */
    protected function _prepareMockField(EntityInterface $entity, EntityInterface $attribute)
    {
        $type = $this->_toolbox->mapType($attribute->get('type'));
        if (!$attribute->has(':value')) {
            $bundle = $this->_resolveBundle($entity);
            $conditions = [
                'EavAttribute.table_alias' => $this->_table->table(),
                'EavAttribute.name' => $attribute->get('name'),
                'EavValues.entity_id' => $entity->get((string)$this->_table->primaryKey()),
            ];

            if ($bundle) {
                $conditions['EavAttribute.bundle'] = $bundle;
            }

            $storedValue = TableRegistry::get('Eav.EavValues')
                ->find()
                ->contain(['EavAttribute'])
                ->select(['id', "value_{$type}", 'extra'])
                ->where($conditions)
                ->limit(1)
                ->first();
        } else {
            $storedValue = $attribute->get(':value');
        }

        $mockField = new Field([
            'name' => $attribute->get('name'),
            'label' => $attribute->get('instance')->get('label'),
            'value' => null,
            'extra' => null,
            'metadata' => new Entity([
                'value_id' => null,
                'instance_id' => $attribute->get('instance')->get('id'),
                'attribute_id' => $attribute->get('id'),
                'entity_id' => $this->_toolbox->getEntityId($entity),
                'table_alias' => $attribute->get('table_alias'),
                'type' => $type,
                'bundle' => $attribute->get('bundle'),
                'handler' => $attribute->get('instance')->get('handler'),
                'required' => $attribute->get('instance')->required,
                'description' => $attribute->get('instance')->description,
                'settings' => $attribute->get('instance')->settings,
                'view_modes' => $attribute->get('instance')->view_modes,
                'entity' => $entity,
                'errors' => [],
            ]),
        ]);

        if ($storedValue) {
            $mockField->set('value', $this->_toolbox->marshal($storedValue->get("value_{$type}"), $type));
            $mockField->set('extra', $storedValue->get('extra'));
            $mockField->metadata->set('value_id', $storedValue->id);
        }

        $mockField->isNew($entity->isNew());

        return $mockField;
    }

    /**
     * Resolves `bundle` name using $entity as context.
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity to use as context when
     *  resolving bundle
     * @return string Bundle name as string value, it may be an empty string if no
     *  bundle should be applied
     */
    protected function _resolveBundle(EntityInterface $entity)
    {
        $bundle = $this->config('bundle');
        if (is_callable($bundle)) {
            $callable = $this->config('bundle');
            $bundle = $callable($entity);
        }

        return (string)$bundle;
    }
}
