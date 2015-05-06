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
namespace User\Test\TestCase\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use User\Model\Behavior\WhoDidItBehavior;

/**
 * WhoDidItBehaviorTest class.
 */
class WhoDidItBehaviorTest extends TestCase
{

    /**
     * Instance of behavior being tested.
     *
     * @var \User\Model\Behavior\WhoDidItBehavior
     */
    public $Behavior;

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'app.contents',
    ];

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        $table = TableRegistry::get('Contents');
        $this->Behavior = new WhoDidItBehavior($table, [
            'idCallable' => function () {
                return 1;
            }
        ]);
    }

    /**
     * test beforeSave() method when creating new entities.
     *
     * @return void
     */
    public function testBeforeSaveNewEntity()
    {
        $event = new Event('Model.beforeSave');
        $entity = new Entity(['id' => 100, 'title' => 'Random String Title', 'slug' => '']);
        $entity->isNew(true);

        $this->Behavior->beforeSave($event, $entity);
        $this->assertEquals(1, $entity->get('created_by'));
        $this->assertEquals(1, $entity->get('modified_by'));
    }

    /**
     * test beforeSave() method when updating an existing entity.
     *
     * @return void
     */
    public function testBeforeSaveEditEntity()
    {
        $event = new Event('Model.beforeSave');
        $entity = new Entity(['id' => 100, 'title' => 'Random String Title', 'slug' => '']);
        $entity->isNew(false);

        $this->Behavior->beforeSave($event, $entity);
        $this->assertTrue(!$entity->has('created_by'));
        $this->assertEquals(1, $entity->get('modified_by'));
    }
}
