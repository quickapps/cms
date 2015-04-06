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

use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use QuickApps\Core\Plugin;

/**
 * FieldableBehaviorTest class.
 */
class FieldableBehaviorTest extends TestCase
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
        'app.nodes',
        'app.plugins',
    ];

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        $this->table = TableRegistry::get('Node.Nodes');
        $this->table->addBehavior('Field.Fieldable');
    }

    /**
     * testFindUsingCustomFieldsInWhereClause.
     *
     * @return void
     */
    public function testFindUsingCustomFieldsInWhereClause()
    {
        $matching1 = $this->table
            ->find()
            ->where(['article-introduction LIKE' => '%Welcome to QuickAppsCMS%'])
            ->limit(1)
            ->first();

        $matching2 = $this->table
            ->find('all', ['bundle' => 'article'])
            ->where(['article-introduction LIKE' => '%Welcome to QuickAppsCMS%'])
            ->limit(1)
            ->first();

        $this->assertNotEmpty($matching1);
        $this->assertNotEmpty($matching2);
    }

    /**
     * testCustomFieldsAttach.
     *
     * @return void
     */
    public function testCustomFieldsAttach()
    {
        $entity = $this->table
            ->find()
            ->limit(1)
            ->first()
            ->toArray();
        $hasFields = isset($entity['_fields']);
        $this->assertTrue($hasFields);
    }
}
