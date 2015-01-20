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

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Field\Model\Behavior\FieldableBehavior;

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
        'app.nodes',
        'app.field_instances',
        'app.field_values',
    ];

/**
 * setUp().
 *
 * @return void
 */
    public function setUp()
    {
        $this->table = TableRegistry::get('Nodes');
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
            ->where([':article-introduction LIKE' => '%Lorem%'])
            ->limit(1)
            ->first();
        $matching2 = $this->table
            ->find('all', ['bundle' => 'art*'])
            ->where([':article-introduction LIKE' => '%Lorem%'])
            ->limit(1)
            ->first();
        $matching3 = $this->table
            ->find('all', ['bundle' => 'art?cle'])
            ->where([':article-introduction LIKE' => '%Lorem%'])
            ->limit(1)
            ->first();
        $matching4 = $this->table
            ->find('all', ['bundle' => ['article', 'dummy']])
            ->where([':article-introduction LIKE' => '%Lorem%'])
            ->limit(1)
            ->first();

        $this->assertNotEmpty($matching1);
        $this->assertNotEmpty($matching2);
        $this->assertNotEmpty($matching3);
        $this->assertNotEmpty($matching4);
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
            ->first();
        $this->assertNotEmpty($entity->get('_fields'));
    }
}
