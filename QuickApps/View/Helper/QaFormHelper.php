<?php
App::uses('FormHelper', 'View/Helper');

/**
 * Form Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class QaFormHelper extends FormHelper {
/**
 * Returns false if given form field described by the current entity has no errors.
 * Otherwise it returns the validation message
 *
 * @return boolean True on errors.
 */
	public function tagIsInvalid() {
		return parent::tagIsInvalid();
	}

/**
 * Returns a formatted text element for FORM INPUTS.
 *
 * ### Options
 *
 * - `tag` Tag name. default `span`.
 * - `escape` Whether or not the contents should be html_entity escaped.
 *
 * @param string $text The text for the help block element
 * @param string $options Additional HTML attributes of the tag, see above.
 * @return string The formatted tag element.
 */
	public function helpBlock($text, $options = array('tag' => 'span')) {
		$data = compact('text', 'options');
		$this->hook('form_help_block_alter', $data);

		extract($data);

		$options['tag'] = !isset($options['tag']) ? 'span' : $options['tag'];
		$tag = $options['tag'];

		unset($options['tag']);

		return $this->_View->Html->tag($tag, $text, $options);
	}

/**
 * Returns an HTML FORM element.
 *
 * ### Options:
 *
 * - `type` Form method defaults to POST
 * - `action`  The controller action the form submits to, (optional).
 * - `url`  The url the form submits to. Can be a string or a url array,
 * - `default`  Allows for the creation of Ajax forms.
 * - `onsubmit` Used in conjunction with 'default' to create ajax forms.
 * - `inputDefaults` set the default $options for FormHelper::input(). Any options that would
 *	be set when using FormHelper::input() can be set here.  Options set with `inputDefaults`
 *	can be overridden when calling input()
 * - `encoding` Set the accept-charset encoding for the form.  Defaults to `Configure::read('App.encoding')`
 *
 * @access public
 * @param string $model The model object which the form is being defined for
 * @param array $options An array of html attributes and options.
 * @return string An formatted opening FORM tag.
 * @link http://book.cakephp.org/view/1384/Creating-Forms
 */
	public function create($model = null, $options = array()) {
		$data = compact('model', 'options');
		$this->hook('form_create_alter', $data);

		extract($data);

		return parent::create($model, $options);
	}

/**
 * Closes an HTML form, cleans up values set by FormHelper::create(), and writes hidden
 * input fields where appropriate.
 *
 * If $options is set a form submit button will be created. Options can be either a string or an array.
 *
 * {{{
 * array usage:
 *
 * array('label' => 'save'); value="save"
 * array('label' => 'save', 'name' => 'Whatever'); value="save" name="Whatever"
 * array('name' => 'Whatever'); value="Submit" name="Whatever"
 * array('label' => 'save', 'name' => 'Whatever', 'div' => 'good') <div class="good"> value="save" name="Whatever"
 * array('label' => 'save', 'name' => 'Whatever', 'div' => array('class' => 'good')); <div class="good"> value="save" name="Whatever"
 * }}}
 *
 * @param mixed $options as a string will use $options as the value of button,
 * @return string a closing FORM tag optional submit button.
 * @access public
 * @link http://book.cakephp.org/view/1389/Closing-the-Form
 */
	public function end($options = null) {
		$this->hook('form_end_alter', $options);

		return parent::end($options);
	}

/**
 * Generates a hidden field with a security hash based on the fields used in the form.
 *
 * @param array $fields The list of fields to use when generating the hash
 * @return string A hidden input field with a security hash
 */
	public function secure($fields = array()) {
		$this->hook('form_secure_alter', $fields);

		return parent::secure($fields);
	}

/**
 * Add to or get the list of fields that are currently unlocked.
 * Unlocked fields are not included in the field hash used by SecurityComponent
 * unlocking a field once its been added to the list of secured fields will remove
 * it from the list of fields.
 *
 * @param string $name The dot separated name for the field.
 * @return mixed Either null, or the list of fields.
 */
	public function unlockField($name = null) {
		$this->hook('form_unlock_field_alter', $name);

		return parent::unlockField($name);
	}

/**
 * Returns true if there is an error for the given field, otherwise false
 *
 * @param string $field This should be "Modelname.fieldname"
 * @return boolean If there are errors this method returns true, else false.
 * @access public
 * @link http://book.cakephp.org/view/1426/isFieldError
 */
	public function isFieldError($field) {
		$this->hook('form_is_field_error_alter', $field);

		return parent::isFieldError($field);
	}

