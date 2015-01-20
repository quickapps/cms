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
namespace Wysiwyg\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

/**
 * Main Hook Listener for Wysiwyg plugin.
 *
 */
class WysiwygHook implements EventListenerInterface {

/**
 * Indicates if CKEditor's JS files were already included.
 *
 * @var bool
 */
	protected static $_scriptsLoaded = false;

/**
 * Counts how many CK instances has been created.
 *
 * @var bool
 */
	protected static $_counter = 0;

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
 * Converts the given text area into a WYSIWYG editor.
 *
 * @param \Cake\Event\Event $event The event that was triggered
 * @param string $fieldName Field name
 * @param array &$options Array of options
 * @return void
 */
	public function alterTextarea(Event $event, $fieldName, &$options) {
		if (
			!empty($options['class']) &&
			strpos($options['class'], 'ckeditor') !== false
		) {
			static::$_counter++;
			$editorId = 'ck-editor-' . static::$_counter;
			$options['class'] .= ' ' . $editorId;
			$_View = $event->subject();

			if (!static::$_scriptsLoaded) {
				static::$_scriptsLoaded = true;
				$filebrowserBrowseUrl = $_View->Url->build(['plugin' => 'Wysiwyg', 'controller' => 'finder']);
				$_View->Html->script('Wysiwyg.ckeditor/ckeditor.js', ['block' => true]);
				$_View->Html->script('Wysiwyg.ckeditor/adapters/jquery.js', ['block' => true]);
				$_View->Html->scriptBlock('$(document).ready(function () {
					CKEDITOR.editorConfig = function(config) {
						config.filebrowserBrowseUrl = "' . $filebrowserBrowseUrl . '";
					};
				});', ['block' => true]);
				$this->_includeLinksToNodes($_View);
			}
		}
	}

/**
 * Alters CKEditor's link plugin.
 *
 * Allows to link to QuickAppsCMS's contents, adds to layout header some JS
 * code and files.
 * 
 * @param \Cake\View\View $View Instance of view class
 * @return void
 */
	protected function _includeLinksToNodes($View) {
		$items = [];
		$nodes = TableRegistry::get('Node.Nodes')
			->find('all', ['fieldable' => false])
			->contain(['NodeTypes'])
			->where(['status' => 1])
			->order(['sticky' => 'DESC', 'modified' => 'DESC']);

		foreach ($nodes as $node) {
			$items[] = ["{$node->type}: " . h($node->title), $View->Url->build($node->url, true)];
		}

		$View->Html->scriptBlock('var linksToNodesItems = ' . json_encode($items) . ';', ['block' => true]);
		$View->Html->scriptBlock('var linksToNodesLabel = "' . __d('wysiwyg', 'Link to content') . '";', ['block' => true]);
		$View->Html->script('Wysiwyg.links.js', ['block' => true]);
	}

}
