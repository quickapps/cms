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

use Cake\Database\Expression\Comparison;
use Cake\Datasource\EntityInterface;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Field\Collection\FieldCollection;
use Field\Error\InvalidBundle;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldValue;
use QuickApps\Event\HookAwareTrait;
use \ArrayObject;

/**
 * Fieldable Behavior.
 *
 * Allows additional fields to be attached to Tables. Any Table (Nodes, Users, etc.)
 * can use this behavior to make itself `fieldable` and thus allow fields to be
 * attached to it.
 *
 * The Field API defines two primary data structures, FieldInstance and FieldValue:
 *
 * - FieldInstance: is a Field attached to a single Table. (Schema equivalent: column)
 * - FieldValue: is the stored data for a particular [FieldInstance, Entity]
 *   tuple of your Table. (Schema equivalent: cell value)
 *
 * **This behavior allows you to add _virtual columns_ to your table schema.**
 * @link https://github.com/quickapps/docs/blob/2.x/en/developers/field-api.rst
 */
class FieldableBehavior extends Behavior
{

    use HookAwareTrait;

    /**
     * Table which this behavior is attached to.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table;

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
     * - `tableAlias`: Name of the table being managed. Defaults to null (auto-detect).
     * - `bundle`: Bundle within this the table. Can be a string or a callable
     *    method that must return a string to use as bundle.
     *    Default null (use `tableAlias` option always).
     * - `enabled`: True enables this behavior or false for disable. Default to true.
     *
     * When using `bundle` feature, `tableAlias` becomes:
     *
     *     <real_table_name>:<bundle>
     *
     * If `bundle` is set to a callable function, this function receives an entity
     * as first argument and the table instance as second argument, the callable
     * must return a string value to use as `bundle`.
     *
     * ```php
     * // ...
     * 'bundle' => function ($entity, $table) {
     *     return $entity->type;
     * },
     * ```
     *
     * Bundles are usually set to dynamic values as the example above, where
     * we use the `type` property of each entity to generate the `bundle` name. For
     * example, for the "nodes" table we have "node" entities, but we may have
     * "article nodes", "page nodes", etc depending on the "type of node" they are;
     * "article" and "page" **are bundles** of "nodes" table.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'tableAlias' => null,
        'bundle' => null,
        'enabled' => true,
        'implementedMethods' => [
            'configureFieldable' => 'configureFieldable',
            'attachEntityFields' => 'attachEntityFields',
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
        if (empty($config['tableAlias'])) {
            $config['tableAlias'] = Inflector::underscore($table->alias());
        }
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
            'Model.beforeValidate' => ['callable' => 'beforeValidate', 'priority' => 15],
            'Model.afterValidate' => ['callable' => 'afterValidate', 'priority' => 15],
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
     * You can enable or disable this behavior for a single `find()` operation by
     * setting `fieldable` to false in the options array for find method. e.g.:
     *
     * ```php
     * $this->Nodes
     *     ->find('all', [
     *         'fieldable' => false,
     *     ]);
     * ```
     *
     * It also looks for custom fields in WHERE clause. This will search entities in
     * all bundles this table may have, if you need to restrict the search to an
     * specific bundle you must use the `bundle` key in find()'s options:
     *
     * ```php
     * $this->Nodes
     *     ->find('all', ['bundle' => 'articles'])
     *     ->where([':article-title' => 'My first article!']);
     * ```
     *
     * The `bundle` option has no effects if no custom fields are given in the
     * WHERE clause.
     *
     * @param \Cake\Event\Event $event The beforeFind event that was triggered
     * @param \Cake\ORM\Query $query The original query to modify
     * @param \ArrayObject $options Additional options given as an array
     * @param bool $primary Whether this find is a primary query or not
     * @return bool|null
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if ((isset($options['fieldable']) && $options['fieldable'] === false) ||
            !$this->config('enabled')
        ) {
            return true;
        }

        $query = $this->_scopeQuery($query, $options);
        $query->formatResults(function ($results) use ($event, $options, $primary) {
            $results = $results->map(function ($entity) use ($event, $options, $primary) {
                if ($entity instanceof EntityInterface) {
                    $entity = $this->attachEntityFields($entity);
                    foreach ($entity->get('_fields') as $field) {
                        $fieldEvent = $this->trigger(["Field.{$field->metadata->handler}.Entity.beforeFind", $event->subject()], $field, $options, $primary);
                        if ($fieldEvent->result === false) {
                            $entity = false; // remove from collection
                            break;
                        } elseif ($fieldEvent->isStopped()) {
                            $event->stopPropagation(); // abort find()
                            return;
                        }
                    }
                }
                return $entity;
            });
            return $results->filter();
        });
    }

    /**
     * Before an entity is saved.
     *
     * ### Events Triggered:
     *
     * - `Field.<FieldHandler>.Entity.beforeSave`: It receives two arguments, the
     *    field entity representing the field being saved and options array. The
     *    options array is passed as an ArrayObject, so any changes in it will be
     *    reflected in every listener and remembered at the end of the event so it
     *    can be used for the rest of the save operation. Returning false in any of
     *    the Field Handler will abort the saving process. If the Field event is
     *    stopped using the event API, the Field event object's `result` property
     *    will be returned.
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

        if (!$this->_validationEvents($entity, $options)) {
            return false;
        }

        $pk = $this->_table->primaryKey();
        $tableAlias = $this->_guessTableAlias($entity);
        $this->_cache['createValues'] = [];

        foreach ($this->_getTableFieldInstances($entity) as $instance) {
            if (!$entity->has(":{$instance->slug}")) {
                continue;
            }

            $field = $this->_getMockField($entity, $instance);
            $options['_post'] = $this->_preparePostData($field);

            // auto-magic
            if (is_array($options['_post'])) {
                $field->set('extra', $options['_post']);
                $field->set('value', null);
            } else {
                $field->set('extra', null);
                $field->set('value', $options['_post']);
            }

            $fieldEvent = $this->trigger(["Field.{$instance->handler}.Entity.beforeSave", $event->subject()], $field, $options);
            if ($fieldEvent->result === false) {
                $this->attachEntityFields($entity);
                return false;
            } elseif ($fieldEvent->isStopped()) {
                $this->attachEntityFields($entity);
                $event->stopPropagation();
                return $fieldEvent->result;
            }

            $valueEntity = new FieldValue([
                'id' => $field->metadata['field_value_id'],
                'field_instance_id' => $field->metadata['field_instance_id'],
                'field_instance_slug' => $field->name,
                'entity_id' => $entity->{$pk},
                'table_alias' => $tableAlias,
                'type' => $field->metadata['type'],
                "value_{$field->metadata['type']}" => $field->value,
                'extra' => $field->extra,
            ]);

            if ($entity->isNew() || empty($valueEntity->id)) {
                $this->_cache['createValues'][] = $valueEntity;
            } else {
                if (!TableRegistry::get('Field.FieldValues')->save($valueEntity)) {
                    $this->attachEntityFields($entity);
                    $event->stopPropagation();
                    return false;
                }
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

        // as we don't know entity's ID on beforeSave, we must delay EntityValues
        // storage; all this occurs inside a transaction so we are safe
        if (!empty($this->_cache['createValues'])) {
            foreach ($this->_cache['createValues'] as $valueEntity) {
                $valueEntity->set('entity_id', $entity->id);
                $valueEntity->unsetProperty('id');
                TableRegistry::get('Field.FieldValues')->save($valueEntity);
            }
            $this->_cache['createValues'] = [];
        }

        $instances = $this->_getTableFieldInstances($entity);
        foreach ($instances as $instance) {
            $field = $this->_getMockField($entity, $instance);
            $this->trigger(["Field.{$instance->handler}.Entity.afterSave", $event->subject()], $field, $options);

            // remove POST info after saved
            if ($entity->has(":{$instance->slug}")) {
                $entity->unsetProperty(":{$instance->slug}");
            }
        }

        return true;
    }

    /**
     * Before entity validation process.
     *
     * ### Events Triggered:
     *
     * - `Field.<FieldHandler>.Entity.beforeValidate`: Will be triggered right
     *    before any validation is done for the passed entity if the validate key in
     *    $options is not set to false. Listeners will receive as arguments the
     *    field entity, the options array and the validation object to be used for
     *    validating the entity. If the event is stopped the validation result will
     *    be set to the result of the event itself.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity being validated
     * @param array $options Additional options given as an array
     * @param \Cake\Validation\Validator $validator The validator object
     * @return bool True on success
     */
    public function beforeValidate(Event $event, EntityInterface $entity, $options, $validator)
    {
        if (!$this->config('enabled')) {
            return true;
        }

        $instances = $this->_getTableFieldInstances($entity);
        foreach ($instances as $instance) {
            $field = $this->_getMockField($entity, $instance);
            $fieldEvent = $this->trigger(["Field.{$field->metadata['handler']}.Entity.beforeValidate", $event->subject()], $field, $options, $validator);

            if ($fieldEvent->isStopped()) {
                $this->attachEntityFields($entity);
                $event->stopPropagation();
                return $fieldEvent->result;
            }
        }
    }

