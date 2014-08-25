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
namespace System\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use QuickApps\Core\HookTrait;

/**
 * Represents "plugins" database table.
 *
 * This table represents all plugins (including themes) installed in the site.
 * It also triggers the following events:
 *
 * - `Plugin.<PluginName>.beforeValidate`
 * - `Plugin.<PluginName>.afterValidate`
 * - `Plugin.<PluginName>.beforeSave`
 * - `Plugin.<PluginName>.afterSave`
 * - `Plugin.<PluginName>.beforeDelete`
 * - `Plugin.<PluginName>.afterDelete`
 *
 * The names of these events should be descriptive enough to let you know
 * what they do, or for what they are aimed to. You can find the details in
 * the DockBlocks of each method in this class.
 */
class PluginsTable extends Table {

	use HookTrait;

/**
 * Alter the schema used by this table.
 *
 * @param \Cake\Database\Schema\Table $table The table definition fetched from database
 * @return \Cake\Database\Schema\Table the altered schema
 */
	protected function _initializeSchema(Schema $table) {
		$table->columnType('settings', 'serialized');
		return $table;
	}

/**
 * Settings validation rules.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationSettings(Validator $validator) {
		return $validator;
	}

/**
 * Here we set default values for plugin's settings.
 *
 * Similar to Field Handlers, plugins may implement the
 * `Plugin.<PluginName>.settingsDefaults` event to provide default settings values.
 * 
 * @param \Cake\Event\Event $event
 * @param \Cake\ORM\Query $query
 * @param array $options
 * @param boolean $primary
 * @return void
 */
	public function beforeFind(Event $event, Query $query, array $options, $primary) {
		$query->formatResults(function ($results) {
			return $results->map(function($plugin) {
				if ($plugin->has('settings') && $plugin->has('name')) {
					$settingsDefaults = $this->hook("Plugin.{$plugin->name}.settingsDefaults")->result;
					if (!is_array($settingsDefaults)) {
						$settingsDefaults = [];
					}
					$settings = Hash::merge($settingsDefaults, $plugin->settings);
					$plugin->set('settings', $settings);
					return $plugin;
				}
			});
		});
	}

/**
 * Triggers the `Plugin.<PluginName>.beforeValidate` event. Or may trigger
 * `Plugin.<PluginName>.settingsValidate` as well.
 * 
 * @param \Cake\Event\Event $event
 * @param \Cake\ORM\Entity $entity The Plugin entity that is going to be validated
 * @param array $options
 * @param \Cake\Validation\Validator $validator
 * @return bool False if save operation should not continue, true otherwise
 */
	public function beforeValidate(Event $event, Entity $entity, $options, Validator $validator) {
		if (!empty($options['validate']) && $options['validate'] == 'settings') {
			$this->hook(['Plugin.' . $entity->get('_plugin_name') . '.settingsValidate', $event->subject], $entity, $validator);
		}
	}

/**
 * Triggers the `Plugin.<PluginName>.afterSave` event.
 * This method automatically regenerates system's snapshot.
 * 
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $plugin The Plugin entity that was saved
 * @param array $options The options passed to the save method
 * @return void
 */
	public function afterSave(Event $event, Entity $plugin, $options) {
		snapshot();
	}

/**
 * Triggers the "Plugin.<PluginName>.afterDelete" event.
 * This method automatically regenerates system's snapshot.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $plugin The Plugin entity that was deleted
 * @param array $options the options passed to the delete method
 * @return void
 */
	public function afterDelete(Event $event, Entity $plugin, $options = []) {
		snapshot();
	}

}
