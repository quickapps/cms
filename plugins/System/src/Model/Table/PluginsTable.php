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
namespace System\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use QuickApps\Event\HookAwareTrait;
use \ArrayObject;

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
class PluginsTable extends Table
{

    use HookAwareTrait;

    /**
     * Alter the schema used by this table.
     *
     * @param \Cake\Database\Schema\Table $table The table definition fetched from database
     * @return \Cake\Database\Schema\Table the altered schema
     */
    protected function _initializeSchema(Schema $table)
    {
        $table->columnType('settings', 'serialized');
        return $table;
    }

    /**
     * Settings validation rules.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationSettings(Validator $validator)
    {
        return $validator;
    }

    /**
     * Here we set default values for plugin's settings.
     *
     * Similar to Field Handlers, plugins may implement the
     * `Plugin.<PluginName>.settingsDefaults` event to provide default settings values.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Query $query Query object
     * @param \ArrayObject $options Additional options as an array
     * @param bool $primary Whether is find is a primary query or not
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->formatResults(function ($results) {
            return $results->map(function ($plugin) {
                if ($plugin->has('settings') && $plugin->has('name')) {
                    $settingsDefaults = $this->trigger("Plugin.{$plugin->name}.settingsDefaults")->result;
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
     * Triggers `Plugin.<PluginName>.settingsValidate` event.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $entity The Plugin entity that is going to be validated
     * @param \ArrayObject $options Additional options as an array
     * @param \Cake\Validation\Validator $validator The validator object
     * @return bool|null False if save operation should not continue, true otherwise
     */
    public function beforeValidate(Event $event, Entity $entity, ArrayObject $options, Validator $validator)
    {
        if (!empty($options['validate']) && $options['validate'] == 'settings') {
            $this->trigger(['Plugin.' . $entity->get('_plugin_name') . '.settingsValidate', $event->subject()], $entity, $validator);
        }
    }

    /**
     * Set plugin's load ordering to LAST if it's a new plugin being installed.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $plugin The Plugin entity being saved
     * @param \ArrayObject $options The options passed to the save method
     * @return void
     */
    public function beforeSave(Event $event, Entity $plugin, ArrayObject $options = null)
    {
        if ($plugin->isNew()) {
            $max = $this->find()
                ->order(['ordering' => 'DESC'])
                ->limit(1)
                ->first();
            $plugin->set('ordering', $max->ordering + 1);
        }
    }

    /**
     * This method automatically regenerates system's snapshot.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $plugin The Plugin entity that was saved
     * @param \ArrayObject $options The options passed to the save method
     * @return void
     */
    public function afterSave(Event $event, Entity $plugin, ArrayObject $options = null)
    {
        snapshot();
    }

    /**
     * This method automatically regenerates system's snapshot.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $plugin The Plugin entity that was deleted
     * @param \ArrayObject $options the options passed to the delete method
     * @return void
     */
    public function afterDelete(Event $event, Entity $plugin, ArrayObject $options = null)
    {
        snapshot();
    }
}
