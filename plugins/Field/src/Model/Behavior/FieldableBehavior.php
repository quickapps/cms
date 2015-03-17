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
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Field\Collection\FieldCollection;
use Field\Error\InvalidBundle;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldValue;
use QuickApps\Event\HookAwareTrait;

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
 *
 *
 * ***
 *
 *
 * This behavior modifies each query of your table in order to merge custom-fields
 * records into each entity under the `_fields` property.
 *
 * ## Entity Example:
 *
 * ```php
 * $user = $this->Users->get(1);
 * // User's properties might look as follows:
 * [id] => 1,
 * [password] => e10adc3949ba59abbe56e057f20f883e,
 * ...
 * [_fields] => [
 *      [0] => [
 *          [name] => user-age,
 *          [label] => User Age,
 *          [value] => 22,
 *          [raw] => null,
 *          [metadata] => [ ... ]
 *      ],
 *      [1] => [
 *           [name] => user-phone,
 *           [label] => User Phone,
 *           [value] => null, // no data stored
 *           [raw] => null, // no data stored
 *           [metadata] => [ ... ]
 *      ],
 *      ...
 *      [n] => [ ... ]
 * ]
 * ```
 *
 * In the example above, User entity has a custom field named `user-age` and its
 * current value is 22. In the other hand, it also has a `user-phone` field but
 * no information was given (Schema equivalent: NULL cell).
 *
 * As you might see, the `_field` key contains an array list of all fields attached
 * to every entity. Each field (each element under the `_field` key) is an object
 * (Field Entity), and it have a number of properties such as `label`, `value`, etc.
 * All properties are described below:
 *
 * -  `name`: Machine-name of this field. ex. `article-body` (Schema equivalent: column name).
 * -  `label`: Human readable name of this field e.g.: `User Last name`.
 * -  `value`: Value for this [field, entity] tuple. (Schema equivalent: cell value)
 * -  `raw`: Raw value data.
 * -  `metadata`: Metadata (an Entity Object).
 *     - `field_value_id`: ID of the value stored in `field_values` table.
 *     - `field_instance_id`: ID of field instance (`field_instances` table)
 *        attached to the table.
 *     - `entity_id`: ID of the Entity this field is attached to.
 *     - `table_alias`: Name of the table this field is attached to. e.g: `users`.
 *     - `description`: Something about this field: e.g.: `Please enter your last name`.
 *     - `required`: 0|1.
 *     - `settings`: Any extra information array handled by this particular field.
 *     - `view_modes`: Information about how this field should be rendered on each
 *        View Mode. Information is stored as `view-mode-name` => `rendering-information`.
 *     - `handler`: Name of the Field Handler (without namespace). e.g.
 *       `TaxonomyField` for `Taxonomy\Event\TaxonomyField` class.
 *     - `errors`: Array of validation error messages, only on edit mode.
 *     - `entity`: Entity object this field is attached to.
 *
 * **Notes:**
 *
 * -    The `metadata` key on every field is actually an entity object.
 * -    The `_field` key which holds all the fields is actually an instance of
 *      `Field/Collection/FieldCollection`, which behaves as an array
 *      (so you can iterate over it). It adds some utility methods for handling
 *      fields, for instance it allows you to access an specific field by its
 *      corresponding numeric index or by its machine-name.
 *
 * ### Accessing Field Properties
 *
 * Once you have your Entity (e.g. User Entity), you would probably need to get
 * its attached fields and do fancy thing with them. Following with our User
 * entity example:
 *
 * ```php
 * // In your controller
 * $user = $this->Users->get($id);
 * echo $user->_fields[0]->label . ': ' . $user->_fields[0]->value;
 * // out: User Age: 22
 *
 * echo "This field is attached to '" . $user->_fields[0]->metadata->table_alias . "' table";
 * // out: This field is attached to 'users' table;
 * ```
 *
 * ## Searching over custom fields
 *
 * This behavior allows you to perform WHERE clauses using any of the fields
 * attached to your table. Every attached field has a "machine-name"
 * (a.k.a. field slug), you should use this "machine-name" prefixed with `:`,
 * for example:
 *
 * ```php
 * TableRegistry::get('Users')
 *   ->find('all')
 *   ->where(['Users.:first-name LIKE' => 'John%']);
 * ```
 *
 * `Users` table has a custom field attached (first-name), and we are looking for
 * all the users whose `first-name` starts with `John`.
 *
 * ## Value vs Raw
 *
 * In the "Entity Example" above you might notice that each field attached to
 * entities has two properties that looks pretty similar, `value` and `raw`,
 * as both are intended to store information. Here we explain the "why" of this.
 *
 * Field Handlers may store complex information or structures. For example,
 * `AlbumField` handler may store a list of photos for each entity. In those cases
 * you should use the `raw` property to store your array list of photos, while
 * `value` property should always store a Human-Readable representation of
 * your field's value.
 *
 * In our `AlbumField` example, we could store an array list of file names and titles
 * for a given entity under the `raw` property. And we could save photo's titles as
 * space-separated values under `value` property:
 *
 * ```php
 * // raw:
 * [photos] => [
 *     ['title' => 'OMG!', 'file' => 'omg.jpg'],
 *     ['title' => 'Look at this, lol', 'file' => 'cats-fighting.gif'],
 *     ['title' => 'Fuuuu', 'file' => 'fuuuu-meme.png'],
 * ]
 *
 * // value: OMG! Look at this lol Fuuuu
 * ```
 *
 * In our example when rendering an entity with `AlbumField` attached to it,
 * `AlbumField` should use `raw` information to create a representation of
 * itself, while `value` information would acts like some kind of `words index`
 * when using `Searching over custom fields` feature described above.
 *
 * **Important:**
 *
 * - FieldableBehavior automatically serializes & unserializes the `raw`
 *   property for you, so you should always treat `raw` as an array.
 * - `Search over fields` feature described above uses the `value` property
 *   when looking for matches. So in this way your entities can be found when
 *   using Field's machine-name in WHERE clauses.
 * - Using `raw` is not mandatory, for instance your Field Handler could use
 *   an additional table schema to store entities information and leave `raw`
 *   as NULL. In that case, your Field Handler must take care of joining entities
 *   with that external table of information.
 *
 * **Summarizing:** `value` is intended to store `plain text` information suitable
 * for searches, while `raw` is intended to store sets of complex information.
 *
 * ***
 *
 * ## Using this behavior
 *
 * Just like any other behavior, in your Table constructor attach this behavior
 * as usual:
 *
 * ```php
 * $this->attachBehavior('Field.Fieldable');
 * ```
 *
 * ## Enable/Disable Field Attachment
 *
 * If for some reason you don't need custom fields to be fetched under the `_field`
 * of your entities you should use the unbindFieldable(). Or bindFieldable() to
 * enable it again.
 *
 * ```php
 * // there wont be a "_field" key on your User entity
 * $this->User->unbindFieldable();
 * $this->Users->get($id);
 * ```
 *
 * ## About Field Handlers
 *
 * Field Handler are "Listeners" classes which must take care of storing, organizing
 * and retrieving information for each entity's field. All this is archived using
 * QuickAppsCMS's events system
 *
 * Similar to Event Listeners and Hooktags, Field Handlers classes
 * must define a series of events, which has been organized in two groups or
 * "event subspaces":
 *
 * - `Field.<FieldHandler>.Entity`: For handling Entity's related events such
 *    as `entity save`, `entity delete`, etc.
 * - `Field.<FieldHandler>.Instance`: Related to Field Instances events, such as
 *    "instance being detached from table", "new instance attached to table", etc.
 *
 * Below, a list of available events:
 *
 * **Entity events:**
 *
 * **NOTE:** In order to make reading more comfortable the
 * `Field.<FieldHandler>.Entity.` prefix has been trimmed from each event
 * name listed below. For example, `display` is actually
 * `Field.Field.<FieldHandler>.Entity.info`
 *
 * - `display`: When an entity is being rendered.
 * - `edit`: When an entity is being rendered in `edit` mode. (backend usually).
 * - `fieldAttached`: When a field is attached to entity's "_field" property.
 * - `beforeFind`: Before an entity is retrieved from DB.
 * - `beforeValidate`: Before entity is validated as part of save operation.
 * - `afterValidate`: After entity is validated as part of save operation.
 * - `beforeSave`: Before entity is saved.
 * - `afterSave`: After entity was saved.
 * - `beforeDelete`: Before entity is deleted.
 * - `afterDelete`: After entity was deleted.
 *
 * ***
 *
 * **Instance events:**
 *
 * **NOTE:** In order to make reading more comfortable the
 * `Field.<FieldHandler>.Instance.` prefix has been trimmed from each event
 * name listed below. For example, `info` is actually
 * `Field.<FieldHandler>.Instance.info`
 *
 * - `info`: When QuickAppsCMS asks for information about each registered Field.
 * - `settingsForm`: Additional settings for this field, should define the way
 *    the values will be stored in the database.
 * - `settingsDefaults`: Default values for field settings form's inputs.
 * - `settingsValidate`: Before instance's settings are changed, here you can
 *    apply your own validation rules.
 * - `viewModeForm`: Additional view mode settings, should define the way the
 *    values will be rendered for a particular view mode.
 * - `viewModeDefaults`: Default values for view mode settings form's inputs.
 * - `viewModeValidate`: Before view-mode's settings are changed, here you can
 *    apply your own validation rules.
 * - `beforeAttach`: Before field is attached to Tables.
 * - `afterAttach`: After field is attached to Tables.
 * - `beforeDetach`: Before field is detached from Tables.
 * - `afterDetach`: After field is detached from Tables.
 *
 *
 * ## Preparing Field Inputs
 *
 * Your Field Handler should somehow render some form elements (inputs, selects,
 * textareas, etc) when rendering Table Entities in `edit mode`. For this we have
 * the `Field.<FieldHandler>.Entity.edit` event, which should return a HTML
 * containing all the form elements for [entity, field_instance] tuple.
 *
 * For example, lets suppose we have a `TextField` attached to `Users` Table for
 * storing their `favorite_food`, and now we are editing some specific `User`
 * Entity (i.e.: User.id = 4), so in the form editing page we should see some
 * inputs for change some values like `username` or `password`, and also we
 * should see a `favorite_food` input where Users shall type in their favorite
 * food. Well, your TextField Handler should return something like this:
 *
 * ```html
 * <!-- note the ":" prefix -->
 * <input name=":favorite_food" value="<current_value_from_entity>" />
 * ```
 *
 * To accomplish this, your Field Handler should properly catch the
 * `Field.<FieldHandler>.Entity.edit` event, example:
 *
 * ```php
 * public function entityEdit(Event $event, $field) {
 *     return '<input name=":' . $field->name . '" value="' . $field->value . '" />";
 * }
 * ```
 *
 * As usual, the second argument `$field` contains all the information you will
 * need to properly render your form inputs.
 *
 * You must tell to QuickAppsCMS that the fields you are sending in your POST
 * action are actually virtual fields. To do this, all your input's `name`
 * attributes **must be prefixed** with `:` followed by its machine
 * (a.k.a. `slug`) name:
 *
 * ```html
 * <input name=":<machine-name>" ... />
 * ```
 *
 * You may also create complex data structures like so:
 *
 * ```html
 * <input name=":album.name" value="<current_value>" />
 * <input name=":album.photo.0" value="<current_value>" />
 * <input name=":album.photo.1" value="<current_value>" />
 * <input name=":album.photo.2" value="<current_value>" />
 * ```
 *
 * The above may produce a $_POST array like below:
 *
 * ```php
 *
 * $_POST = [
 *     :album => [
 *         name => Album Name,
 *         photo => [
 *             0 => url_image1.jpg,
 *             1 => url_image2.jpg,
 *             2 => url_image3.jpg
 *         ]
 *     ],
 *     ...
 *     :other_field => ...,
 * ]
 * ```
 *
 * **Remember**, you should always rely on View::elements() for rendering HTML code:
 *
 * ```php
 * public function editTextField(Event $event, $field) {
 *     $view = $event->subject();
 *     return $View->element('text_field_edit', ['field' => $field]);
 * }
 * ```
 *
 * ## Creating an Edit Form
 *
 * In previous example we had an User edit form. When rendering User's form-inputs
 * usually you would do something like so:
 *
 * ```php
 * // edit.ctp
 * <?php echo $this->Form->input('id', ['type' => 'hidden']); ?>
 * <?php echo $this->Form->input('username'); ?>
 * <?php echo $this->Form->input('password'); ?>
 * ```
 *
 * When rendering virtual fields you can pass the whole Field Object to
 * `FormHelper::input()` method. So instead of passing the input name as first
 * argument (as above) you can do as follow:
 *
 * ```php
 * // Remember, custom fields are under the `_fields` property of your entity
 * <?php echo $this->Form->input($user->_fields[0]); ?>
 * <?php echo $this->Form->input($user->_fields[1]); ?>
 * ```
 *
 * **TIP:** You can get any custom field by using either, its index or its machine
 * name, for example: `$user->_field['user-age']` is the same as `$user->_field[0]`
 *
 * That will render the first and second virtual field attached to your entity.
 * But usually you'll end creating some loop structure and render all of them
 * at once:
 *
 * ```php
 * <?php foreach ($user->_fields as $field): ?>
 *     <?php echo $this->Form->input($field); ?>
 * <?php endforeach; ?>
 * ```
 *
 * As you may see, `Form::input()` **automagically fires** the
 * `Field.<FieldHandler>.Entity.edit` event asking to the corresponding Field
 * Handler for its HTML form elements. Passing the Field object to `Form::input()`
 * is not mandatory, you can manually generate your input elements:
 *
 * ```php
 * <input name=":<?php echo $field->name; ?>" value="<?php echo $field->value; ?>" />
 * ```
 *
 * The `$user` variable used in these examples assumes you used `Controller::set()`
 * method in your controller.
 *
 * A more complete example:
 *
 * ```php
 * // UsersController.php
 * public function edit($id) {
 *     $this->set('user', $this->Users->get($id));
 * }
 *
 * // edit.ctp
 * <?php echo $this->Form->create($user); ?>
 *     <?php echo $this->Form->hidden('id'); ?>
 *     <?php echo $this->Form->input('username'); ?>
 *     <?php echo $this->Form->input('password'); ?>
 *     <!-- Custom Fields -->
 *     <?php foreach ($user->_fields as $field): ?>
 *         <?php echo $this->Form->input($field); ?>
 *     <?php endforeach; ?>
 *     <!-- /Custom Fields -->
 *     <?php echo $this->Form->submit('Save User'); ?>
 * <?php echo $this->Form->end(); ?>
 * ```
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
        $this->_table = $table;
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
     * The `bundle` accepts multiples values:
     *
     * ```php
     * // wildcard is "*", e.g. look in all bundles which names starts with "art"
     * $this->Nodes
     *     ->find('all', ['bundle' => 'art*']);
     *     ->where([':article-title' => 'My first article!']);
     *
     * // single character match is "?"
     * $this->Nodes
     *     ->find('all', ['bundle' => 'arti?les']);
     *     ->where([':article-title' => 'My first article!']);
     *
     * // look in "articles" and "pages" bundles only
     * $this->Nodes
     *     ->find('all', ['bundle' => ['articles', 'pages']]);
     *     ->where([':article-title' => 'My first article!']);
     * ```
     *
     * The `bundle` option has no effects if no custom fields are given in the
     * WHERE clause.
     *
     * @param \Cake\Event\Event $event The beforeFind event that was triggered
     * @param \Cake\ORM\Query $query The original query to modify
     * @param array $options Additional options given as an array
     * @param bool $primary Whether this find is a primary query or not
     * @return bool|null
     */
    public function beforeFind(Event $event, Query $query, $options, $primary)
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
     * class TextField implements EventListenerInterface {
     *     public function implementedEvents() {
     *         return [
     *             'Field.TextField.Entity.beforeSave' => 'entityBeforeSave',
     *         ];
     *     }
     *
     *     public function entityBeforeSave(Event $event, $entity, $field, $options) {
     *          // alter $field, and do nifty things with $options['_post']
     *          // return FALSE; will halt the operation
     *     }
     * }
     * ```
     *
     * You will see `$options` array contains the POST information user just sent
     * when pressing form submit button.
     *
     *     $options['_post']: $_POST information for this [entity, field_instance] tuple.
     *
     * Field Handlers should **alter** `$field->value` and `$field->raw` according
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
                'value' => $field->value,
                'raw' => $field->raw,
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
                    if (is_array($postData)) {
                        $field->set('raw', $postData);
                    } elseif (is_string($postData)) {
                        $field->set('value', $postData);
                    }
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

                if (is_string($value)) {
                    $mock->set('value', $value);
                } else {
                    $mock->set('raw', $value);
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
     * Alters the given $field and fetches incoming POST data, both 'value' and
     * 'raw' properties will be automatically filled for the given $field entity.
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

        // auto-magic: automatically move POST data to "raw" if an array was sent,
        // "value" will be set to flattened raw
        if (is_array($post)) {
            $field->set('value', implode(' ', array_values(Hash::flatten($post))));
            $field->set('raw', $post);
        } else {
            $field->set('value', $post);
            $field->set('raw', []);
        }

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

        $options['bundle'] = empty($options['bundle']) ? null : $options['bundle'];
        $bundle = $this->_calculateBundle($options['bundle']);
        $alias = $this->_table->alias();
        $pk = $this->_table->primaryKey();
        $conn = $query->connection(null);
        list(, $driverClass) = namespaceSplit(strtolower(get_class($conn->driver())));

        $whereClause->traverse(function ($expression) use ($pk, $bundle, $alias, $driverClass) {
            if (!($expression instanceof Comparison)) {
                return;
            }

            $subQuery = $this->_virtualQuery($expression, $bundle);
            if (!$subQuery) {
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
     * @param string $bundle Table's bundle to scope. e.g. `articles` for `Nodes` table
     * @return \Cake\ORM\Query|bool False if not virtual field was found. The query
     *  object to use otherwise
     */
    protected function _virtualQuery($expression, $bundle = '*')
    {
        $field = $this->_parseFieldName($expression->getField());
        $value = $expression->getValue();
        $conjunction = $expression->getOperator();

        if (strpos($field, ':') !== 0) {
            return false;
        }

        $field = str_replace(':', '', $field);
        $subQuery = TableRegistry::get('Field.FieldValues')->find()
            ->select('FieldValues.entity_id')
            ->where([
                "FieldValues.field_instance_slug" => $field,
                "FieldValues.value {$conjunction}" => $value
            ]);

        if ($bundle === '*') {
            // look in all bundles
            $subQuery->where([
                'OR' => [
                    'FieldValues.table_alias' => $this->config('tableAlias'),
                    'FieldValues.table_alias LIKE' => $this->config('tableAlias') . ':%',
                ]
            ]);
        } elseif (is_array($bundle)) {
            // look in multiple bundles
            $subQuery->where(['FieldValues.table_alias IN' => $bundle]);
        } elseif (strpos($bundle, '*') !== false || strpos($bundle, '?') !== false) {
            // look in bundle matching pattern
            $subQuery->where(['FieldValues.table_alias LIKE' => str_replace(['*', '?'], ['%', '_'], $bundle)]);
        } else {
            // look in this specific bundle
            $subQuery->where(['FieldValues.table_alias' => $bundle]);
        }

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
     * Calculates the bundle name to use.
     *
     * ### Example:
     *
     * ```php
     * $this->_calculateBundle('articles');
     * // returns "<tableName>:articles"
     *
     * $this->_calculateBundle(['articles', 'pages']);
     * // returns ["<tableName>:articles", "<tableName>:pages"]
     *
     * $this->_calculateBundle();
     * // returns "*"
     * ```
     *
     * @param string|null $bundle Bundle to use within the table being managed by this
     *  behavior. e.g. `articles`. If not given `*` will be returned
     * @return string|array Bundle name as string if single bundle, or an array
     *  if multiple bundles were detected
     */
    protected function _calculateBundle($bundle = null)
    {
        if (empty($bundle)) {
            return '*';
        }

        if (is_array($bundle)) {
            $out = [];
            foreach ($bundle as $b) {
                $out[] = $this->config('tableAlias') . ':' . (string)$b;
            }
            return $out;
        }

        return $this->config('tableAlias') . ':' . (string)$bundle;
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
            ->select(['id', 'value', 'raw'])
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
            'raw' => null,
            'metadata' => new Entity([
                'field_value_id' => null,
                'field_instance_id' => $instance->id,
                'field_instance_slug' => $instance->slug,
                'entity_id' => $entity->{$pk},
                'table_alias' => $this->_guessTableAlias($entity),
                'description' => $instance->description,
                'required' => $instance->required,
                'settings' => $instance->settings,
                'view_modes' => $instance->view_modes,
                'handler' => $instance->handler,
                'errors' => [],
                'entity' => $entity,
            ])
        ]);

        if ($storedValue) {
            $mockField->metadata->accessible('*', true);
            $mockField->set('value', $storedValue->value);
            $mockField->set('raw', $storedValue->raw);
            $mockField->metadata->set('field_value_id', $storedValue->id);
            $mockField->metadata->accessible('*', false);
        }

        $mockField->isNew($entity->isNew());
        $mockField->accessible('*', false);
        $mockField->accessible('value', true);
        $mockField->accessible('raw', true);
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
