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
namespace CMS\TestSuite;

use Cake\Event\EventManager;
use Cake\TestSuite\IntegrationTestCase as CakeIntegrationTestCase;

/**
 * {@inheritDoc}
 */
abstract class IntegrationTestCase extends CakeIntegrationTestCase
{

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $eventManagerInstance = EventManager::instance();
        parent::setUp();
        EventManager::instance($eventManagerInstance);
        $this->session(mockUserSession());
        include QUICKAPPS_CORE . '/config/routes_site.php';
    }
}
