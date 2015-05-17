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
namespace Field\Field;

use Cake\Validation\Validator;
use CMS\View\View;
use Field\Handler;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldInstance;

/**
 * List Field Handler.
 *
 * Defines list field types, used to create selection lists.
 */
class ListField extends Handler
{

    /**
     * {@inheritDoc}
     */
    public function info()
    {
        return [
            'type' => 'text',
            'name' => __d('field', 'List'),
            'description' => __d('field', 'Defines list field types, used to create selection lists.'),
            'hidden' => false,
            'maxInstances' => 0,
            'searchable' => true,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function render(Field $field, View $view)
    {
        return $view->element('Field.ListField/display', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function edit(Field $field, View $view)
    {
        return $view->element('Field.ListField/edit', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Field $field, Validator $validator)
    {
        if ($field->metadata->required) {
            $validator
                ->requirePresence($field->name)
                ->notEmpty($field->name, __d('field', 'Field required.'));
        } else {
            $validator->allowEmpty($field->name);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeSave(Field $field, $post)
    {
        $value = $post;
        if (is_array($value)) {
            $value = implode(' ', array_values($value));
        }
        $field->set('value', $value);
        $field->set('extra', $post);
    }

    /**
     * {@inheritDoc}
     */
    public function settings(FieldInstance $instance, View $view)
    {
        return $view->element('Field.ListField/settings_form', compact('instance'));
    }

    /**
     * {@inheritDoc}
     */
    public function viewModeSettings(FieldInstance $instance, View $view, $viewMode)
    {
        return $view->element('Field.ListField/view_mode_form', compact('instance', 'viewMode'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultViewModeSettings(FieldInstance $instance, $viewMode)
    {
        return [
            'label_visibility' => 'above',
            'shortcodes' => true,
            'hidden' => false,
            'formatter' => 'default',
        ];
    }
}