/**
 * Returns a formatted error message for given FORM field, NULL if no errors.
 *
 * ### Options:
 *
 * - `escape`  bool  Whether or not to html escape the contents of the error.
 * - `wrap`  mixed  Whether or not the error message should be wrapped in a div. If a
 *   string, will be used as the HTML tag to use.
 * - `class` string  The classname for the error message
 *
 * @param string $field A field name, like "Modelname.fieldname"
 * @param mixed $text Error message or array of $options. If array, `attributes` key
 * will get used as html attributes for error container
 * @param array $options Rendering options for <div /> wrapper tag
 * @return string If there are errors this method returns an error message, otherwise null.
 * @access public
 * @link http://book.cakephp.org/view/1423/error
 */
	public function error($field, $text = null, $options = array()) {
		$data = compact('field', 'text', 'options');

		$this->hook('form_error_alter', $data);
		extract($data);

		return parent::error($field, $text, $options);
	}

/**
 * Returns a formatted LABEL element for HTML FORMs. Will automatically generate
 * a for attribute if one is not provided.
 *
 * @param string $fieldName This should be "Modelname.fieldname"
 * @param string $text Text that will appear in the label field.
 * @param mixed $options An array of HTML attributes, or a string, to be used as a class name.
 * @return string The formatted LABEL element
 * @link http://book.cakephp.org/view/1427/label
 */
	public function label($fieldName = null, $text = null, $options = array()) {
		$data = compact('fieldName', 'text', 'options');

		$this->hook('form_label_alter', $data);
		extract($data);

		return parent::label($fieldName, $text, $options);
	}

/**
 * Generate a set of inputs for `$fields`.  If $fields is null the current model
 * will be used.
 *
 * In addition to controller fields output, `$fields` can be used to control legend
 * and fieldset rendering with the `fieldset` and `legend` keys.
 * `$form->inputs(array('legend' => 'My legend'));` Would generate an input set with
 * a custom legend.  You can customize individual inputs through `$fields` as well.
 *
 * {{{
 *	$form->inputs(array(
 *		'name' => array('label' => 'custom label')
 *	));
 * }}}
 *
 * In addition to fields control, inputs() allows you to use a few additional options.
 *
 * - `fieldset` Set to false to disable the fieldset. If a string is supplied it will be used as
 *	the classname for the fieldset element.
 * - `legend` Set to false to disable the legend for the generated input set. Or supply a string
 *	to customize the legend text.
 *
 * @param mixed $fields An array of fields to generate inputs for, or null.
 * @param array $blacklist a simple array of fields to not create inputs for.
 * @return string Completed form inputs.
 */
	public function inputs($fields = null, $blacklist = null) {
		$data = compact('fields', 'blacklist');

		$this->hook('form_inputs_alter', $data);
		extract($data);

		return parent::inputs($fields, $blacklist);
	}

/**
 * Generates a form input element complete with label and wrapper div
 *
 * ### Options
 *
 * See each field type method for more information. Any options that are part of
 * $attributes or $options for the different **type** methods can be included in `$options` for input().
 *
 * - `type` - Force the type of widget you want. e.g. `type => 'select'`
 * - `label` - Either a string label, or an array of options for the label. See FormHelper::label()
 * - `div` - Either `false` to disable the div, or an array of options for the div.
 *	See HtmlHelper::div() for more options.
 * - `options` - for widgets that take options e.g. radio, select
 * - `error` - control the error message that is produced
 * - `empty` - String or boolean to enable empty select box options.
 * - `before` - Content to place before the label + input.
 * - `after` - Content to place after the label + input.
 * - `between` - Content to place between the label + input.
 * - `format` - format template for element order. Any element that is not in the array, will not be in the output.
 *	- Default input format order: array('before', 'label', 'between', 'input', 'after', 'error')
 *	- Default checkbox format order: array('before', 'input', 'between', 'label', 'after', 'error')
 *	- Hidden input will not be formatted
 *	- Radio buttons cannot have the order of input and label elements controlled with these settings.
 *
 * @param string $fieldName This should be "Modelname.fieldname"
 * @param array $options Each type of input takes different options.
 * @return string Completed form widget.
 * @access public
 * @link http://book.cakephp.org/view/1390/Automagic-Form-Elements
 */
	public function input($fieldName, $options = array()) {
		$data = compact('fieldName', 'options');

		$this->hook('form_input_alter', $data);

		if (isset($options['type'])) {
			$this->hook("form_{$options['type']}_alter", $data);
		}

		extract($data);

		if (isset($options['helpBlock'])) {
			if (!empty($options['helpBlock'])) {
				$options['helpBlock'] = $this->helpBlock($options['helpBlock']);

				if (isset($options['after'])) {
					$options['after'] = $options['after'] . $options['helpBlock'];
				} else {
					$options['after'] = $options['helpBlock'];
				}
			}

			unset($options['helpBlock']);
		}

		return parent::input($fieldName, $options);
	}


