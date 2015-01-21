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
namespace QuickApps\Test\TestCase\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use QuickApps\Model\Behavior\SluggableBehavior;

/**
 * SluggableBehaviorTest class.
 */
class SluggableBehaviorTest extends TestCase {

    /**
     * Instance of behavior being tested.
     * 
     * @var \QuickApps\Model\Behavior\SluggableBehavior
     */
    public $Behavior;

    /**
     * Fixtures.
     * 
     * @var array
     */
    public $fixtures = [
        'app.nodes',
    ];

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        $table = TableRegistry::get('Nodes');
        $this->Behavior = new SluggableBehavior($table);
    }

    /**
     * test that beforeSave() method throws when an invalid entity is given.
     *
     * @return void
     * @expectedException \Cake\Error\FatalErrorException
     */
    public function testBeforeSaveThrow()
    {
        $event = new Event('Model.beforeSave');
        $entity = new Entity(['id' => 100, 'not_title_label' => 'Random String Title', 'slug' => '']);

        $this->Behavior->beforeSave($event, $entity);
    }

    /**
     * test beforeSave() method.
     *
     * @return void
     */
    public function testBeforeSave()
    {
        $event = new Event('Model.beforeSave');
        $entity = new Entity(['id' => 100, 'title' => 'Random String Title', 'slug' => '']);
        $entity->isNew(true);

        $this->Behavior->beforeSave($event, $entity);
        $this->assertEquals('random-string-title', $entity->get('slug'));
    }

    /**
     * test that beforeSave() generates a new slug only when a new entity is created.
     *
     * @return void
     */
    public function testBeforeSaveOnCreate()
    {
        $event = new Event('Model.beforeSave');
        $this->Behavior->config('on', 'create');

        $entity = new Entity(['id' => 100, 'title' => 'Lorem ipsum', 'slug' => '']);
        $entity->isNew(true);
        $this->Behavior->beforeSave($event, $entity);
        $this->assertEquals('lorem-ipsum', $entity->get('slug'));

        $entity = new Entity(['id' => 101, 'title' => 'Lorem ipsum', 'slug' => 'dont-touch']);
        $entity->isNew(false);
        $this->Behavior->beforeSave($event, $entity);
        $this->assertEquals('dont-touch', $entity->get('slug'));
    }

    /**
     * test that beforeSave() generates a new slug only when entity is updated.
     *
     * @return void
     */
    public function testBeforeSaveOnUpdate()
    {
        $event = new Event('Model.beforeSave');
        $this->Behavior->config('on', 'update');

        $entity = new Entity(['id' => 100, 'title' => 'Lorem ipsum', 'slug' => 'change-this']);
        $entity->isNew(false);
        $this->Behavior->beforeSave($event, $entity);
        $this->assertEquals('lorem-ipsum', $entity->get('slug'));

        $entity = new Entity(['id' => 101, 'title' => 'Lorem ipsum', 'slug' => 'dont-touch']);
        $entity->isNew(true);
        $this->Behavior->beforeSave($event, $entity);
        $this->assertEquals('dont-touch', $entity->get('slug'));
    }

}
