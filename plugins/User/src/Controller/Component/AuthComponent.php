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
namespace User\Controller\Component;

use Cake\Controller\Component\AuthComponent as CakeAuthComponent;
use QuickApps\Event\EventDispatcherTrait;

/**
 * Authentication control component class
 *
 * Binds access control with user authentication and session management.
 * This class acts as a wrapper for CakePHP's AuthComponent, provides
 * so specific functionalities used by QuickAppsCMS.
 */
class AuthComponent extends CakeAuthComponent
{

    use EventDispatcherTrait;
}