/**
 * Creates a checkbox input widget.
 *
 * ### Options:
 *
 * - `value` - the value of the checkbox
 * - `checked` - boolean indicate that this checkbox is checked.
 * - `hiddenField` - boolean to indicate if you want the results of checkbox() to include
 *	a hidden input with a value of ''.
 * - `disabled` - create a disabled input.
 *
 * @param string $fieldName Name of a field, like this "Modelname.fieldname"
 * @param array $options Array of HTML attributes.
 * @return string An HTML text input element.
 * @access public
 * @link http://book.cakephp.org/view/1414/checkbox
 */
	public function checkbox($fieldName, $options = array()) {
		$data = compact('fieldName', 'options');

		$this->hook('form_checkbox_alter', $data);
		extract($data);

		return parent::checkbox($fieldName, $options);
	}

/**
 * Creates a set of radio widgets. Will create a legend and fieldset
 * by default.  Use $options to control this
 *
 * ### Attributes:
 *
 * - `separator` - define the string in between the radio buttons
 * - `between` - the string between legend and input set
 * - `legend` - control whether or not the widget set has a fieldset & legend
 * - `value` - indicate a value that is should be checked
 * - `label` - boolean to indicate whether or not labels for widgets show be displayed
 * - `hiddenField` - boolean to indicate if you want the results of radio() to include
 *    a hidden input with a value of ''. This is useful for creating radio sets that non-continuous
 * - `disabled` - Set to `true` or `disabled` to disable all the radio buttons.
 * - `empty` - Set to `true` to create a input with the value '' as the first option.  When `true`
 *   the radio label will be 'empty'.  Set this option to a string to control the label value.
 *
 * @param string $fieldName Name of a field, like this "Modelname.fieldname"
 * @param array $options Radio button options array.
 * @param array $attributes Array of HTML attributes, and special attributes above.
 * @return string Completed radio widget set.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function radio($fieldName, $options = array(), $attributes = array()) {
		$data = compact('fieldName', 'options', 'attributes');

		$this->hook('form_radio_alter', $data);
		extract($data);

		return parent::radio($fieldName, $options, $attributes);
	}

/**
 * Creates a textarea widget.
 *
 * ### Options:
 *
 * - `escape` - Whether or not the contents of the textarea should be escaped. Defaults to true.
 *
 * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
 * @param array $options Array of HTML attributes, and special options above.
 * @return string A generated HTML text input element
 * @access public
 * @link http://book.cakephp.org/view/1433/textarea
 */
	public function textarea($fieldName, $options = array()) {
		$data = compact('fieldName', 'options');

		$this->hook('form_textarea_alter', $data);
		extract($data);

		return parent::textarea($fieldName, $options);
	}

/**
 * Creates a hidden input field.
 *
 * @param string $fieldName Name of a field, in the form of "Modelname.fieldname"
 * @param array $options Array of HTML attributes.
 * @return string A generated hidden input
 * @access public
 * @link http://book.cakephp.org/view/1425/hidden
 */
	public function hidden($fieldName, $options = array()) {
		$data = compact('fieldName', 'options');

		$this->hook('form_hidden_alter', $data);
		extract($data);

		return parent::hidden($fieldName, $options);
	}

/**
 * Creates file input widget.
 *
 * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
 * @param array $options Array of HTML attributes.
 * @return string A generated file input.
 * @access public
 * @link http://book.cakephp.org/view/1424/file
 */
	public function file($fieldName, $options = array()) {
		$data = compact('fieldName', 'options');

		$this->hook('form_file_alter', $data);
		extract($data);

		return parent::file($fieldName, $options);
	}

