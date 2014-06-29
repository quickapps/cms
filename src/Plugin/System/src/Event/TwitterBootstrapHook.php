<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Hook;

use Cake\Event\Event;
use Cake\Event\EventListener;

/**
 * Applies some Twitter Bootstrap CSS styles to form/html elements.
 *
 * By default all CSS and HTML code generated automatically by QuickAppsCMS
 * follow Twitter Bootstrap's conventions.
 *
 * Anyway you are able to define your own "Stylizer" by creating a Hook Listener
 * with higher priority and stopping hook propagation. This listener has a priory of 10 by default.
 *
 * See [CakePHP's event system](http://book.cakephp.org/3.0/en/core-libraries/events.html)
 * for more information.
 */
class TwitterBootstrapHook implements EventListener {

/**
 * List of custom form templates.
 *
 * @var array
 */
	protected $_templates = [
		'groupContainer' => '<div class="input {{type}}{{required}} form-group">{{content}}</div>',
		'groupContainerError' => '<div class="input {{type}}{{required}} has-error">{{content}}{{error}}</div>',
		'radioContainer' => '<div class="radio">{{input}}{{label}}</div>',
		'error' => '<p class="text-danger">{{content}}</p>',
		'errorList' => '<p class="text-danger"><ul>{{content}}</ul></p>',
	];

/**
 * Implemented hook events list.
 *
 * @return array List of implemented hooks
 */
	public function implementedEvents() {
		return [
			'Alter.FormHelper.create' => 'alterFormCreate',
			'Alter.FormHelper.input' => 'alterFormInput',
			'Alter.FormHelper.textarea' => 'alterFormTextarea',
			'Alter.FormHelper.select' => 'alterFormSelect',
			'Alter.FormHelper.button' => 'alterFormButton',
			'Alter.FormHelper.submit' => 'alterFormSubmit',
		];
	}

/**
 * Adds custom templates on Form::create().
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param mixed $model
 * @param array $options
 * @return array
 */
	public function alterFormCreate(Event $event, &$model, &$options) {
		if (empty($options['templates'])) {
			$options['templates'] = $this->_templates;
		}
	}

/**
 * Appends some CSS classes to generic input (text, textarea, select) elements.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param string $fieldName
 * @param array $options
 * @return array
 */
	public function alterFormInput(Event $event, &$fieldName, &$options) {
		if (
			!empty($options['type']) &&
			!in_array($options['type'], ['text', 'textarea', 'select'])
		) {
			return;
		}

		$prefix = '';

		if (!empty($options['class'])) {
			$prefix = $options['class'] . ' ';
		}

		if (strpos($prefix, 'form-control') === false) {
			$options['class'] = $prefix . 'form-control';
		}
	}

/**
 * Appends some CSS classes to textarea elements.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param string $fieldName
 * @param array $options
 * @return array
 */
	public function alterFormTextarea(Event $event, &$fieldName, &$options) {
		$prefix = '';

		if (!empty($options['class'])) {
			$prefix = $options['class'] . ' ';
		}

		if (strpos($prefix, 'form-control') === false) {
			$options['class'] = $prefix . 'form-control';
		}
	}

/**
 * Appends some CSS classes to select elements.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param string $fieldName
 * @param array $options
 * @param array $attributes
 * @return array
 */
	public function alterFormSelect(Event $event, &$fieldName, &$options, &$attributes) {
		$prefix = '';

		if (!empty($attributes['class'])) {
			$prefix = $attributes['class'] . ' ';
		}

		if (strpos($prefix, 'form-control') === false) {
			$attributes['class'] = $prefix . 'form-control';
		}
	}

/**
 * Appends some CSS classes to generic buttons.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param string $title
 * @param array $options
 * @return array
 */
	public function alterFormButton(Event $event, &$title, &$options) {
		$prefix = '';

		if (!empty($options['class'])) {
			$prefix = $options['class'] . ' ';
		}

		if (strpos($prefix, 'btn') === false) {
			$options['class'] = $prefix . 'btn btn-default';
		}
	}

/**
 * Appends some CSS classes to submit buttons.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param string $caption
 * @param array $options
 * @return array
 */
	public function alterFormSubmit(Event $event, &$caption, &$options) {
		$prefix = '';

		if (!empty($options['class'])) {
			$prefix = $options['class'] . ' ';
		}

		if (strpos($prefix, 'btn') === false) {
			$options['class'] = $prefix . 'btn btn-primary';
		}
	}

}
