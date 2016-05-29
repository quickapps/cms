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
namespace Eav\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * Represents a dummy table which EAV can be attached to.
 */
class DummyFixture extends TestFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'dummy';

    /**
     * Table columns.
     *
     * @var array
     */
    public $fields = [
        '_constraints' => [
            'primary' => [
                'type' => 'primary',
                'columns' => [0 => 'id'],
                'length' => [],
            ],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci',
        ],
        'id' => [
            'type' => 'integer',
            'unsigned' => false,
            'null' => false,
            'default' => null,
            'comment' => '',
            'autoIncrement' => true,
            'precision' => null,
        ],
        'name' => [
            'type' => 'string',
            'length' => 200,
            'null' => true,
            'default' => null,
            'comment' => '',
            'precision' => null,
            'fixed' => null,
        ],
    ];

    /**
     * Table records.
     *
     * @var array
     */
    public $records = [
        ['id' => 1, 'name' => 'Lorem'],
        ['id' => 2, 'name' => 'Ipsum'],
        ['id' => 3, 'name' => 'dolor'],
    ];
}