/**
 * Creates a `<button>` tag.  The type attribute defaults to `type="submit"`
 * You can change it to a different value by using `$options['type']`.
 *
 * ### Options:
 *
 * - `escape` - HTML entity encode the $title of the button. Defaults to false.
 *
 * @param string $title The button's caption. Not automatically HTML encoded
 * @param array $options Array of options and HTML attributes.
 * @return string A HTML button tag.
 * @access public
 * @link http://book.cakephp.org/view/1415/button
 */
	public function button($title, $options = array()) {
		$data = compact('title', 'options');

		$this->hook('form_button_alter', $data);
		extract($data);

		return parent::file($title, $options);
	}

/**
 * Create a `<button>` tag with `<form>` using POST method.
 *
 * This method creates an element <form>. So do not use this method in some opened form.
 *
 * ### Options:
 *
 * - `data` - Array with key/value to pass in input hidden
 * - Other options is the same of button method.
 *
 * @param string $title The button's caption. Not automatically HTML encoded
 * @param mixed $url URL as string or array
 * @param array $options Array of options and HTML attributes.
 * @return string A HTML button tag.
 */
	public function postButton($title, $url, $options = array()) {
		$data = compact('title', 'url', 'options');

		$this->hook('form_post_button_alter', $data);
		extract($data);

		return parent::postButton($title, $url, $options);
	}

/**
 * Creates an HTML link, but access the url using method POST. Requires javascript enabled in browser.
 *
 * This method creates an element <form>. So do not use this method in some opened form.
 *
 * ### Options:
 *
 * - `data` - Array with key/value to pass in input hidden
 * - Other options is the same of HtmlHelper::link() method.
 * - The option `onclick` will be replaced.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param mixed $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of HTML attributes.
 * @param string $confirmMessage JavaScript confirmation message.
 * @return string An `<a />` element.
 */
	public function postLink($title, $url = null, $options = array(), $confirmMessage = false) {
		$data = compact('title', 'url', 'options', 'confirmMessage');

		$this->hook('form_post_link_alter', $data);
		extract($data);

		return parent::postLink($title, $url, $options, $confirmMessage);
	}

/**
 * Creates a submit button element.  This method will generate `<input />` elements that
 * can be used to submit, and reset forms by using $options.  image submits can be created by supplying an
 * image path for $caption.
 *
 * ### Options
 *
 * - `div` - Include a wrapping div?  Defaults to true.  Accepts sub options similar to
 *   FormHelper::input().
 * - `before` - Content to include before the input.
 * - `after` - Content to include after the input.
 * - `type` - Set to 'reset' for reset inputs.  Defaults to 'submit'
 * - Other attributes will be assigned to the input element.
 *
 * ### Options
 *
 * - `div` - Include a wrapping div?  Defaults to true.  Accepts sub options similar to
 *   FormHelper::input().
 * - Other attributes will be assigned to the input element.
 *
 * @param string $caption The label appearing on the button OR if string contains :// or the
 *  extension .jpg, .jpe, .jpeg, .gif, .png use an image if the extension
 *  exists, AND the first character is /, image is relative to webroot,
 *  OR if the first character is not /, image is relative to webroot/img.
 * @param array $options Array of options.  See above.
 * @return string A HTML submit button
 * @access public
 * @link http://book.cakephp.org/view/1431/submit
 */
	public function submit($caption = null, $options = array()) {
		$data = compact('caption', 'options');

		$this->hook('form_submit_alter', $data);
		extract($data);

		return parent::submit($caption, $options);
	}

