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
namespace Field\Model\Entity;

use Cake\ORM\Entity;
use QuickApps\Event\HookAwareTrait;
use QuickApps\View\ViewModeAwareTrait;

/**
 * Mock Field.
 *
 * This entity represents a table cell and holds the following attributes:
 *
 * - name: Machine name of this field. ex. `user-age`. (Schema equivalent: column name)
 * - label: Human readable name of this field e.g.: `User Last name`.
 * - value: Value for this [FieldInstance, Entity] tuple. (Schema equivalent: cell value).
 * - extra: Extra information.
 * - metadata: A mock entity which holds the following properties describing a column
 *   - value_id: ID of the value stored in `field_values` table (from where `value` comes from).
 *   - instance_id: ID of the field instance (`field_instances` table) attached to Table.
 *   - table_alias: Name of the table this field is attached to. e.g: `users`.
 *   - entity_id: ID of the entity to which this field is attached to.
 *   - handler: Name of the `Listener Class` a.k.a. `Field Handler`. ex: `Field\Text`
 *   - type: Type of data stored by this field (datetime, decilmal, etc).
 *   - required: True if required, false otherwise
 *   - description: Something about this field: e.g.: `Please enter your name`.
 *   - settings: Array of additional information handled by this particular field. ex: `max_len`, `min_len`, etc
 *   - view_modes: View modes settings, describes how field should be rendered.
 *   - entity: Entity object this field is attached to.
 *   - errors: Validation error messages
 *
 * @property string $name
 * @property string $label
 * @property string $value
 * @property string $extra
 * @property \Cake\Datasource\EntityInterface $metadata
 */
class Field extends Entity
{

    use HookAwareTrait;
    use ViewModeAwareTrait;

    /**
     * Gets field's View Mode's settings for the in-use View Mode.
     *
     * @return array
     */
    protected function _getViewModeSettings()
    {
        $viewMode = $this->viewMode();
        $settings = [];
        if (!empty($this->metadata->view_modes[$viewMode])) {
            $settings = $this->metadata->view_modes[$viewMode];
        }
        return $settings;
    }

    /**
     * String representation of this field.
     *
     * Defaults to field's `value` property.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->get('value');
    }

    /**
     * Returns an array that can be used to describe the internal state of
     * this object.
     *
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'name' => $this->get('name'),
            'label' => $this->get('label'),
            'value' => $this->get('value'),
            'extra' => $this->get('extra'),
            'metadata' => [
                'value_id' => $this->get('metadata')->get('value_id'),
                'instance_id' => $this->get('metadata')->get('instance_id'),
                'attribute_id' => $this->get('metadata')->get('attribute_id'),
                'entity_id' => $this->get('metadata')->get('entity_id'),
                'table_alias' => $this->get('metadata')->get('table_alias'),
                'type' => $this->get('metadata')->get('type'),
                'bundle' => $this->get('metadata')->get('bundle'),
                'handler' => $this->get('metadata')->get('handler'),
                'required' => $this->get('metadata')->get('required'),
                'description' => $this->get('metadata')->get('description'),
                'settings' => $this->get('metadata')->get('settings'),
                'view_modes' => $this->get('metadata')->get('view_modes'),
                'entity' => 'Object',
                'errors' => $this->get('metadata')->get('errors'),
            ],
        ];
    }
}
