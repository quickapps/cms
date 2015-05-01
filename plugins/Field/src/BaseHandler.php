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
namespace Field;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Entity;
use Cake\Validation\Validator;
use Field\Model\Entity\Field;
use QuickApps\Event\EventDispatcherTrait;
use QuickApps\Shortcode\ShortcodeTrait;

/**
 * Base Handler class.
 *
 * All handlers classes should extend this class. It adds some utility
 * methods, and define some default events handlers.
 */
class BaseHandler implements EventListenerInterface
{

    use EventDispatcherTrait;
    use ShortcodeTrait;

    /**
     * Return a list of implemented events.
     *
     * Events names must be named as follow:
     *
     *     Field.<FieldHandlerName>.<Entity|Instance>.<eventName>
     *
     * Example:
     *
     *     Field.TextField.Entity.edit
     *
     * Where:
     *
     * - `Field`: Prefix, the event subspace.
     * - `TextField`: Name of the class for Text Handler in this example.
     * - `Entity` or `Instance`: "Entity" for events related to entities
     *    (an User, a Node, etc), or "Instance" for field instances events.
     * - `edit`: The name of the event.
     *
     * You can override this method and provide a customized set of event handlers.
     *
     * @return array
     */
    public function implementedEvents()
    {
        list(, $handlerName) = namespaceSplit(get_class($this));
        return [
            // Related to Entity:
            "{$handlerName}.Entity.display" => 'entityDisplay',
            "{$handlerName}.Entity.edit" => 'entityEdit',
            "{$handlerName}.Entity.find" => 'entityBeforeFind',
            "{$handlerName}.Entity.validate" => 'entityValidate',
            "{$handlerName}.Entity.beforeSave" => 'entityBeforeSave',
            "{$handlerName}.Entity.afterSave" => 'entityAfterSave',
            "{$handlerName}.Entity.beforeDelete" => 'entityBeforeDelete',
            "{$handlerName}.Entity.afterDelete" => 'entityAfterDelete',
            "{$handlerName}.Entity.fieldAttached" => 'entityFieldAttached',

            // Related to Instance:
            "{$handlerName}.Instance.info" => 'instanceInfo',
            "{$handlerName}.Instance.settingsForm" => 'instanceSettingsForm',
            "{$handlerName}.Instance.settingsDefaults" => 'instanceSettingsDefaults',
            "{$handlerName}.Instance.settingsValidate" => 'instanceSettingsValidate',
            "{$handlerName}.Instance.viewModeForm" => 'instanceViewModeForm',
            "{$handlerName}.Instance.viewModeDefaults" => 'instanceViewModeDefaults',
            "{$handlerName}.Instance.viewModeValidate" => 'instanceViewModeValidate',
            "{$handlerName}.Instance.beforeAttach" => 'instanceBeforeAttach',
            "{$handlerName}.Instance.afterAttach" => 'instanceAfterAttach',
            "{$handlerName}.Instance.beforeDetach" => 'instanceBeforeDetach',
            "{$handlerName}.Instance.afterDetach" => 'instanceAfterDetach',
        ];
    }

    /**
     * Defines how the field will actually display its contents when rendering entities.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Field information
     * @param array $options Additional array of options
     * @return string HTML representation of this field
     */
    public function entityDisplay(Event $event, Field $field, $options = [])
    {
        return '';
    }

    /**
     * Renders field in edit mode.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Field information
     * @param array $options Options given as an array
     * @return string HTML containing from elements
     */
    public function entityEdit(Event $event, Field $field, $options = [])
    {
        return '';
    }

    /**
     * Triggered when custom field is attached to entity under the "_fields" property.
     *
     * This method is commonly used to alter custom field values before it gets
     * attached to entity. For instance, set default values.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field The field that is being attached
     *  to entity, you can alter this before field is attached
     * @return void
     */
    public function entityFieldAttached(Event $event, Field $field)
    {
    }

    /**
     * Triggered on entity's "beforeFind" event.
     *
     * Can be used as preprocessor, as fields can directly alter the entity's
     * properties before it's returned as part of a find query.
     *
     * Returning false will cause the entity to be removed from the resulting find
     * collection. In the other hand, stopping the given event will halt the
     * entire find operation.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Field information
     * @param array $options Options given as an array
     * @param bool $primary Whether this event was triggered as part of a primary
     *  find query or not
     * @return mixed
     */
    public function entityBeforeFind(Event $event, Field $field, $options, $primary)
    {
    }


    /**
     * After an entity is validated as part of save process.
     *
     * This is where Field Handlers must validate their information.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Field information
     * @param \Cake\Validation\Validator $validator The validator object
     * @return bool False will halt the save process
     */
    public function entityValidate(Event $event, Field $field, Validator $validator)
    {
        return true;
    }

    /**
     * Before each entity is saved.
     *
     * Returning a non-true value will halt the save operation, as stopping
     * the event as well.
     *
     * The options array contains the `_post` key, which holds all the information
     * you need to update you field:
     *
     * ```php
     * $options['_post']
     * ```
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Field information
     * @param array $options Options given as an array
     * @return bool
     */
    public function entityBeforeSave(Event $event, Field $field, $options)
    {
        return true;
    }

