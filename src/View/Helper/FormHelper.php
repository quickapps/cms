<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;
use Cake\View\Widget\WidgetRegistry;
use QuickApps\Utility\HookTrait;

/**
 * Form helper library.
 *
 * Overwrites CakePHP's Form Helper and adds alter hooks to every method,
 * so plugins may alter form elements rendering cycle.
 */
class FormHelper extends CakeFormHelper {

	use HookTrait;

/**
 * Used by input() method.
 *
 * @var boolean
 */
	protected $_isRendering = false;

/**
 * {@inheritdoc}
 *
 * @param \Cake\View\Widget\WidgetRegistry $instance The registry instance to set.
 * @param array $widgets An array of widgets
 * @return \Cake\View\Widget\WidgetRegistry
 */
	public function widgetRegistry(WidgetRegistry $instance = null, $widgets = []) {
		$this->alter('FormHelper.widgetRegistry', $instance, $widgets);

		return parent::widgetRegistry($instance, $widgets);
	}

/**
 * {@inheritdoc}
 *
 * @param mixed $model The context for which the form is being defined. Can
 *   be an ORM entity, ORM resultset, or an array of meta data. You can use false or null
 *   to make a model-less form.
 * @param array $options An array of html attributes and options.
 * @return string An formatted opening FORM tag.
 */
	public function create($model = null, $options = []) {
		$this->alter('FormHelper.create', $model, $options);
		return parent::create($model, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param array $secureAttributes will be passed as html attributes into the hidden input elements generated for the
 *   Security Component.
 * @return string A closing FORM tag.
 */
	public function end($secureAttributes = []) {
		$this->alter('FormHelper.end', $secureAttributes);
		return parent::end($secureAttributes);
	}

/**
 * {@inheritdoc}
 *
 * @param array|null $fields If set specifies the list of fields to use when
 *    generating the hash, else $this->fields is being used.
 * @param array $secureAttributes will be passed as html attributes into the hidden
 *    input elements generated for the Security Component.
 * @return string A hidden input field with a security hash
 */
	public function secure(array $fields = array(), array $secureAttributes = array()) {
		$this->alter('FormHelper.secure', $fields, $secureAttributes);
		return parent::secure($fields, $secureAttributes);
	}

/**
 * {@inheritdoc}
 *
 * @param string $name The dot separated name for the field.
 * @return mixed Either null, or the list of fields.
 */
	public function unlockField($name = null) {
		$this->alter('FormHelper.unlockField', $name);
		return parent::unlockField($name);
	}

/**
 * {@inheritdoc}
 *
 * @param string $field This should be "Modelname.fieldname"
 * @return boolean If there are errors this method returns true, else false.
 */
	public function isFieldError($field) {
		$this->alter('FormHelper.isFieldError', $field);
		return parent::isFieldError($field);
	}

/**
 * {@inheritdoc}
 *
 * @param string $field A field name, like "Modelname.fieldname"
 * @param string|array $text Error message as string or array of messages. If an array,
 *   it should be a hash of key names => messages.
 * @param array $options See above.
 * @return string Formatted errors or ''.
 */
	public function error($field, $text = null, array $options = []) {
		$this->alter('FormHelper.error', $field, $text, $options);
		return parent::error($field, $text, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName This should be "Modelname.fieldname"
 * @param string $text Text that will appear in the label field. If
 *   $text is left undefined the text will be inflected from the
 *   fieldName.
 * @param array|string $options An array of HTML attributes, or a string, to be used as a class name.
 * @return string The formatted LABEL element
 */
	public function label($fieldName, $text = null, array $options = array()) {
		$this->alter('FormHelper.label', $fieldName, $text, $options);
		return parent::label($fieldName, $text, $options);
	}
	
/**
 * {@inheritdoc}
 *
 *
 * @param array $fields An array of customizations for the fields that will be
 *   generated. This array allows you to set custom types, labels, or other options.
 * @param array $options Options array. Valid keys are:
 * - `fieldset` Set to false to disable the fieldset.
 * - `legend` Set to false to disable the legend for the generated input set. Or supply a string
 *    to customize the legend text.
 * @return string Completed form inputs.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::inputs
 */
	public function allInputs(array $fields = [], array $options = []) {
		$this->alter('FormHelper.allInputs', $fields, $options);
		return parent::inputs($fields, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param array $fields An array of customizations for the fields that will be
 *   generated. This array allows you to set custom types, labels, or other options.
 * @param array $blacklist A list of fields to not create inputs for.
 * @param array $options Options array. Valid keys are:
 * - `fieldset` Set to false to disable the fieldset.
 * - `legend` Set to false to disable the legend for the generated input set. Or supply a string
 *    to customize the legend text.
 * @return string Completed form inputs.
 */
	public function inputs(array $fields, array $options = []) {
		$this->alter('FormHelper.inputs', $fields, $options);
		return parent::inputs($fields, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fields the form inputs to wrap in a fieldset
 * @param array $options Options array. Valid keys are:
 * - `fieldset` Set to false to disable the fieldset.
 * - `legend` Set to false to disable the legend for the generated input set. Or supply a string
 *    to customize the legend text.
 * @return string Completed form inputs.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::inputs
 */
	public function fieldset($fields = '', array $options = []) {
		$this->alter('FormHelper.fieldset', $fields, $options);
		return parent::fieldset($fields, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName This should be "Modelname.fieldname"
 * @param array $options Each type of input takes different options.
 * @return string Completed form widget.
 */
	public function input($fieldName, array $options = []) {
		if (!is_string($fieldName) && $fieldName instanceof \Field\Model\Entity\Field) {
			if (!$this->_isRendering) {
				$this->_isRendering = true;
				$event = $this->invoke("Field.{$fieldName->metadata->handler}.Entity.edit", $this->_View, $fieldName, $options);
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

		$this->alter('FormHelper.input', $fieldName, $options);
		return parent::input($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Name of a field, like this "Modelname.fieldname"
 * @param array $options Array of HTML attributes.
 * @return string An HTML text input element.
 */
	public function checkbox($fieldName, array $options = []) {
		$this->alter('FormHelper.checkbox', $fieldName, $options);
		return parent::checkbox($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Name of a field, like this "Modelname.fieldname"
 * @param array $options Radio button options array.
 * @param array $attributes Array of HTML attributes, and special attributes above.
 * @return string Completed radio widget set.
 */
	public function radio($fieldName, $options = [], array $attributes = []) {
		$this->alter('FormHelper.radio', $fieldName, $options, $attributes);
		return parent::radio($fieldName, $options, $attributes);
	}

/**
 * {@inheritdoc}
 *
 * @param string $method Method name / input type to make.
 * @param array $params Parameters for the method call
 * @return string Formatted input method.
 * @throws \Cake\Error\Exception When there are no params for the method call.
 */
	public function __call($method, $params) {
		return parent::__call($method, $params);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
 * @param array $options Array of HTML attributes, and special options above.
 * @return string A generated HTML text input element
 */
	public function textarea($fieldName, array $options = []) {
		$this->alter('FormHelper.textarea', $fieldName, $options);
		return parent::textarea($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Name of a field, in the form of "Modelname.fieldname"
 * @param array $options Array of HTML attributes.
 * @return string A generated hidden input
 */
	public function hidden($fieldName, array $options = []) {
		$this->alter('FormHelper.hidden', $fieldName, $options);
		return parent::hidden($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
 * @param array $options Array of HTML attributes.
 * @return string A generated file input.
 */
	public function file($fieldName, array $options = []) {
		$this->alter('FormHelper.file', $fieldName, $options);
		return parent::file($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $title The button's caption. Not automatically HTML encoded
 * @param array $options Array of options and HTML attributes.
 * @return string A HTML button tag.
 */
	public function button($title, array $options = []) {
		$this->alter('FormHelper.button', $title, $options);
		return parent::button($title, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $title The button's caption. Not automatically HTML encoded
 * @param string|array $url URL as string or array
 * @param array $options Array of options and HTML attributes.
 * @return string A HTML button tag.
 */
	public function postButton($title, $url, array $options = []) {
		$this->alter('FormHelper.postButton', $title, $url, $options);
		return parent::postButton($title, $url, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of HTML attributes.
 * @param boolean|string $confirmMessage JavaScript confirmation message.
 * @return string An `<a />` element.
 */
	public function postLink($title, $url = null, array $options = [], $confirmMessage = false) {
		$this->alter('FormHelper.postLink', $title, $url, $options, $confirmMessage);
		return parent::postLink($title, $url, $options, $confirmMessage);
	}

/**
 * {@inheritdoc}
 *
 * @param string $caption The label appearing on the button OR if string contains :// or the
 *  extension .jpg, .jpe, .jpeg, .gif, .png use an image if the extension
 *  exists, AND the first character is /, image is relative to webroot,
 *  OR if the first character is not /, image is relative to webroot/img.
 * @param array $options Array of options. See above.
 * @return string A HTML submit button
 */
	public function submit($caption = null, array $options = []) {
		$this->alter('FormHelper.submit', $caption, $options);
		return parent::submit($caption, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $options Array of the OPTION elements (as 'value'=>'Text' pairs) to be used in the
 *   SELECT element
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 */
	public function select($fieldName, $options = [], array $attributes = []) {
		$this->alter('FormHelper.select', $fieldName, $options, $attributes);
		return parent::select($fieldName, $options, $attributes);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $options Array of the OPTION elements (as 'value'=>'Text' pairs) to be used in the
 *   checkboxes element.
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 */
	public function multiCheckbox($fieldName, $options, array $attributes = []) {
		$this->alter('FormHelper.multiCheckbox', $fieldName, $options, $attributes);
		return parent::multiCheckbox($fieldName, $options, $attributes);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options Options & HTML attributes for the select element
 * @return string A generated day select box.
 */
	public function day($fieldName = null, array $options = []) {
		$this->alter('FormHelper.day', $fieldName, $options);
		return parent::day($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options Options & attributes for the select elements.
 * @return string Completed year select input
 */
	public function year($fieldName, array $options = []) {
		$this->alter('FormHelper.widgetRegistry', $fieldName, $options);
		return parent::widgetRegistry($instance, $widgets);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options Attributes for the select element
 * @return string A generated month select dropdown.
 */
	public function month($fieldName, array $options = []) {
		$this->alter('FormHelper.month', $fieldName, $options);
		return parent::month($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options List of HTML attributes
 * @return string Completed hour select input
 */
	public function hour($fieldName, array $options = []) {
		$this->alter('FormHelper.hour', $fieldName, $options);
		return parent::hour($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options Array of options.
 * @return string Completed minute select input.
 */
	public function minute($fieldName, array $options = []) {
		$this->alter('FormHelper.minute', $fieldName, $options);
		return parent::minute($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options Array of options
 * @return string Completed meridian select input
 */
	public function meridian($fieldName, array $options = []) {
		$this->alter('FormHelper.meridian', $fieldName, $options);
		return parent::meridian($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options Array of Options
 * @return string Generated set of select boxes for the date and time formats chosen.
 */
	public function dateTime($fieldName, array $options = []) {
		$this->alter('FormHelper.dateTime', $fieldName, $options);
		return parent::dateTime($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options Options & HTML attributes for the select element
 * @return string A generated day select box.
 */
	public function time($fieldName, array $options = []) {
		$this->alter('FormHelper.time', $fieldName, $options);
		return parent::time($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $options Array of Options
 * @return string Generated set of select boxes for time formats chosen.
 */
	public function date($fieldName, array $options = []) {
		$this->alter('FormHelper.date', $fieldName, $options);
		return parent::date($fieldName, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $type The type of context. This key
 *   can be used to overwrite existing providers.
 * @param callable $check A callable that returns a object
 *   when the form context is the correct type.
 * @return void
 */
	public function addContextProvider($type, callable $check) {
		$this->alter('FormHelper.addContextProvider', $type, $check);
		return parent::addContextProvider($type, $check);
	}

/**
 * {@inheritdoc}
 *
 * @param string $name The name of the widget. e.g. 'text'.
 * @param array|\Cake\View\Widget\WidgetInterface $spec Either a string class name or an object
 *    implementing the WidgetInterface.
 * @return void
 */
	public function addWidget($name, $spec) {
		$this->alter('FormHelper.addWidget', $name, $spec);
		return parent::addWidget($name, $spec);
	}

/**
 * {@inheritdoc}
 *
 * @param string $name The name of the widget. e.g. 'text'.
 * @param array $attrs The attributes for rendering the input.
 * @return void
 */
	public function widget($name, array $data = []) {
		$this->alter('FormHelper.widget', $name, $data);
		return parent::widget($name, $data);
	}

}
