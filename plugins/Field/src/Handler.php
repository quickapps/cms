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

use Cake\Validation\Validator;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldInstance;
use QuickApps\View\View;

/**
 * Base field handler class.
 *
 * All fields classes should extend this class.
 */
class Handler
{

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
     * @return array
     */
    public function info()
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
     * Defines how the field will actually display its contents when rendering
     * entities.
     *
     * You can use `$view->viewMode();` to get the view-mode being used when
     * rendering the entity.
     *
     * @param \Field\Model\Entity\Field $field Field information
     * @param \QuickApps\View\View $view The view instance being used
     * @return string HTML representation of this field
     */
    public function render(Field $field, View $view)
    {
        return '';
    }

    /**
     * Renders the field in edit mode.
     *
     * @param \Field\Model\Entity\Field $field Field information
     * @param \QuickApps\View\View $view The view instance being used
     * @return string HTML containing from elements
     */
    public function edit(Field $field, View $view)
    {
        return '';
    }

    /**
     * Triggered when custom field is attached to entity under the "_fields"
     * property.
     *
     * This method is commonly used to alter custom field values before it gets
     * attached to entity. For instance, set default values.
     *
     * @param \Field\Model\Entity\Field $field The field that is being attached
     *  to entity, you can alter this before field is attached
     * @return void
     */
    public function fieldAttached(Field $field)
    {
    }

    /**
     * Triggered on entity's "beforeFind" event.
     *
     * Can be used as preprocessor, as fields can directly alter the entity's
     * properties before it's returned as part of a find query.
     *
     * Returning NULL will cause the entity to be removed from the resulting find
     * collection. In the other hand, returning FALSE will halt the entire find
     * operation. Otherwise you MUST RETURN TRUE.
     *
     * @param \Field\Model\Entity\Field $field Field information
     * @param array $options Options given as an array
     * @param bool $primary Whether this event was triggered as part of a primary
     *  find query or not
     * @return mixed
     */
    public function beforeFind(Field $field, array $options, $primary)
    {
        return true;
    }

    /**
     * After an entity is validated as part of save process.
     *
     * This is where Fields must validate their information. To do so, they should
     * alter the provided Validator instance, this instance will be later used to
     * validate the information.
     *
     * If you want to halt the save and validation process you can return FALSE.
     *
     * @param \Field\Model\Entity\Field $field Field information
     * @param \Cake\Validation\Validator $validator The validator object
     * @return bool|null False will halt the save process
     */
    public function validate(Field $field, Validator $validator)
    {
        return true;
    }

    /**
     * Before each entity is saved.
     *
     * Returning a FALSE will halt the save operation.
     *
     * @param \Field\Model\Entity\Field $field Field information
     * @param mixed $post Holds the POST information that was sent by user when
     *  saving entity's form
     * @return bool
     */
    public function beforeSave(Field $field, $post)
    {
        return true;
    }

    /**
     * After each entity is saved.
     *
     * @param \Field\Model\Entity\Field $field Field information
     * @return void
     */
    public function afterSave(Field $field)
    {
    }

    /**
     * Before an entity is deleted from database.
     *
     * @param \Field\Model\Entity\Field $field Field information
     * @return bool False will halt the delete process
     */
    public function beforeDelete(Field $field)
    {
        return true;
    }

    /**
     * After an entity was deleted from database.
     *
     * @param \Field\Model\Entity\Field $field Field information
     * @return void
     */
    public function afterDelete(Field $field)
    {
    }

    /**
     * Renders all the form elements to be used on the field's settings form.
     *
     * Field settings will be the same for all shared instances of the same field
     * and should define the way the value will be stored in the database.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param \QuickApps\View\View $view View instance being used
     * @return string HTML form elements for the settings page
     */
    public function settings(FieldInstance $instance, View $view)
    {
        return '';
    }

    /**
     * Returns an array of default values for field settings form's inputs.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @return array
     */
    public function defaultSettings(FieldInstance $instance)
    {
        return [];
    }

    /**
     * Triggered before instance's settings are changed.
     *
     * Here Field Handlers can apply custom validation rules to their settings.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $settings Settings values to be validated
     * @param \Cake\Validation\Validator $validator The validator object
     * @return void
     */
    public function validateSettings(FieldInstance $instance, array $settings, Validator $validator)
    {
    }

    /**
     * Renders all the form elements to be used on the field view mode form.
     *
     * Here is where you should render form elements to hold settings about how
     * Entities should be rendered for a particular View Mode. You can provide
     * different input elements depending on the view mode, you can use
     * `$viewMode` to distinct between view modes.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param \QuickApps\View\View $view View instance being used
     * @param string $viewMode Name of the view mode being handled
     * @return string HTML form elements for the settings page
     */
    public function viewModeSettings(FieldInstance $instance, View $view, $viewMode)
    {
        return '';
    }

    /**
     * Returns an array of defaults values for each input in the view modes form.
     *
     * You can provide different default values depending on the view mode, you
     * can use `$viewMode` to distinct between view modes.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param string $viewMode Name of the view mode being handled
     * @return array
     */
    public function defaultViewModeSettings(FieldInstance $instance, $viewMode)
    {
        return [];
    }

    /**
     * Triggered before instance's view mode settings are changed.
     *
     * Here Field Handlers can apply custom validation rules to view mode's settings.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @param array $settings View mode's setting values to be validated
     * @param \Cake\Validation\Validator $validator The validator object
     * @param string $viewMode Name of the view mode being handled
     * @return void
     */
    public function validateViewModeSettings(FieldInstance $instance, array $settings, Validator $validator, $viewMode)
    {
    }

    /**
     * Before an new instance of this field is attached to a database table.
     *
     * Stopping this event or returning false will abort the attach operation.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @return bool False will halt the attach process
     */
    public function beforeAttach(FieldInstance $instance)
    {
        return true;
    }

    /**
     * After an new instance of this field is attached to a database table.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @return void
     */
    public function afterAttach(FieldInstance $instance)
    {
    }

    /**
     * Before an instance of this field is detached from a database table.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance information
     * @return bool False will halt the detach process
     */
    public function beforeDetach(FieldInstance $instance)
    {
        return true;
    }

    /**
     * After an instance of this field was detached from a database table.
     *
     * Here is when you should remove all the stored data for this instance from the
     * DB. For example, if your field stores physical files for every entity, then
     * you should delete those files.
     *
     * NOTE: By default QuickAppsCMS, automatically removes all related records
     * from the `eav_values` table.
     *
     * @param \Field\Model\Entity\FieldInstance $instance Instance entity that was
     *  detached (removed from "field_instances" table)
     * @return void
     */
    public function afterDetach(FieldInstance $instance)
    {
    }
}
