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
namespace User\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Network\Session;

/**
 * WhoDidIt Behavior.
 *
 * Handles "created_by", "modified_by" fields for a given table. It's similar to
 * the "created", "modified" automagic, but it stores the logged User's ID.
 *
 * This is useful to track who created records, and the last user that has
 * changed them.
 */
class WhoDidItBehavior extends Behavior
{

    /**
     * Table which this behavior is attached to.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table;

    /**
     * Default configuration.
     *
     * - idCallable: It can be either a callable method that should return logged
     *   User's ID or a string representing a `session key` to read the ID. By
     *   Defaults it's set yo `Auth.id` for reading from Auth's session.
     *
     * - createdByField: The name of the "created_by" field in DB. Defaults to
     *   `created_by`.
     *
     * - modifiedByField: The name of the "modified_by" field in DB. Default to 
     *   `modified_by`.
     *
     * - userModel: The name of the Users class table, used to bind user's
     *   information to the table being managed by this behavior. Defaults to
     *   `User.Users`
     *
     * - autoBind: Automatically bind the table to the User table. (default true)
     *
     * @var array
     */
    protected $_defaultConfig = [
        'idCallable' => 'Auth.id',
        'createdByField' => 'created_by',
        'modifiedByField' => 'modified_by',
        'userModel' => 'User.Users',
        'autoBind' => true,
    ];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to
     * @param array $config Configuration array for this behavior
     */
    public function __construct(Table $table, array $config = [])
    {
        $this->_table = $table;
        parent::__construct($this->_table, $config);

        if ($this->config('auto_bind')) {
            if ($this->_table->hasField($this->config('createdByField'))) {
                $this->_table->belongsTo('CreatedBy', [
                    'className' => $this->config('userModel'),
                    'foreignKey' => $this->config('createdByField'),
                ]);
            }

            if ($this->_table->hasField($this->config('modifiedByField'))) {
                $this->_table->belongsTo('ModifiedBy', [
                    'className' => $this->config('userModel'),
                    'foreignKey' => $this->config('modifiedByField'),
                ]);
            }
        }
    }

    /**
     * Run before a model is saved.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $entity The entity being saved
     * @param array $options Array of options for the save operation
     * @return bool True if save should proceed, false otherwise
     */
    public function beforeSave(Event $event, $entity, $options = [])
    {
        if ($this->_table->hasField($this->config('createdByField')) ||
            $this->_table->hasField($this->config('modifiedByField'))
        ) {
            $userId = $this->_getUserId();
            if ($userId > 0) {
                $entity->set($this->config('modifiedByField'), $userId);
                if ($entity->isNew()) {
                    $entity->set($this->config('createdByField'), $userId);
                }
            }
        }

        return true;
    }

    /**
     * Gets current User's ID.
     *
     * @return int User ID, zero if not found
     */
    protected function _getUserId()
    {
        $callable = $this->config('idCallable');
        $id = 0;

        if (is_string($callable)) {
            $session = Session::create();
            $id = $session->read($callable);
        } elseif (is_callable($callable)) {
            $id = $callable();
        }

        return (int)$id;
    }
}
