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
use QuickApps\Core\Package\Rule\Constraint;

/**
 * ConstraintTest class.
 */
class ConstraintTest extends TestCase
{

   /**
    * test match() method.
    *
    * @return void
    */
    public function testMatch()
    {
        $tests = [
            // Modifiers
            ['provided' => '3.0.0-RC2', 'constraints' => '3.0.x-dev', 'expected' => true],
            ['provided' => '2.0.1-dev', 'constraints' => '2.0.x-dev', 'expected' => true],
            ['provided' => '2.1-dev', 'constraints' => '2.0.x-dev', 'expected' => false],
            ['provided' => '2.6.8-dev', 'constraints' => '2.6.8', 'expected' => true],
            ['provided' => '2.6.8', 'constraints' => '2.6.8-dev', 'expected' => true],
            ['provided' => 'dev-master', 'constraints' => 'dev-master', 'expected' => true],
            ['provided' => 'dev-master', 'constraints' => '*', 'expected' => true],
            ['provided' => 'dev-master', 'constraints' => '2.6.8', 'expected' => false],
            ['provided' => 'dev-master', 'constraints' => '>=3.0', 'expected' => true],
            ['provided' => '5.6.16', 'constraints' => '5.6.x@dev#abc123', 'expected' => true],
            ['provided' => '5.6.16', 'constraints' => '5.6.x-dev#abc123', 'expected' => true],
            ['provided' => '1.0.1.6700', 'constraints' => '1.0.*@alpha', 'expected' => true],
            ['provided' => '1.0.1', 'constraints' => '1.0.*@beta', 'expected' => true],
            ['provided' => '1.0.1', 'constraints' => '@dev', 'expected' => true],
            // Basics
            ['provided' => '1.0', 'constraints' => '1.*', 'expected' => true],
            ['provided' => '1.2.2', 'constraints' => '*', 'expected' => true],
            ['provided' => '1.1.8', 'constraints' => '1.1.*', 'expected' => true],
            // AND ranges
            ['provided' => '8.5', 'constraints' => '>=7.0 <8.6.6', 'expected' => true],
            ['provided' => '7.6.66-alpha', 'constraints' => '>=7.0 <8.6.6', 'expected' => true],
            ['provided' => '5.0', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            ['provided' => '5', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            ['provided' => '9.0', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            ['provided' => '9', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            ['provided' => '9-alpha', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            // Hyphen range
            ['provided' => '1.6', 'constraints' => '1.0 - 2.0', 'expected' => true],
            ['provided' => '3.0', 'constraints' => '1.0 - 2.0', 'expected' => false],
            ['provided' => '1.6', 'constraints' => '>=1.0.0 <2.1', 'expected' => true],
            // OR
            ['provided' => '2.6.8', 'constraints' => '2.6.8 || 2.6.5', 'expected' => true],
            ['provided' => '2.6.5', 'constraints' => '2.6.8 || 2.6.5', 'expected' => true],
            ['provided' => '2.6.6', 'constraints' => '2.6.8 || 2.6.5', 'expected' => false],
            ['provided' => '2.6.7', 'constraints' => '2.6.8 || 2.6.5', 'expected' => false],
            // AND + OR
            ['provided' => '2.6.8', 'constraints' => '2.6.8 || >=7.0 <8.6.6', 'expected' => true],
            ['provided' => '8.5', 'constraints' => '2.6.8 || >=7.0 <8.6.6', 'expected' => true],
            // Tilde operator
            ['provided' => '3.0', 'constraints' => '~1.2', 'expected' => false],
            ['provided' => '1.5', 'constraints' => '>=1.2 <2.0', 'expected' => true],
            ['provided' => '2.0', 'constraints' => '>=1.0 <1.1', 'expected' => false],
            // Caret operator
            ['provided' => '1.9', 'constraints' => '^1.2.3', 'expected' => true],
            ['provided' => '1.0', 'constraints' => '>=1.2.3 <2.0', 'expected' => false],
            // Logical operators
            ['provided' => '1.1.8', 'constraints' => '>1.0', 'expected' => true],
            ['provided' => '2.1', 'constraints' => '<=2.0', 'expected' => false],
            ['provided' => '2.0', 'constraints' => '<>2.1.6', 'expected' => true],
            ['provided' => '2.0.1', 'constraints' => '!=1.0', 'expected' => true],

        ];

        foreach ($tests as $test) {
            $current = Constraint::match($test['provided'], $test['constraints']) ? 'true' : 'false';
            $expected = $test['expected'] ? 'true' : 'false';
            $this->assertEquals(
                "{$test['provided']} @ {$test['constraints']} -> {$expected}",
                "{$test['provided']} @ {$test['constraints']} -> {$current}"
            );
        }
    }
}
