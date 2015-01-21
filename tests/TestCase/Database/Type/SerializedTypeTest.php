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
namespace QuickApps\Test\TestCase\Database\Type;

use Cake\Database\Driver\Mysql;
use Cake\TestSuite\TestCase;
use QuickApps\Database\Type\SerializedType;

/**
 * SerializedTypeTest class.
 */
class SerializedTypeTest extends TestCase
{

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
	public function setUp()
	{
		parent::setUp();
		$this->_instance = new SerializedType();
	}

	/**
	 * test toPHP() method when working with serialized arrays.
	 *
	 * @return void
	 */
	public function testToPHPArray()
	{
		$driver = new Mysql();
		$array = [1, 'a' => 'B', 'c' => 'd'];

		$this->assertEquals($array, $this->_instance->toPHP(serialize($array), $driver));
	}

	/**
	 * test toPHP() method when working with serialized objects.
	 *
	 * @return void
	 */
	public function testToPHPObject()
	{
		$driver = new Mysql();
		$object = new \stdClass();
		$object->subject = 'I lost my unicorn';
		$object->body = "Don't remember where I parked him.";

		$this->assertEquals($object, $this->_instance->toPHP(serialize($object), $driver));
	}

	/**
	 * test that toPHP() returns the same input when it's not a serialized element.
	 *
	 * @return void
	 */
	public function testToPHPNonSerializable()
	{
		$driver = new Mysql();
		$notSerializedString = ' asd89 a9a 99a %%&';

		$this->assertEquals($notSerializedString, $this->_instance->toPHP($notSerializedString, $driver));
	}	

	/**
	 * test toDatabase() method when working with arrays.
	 *
	 * @return void
	 */
	public function testToDatabaseArray()
	{
		$driver = new Mysql();
		$array = [1, 'a' => 'B', 'c' => 'd'];

		$this->assertEquals(serialize($array), $this->_instance->toDatabase($array, $driver));
	}

	/**
	 * test toDatabase() method when working with objects.
	 *
	 * @return void
	 */
	public function testToDatabaseObject()
	{
		$driver = new Mysql();
		$object = new \stdClass();
		$object->subject = 'I think I found him';
		$object->body = 'he was under my bed';

		$this->assertEquals(serialize($object), $this->_instance->toDatabase($object, $driver));
	}

	/**
	 * test that toDatabase() returns the same input when it's not a serializable element.
	 *
	 * @return void
	 */
	public function testToDatabaseNonSerializable()
	{
		$driver = new Mysql();
		$notSerializedString = 'Gman: Bla bla bla mister freeman';

		$this->assertEquals($notSerializedString, $this->_instance->toDatabase($notSerializedString, $driver));
	}

}
