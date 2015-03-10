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
namespace User\Notification;

use Cake\Utility\Inflector;
use User\Error\InvalidUserMessageException;
use User\Model\Entity\User;
use User\Notification\Message\BaseMessage;

/**
 * A simple class for handling user notification emails.
 *
 * ### Usage:
 *
 * ```php
 * $user = $this->Users->get($id);
 * $optionsArray = ['updateToken' => false];
 * $result = NotificationManager::welcome($user, $optionsArray)->send();
 * ```
 *
 * This class comes with a few built-in messages:
 *
 * - welcome
 * - activated
 * - blocked
 * - cancelRequest
 * - canceled
 * - passwordRequest
 *
 * ### Registering new messages:
 *
 * More messages can be registered (or overwritten) to this class using the
 * `addMessage()` method as follows:
 *
 * ```php
 * NotificationManager::addMessage('bye', 'ClassName\Extending\BaseMessage');
 * ```
 *
 * After registered you can start sending messages of `bye` type as below:
 *
 * ```php
 * NotificationManager::bye($user, $optionsArray)->send();
 * ```
 *
 * @method \User\Notification\Message\WelcomeMessage welcome(\User\Model\Entity\User $user, array $config = [])
 * @method \User\Notification\Message\ActivatedMessage activated(\User\Model\Entity\User $user, array $config = [])
 * @method \User\Notification\Message\BlockedMessage blocked(\User\Model\Entity\User $user, array $config = [])
 * @method \User\Notification\Message\CancelRequestMessage cancelRequest(\User\Model\Entity\User $user, array $config = [])
 * @method \User\Notification\Message\CanceledMessage canceled(\User\Model\Entity\User $user, array $config = [])
 * @method \User\Notification\Message\PasswordRequestMessage passwordRequest(\User\Model\Entity\User $user, array $config = [])
 */
class NotificationManager
{

    protected static $_messages = [
        'welcome' => 'User\\Notification\\Message\\WelcomeMessage',
        'activated' => 'User\\Notification\\Message\\ActivatedMessage',
        'blocked' => 'User\\Notification\Message\BlockedMessage',
        'cancelRequest' => 'User\\Notification\\Message\\CancelRequestMessage',
        'canceled' => 'User\\NotificationMessage\\CanceledMessage',
        'passwordRequest' => 'User\\Notification\\Message\\PasswordRequestMessage',
    ];

    /**
     * Magic method for dispatching to message handlers.
     *
     * @param string $method Name of the method
     * @param array $arguments Arguments for the invoked method
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        if (!isset(static::$_messages[$method])) {
            throw new InvalidUserMessageException([$method, $method]);
        }

        if (empty($arguments[0]) ||
            !($arguments[0] instanceof User)
        ) {
            throw new \InvalidArgumentException(sprintf('The first argument for NotificationManager::%s() must be an User entity.', $name));
        }

        $handler = static::$_messages[$method];
        if (is_string($handler)) {
            if (!str_starts_with($handler, '\\')) {
                $handler = "\\{$handler}";
            }

            if (class_exists($handler)) {
                $config = !empty($arguments[1]) ? (array)$arguments[1] : [];
                $handler = new $handler($arguments[0], $config);
            }
        }

        if ($handler instanceof BaseMessage) {
            return $handler;
        }

        throw new InvalidUserMessageException([$method, $method]);
    }

    /**
     * Looks for variables tags in the given message and replaces with their
     * corresponding values. For example, "[site:name] will be replaced with user's
     * real name.
     *
     * @param string $name CamelizedName for when using this message
     * @return string|\User\Notification\Message\BaseMessage Message handler. It can
     *  be either a string for a class name to instance, or a constructed message
     *  handler object.
     */
    public static function addMessage($name, $handler)
    {
        static::$_messages[Inflector::camelize($name)] = $handler;
    }
}
