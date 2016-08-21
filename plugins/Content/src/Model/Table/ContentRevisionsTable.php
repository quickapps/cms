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
namespace Content\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Content\Model\Entity\Content;
use \ArrayObject;

/**
 * Represents "content_revisions" database table.
 *
 */
class ContentRevisionsTable extends Table
{

    /**
     * Alter the schema used by this table.
     *
     * @param \Cake\Database\Schema\Table $table The table definition fetched from database
     * @return \Cake\Database\Schema\Table the altered schema
     */
    protected function _initializeSchema(Schema $table)
    {
        $table->columnType('data', 'serialized');

        return $table;
    }

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    /**
     * Attaches ContentType information to each content revision.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Query $query The query object
     * @param \ArrayObject $options Additional options given as an array
     * @param bool $primary Whether this find is a primary query or not
     * @return Query
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->formatResults(function ($results) {
            return $results->map(function ($revision) {
                try {
                    if (isset($revision->data->content_type_id)) {
                        $contentType = TableRegistry::get('Content.ContentTypes')
                            ->find()
                            ->where(['id' => $revision->data->content_type_id])
                            ->first();
                        $revision->data->set('content_type', $contentType);
                    }
                } catch (\Exception $e) {
                    $revision->data->set('content_type', false);
                }

                return $revision;
            });
        });

        return $query;
    }
}