/**
 * Returns a formatted SELECT element.
 *
 * ### Attributes:
 *
 * - `showParents` - If included in the array and set to true, an additional option element
 *   will be added for the parent of each option group. You can set an option with the same name
 *   and it's key will be used for the value of the option.
 * - `multiple` - show a multiple select box.  If set to 'checkbox' multiple checkboxes will be
 *   created instead.
 * - `empty` - If true, the empty select option is shown.  If a string,
 *   that string is displayed as the empty element.
 * - `escape` - If true contents of options will be HTML entity encoded. Defaults to true.
 * - `value` The selected value of the input.
 * - `class` - When using multiple = checkbox the classname to apply to the divs. Defaults to 'checkbox'.
 *
 * ### Using options
 *
 * A simple array will create normal options:
 *
 * {{{
 * $options = array(1 => 'one', 2 => 'two);
 * parent::select('Model.field', $options));
 * }}}
 *
 * While a nested options array will create optgroups with options inside them.
 * {{{
 * $options = array(
 *	1 => 'bill',
 *	'fred' => array(
 *		2 => 'fred',
 *		3 => 'fred jr.'
 *	 )
 * );
 * parent::select('Model.field', $options);
 * }}}
 *
 * In the above `2 => 'fred'` will not generate an option element.  You should enable the `showParents`
 * attribute to show the fred option.
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $options Array of the OPTION elements (as 'value'=>'Text' pairs) to be used in the
 *	SELECT element
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @access public
 * @link http://book.cakephp.org/view/1430/select
 */
	public function select($fieldName, $options = array(), $attributes = array()) {
		$data = compact('fieldName', 'options', 'attributes');

		$this->hook('form_select_alter', $data);
		extract($data);

		return parent::select($fieldName, $options, $attributes);
	}

/**
 * Returns a SELECT element for days.
 *
 * ### Attributes:
 *
 * - `empty` - If true, the empty select option is shown.  If a string,
 *   that string is displayed as the empty element.
 * - `value` The selected value of the input.
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $attributes HTML attributes for the select element
 * @return string A generated day select box.
 * @access public
 * @link http://book.cakephp.org/view/1419/day
 */
	public function day($fieldName = null, $attributes = array()) {
		$data = compact('fieldName', 'attributes');

		$this->hook('form_day_alter', $data);
		extract($data);

		return parent::day($fieldName, $attributes);
	}

/**
 * Returns a SELECT element for years
 *
 * ### Attributes:
 *
 * - `empty` - If true, the empty select option is shown.  If a string,
 *   that string is displayed as the empty element.
 * - `orderYear` - Ordering of year values in select options.
 *   Possible values 'asc', 'desc'. Default 'desc'
 * - `value` The selected value of the input.
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param integer $minYear First year in sequence
 * @param integer $maxYear Last year in sequence
 * @param array $attributes Attribute array for the select elements.
 * @return string Completed year select input
 * @access public
 * @link http://book.cakephp.org/view/1416/year
 */
	public function year($fieldName, $minYear = null, $maxYear = null, $attributes = array()) {
		$data = compact('fieldName', 'minYear', 'maxYear', 'attributes');

		$this->hook('form_year_alter', $data);
		extract($data);

		return parent::year($fieldName, $minYear, $maxYear, $attributes);
	}

/**
 * Returns a SELECT element for months.
 *
 * ### Attributes:
 *
 * - `monthNames` - If false, 2 digit numbers will be used instead of text.
 *   If a array, the given array will be used.
 * - `empty` - If true, the empty select option is shown.  If a string,
 *   that string is displayed as the empty element.
 * - `value` The selected value of the input.
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param array $attributes Attributes for the select element
 * @return string A generated month select dropdown.
 * @access public
 * @link http://book.cakephp.org/view/1417/month
 */
	public function month($fieldName, $attributes = array()) {
		$data = compact('fieldName', 'attributes');

		$this->hook('form_month_alter', $data);
		extract($data);

		return parent::month($fieldName, $attributes);
	}

/**
 * Returns a SELECT element for hours.
 *
 * ### Attributes:
 *
 * - `empty` - If true, the empty select option is shown.  If a string,
 *   that string is displayed as the empty element.
 * - `value` The selected value of the input.
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param boolean $format24Hours True for 24 hours format
 * @param array $attributes List of HTML attributes
 * @return string Completed hour select input
 * @access public
 * @link http://book.cakephp.org/view/1420/hour
 */
	public function hour($fieldName, $format24Hours = false, $attributes = array()) {
		$data = compact('fieldName', 'format24Hours', 'attributes');

		$this->hook('form_hour_alter', $data);
		extract($data);

		return parent::hour($fieldName, $format24Hours, $attributes);
	}

