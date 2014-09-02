<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Test\TestCase\Database\Type;

use Cake\Database\Driver\Mysql;
use Cake\TestSuite\TestCase;
use QuickApps\Database\Type\SerializedType;

/**
 * SerializedTypeTest class.
 */
class SerializedTypeTest extends TestCase {

/**
 * Instance of the class being tested.
 * 
 * @var \QuickApps\Database\Type\SerializedType
 */
	protected $_instance = null;

/**
 * setUp().
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->_instance = new SerializedType();
	}

/**
 * test toPHP() method.
 *
 * @return void
 */
	public function testToPHP() {
		$array = [1, 'a' => 'B', 'c' => 'd'];
		$notSerializedString = ' asd89 a9a 99a %%&';
		$serialized = serialize($array);
		$driver = new Mysql();

		$this->assertEquals($array, $this->_instance->toPHP($serialized, $driver));
		$this->assertEquals($notSerializedString, $this->_instance->toPHP($notSerializedString, $driver));
	}

/**
 * test toDatabase() method.
 *
 * @return void
 */
	public function testToDatabase() {
		$array = [1, 'a' => 'B', 'c' => 'd'];
		$serialized = serialize($array);
		$driver = new Mysql();

		$this->assertEquals($serialized, $this->_instance->toDatabase($array, $driver));
		$this->assertEquals(serialize($driver), $this->_instance->toDatabase($driver, $driver));
	}

}
