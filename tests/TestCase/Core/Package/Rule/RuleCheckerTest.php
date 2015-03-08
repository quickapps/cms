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
namespace QuickApps\Test\TestCase\Core\Package\Rule;

use Cake\TestSuite\TestCase;
use QuickApps\Core\Package\Rule\RuleChecker;

/**
 * PluginTest class.
 */
class RuleCheckerTest extends TestCase
{

    /**
     * Set of rules that should pass checking process.
     *
     * @var array
     */
    public $passRules = [];

    /**
     * Set of rules that should NO pass checking process.
     *
     * @var array
     */
    public $failRules = [];

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->passRules = [
            'quickapps/cms' => '>1.0',
            'php' => '>4.0',
        ];

        $this->failRules = [
            'quickapps/cms' => '<1.0',
            'php' => '<4.0',
        ];
    }

    /**
     * test check() method.
     *
     * @return void
     */
    public function testCheck()
    {
        $checker1 = new RuleChecker($this->passRules);
        $checker2 = new RuleChecker($this->failRules);

        $this->assertTrue($checker1->check());
        $this->assertFalse($checker2->check());
    } 

    /**
     * test pass() method.
     *
     * @return void
     */
    public function testPass()
    {
        $checker1 = new RuleChecker($this->passRules);
        $checker2 = new RuleChecker($this->failRules);

        $checker1->check();
        $checker2->check();

        $this->assertNotEmpty($checker1->pass(true));
        $this->assertEmpty($checker2->pass(true));
    }

    /**
     * test fail() method.
     *
     * @return void
     */
    public function testFail()
    {
        $checker1 = new RuleChecker($this->passRules);
        $checker2 = new RuleChecker($this->failRules);

        $checker1->check();
        $checker2->check();

        $this->assertEmpty($checker1->fail(true));
        $this->assertNotEmpty($checker2->fail(true));
    }
}
