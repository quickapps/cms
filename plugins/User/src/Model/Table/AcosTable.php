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
namespace User\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Table;
use User\Model\Entity\Aco;

/**
 * Represents "acos" database table.
 *
 */
class AcosTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Tree');
        $this->belongsToMany('Roles', [
            'className' => 'User.Roles',
            'joinTable' => 'permissions',
            'propertyName' => 'roles',
        ]);

        // removes all permissions when a node is removed from the tree
        $this->hasMany('Permissions', [
            'className' => 'User.Permissions',
            'propertyName' => 'permissions',
            'dependent' => true,
        ]);
    }

    /**
     * We create a hash of "alias" property so we can perform
     * case sensitive SQL comparisons.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \User\Model\Entity\Aco $aco ACO entity being saved
     * @return void
     */
    public function beforeSave(Event $event, Aco $aco)
    {
        if ($aco->isNew()) {
            $aco->set('alias_hash', md5($aco->alias));
        }
    }

    /**
     * Retrieves the ACO node for this model
     *
     * @param string $ref String value `Prefix/Controller/action`
     * @return array Node found in database
     */
    public function node($ref)
    {
        $type = $this->alias();
        $table = $this->table();

        if (is_string($ref)) {
            $path = explode('/', $ref);
        } elseif (is_array($ref)) {
            $path = implode('/', array_values(array_filter($ref)));
            $path = explode('/', $path);
        }

        if (!$path) {
            return false;
        }

        $result = null;
        $start = $path[0];
        unset($path[0]);

        $queryData = [
            'conditions' => [
                "{$type}.lft" . ' <= ' . "{$type}0.lft",
                "{$type}.rght" . ' >= ' . "{$type}0.rght",
            ],
            'fields' => ['id', 'parent_id', 'alias'],
            'join' => [[
                    'table' => $table,
                    'alias' => "{$type}0",
                    'type' => 'INNER',
                    'conditions' => [
                        "{$type}0.alias_hash" => md5($start),
                        "{$type}0.plugin = {$type}.plugin",
                    ]
            ]],
            'order' => "{$type}.lft" . ' DESC'
        ];

        foreach ($path as $i => $alias) {
            $j = $i - 1;

            $queryData['join'][] = [
                'table' => $table,
                'alias' => "{$type}{$i}",
                'type' => 'INNER',
                'conditions' => [
                    "{$type}{$i}.lft" . ' > ' . "{$type}{$j}.lft",
                    "{$type}{$i}.rght" . ' < ' . "{$type}{$j}.rght",
                    "{$type}{$i}.alias_hash" => md5($alias),
                    "{$type}{$i}.plugin = {$type}{$i}.plugin",
                    "{$type}{$j}.id" . ' = ' . "{$type}{$i}.parent_id"
                ]
            ];

            $queryData['conditions'] = [
                'OR' => [
                    "{$type}.lft" . ' <= ' . "{$type}0.lft" . ' AND ' . "{$type}.rght" . ' >= ' . "{$type}0.rght",
                    "{$type}.lft" . ' <= ' . "{$type}{$i}.lft" . ' AND ' . "{$type}.rght" . ' >= ' . "{$type}{$i}.rght"
                ]
            ];
        }
        $query = $this->find('all', $queryData);
        $result = $query->toArray();
        $path = array_values($path);
        if (!isset($result[0]) ||
            (!empty($path) && $result[0]->alias !== $path[count($path) - 1]) ||
            (empty($path) && $result[0]->alias !== $start)
        ) {
            return false;
        }

        return $query;
    }
}
