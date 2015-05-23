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
namespace Field\Test\TestCase\Model\Behavior;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * EavBehaviorTest class.
 */
class EavBehaviorTest extends TestCase
{

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'app.eav_attributes',
        'app.eav_values',
        'app.field_instances',
        'app.contents',
        'app.plugins',
    ];

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        $this->table = TableRegistry::get('Content.Contents');
        $this->table->addBehavior('Eav.Eav');
    }

    /**
     * testAddColumnErrors.
     *
     * @return void
     */
    public function testAddColumnErrors()
    {
        $result = $this->table->addColumn('virtual-column', ['type' => 'string'], true);
        $this->assertEmpty($result);
    }

    /**
     * testAddColumnBoolean.
     *
     * @return void
     */
    public function testAddColumnBoolean()
    {
        $result = $this->table->addColumn('virtual-column', ['type' => 'string'], false);
        $this->assertTrue($result);
    }

    /**
     * testAddColumnBoolean.
     *
     * @return void
     */
    public function testDropColumn()
    {
        $this->table->addColumn('virtual-column', ['type' => 'string'], false);
        $result = $this->table->dropColumn('virtual-column');
        $this->assertTrue($result);
    }
}
