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
namespace Node\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Query;
use Node\Model\Entity\Node;

/**
 * Represents "node_revisions" database table.
 *
 */
class NodeRevisionsTable extends Table {

/**
 * Initialize a table instance. Called after the constructor.
 *
 * @param array $config Configuration options passed to the constructor
 * @return void
 */
	public function initialize(array $config) {
		$this->addBehavior('Timestamp');
	}

/**
 * Unserializes the stored node for each revision.
 *
 * Each revision's "data" property stores a serialized version of a Node
 * entity. If for some reason this information can not be unserialized "data"
 * property will be set to FALSE.
 * 
 * @param \Cake\Event\Event $event
 * @param \Cake\ORM\Query $query
 * @param array $options
 * @param boolean $primary
 * @return void
 */
	public function beforeFind(Event $event, Query $query, array $options, $primary) {
		return $query->formatResults(function($results) {
			return $results->map(function($row) {
				//@codingStandardsIgnoreStart
				$node = @unserialize($row->data);
				//@codingStandardsIgnoreEnd
				if ($node instanceof Node) {
					$row->set('data', $node);
				} else {
					$row->set('data', false);
				}
				return $row;
			});
		});
	}

}
