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
use QuickApps\Event\HookAwareTrait;
use \ArrayObject;

/**
 * Fieldable Behavior.
 *
 * A more flexible EAV approach. Allows additional fields to be attached to Tables.
 * Any Table (Nodes, Users, etc.) can use this behavior to make itself `fieldable`
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

    use HookAwareTrait;

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
     * - `tableAlias`: Name of the table being managed. Defaults to null (auto-
     *   detect).
     *
     * - `bundle`: Bundle within this the table. Can be a string or a callable
     *   method that must return a string to use as bundle. Default null.
     *
     * - `enabled`: True enables this behavior or false for disable. Default to
     *   true.
     *
     * Bundles are usually set to dynamic values. For example, for the "nodes" table
     * we have "node" entities, but we may have "article nodes", "page nodes", etc
     * depending on the "type of node" they are; is said that "article" and "page"
     * **are bundles** of "nodes" table.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'bundle' => null,
        'enabled' => true,
        'implementedMethods' => [
            'configureFieldable' => 'configureFieldable',
            'attachFields' => 'attachEntityFields',
            'unbindFieldable' => 'unbindFieldable',
            'bindFieldable' => 'bindFieldable',
        ],
    ];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to
     * @param array $config Configuration array for this behavior
     */
    public function __construct(Table $table, array $config = [])
    {
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
     * $nodes = $this->Nodes->find('all', ['fieldable' => false]);
     * $node = $this->Nodes->get($id, ['fieldable' => false]);
     * ```
     *
     * It also looks for custom fields in WHERE clause. This will search entities in
     * all bundles this table may have, if you need to restrict the search to an
     * specific bundle you must use the `bundle` key in find()'s options:
     *
     * ```php
     * $this->Nodes
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
        return parent::beforeFind($event, $query, $options, $primary);
    }

    /**
     * {@inheritDoc}
     *
     * Attaches entity's field under the `fields` property, this method is invoked
     * by `beforeFind()` when iterating result sets.
     */
    public function attachEntityAttributes(EntityInterface $entity, array $options = [])
    {
        $entity = $this->attachEntityFields($entity);
        extract($options);

        foreach ($entity->get('_fields') as $field) {
            $fieldEvent = $this->trigger(["Field.{$field->get('metadata')->get('handler')}.Entity.beforeFind", $event->subject()], $field, $options, $primary);
            if ($fieldEvent->result === false) {
                return false; // remove entity from collection
            } elseif ($fieldEvent->isStopped()) {
                return null; // abort find() operation
            }
        }

        return $entity;
    }

    /**
     * Before an entity is saved.
     *
     * ### Events Triggered:
     *
     * - `Field.<FieldHandler>.Entity.validate`: It receives three arguments, the
     *   field entity representing the field being saved, an options array and a
     *   Validator object. The options array is passed as an ArrayObject, so any
     *   changes in it will be reflected in every listener and remembered at the end
     *   of the event so it can be used for the rest of the save operation. The
     *   validator object should be altered by adding rules that will be used later
     *   to validate the given field entity, this validator object is used
     *   exclusively to validate the given field entity.
     *
     * - `Field.<FieldHandler>.Entity.beforeSave`: It receives two arguments, the
     *   field entity representing the field being saved and options array. The
     *   options array is passed as an ArrayObject, so any changes in it will be
     *   reflected in every listener and remembered at the end of the event so it
     *   can be used for the rest of the save operation. Returning false in any of
     *   the Field Handler will abort the saving process. If the Field event is
     *   stopped using the event API, the Field event object's `result` property
     *   will be returned.
     *
     * Here is where we dispatch each custom field's `$_POST` information to its
     * corresponding Field Handler, so they can operate over their values.
     *
     * Fields Handler's `Field.<FieldHandler>.Entity.beforeSave` event is triggered
     * over each attached field for this entity, so you should have a listener like:
     *
     * ```php
     * class TextField implements EventListenerInterface
     * {
     *     public function implementedEvents()
     *     {
     *         return [
     *             'Field.TextField.Entity.beforeSave' => 'entityBeforeSave',
     *         ];
     *     }
     *
     *     public function entityBeforeSave(Event $event, $entity, $field, $options)
     *     {
     *          // alter $field, and do nifty things with $options['_post']
     *          // return FALSE; will halt the operation
     *     }
     * }
     * ```
     *
     * You will see `$options` array contains the POST information user just sent
     * when pressing form submit button:
     *
     * ```php
     * // $_POST information for this [entity, field_instance] tuple.
     * $options['_post']
     * ```
     *
     * Field Handlers should **alter** `$field->value` and `$field->extra` according
     * to its needs **using $options['_post']**.
     *
     * **NOTE:** Returning boolean FALSE will halt the whole Entity's save operation.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity being saved
     * @param array $options Additional options given as an array
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     * @return bool True if save operation should continue
     */
    public function beforeSave(Event $event, EntityInterface $entity, $options)
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
            if (!$entity->has($attr->get('name'))) {
                continue;
            }

            $field = $this->_prepareMockField($entity, $attr);
            $options['_post'] = $this->_fetchPost($field);
            $fieldEvent = $this->trigger(["Field.{$attr->get('instance')->get('handler')}.Entity.beforeSave", $event->subject()], $field, $options);

            if ($fieldEvent->result === false) {
                $this->attachEntityFields($entity);
                return false;
            } elseif ($fieldEvent->isStopped()) {
                $this->attachEntityFields($entity);
                $event->stopPropagation();
                return $fieldEvent->result;
            }

            $data = [
                'eav_attribute_id' => $field->get('metadata')->get('attribute_id'),
                'entity_id' => $this->_getEntityId($entity),
                "value_{$field->metadata['type']}" => $field->get('value'),
                'extra' => $field->get('extra'),
            ];

            if ($field->get('metadata')->get('value_id')) {
                $valueEntity = $this->Values->get($field->get('metadata')->get('value_id'));
                $valueEntity = $this->Values->patchEntity($valueEntity, $data, ['validate' => false]);
            } else {
                $valueEntity = $this->Values->newEntity($data, ['validate' => false]);
            }

            if ($entity->isNew() || $valueEntity->isNew()) {
                $this->_cache['createValues'][] = $valueEntity;
            } elseif (!$this->Values->save($valueEntity)) {
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
     *    successful insert or save, listeners will receive two arguments, the field
     *    entity and the options array. The type of operation performed (insert or
     *    update) can be infer by checking the entity's method `isNew`, true meaning
     *    an insert and false an update.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param array $options Additional options given as an array
     * @return bool True always
     */
    public function afterSave(Event $event, EntityInterface $entity, $options)
    {
        if (!$this->config('enabled')) {
            return true;
        }

        // as we don't know entity's ID on beforeSave, we must delay values storage;
        // all this occurs inside a transaction so we are safe
        if (!empty($this->_cache['createValues'])) {
            foreach ($this->_cache['createValues'] as $valueEntity) {
                $valueEntity->set('entity_id', $this->_getEntityId($entity));
                $valueEntity->unsetProperty('id');
                $this->Values->save($valueEntity);
            }
            $this->_cache['createValues'] = [];
        }

        foreach ($this->_attributesForEntity($entity) as $attr) {
            $field = $this->_prepareMockField($entity, $attr);
            $this->trigger(["Field.{$attr->get('instance')->get('handler')}.Entity.afterSave", $event->subject()], $field, $options);
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
     * @param array $options Additional options given as an array
     * @return bool
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     */
    public function beforeDelete(Event $event, EntityInterface $entity, $options)
    {
        if (!$this->config('enabled')) {
            return true;
        }

        if (!$options['atomic']) {
            throw new FatalErrorException(__d('field', 'Entities in fieldable tables can only be deleted using transaction. Set [atomic = true]'));
        }

        foreach ($this->_attributesForEntity($entity) as $attr) {
            $field = $this->_prepareMockField($entity, $attr);
            $fieldEvent = $this->trigger(["Field.{$attr->get('instance')->get('handler')}.Entity.beforeDelete", $event->subject()], $field, $options);

            if ($fieldEvent->isStopped()) {
                $event->stopPropagation();
                return $fieldEvent->result;
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
     * @param array $options Additional options given as an array
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     * @return void
     */
    public function afterDelete(Event $event, EntityInterface $entity, $options)
    {
        if (!$this->config('enabled')) {
            return;
        }

        if (!$options['atomic']) {
            throw new FatalErrorException(__d('field', 'Entities in fieldable tables can only be deleted using transactions. Set [atomic = true]'));
        }

        if (!empty($this->_cache['afterDelete'])) {
            foreach ((array)$this->_cache['afterDelete'] as $field) {
                $this->trigger(["Field.{$field->handler}.Entity.afterDelete", $event->subject()], $field, $options);
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
            $mock = $this->_prepareMockField($entity, $attr);
            if ($entity->has($mock->get('name'))) {
                $this->_fetchPost($mock);
            }

            $this->trigger(["Field.{$attr->get('instance')->get('handler')}.Entity.fieldAttached", $this->_table], $mock);
            $_fields[] = $mock;
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
            $fieldEvent = $this->trigger(["Field.{$attr->get('instance')->get('handler')}.Entity.validate", $this->_table], $field, $validator);
            if ($fieldEvent->isStopped()) {
                $this->attachEntityFields($entity);
                return $fieldEvent->result;
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
     * Gets all attributes that should be attached to the given entity, this entity
     * will be used as context to calculate the proper bundle.
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity context
     * @return array
     */
    protected function _attributesForEntity(EntityInterface $entity)
    {
        $bundle = $this->_resolveBundle($entity);
        $this->_fetchAttributes();

        $out = [];
        if (empty($bundle)) {
            $out = $this->_attributes;
        } elseif (isset($this->_attributesByBundle[$bundle])) {
            $out = (array)$this->_attributesByBundle[$bundle];
        }

        foreach ($out as $name => $attr) {
            if (!$attr->has('instance')) {
                $instance = $this->Attributes->Instance
                    ->find()
                    ->where(['eav_attribute_id' => $attr->get('id')])
                    ->limit(1)
                    ->first();
                $attr->set('instance', $instance);
            }
        }

        return $out;
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
        $type = $this->_mapType($attribute->get('type'));
        $bundle = $this->_resolveBundle($entity);
        $conditions = [
            'EavAttribute.table_alias' => $this->_tableAlias,
            'EavAttribute.name' => $attribute->get('name'),
            'EavValues.entity_id' => $entity->get((string)$this->_table->primaryKey()),
        ];

        if ($bundle) {
            $conditions['EavAttribute.bundle'] = $bundle;
        }

        $storedValue = $this->Values
            ->find()
            ->contain(['EavAttribute'])
            ->select(['id', "value_{$type}", 'extra'])
            ->where($conditions)
            ->limit(1)
            ->first();

        $mockField = new Field([
            'name' => $attribute->get('name'),
            'label' => $attribute->get('instance')->get('label'),
            'value' => null,
            'extra' => null,
            'metadata' => new Entity([
                'value_id' => null,
                'instance_id' => $attribute->get('instance')->get('id'),
                'attribute_id' => $attribute->get('id'),
                'entity_id' => $this->_getEntityId($entity),
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
            $mockField->set('value', $storedValue->get("value_{$type}"));
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
        if ($bundle !== null) {
            if (is_callable($bundle)) {
                $callable = $this->config('bundle');
                return (string)$callable($entity);
            } elseif (is_string($bundle)) {
                return $bundle;
            }
        }
        return '';
    }
}
