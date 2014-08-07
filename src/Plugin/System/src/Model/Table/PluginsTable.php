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
use Cake\ORM\Table;

/**
 * Represents "plugins" database table.
 *
 */
class PluginsTable extends Table {

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
 * Regenerate snapshot after table changes.
 *
 * @param \Cake\Event\Event $table The table definition fetched from database
 * @param \Cake\ORM\Entity $entity
 * @param array $options
 * @return void
 */
	protected function afterSave(Event $event, Entity $entity, $options) {
		snapshot();
	}

}