    /**
     * After entity validation process.
     *
     * ### Events Triggered:
     *
     * - `Field.<FieldHandler>.Entity.afterValidate`: Will be triggered right after
     *    the `validate()` method is called in the entity. Listeners will receive as
     *    arguments the the field entity, the options array and the validation
     *    object to be used for validating the entity. If the event is stopped the
     *    validation result will be set to the result of the event itself.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was validated
     * @param array $options Additional options given as an array
     * @param Validator $validator The validator object
     * @return bool True on success
     */
    public function afterValidate(Event $event, EntityInterface $entity, $options, $validator)
    {
        if (!$this->config('enabled')) {
            return true;
        }

        if ($entity->has('_fields')) {
            $entityErrors = $entity->errors();
            foreach ($entity->get('_fields') as $field) {
                $postName = ":{$field->name}";
                $postData = $entity->get($postName);

                if (!empty($entityErrors[$postName])) {
                    $field->set('value', $postData);
                    $field->metadata->set('errors', (array)$entityErrors[$postName]);
                }

                $fieldEvent = $this->trigger(["Field.{$field->metadata['handler']}.Entity.afterValidate", $event->subject()], $field, $options, $validator);
                if ($fieldEvent->isStopped()) {
                    $event->stopPropagation();
                    return $fieldEvent->result;
                }
            }
        }
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
     * **NOTE:** This method automatically removes all field values
     * from `field_values` database table for each entity.
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

        $tableAlias = $this->_guessTableAlias($entity);
        $instances = $this->_getTableFieldInstances($entity);

        foreach ($instances as $instance) {
            // invoke fields beforeDelete so they can do their stuff
            // e.g.: Delete entity information from another table.
            $field = $this->_getMockField($entity, $instance);
            $fieldEvent = $this->trigger(["Field.{$instance->handler}.Entity.beforeDelete", $event->subject()], $field, $options);

            if ($fieldEvent->isStopped()) {
                $event->stopPropagation();
                return false;
            }

            $valueToDelete = TableRegistry::get('Field.FieldValues')
                ->find()
                ->where([
                    'entity_id' => $entity->get((string)$this->_table->primaryKey()),
                    'table_alias' => $tableAlias,
                ])
                ->limit(1)
                ->first();

            if ($valueToDelete) {
                $success = TableRegistry::get('Field.FieldValues')->delete($valueToDelete);
                if (!$success) {
                    return false;
                }
            }

            // holds in cache field mocks, so we can catch them on afterDelete
            $this->_cache['fields.beforeDelete'][] = $field;
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

        if (!empty($this->_cache['fields.beforeDelete']) && is_array($this->_cache['fields.beforeDelete'])) {
            foreach ($this->_cache['fields.beforeDelete'] as $field) {
                $this->trigger(["Field.{$field->handler}.Entity.afterDelete", $event->subject()], $field, $options);
            }
            $this->_cache['fields.beforeDelete'] = [];
        }
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
        $_accessible = [];
        foreach ($entity->visibleProperties() as $property) {
            $_accessible[$property] = $entity->accessible($property);
        }
        $entity->accessible('*', true);
        foreach ($_accessible as $property => $access) {
            $entity->accessible($property, $access);
        }

        $_fields = [];
        $instances = $this->_getTableFieldInstances($entity);
        foreach ($instances as $instance) {
            $mock = $this->_getMockField($entity, $instance);

            // restore from $_POST:
            if ($entity->has(":{$instance->slug}")) {
                $value = $entity->get(":{$instance->slug}");
                if (is_array($value)) {
                    $mock->set('extra', $value);
                    $mock->set('value', null);
                } else {
                    $mock->set('extra', null);
                    $mock->set('value', $value);
                }
            }

            $this->trigger(["Field.{$mock->metadata['handler']}.Entity.fieldAttached", $this->_table], $mock);
            $_fields[] = $mock;
        }

        $entity->set('_fields', new FieldCollection($_fields));
        return $entity;
    }

    /**
     * Triggers before/after validate events.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity being validated
     * @param array $options Options for validation process
     * @return bool True if save operation should continue, false otherwise
     */
    protected function _validationEvents(EntityInterface $entity, $options = [])
    {
        $validator = $this->_table->validator();
        $event = $this->_table->dispatchEvent('Model.beforeValidate', compact('entity', 'options', 'validator'));
        if ($event->result === false) {
            return false;
        }

        $errors = $validator->errors($entity->toArray(), $entity->isNew());
        $entity->errors($errors);
        $this->_table->dispatchEvent('Model.afterValidate', compact('entity', 'options', 'validator'));

        if (!empty($errors)) {
            return false;
        }

        return true;
    }

    /**
     * Alters the given $field and fetches incoming POST data, the 'value' property
     * will be automatically filled for the given $field entity.
     *
     * @param \Field\Model\Entity\Field $field The field entity for which
     *  fetch POST information
     * @return mixed Raw POST information
     */
    protected function _preparePostData(Field $field)
    {
        $post = $field
            ->get('metadata')
            ->get('entity')
            ->get(':' . $field->get('metadata')->get('field_instance_slug'));
        $field->set('value', $post);
        return $post;
    }

    /**
     * Look for `:<machine-name>` patterns in query's WHERE clause.
     *
     * Allows to search entities using custom fields as conditions in WHERE clause.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param array $options Array of options
     * @return \Cake\ORM\Query The modified query object
     */
    protected function _scopeQuery(Query $query, $options)
    {
        $whereClause = $query->clause('where');
        if (!$whereClause) {
            return $query;
        }

        $alias = $this->_table->alias();
        $pk = $this->_table->primaryKey();
        $conn = $query->connection(null);
        list(, $driverClass) = namespaceSplit(strtolower(get_class($conn->driver())));

        $whereClause->traverse(function ($expression) use ($pk, $alias, $driverClass) {
            if (!($subQuery = $this->_virtualQuery($expression))) {
                return;
            }

            switch ($driverClass) {
                case 'sqlite':
                    $field = "({$alias}.{$pk} || '')";
                    break;
                case 'mysql':
                case 'postgres':
                case 'sqlserver':
                default:
                    $field = "CONCAT({$alias}.{$pk}, '')";
                    break;
            }

            $expression->setField($field);
            $expression->setValue($subQuery);
            $expression->setOperator('IN');
        });

        return $query;
    }

    /**
     * Creates a sub-query for matching virtual fields.
     *
     * @param \Cake\Database\Expression\Comparison $expression Expression to scope
     * @param string|null $bundle Table's bundle to scope. e.g. `articles` for `Nodes`
     *  table will look over nodes:articles
     * @return \Cake\ORM\Query|bool False if not virtual field was found, or search
     *  feature was disabled for this field. The query object to use otherwise
     */
    protected function _virtualQuery($expression, $bundle = null)
    {
        if (!($expression instanceof Comparison)) {
            return false;
        }

        $field = $this->_parseFieldName($expression->getField());
        $value = $expression->getValue();
        $conjunction = $expression->getOperator();

        if (strpos($field, ':') !== 0) {
            return false;
        }

        $field = str_replace(':', '', $field);
        $instance = TableRegistry::get('Field.FieldInstances')
            ->find()
            ->select(['type', 'table_alias', 'handler'])
            ->where(['slug' => $field])
            ->limit(1)
            ->first();

        if (!$instance || !fieldsInfo($instance->handler)['searchable']) {
            return false;
        }

        $subQuery = TableRegistry::get('Field.FieldValues')->find()
            ->select('FieldValues.entity_id')
            ->where([
                "FieldValues.field_instance_slug" => $field,
                "FieldValues.value_{$instance->type} {$conjunction}" => $value,
            ]);

        $subQuery->where(['FieldValues.table_alias' => $instance->table_alias]);
        return $subQuery;
    }

    /**
     * Gets a clean field name from query expression.
     *
     * ### Example:
     *
     * ```php
     * $this->_parseFieldName('Tablename.:virtual');
     * // returns ":virtual"
     *
     * $this->_parseFieldName('Tablename.non_virtual');
     * // returns "non_virtual"
     *
     * $this->_parseFieldName('my_column');
     * // returns "my_column"
     * ```
     *
     * @param string $column Column name from query
     * @return string
     */
    protected function _parseFieldName($column)
    {
        list($tableName, $fieldName) = pluginSplit((string)$column);

        if (!$fieldName) {
            $fieldName = $tableName;
        }

        $fieldName = preg_replace('/\s{2,}/', ' ', $fieldName);
        list($fieldName, ) = explode(' ', trim($fieldName));
        return $fieldName;
    }

    /**
     * Creates a new Virtual "Field" to be attached to the given entity.
     *
     * This mock Field represents a new property (table column) of the entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity where the
     *  generated virtual field will be attached
     * @param \Field\Model\Entity\FieldInstance $instance The instance where to get
     *  the information when creating the mock field.
     * @return \Field\Model\Entity\Field
     */
    protected function _getMockField(EntityInterface $entity, $instance)
    {
        $pk = $this->_table->primaryKey();
        $storedValue = TableRegistry::get('Field.FieldValues')->find()
            ->select(['id', "value_{$instance->type}", 'extra'])
            ->where([
                'FieldValues.field_instance_id' => $instance->id,
                'FieldValues.table_alias' => $this->_guessTableAlias($entity),
                'FieldValues.entity_id' => $entity->get((string)$this->_table->primaryKey())
            ])
            ->limit(1)
            ->first();

        $mockField = new Field([
            'name' => $instance->slug,
            'label' => $instance->label,
            'value' => null,
            'extra' => null,
            'metadata' => new Entity([
                'field_value_id' => null,
                'field_instance_id' => $instance->id,
                'field_instance_slug' => $instance->slug,
                'entity_id' => $entity->{$pk},
                'handler' => $instance->handler,
                'type' => $instance->type,
                'entity' => $entity,
                'required' => $instance->required,
                'table_alias' => $this->_guessTableAlias($entity),
                'description' => $instance->description,
                'settings' => $instance->settings,
                'view_modes' => $instance->view_modes,
                'errors' => [],
            ])
        ]);

        if ($storedValue) {
            $mockField->metadata->accessible('*', true);
            $mockField->set('value', $storedValue->get("value_{$instance->type}"));
            $mockField->set('extra', $storedValue->get('extra'));
            $mockField->metadata->set('field_value_id', $storedValue->id);
            $mockField->metadata->accessible('*', false);
        }

        $mockField->isNew($entity->isNew());
        $mockField->accessible('*', false);
        $mockField->accessible('value', true);
        $mockField->accessible('extra', true);
        return $mockField;
    }

    /**
     * Gets table alias this behavior is attached to.
     *
     * This method requires an entity, so we can properly take care of the `bundle`
     * option. If this option is not used, then `Table::alias()` is returned.
     *
     * @param \Cake\Datasource\EntityInterface $entity From where try to guess `bundle`
     * @return string Table alias
     * @throws \Field\Error\InvalidBundle When `bundle` option is used but
     *  was unable to resolve bundle name
     */
    protected function _guessTableAlias(EntityInterface $entity)
    {
        $tableAlias = $this->config('tableAlias');
        if ($this->config('bundle')) {
            $bundle = $this->_resolveBundle($entity);
            if ($bundle === false) {
                throw new InvalidBundle(
                    __d('field', 'FieldableBehavior: The "bundle" option was set to "{0}", but this property could not be found on entities being fetched.', $this->config('bundle'))
                );
            }

            $tableAlias = "{$tableAlias}:{$bundle}";
        }
        return $tableAlias;
    }

    /**
     * Resolves `bundle` name using $entity as context.
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity to use as context when resolving bundle
     * @return mixed Bundle name as string value on success, false otherwise
     */
    protected function _resolveBundle(EntityInterface $entity)
    {
        $bundle = $this->config('bundle');
        if ($bundle !== null) {
            if (is_callable($bundle)) {
                $callable = $this->config('bundle');
                return $callable($entity, $this->_table);
            } elseif (is_string($bundle)) {
                return $bundle;
            }
        }
        return false;
    }

    /**
     * Used to reduce database queries.
     *
     * @param \Cake\Datasource\EntityInterface $entity An entity used to guess
     *  table name to be used
     * @return \Cake\Datasource\ResultSetInterface Field instances attached to
     *  current table as a query result
     */
    protected function _getTableFieldInstances(EntityInterface $entity)
    {
        $tableAlias = $this->_guessTableAlias($entity);
        if (isset($this->_cache["TableFieldInstances_{$tableAlias}"])) {
            return $this->_cache["TableFieldInstances_{$tableAlias}"];
        }

        $FieldInstances = TableRegistry::get('Field.FieldInstances');
        $this->_cache["TableFieldInstances_{$tableAlias}"] = $FieldInstances->find()
            ->where(['FieldInstances.table_alias' => $tableAlias])
            ->order(['ordering' => 'ASC'])
            ->all();
        return $this->_cache["TableFieldInstances_{$tableAlias}"];
    }
}
