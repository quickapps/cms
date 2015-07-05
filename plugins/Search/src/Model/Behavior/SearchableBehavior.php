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
namespace Search\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Search\Engine\EngineInterface;
use Search\Error\EngineNotFoundException;
use \ArrayObject;

/**
 * This behavior allows entities to be searchable using interchangeable search
 * engines.
 *
 * By default `GenericEngine` will be used if not provided. New engines can be
 * created and attached, such as `elasticsearch`, etc.
 */
class SearchableBehavior extends Behavior
{

    /**
     * Instance of the engine being used.
     *
     * @var null|\Search\Engine\EngineInterface
     */
    protected $_engine = null;

    /**
     * Behavior configuration array.
     *
     * - indexOn: Indicates when to index entities. `update` when entity is being
     *   updated, `insert` when a new entity is persisted into DB. Or `both` (by
     *   default).
     *
     * @var array
     */
    protected $_defaultConfig = [
        'engine' => [
            'className' => 'Search\\Engine\\Generic\\GenericEngine',
            'config' => [
                'operators' => [],
                'strict' => [],
                'bannedWords' => [],
            ]
        ],
        'indexOn' => 'both',
    ];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to.
     * @param array $config The config for this behavior.
     * @throws \Search\Error\EngineNotFoundException When no engine was
     *  configured
     */
    public function __construct(Table $table, array $config = [])
    {
        parent::__construct($table, $config);
        $engineClass = $this->config('engine.className');
        $engineClass = empty($engineClass) ? 'Search\\Engine\\Generic\\GenericEngine' : $engineClass;
        if (!class_exists($engineClass)) {
            throw new EngineNotFoundException(__d('search', 'The search engine "{0}" was not found.', $engineClass));
        }

        $this->_engine = new $engineClass($table, (array)$this->config('engine.config'));
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        $events = parent::implementedEvents();
        $events['afterSave'] = [
            'callable' => 'afterSave',
            'priority' => 500,
            'passParams' => true
        ];
        return $events;
    }

    /**
     * Generates a list of words after each entity is saved.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param \ArrayObject $options Additional options
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $isNew = $entity->isNew();
        if (($this->config('on') === 'update' && $isNew) ||
            ($this->config('on') === 'insert' && !$isNew) ||
            (isset($options['index']) && $options['index'] === false)
        ) {
            return;
        }

        $this->engine()->index($entity);
    }

    /**
     * Prepares entity to delete its words-index.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was removed
     * @param \ArrayObject $options Additional options
     * @return bool
     */
    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        return $this->engine()->delete($entity);
    }

    /**
     * Gets entities matching the given search criteria.
     *
     * @param mixed $criteria A search-criteria compatible with the Search Engine
     *  being used
     * @param \Cake\ORM\Query|null $query The query to scope, or null to create one
     * @return \Cake\ORM\Query Scoped query
     * @throws Cake\Error\FatalErrorException When query gets corrupted while
     *  processing tokens
     */
    public function search($criteria, Query $query = null)
    {
        if ($query === null) {
            $query = $this->_table->find();
        }
        return $this->engine()->search($criteria, $query);
    }

    /**
     * Gets/sets search engine instance.
     *
     * @return \Search\Engine\EngineInterface
     */
    public function engine(EngineInterface $engine = null)
    {
        if ($engine !== null) {
            $this->_engine = $engine;
        }
        return $this->_engine;
    }
}