    /**
     * After each entity is saved.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Field information
     * @param array $options Options given as an array
     * @return void
     */
    public function entityAfterSave(Event $event, Field $field, $options)
    {
    }

    /**
     * Before an entity is deleted from database.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Field information
     * @param array $options Options given as an array
     * @return bool False will halt the delete process
     */
    public function entityBeforeDelete(Event $event, Field $field, $options)
    {
        return true;
    }

    /**
     * After an entity is deleted from database.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Field information
     * @param array $options Options given as an array
     * @return void
     */
    public function entityAfterDelete(Event $event, Field $field, $options)
    {
    }

    /**
     * Returns an array of information of this field.
     *
     * - `type` (string): Type of data this field stores, possible values are:
     *   datetime, decimal, int, text, varchar.
     *
     * - `name` (string): Human readable name of this field. ex. `Selectbox`
     *   Defaults to class name.
     *
     * - `description` (string): Something about what this field does or allows
     *   to do. Defaults to class name.
     *
     * - `hidden` (bool): If set to false users can not use this field via
     *   Field UI. Defaults to true, users can use it via Field UI.
     *
     * - `maxInstances` (int): Maximum number instances of this field a table
     *   can have. Set to 0 to indicates no limits. Defaults to 0.
     *
     * - `searchable` (bool): Whether this field can be searched using WHERE
     *   clauses.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @return array
     */
    public function instanceInfo(Event $event)
    {
        list(, $handlerName) = namespaceSplit(get_class($this));
        return [
            'type' => 'varchar',
            'name' => (string)$handlerName,
            'description' => (string)$handlerName,
            'hidden' => false,
            'maxInstances' => 0,
            'searchable' => true,
        ];
    }

    /**
     * Renders all the form elements to be used on the field settings form.
     *
     * Field settings will be the same for all shared instances of the same field
     * and should define the way the value will be stored in the database.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $options Options given as an array
     * @return string HTML form elements for the settings page
     */
    public function instanceSettingsForm(Event $event, $instance, $options = [])
    {
        return '';
    }

    /**
     * Returns an array of default values for field settings form's inputs.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $options Options given as an array
     * @return array
     */
    public function instanceSettingsDefaults(Event $event, $instance, $options = [])
    {
        return [];
    }

    /**
     * Triggered before instance's settings are changed.
     *
     * Here Field Handlers can apply custom validation rules to their settings.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param array $settings Settings values to be validated
     * @param \Cake\Validation\Validator $validator The validator object
     * @return void
     */
    public function instanceSettingsValidate(Event $event, array $settings, Validator $validator)
    {
    }

    /**
     * Renders all the form elements to be used on the field view mode form.
     *
     * Here is where you should render form elements to hold settings about how
     * Entities should be rendered for a particular View Mode.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $options Options given as an array
     * @return string HTML form elements for the settings page
     */
    public function instanceViewModeForm(Event $event, $instance, $options = [])
    {
        return '';
    }

    /**
     * Returns an array of defaults values for each input in the view modes form.
     *
     * You can provide different default values depending on the view mode, you
     * can use `$option['viewMode']` to distinct between view modes.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $options Options given as an array
     * @return array
     */
    public function instanceViewModeDefaults(Event $event, $instance, $options = [])
    {
        return [];
    }

    /**
     * Triggered before instance's view mode settings are changed.
     *
     * Here Field Handlers can apply custom validation rules to view mode's settings.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param array $settings View mode's setting values to be validated
     * @param \Cake\Validation\Validator $validator The validator object
     * @return void
     */
    public function instanceViewModeValidate(Event $event, array $settings, Validator $validator)
    {
    }

    /**
     * Before an new instance of this field is attached to a database table.
     *
     * Stopping this event or returning false will abort the attach operation.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $options Options given as an array
     * @return bool False will halt the attach process
     */
    public function instanceBeforeAttach(Event $event, $instance, $options = [])
    {
        return true;
    }

    /**
     * After an new instance of this field is attached to a database table.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $options Options given as an array
     * @return void
     */
    public function instanceAfterAttach(Event $event, $instance, $options = [])
    {
    }

    /**
     * Before an instance of this field is detached from a database table.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $options Options given as an array
     * @return bool False will halt the detach process
     */
    public function instanceBeforeDetach(Event $event, $instance, $options = [])
    {
        return true;
    }

    /**
     * After an instance of this field was detached from a database table.
     *
     * Here is when you should remove all the stored data for this instance from
     * the DB. For example, if your field stores physical files for every entity,
     * then you should delete those files.
     *
     * NOTE: By default QuickApps CMS, automatically removes all related records
     * from the `field_values` table.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\FieldInstance $instance Instance entity being
     *  detached (deleted from "field_instances" table)
     * @param array $options Options given as an array
     * @return void
     */
    public function instanceAfterDetach(Event $event, $instance, $options = [])
    {
    }
}
