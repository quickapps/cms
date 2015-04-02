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
 */
class PluginsTable extends Table
{

    use HookAwareTrait;

    /**
     * Get the Model callbacks this table is interested in.
     *
     * @return array
     */
    public function implementedEvents()
    {
        $events = parent::implementedEvents();
        $events['Plugins.settings.validate'] = 'settingsValidate';
        $events['Plugins.settings.defaultValues'] = 'settingsDefaultValues';
        return $events;
    }

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('System.Serializable', [
            'columns' => ['settings']
        ]);
    }

    /**
     * Validates plugin settings before persisted in DB.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param array $data Information to be validated
     * @param \ArrayObject $options Options given to pathEntity()
     * @return void
     */
    public function settingsValidate(Event $event, array $data, ArrayObject $options)
    {
        if (!empty($options['entity'])) {
            $validator = $this->validator('settings');
            $this->trigger("Plugin.{$options['entity']->name}.settingsValidate", $data, $validator);
            $errors = $validator->errors((array)$data);
            foreach ($errors as $k => $v) {
                $options['entity']->errors("settings:{$k}", $v);
            }
        }
    }

    /**
     * Here we set default values for plugin's settings.
     *
     * Triggers the `Plugin.<PluginName>.settingsDefaults` event, event listeners
     * should catch the event and return an array as `key` => `value` with default
     * values.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $entity The entity where to put those values
     * @return array
     */
    public function settingsDefaultValues(Event $event, Entity $entity)
    {
        if ($entity->has('name')) {
            return (array)$this->trigger("Plugin.{$entity->name}.settingsDefaults")->result;
        }

        return [];
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
