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
use Cake\ORM\Table;

/**
 * Represents "options" database table.
 *
 */
class OptionsTable extends Table
{

    /**
     * Alter the schema used by this table.
     *
     * @param \Cake\Database\Schema\Table $table The table definition fetched from database
     * @return \Cake\Database\Schema\Table the altered schema
     */
    protected function _initializeSchema(Schema $table)
    {
        $table->columnType('value', 'serialized');
        return $table;
    }

    /**
     * Regenerates system's snapshot.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $option The option entity that was saved
     * @return void
     */
    public function afterSave(Event $event, Entity $option)
    {
        snapshot();
    }

    /**
     * Regenerates system's snapshot.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $option The option entity that was saved
     * @return void
     */
    public function afterDelete(Event $event, Entity $option)
    {
        snapshot();
    }

    /**
     * Updates the given option.
     *
     * @param string $name Option name
     * @param mixed $value Value to store for this option
     * @param bool|null $autoload Set to true to load this option on bootstrap,
     *  null indicates it should not be modified. Defaults to null (do not change)
     * @param bool $callbacks Whether to trigger callbacks (beforeSavem etc) or not.
     *  Defaults to true
     * @return null|\Cake\ORM\Entity The option as an entity on success, null otherwise
     */
    public function update($name, $value, $autoload = null, $callbacks = true)
    {
        $option = $this
            ->find()
            ->where(['name' => $name])
            ->first();

        if (!$option) {
            return null;
        }

        if ($callbacks) {
            $option->set('value', $value);

            if ($autoload !== null) {
                $option->set('autoload', $autoload);
            }

            return $this->save($option, ['callbacks' => false]);
        }

        return $this->updateAll(['value' => $value], ['name' => $name]);
    }
}
