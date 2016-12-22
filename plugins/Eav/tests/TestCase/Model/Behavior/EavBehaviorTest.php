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
     * The table to which `EavBehavior` is attached to
     *
     * @var \Cake\ORM\Table
     */
    public $table;

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
        $this->table = TableRegistry::get('Dummy');
        $this->table->addBehavior('Eav.Eav');
    }

    /**
     * test that addColumn() actually creates new virtual columns.
     *
     * @return void
     */
    public function testAddColumn()
    {
        $success1 = $this->table->addColumn('user-age', ['type' => 'integer'], false);
        $success2 = $this->table->addColumn('user-birth-date', ['type' => 'date'], false);

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
        $this->table->addColumn('name', ['type' => 'string']);
    }

    /**
     * test EAV interception when retrieving entities.
     *
     * @return void
     */
    public function testFind()
    {
        $entity = $this->table->get(1, ['fields' => ['virtual_text']]);
        $this->assertEquals('This content belongs to a virtual column of type `text`', $entity->get('virtual_text'));

        $entity = $this->table->get(1, ['fields' => ['virtual_integer']]);
        $this->assertEquals(27, $entity->get('virtual_integer'));

        $entity = $this->table->get(1, ['fields' => ['virtual_text', 'virtual_integer']]);
        $this->assertEquals('This content belongs to a virtual column of type `text`', $entity->get('virtual_text'));
        $this->assertEquals(27, $entity->get('virtual_integer'));

        $entityCount = $this->table
            ->find('all')
            ->where([
                'id' => 1,
                'virtual_text LIKE' => '%virtual%'
            ])
            ->count();
        $this->assertTrue($entityCount === 1);
    }

    /**
     * test WHERE conditions against unary expression.
     *
     * @return void
     */
    public function testUnaryExpression()
    {
        $this->table->addColumn('user-birth-date', ['type' => 'date'], false);

        $first = $this->table->get(1);
        $first->set('user-birth-date', time());
        $this->table->save($first);

        $second = $this->table
            ->find('all', ['eav' => true])
            ->where(['user-birth-date IS' => null])
            ->order(['id' => 'ASC'])
            ->first();

        $this->assertTrue(!empty($second) && $second->get('id') == 2);
    }
}
