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
 * Main Hook Listener for Wysiwyg plugin.
 *
 */
class WysiwygHook implements EventListener {

/**
 * Indicates if CKEditor's JS files were already included.
 *
 * @var boolean
 */
	protected static $_jsLoaded = false;

/**
 * Counts how many CK instances has been created.
 *
 * @var boolean
 */
	protected static $_counter = 0;

/**
 * Holds the original template used by FormHelper.
 * 
 * @var string
 */
	protected static $_textareaOriginalTemplate = null;

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class
 * is registered in an event manager, each individual method will be associated
 * with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'Alter.FormHelper.textarea' => 'alterTextarea',
		];
	}

/**
 * Converts the given text area into a wysiwyg editor.
 *
 * @param \Cake\Event\Event $event
 * @param string $fieldName
 * @param array $options
 * @return void
 */
	public function alterTextarea(Event $event, $fieldName, &$options) {
		if (!static::$_textareaOriginalTemplate) {
			static::$_textareaOriginalTemplate = $event->subject->templates('textarea');
		}

		if (
			!empty($options['class']) &&
			strpos($options['class'], 'ckeditor') !== false &&
			!static::$_jsLoaded
		) {
			static::$_counter++;
			static::$_jsLoaded = true;
			$editorId = 'ck-editor-' . static::$_counter;
			$options['class'] .= ' ' . $editorId;
			$extra = '';
			$filebrowserBrowseUrl = $event->subject->_View->Html->url(['plugin' => 'Wysiwyg', 'controller' => 'finder']);
			$event->subject->_View->Html->script('Wysiwyg.ckeditor/ckeditor.js', ['block' => true]);
			$event->subject->_View->Html->script('Wysiwyg.ckeditor/adapters/jquery.js', ['block' => true]);
			$event->subject->_View->Html->scriptBlock('$(document).ready(function () {
				CKEDITOR.editorConfig = function(config) {
					config.filebrowserBrowseUrl = "' . $filebrowserBrowseUrl . '";
				};
			});', ['block' => true]);
			$event->subject->templater()->add(['textarea' => static::$_textareaOriginalTemplate . $extra]);
		} else {
			$event->subject->templater()->add(['textarea' => static::$_textareaOriginalTemplate]);
		}
	}

}
