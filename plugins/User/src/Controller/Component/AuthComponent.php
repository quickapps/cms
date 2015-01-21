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
use QuickApps\Event\HookAwareTrait;

/**
 * Authentication control component class
 *
 * Binds access control with user authentication and session management.
 * This class acts as a wrapper for CakePHP's AuthComponent, provides
 * so specific functionalities used by QuickAppsCMS.
 */
class AuthComponent extends CakeAuthComponent
{

    use HookAwareTrait;

    /**
     * {@inheritDoc}
     *
     * ## Events triggered:
     *
     * - `User.beforeIdentify`: Triggered before any Authenticate Adapter is executed,
     *    returning false or stopping this event will halt the "identify" operation.
     * - `User.afterIdentify`: After user's identification operation has been completed.
     *    This event is triggered even on identification failure, you must distinguish
     *    between success or failure using the given argument.
     *
     * @return array User record data, or false, if the user could not be identified.
     */
    public function identify()
    {
        $event = $this->trigger('User.beforeIdentify');
        if ($event->isStopped() || $event->result === false) {
            return false;
        }

        $result = parent::identify();
        $this->trigger('User.afterIdentify', $result);
        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * ## Events triggered:
     *
     * - `User.beforeLogout`: Triggered before any Authenticate Adapter is executed,
     *    returning false or stopping this event will halt the logout operation.
     * - `User.afterLogout`: After user's logout operation has been completed.
     *    Event listeners can return an alternative redirection URL, if not given
     *    default URL will be used.
     *
     * @return string Normalized config `logoutRedirect`
     * @link http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html#logging-users-out
     */
    public function logout()
    {
        $event = $this->trigger('User.beforeLogout');
        if ($event->isStopped() || $event->result === false) {
            return false;
        }

        $result = parent::logout();
        $eventResult = $this->trigger('User.afterLogout', $result)->result;

        if ($eventResult) {
            $result = $eventResult;
        }

        return $result;
    }
}
