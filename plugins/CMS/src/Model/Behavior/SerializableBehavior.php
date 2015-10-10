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
namespace CMS\Model\Behavior;

use Cake\Database\Type;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use \ArrayObject;

/**
 * Serializable Behavior.
 *
 * Allows store serializable information into table columns.
 */
class SerializableBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'columns' => [],
    ];

    /**
     * Constructor
     *
     * Merges config with the default and store in the config property
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to.
     * @param array $config The config for this behavior.
     */
    public function __construct(Table $table, array $config = [])
    {
        if (isset($config['columns']) && is_string($config['columns'])) {
            $config['columns'] = [$config['columns']];
        }

        parent::__construct($table, $config);
        if (!Type::map('serialized')) {
            Type::map('serialized', 'CMS\Database\Type\SerializedType');
        }

        foreach ($this->config('columns') as $column) {
            $this->_table->schema()->columnType($column, 'serialized');
        }
    }

    /**
     * Triggered before data is converted into entities.
     *
     * Moves multi-value POST data into its corresponding column, for instance given
     * the following POST array:
     *
     * ```php
     * [
     *     'settings:max_time' => 10,
     *     'settings:color' => '#005599',
     *     'my_column:other_option' => 'some value',
     * ]
     * ```
     *
     * It becomes:
     *
     * ```php
     * [
     *     'settings' => [
     *         'max_time' => 10,
     *         'color' => '#005599',
     *     ],
     *     'my_column' => [
     *         'other_option' => 'some value',
     *     ]
     * ]
     * ```
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \ArrayObject $data The POST data to be merged with entity
     * @param \ArrayObject $options The options passed to the marshaller
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $dataArray = Hash::expand((array)$data, ':');
        foreach ($this->config('columns') as $column) {
            if (isset($dataArray[$column])) {
                $data[$column] = $dataArray[$column];
                if ($options['validate']) {
                    $eventName = $this->_table->alias() . ".{$column}.validate";
                    $columnData = (array)$dataArray[$column];
                    $this->_table->dispatchEvent($eventName, compact('columnData', 'options'));
                }
            }
        }
    }

    /**
     * Here we set default values for serializable columns.
     *
     * This method triggers the `<TableAlias>.<columnName>.defaultValues` event, for
     * example "Plugins.settings.defaultValues" for the "settings" columns of the
     * "Plugins" table. Event listeners should catch this event and provides the
     * desired values.
     *
     * ### Options:
     *
     * - flatten: Flattens serialized information into plain entity properties, for
     *   example `settings:some_option` => `value`, where `settings` is the
     *   serialized column and `some_option` a key of the serialized array value.
     *   Valid only for column that stores array values. Example:
     *
     *   Consider the following entity:
     *
     *   ```php
     *   object(Cake\Datasource\EntityInterface) {
     *       'settings' => [
     *           'option_1' => 'Lorem ipsum',
     *           'option_2' => [1, 2, 3, 4],
     *           'option_3' => object,
     *       ],
     *   }
     *   ```
     *
     *   Once `settings` column is flattened the entity will look as follow:
     *
     *   ```php
     *   object(Cake\Datasource\EntityInterface) {
     *       'settings' => [
     *           'option_1' => 'Lorem ipsum',
     *           'option_2' => [1, 2, 3, 4],
     *           'option_3' => object,
     *       ],
     *       'settings:option_1' => 'Lorem ipsum',
     *       'settings:option_2' => [1, 2, 3, 4],
     *       'settings:option_3' => object,
     *   }
     *   ```
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Query $query Query object
     * @param \ArrayObject $options Additional options as an array
     * @param bool $primary Whether is find is a primary query or not
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->formatResults(function ($results) use ($options) {
            return $results->map(function ($entity) use ($options) {
                if (!($entity instanceof EntityInterface)) {
                    return $entity;
                }
                foreach ($this->config('columns') as $column) {
                    if ($entity->has($column)) {
                        $eventName = $this->_table->alias() . ".{$column}.defaultValues";
                        $defaultValue = $this->_table->dispatchEvent($eventName, compact('entity'))->result;
                        $currentValue = $entity->get($column);
                        $newValue = $currentValue;

                        if (is_array($currentValue) && is_array($defaultValue)) {
                            $newValue = Hash::merge($defaultValue, $currentValue);
                        } elseif (is_string($currentValue) && $currentValue === '') {
                            $newValue = $defaultValue;
                        } elseif (empty($currentValue) && !empty($defaultValue)) {
                            $newValue = $defaultValue;
                        }

                        $entity->set($column, $newValue);
                        if (!empty($options['flatten']) && is_array($entity->get($column))) {
                            foreach ($entity->get($column) as $key => $value) {
                                $entity->set("{$column}:{$key}", $value);
                            }
                        }
                    }
                }
                return $entity;
            });
        });
    }
}
