<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;
use Cake\View\Widget\WidgetRegistry;
use QuickApps\Event\HookAwareTrait;

/**
 * Form helper library.
 *
 * Overwrites CakePHP's Form Helper and adds alter hooks to every method,
 * so plugins may alter form elements rendering cycle.
 */
class FormHelper extends CakeFormHelper
{

    use HookAwareTrait;

    /**
     * Used by input() method.
     *
     * @var bool
     */
    protected $_isRendering = false;

    /**
     * {@inheritDoc}
     */
    public function widgetRegistry(WidgetRegistry $instance = null, $widgets = [])
    {
        $this->alter(['FormHelper.widgetRegistry', $this->_View], $instance, $widgets);
        return parent::widgetRegistry($instance, $widgets);
    }

    /**
     * {@inheritDoc}
     */
    public function create($model = null, array $options = [])
    {
        $this->alter(['FormHelper.create', $this->_View], $model, $options);
        return parent::create($model, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function end($secureAttributes = [])
    {
        $this->alter(['FormHelper.end', $this->_View], $secureAttributes);
        return parent::end($secureAttributes);
    }

    /**
     * {@inheritDoc}
     */
    public function secure(array $fields = [], array $secureAttributes = [])
    {
        $this->alter(['FormHelper.secure', $this->_View], $fields, $secureAttributes);
        return parent::secure($fields, $secureAttributes);
    }

    /**
     * {@inheritDoc}
     */
    public function unlockField($name = null)
    {
        $this->alter(['FormHelper.unlockField', $this->_View], $name);
        return parent::unlockField($name);
    }

    /**
     * {@inheritDoc}
     */
    public function isFieldError($field)
    {
        $this->alter(['FormHelper.isFieldError', $this->_View], $field);
        return parent::isFieldError($field);
    }

    /**
     * {@inheritDoc}
     */
    public function error($field, $text = null, array $options = [])
    {
        $this->alter(['FormHelper.error', $this->_View], $field, $text, $options);
        return parent::error($field, $text, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function label($fieldName, $text = null, array $options = [])
    {
        $this->alter(['FormHelper.label', $this->_View], $fieldName, $text, $options);
        return parent::label($fieldName, $text, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function allInputs(array $fields = [], array $options = [])
    {
        $this->alter(['FormHelper.allInputs', $this->_View], $fields, $options);
        return parent::inputs($fields, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function inputs(array $fields, array $options = [])
    {
        $this->alter(['FormHelper.inputs', $this->_View], $fields, $options);
        return parent::inputs($fields, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function fieldset($fields = '', array $options = [])
    {
        $this->alter(['FormHelper.fieldset', $this->_View], $fields, $options);
        return parent::fieldset($fields, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function input($fieldName, array $options = [])
    {
        if (!is_string($fieldName) && $fieldName instanceof \Field\Model\Entity\Field) {
            if (!$this->_isRendering) {
                $this->_isRendering = true;
                $event = $this->trigger(["Field.{$fieldName->metadata->handler}.Entity.edit", $this->_View], $fieldName, $options);
                $this->_isRendering = false;
                return $event->result;
            } else {
                $options += ['value' => $fieldName->value, 'label' => $fieldName->label];

                if ($fieldName->metadata->required) {
                    $options['label'] .= ' *';
                    $options['required'] = 'required';
                }

                return $this->input(":{$fieldName->name}", $options);
            }
        }

        $this->alter(['FormHelper.input', $this->_View], $fieldName, $options);
        return parent::input($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function checkbox($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.checkbox', $this->_View], $fieldName, $options);
        return parent::checkbox($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function radio($fieldName, $options = [], array $attributes = [])
    {
        $this->alter(['FormHelper.radio', $this->_View], $fieldName, $options, $attributes);
        return parent::radio($fieldName, $options, $attributes);
    }

    /**
     * {@inheritDoc}
     */
    public function __call($method, $params)
    {
        return parent::__call($method, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function textarea($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.textarea', $this->_View], $fieldName, $options);
        return parent::textarea($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function hidden($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.hidden', $this->_View], $fieldName, $options);
        return parent::hidden($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function file($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.file', $this->_View], $fieldName, $options);
        return parent::file($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function button($title, array $options = [])
    {
        $this->alter(['FormHelper.button', $this->_View], $title, $options);
        return parent::button($title, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function postButton($title, $url, array $options = [])
    {
        $this->alter(['FormHelper.postButton', $this->_View], $title, $url, $options);
        return parent::postButton($title, $url, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function postLink($title, $url = null, array $options = [], $confirmMessage = false)
    {
        $this->alter(['FormHelper.postLink', $this->_View], $title, $url, $options, $confirmMessage);
        return parent::postLink($title, $url, $options, $confirmMessage);
    }

    /**
     * {@inheritDoc}
     */
    public function submit($caption = null, array $options = [])
    {
        $this->alter(['FormHelper.submit', $this->_View], $caption, $options);
        return parent::submit($caption, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function select($fieldName, $options = [], array $attributes = [])
    {
        $this->alter(['FormHelper.select', $this->_View], $fieldName, $options, $attributes);
        return parent::select($fieldName, $options, $attributes);
    }

    /**
     * {@inheritDoc}
     */
    public function multiCheckbox($fieldName, $options, array $attributes = [])
    {
        $this->alter(['FormHelper.multiCheckbox', $this->_View], $fieldName, $options, $attributes);
        return parent::multiCheckbox($fieldName, $options, $attributes);
    }

    /**
     * {@inheritDoc}
     */
    public function day($fieldName = null, array $options = [])
    {
        $this->alter(['FormHelper.day', $this->_View], $fieldName, $options);
        return parent::day($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function year($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.widgetRegistry', $this->_View], $fieldName, $options);
        return parent::widgetRegistry($instance, $widgets);
    }

    /**
     * {@inheritDoc}
     */
    public function month($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.month', $this->_View], $fieldName, $options);
        return parent::month($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function hour($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.hour', $this->_View], $fieldName, $options);
        return parent::hour($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function minute($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.minute', $this->_View], $fieldName, $options);
        return parent::minute($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function meridian($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.meridian', $this->_View], $fieldName, $options);
        return parent::meridian($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function dateTime($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.dateTime', $this->_View], $fieldName, $options);
        return parent::dateTime($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function time($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.time', $this->_View], $fieldName, $options);
        return parent::time($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function date($fieldName, array $options = [])
    {
        $this->alter(['FormHelper.date', $this->_View], $fieldName, $options);
        return parent::date($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function addContextProvider($type, callable $check)
    {
        $this->alter(['FormHelper.addContextProvider', $this->_View], $type, $check);
        return parent::addContextProvider($type, $check);
    }

    /**
     * {@inheritDoc}
     */
    public function addWidget($name, $spec)
    {
        $this->alter(['FormHelper.addWidget', $this->_View], $name, $spec);
        return parent::addWidget($name, $spec);
    }

    /**
     * {@inheritDoc}
     */
    public function widget($name, array $data = [])
    {
        $this->alter(['FormHelper.widget', $this->_View], $name, $data);
        return parent::widget($name, $data);
    }
}
