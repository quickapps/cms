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
namespace Eav\Test\TestCase\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Eav\Model\Behavior\EavBehavior;

/**
 * EavBehaviorTest class.
 */
class EavBehaviorTest extends TestCase
{

    /**
     * Instance of behavior being tested.
     *
     * @var \Eav\Model\Behavior\EavBehavior
     */
    public $behavior;

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'plugin.eav.dummy',
        'plugin.eav.eav_values',
        'plugin.eav.eav_attributes',
    ];

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        $table = TableRegistry::get('Dummy');
        $this->behavior = new EavBehavior($table);
    }

    /**
     * test that addColumn() actually creates new virtual columns.
     *
     * @return void
     */
    public function testAddColumn()
    {
        $success1 = $this->behavior->addColumn('user-age', ['type' => 'integer'], false);
        $success2 = $this->behavior->addColumn('user-birth-date', ['type' => 'date'], false);

        $this->assertTrue($success1);
        $this->assertTrue($success2);
    }

    /**
     * test that addColumn() method throws when adding a virtual column named same
     * as a physical column.
     *
     * @return void
     * @expectedException \Cake\Error\FatalErrorException
     */
    public function testAddColumnThrows()
    {
        $this->behavior->addColumn('name', ['type' => 'string']);
    }
}