/**
 * Returns a SELECT element for minutes.
 *
 * ### Attributes:
 *
 * - `empty` - If true, the empty select option is shown.  If a string,
 *   that string is displayed as the empty element.
 * - `value` The selected value of the input.
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param string $attributes Array of Attributes
 * @return string Completed minute select input.
 * @access public
 * @link http://book.cakephp.org/view/1421/minute
 */
	public function minute($fieldName, $attributes = array()) {
		$data = compact('fieldName', 'attributes');

		$this->hook('form_minute_alter', $data);
		extract($data);

		return parent::minute($fieldName, $attributes);
	}

/**
 * Returns a SELECT element for AM or PM.
 *
 * ### Attributes:
 *
 * - `empty` - If true, the empty select option is shown.  If a string,
 *   that string is displayed as the empty element.
 * - `value` The selected value of the input.
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param string $attributes Array of Attributes
 * @param bool $showEmpty Show/Hide an empty option
 * @return string Completed meridian select input
 * @access public
 * @link http://book.cakephp.org/view/1422/meridian
 */
	public function meridian($fieldName, $attributes = array()) {
		$data = compact('fieldName', 'attributes');

		$this->hook('form_meridian_alter', $data);
		extract($data);

		return parent::meridian($fieldName, $attributes);
	}

/**
 * Returns a set of SELECT elements for a full datetime setup: day, month and year, and then time.
 *
 * ### Attributes:
 *
 * - `monthNames` If false, 2 digit numbers will be used instead of text.
 *   If a array, the given array will be used.
 * - `minYear` The lowest year to use in the year select
 * - `maxYear` The maximum year to use in the year select
 * - `interval` The interval for the minutes select. Defaults to 1
 * - `separator` The contents of the string between select elements. Defaults to '-'
 * - `empty` - If true, the empty select option is shown.  If a string,
 *   that string is displayed as the empty element.
 * - `value` | `default` The default value to be used by the input.  A value in `$this->data`
 *   matching the field name will override this value.  If no default is provided `time()` will be used.
 *
 * @param string $fieldName Prefix name for the SELECT element
 * @param string $dateFormat DMY, MDY, YMD.
 * @param string $timeFormat 12, 24.
 * @param string $attributes array of Attributes
 * @return string Generated set of select boxes for the date and time formats chosen.
 * @access public
 * @link http://book.cakephp.org/view/1418/dateTime
 */
	public function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $attributes = array()) {
		$data = compact('fieldName', 'dateFormat', 'timeFormat', 'attributes');

		$this->hook('form_date_time_alter', $data);
		extract($data);

		return parent::dateTime($fieldName, $dateFormat, $timeFormat, $attributes);
	}

/**
 * Add support for special HABTM syntax.
 *
 * Sets this helper's model and field properties to the dot-separated value-pair in $entity.
 *
 * @param mixed $entity A field name, like "ModelName.fieldName" or "ModelName.ID.fieldName"
 * @param boolean $setScope Sets the view scope to the model specified in $tagValue
 * @return void
 */
	public function setEntity($entity, $setScope = false) {
		$data = compact('entity', 'setScope');

		$this->hook('form_set_entity_alter', $data);
		extract($data);

		return parent::setEntity($entity, $setScope);
	}

/**
 * Missing method handler - implements various simple input types. Is used to create inputs
 * of various types.  e.g. `$this->Form->text();` will create `<input type="text" />` while
 * `$this->Form->range();` will create `<input type="range" />`
 *
 * ### Usage
 *
 * `$this->Form->search('User.query', array('value' => 'test'));`
 *
 * Will make an input like:
 *
 * `<input type="search" id="UserQuery" name="data[User][query]" value="test" />`
 *
 * The first argument to an input type should always be the fieldname, in `Model.field` format.
 * The second argument should always be an array of attributes for the input.
 *
 * @param string $method Method name / input type to make.
 * @param array $params Parameters for the method call
 * @return string Formatted input method.
 * @throws CakeException When there are no params for the method call.
 */
	public function __call($method, $params) {
		return parent::__call($method, $params);
	}

/**
 * Set/Get inputDefaults for form elements
 *
 * @param array $defaults New default values
 * @param boolean Merge with current defaults
 * @return array inputDefaults
 */
	public function inputDefaults($defaults = null, $merge = false) {
		$data = compact('defaults', 'merge');

		$this->hook('form_inpu_defaults_alter', $data);
		extract($data);

		return parent::inputDefaults($defaults, $merge);
	}
}