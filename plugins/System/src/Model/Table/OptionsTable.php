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
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Represents "options" database table.
 *
 */
class OptionsTable extends Table {

/**
 * Alter the schema used by this table.
 *
 * @param \Cake\Database\Schema\Table $table The table definition fetched from database
 * @return \Cake\Database\Schema\Table the altered schema
 */
	protected function _initializeSchema(Schema $table) {
		$table->columnType('value', 'serialized');
		return $table;
	}

/**
 * Regenerates system's snapshot.
 * 
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $plugin The Plugin entity that was saved
 * @param array $options The options passed to the save method
 * @return void
 */
	public function afterSave(Event $event, Entity $option, $options) {
		snapshot();
	}

/**
 * Regenerates system's snapshot.
 * 
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $plugin The Plugin entity that was saved
 * @param array $options The options passed to the save method
 * @return void
 */
	public function afterDelete(Event $event, Entity $option) {
		snapshot();
	}

/**
 * Updates the given option.
 * 
 * @param string $name Option name
 * @param mixed $value Value to store for this option
 * @param bool|null $autoload Set to true to load this option on bootstrap, null indicates
 * it should not be modified. Defaults to null (do not change)
 * @return null|\Cake\ORM\Entity The option as an entity on success, null otherwise
 */
	public function update($name, $value, $autoload = null) {
		$option = $this
			->find()
			->where(['name' => $name])
			->first();

		if (!$option) {
			return null;
		}

		$option->set('name', $name);
		$option->set('value', $value);

		if ($autoload !== null) {
			$option->set('autoload', $autoload);
		}

		return $this->save($option);
	}

}
