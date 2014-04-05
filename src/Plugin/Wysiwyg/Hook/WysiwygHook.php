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
 * Indicates if CKEditor's js files were already included.
 *
 * @var boolean
 */
	protected static $jsLoaded = false;

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class is registered
 * in an event manager, each individual method will be associated with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'FormHelper.textarea' => 'alterTextarea',
		];
	}

/**
 * Converts the given text area into a wysiwyg editor.
 *
 * @param Event  $event
 * @param string $fieldName
 * @param array $options
 * @return void
 */
	public function alterTextarea(Event $event, $fieldName, &$options) {
		if (
			!empty($options['class']) &&
			strpos($options['class'], 'ckeditor') !== false &&
			!static::$jsLoaded
		) {
			$View = $event->subject->_View;
			$View->Html->script('Wysiwyg.ckeditor/ckeditor.js', ['block' => true]);
			$View->Html->script('Wysiwyg.ckeditor/adapters/jquery.js', ['block' => true]);
			$View->Html->scriptBlock('
			$(document).ready(function () {
				CKEDITOR.editorConfig = function(config) {
					config.filebrowserBrowseUrl = "' . $View->Html->url(['plugin' => 'wysiwyg', 'controller' => 'el_finder', 'prefix' => 'admin']) . '";
				};
			});
			', ['block' => true]);
			static::$jsLoaded = true;
		}
	}

}
