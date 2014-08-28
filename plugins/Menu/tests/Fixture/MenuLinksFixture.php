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
namespace Menu\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MenuLinksFixture class
 */
class MenuLinksFixture extends TestFixture {

	public $fields = [];
	public $records = [];

	public function init() {
		include_once QA_CORE .'/config/Schema/menu_links.php';
		$fixture = new \menu_links();
		$this->fields = $fixture->fields;
		$this->records = $fixture->records;
		parent::init();
	}

}
