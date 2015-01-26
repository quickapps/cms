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
namespace User\Utility;

use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\ModelAwareTrait;
use Cake\Network\Email\Email;
use Cake\Routing\Router;
use QuickApps\Core\Plugin;
use User\Model\Entity\User;

/**
 * A simple class for handling user notification emails.
 *
 * ### Usage:
 *
 *     $user = $this->Users->get($id);
 *     $manager = new NotificationManager($user);
 *     $manager->welcome();
 *
 * The example above will send a "welcome" message to the given user.
 * Messages can be customized on User plugin's configuration page.
 *
 * By default this class automatically regenerates user's token before a
 * message is send, you can disable this behavior by passing `updateToken` option
 * set to false to the class constructor:
 *
 *     new NotificationManager($user, ['updateToken' => false]);
 */
class NotificationManager
{

    use InstanceConfigTrait;
    use ModelAwareTrait;

    /**
     * User entity being managed.
     *
     * @var \User\Model\Entity\User
     */
    protected $_user;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'updateToken' => true
    ];

    /**
     * Constructor.
     *
     * @param \User\Model\Entity\User $user The user being handled
     * @param array $config Overwrites User plugin's settings
     * @throws \Cake\ORM\Error\RecordNotFound When an invalid $user is given
     */
    public function __construct(User $user, array $config = [])
    {
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
        $this->loadModel('User.Users');
        $this->_user = $this->Users->get($user->id);
        $settings = Plugin::settings('User');

        $this->config($settings);
        if (!empty($config)) {
            $this->config($config);
        }
    }

    /**
     * Sends "welcome" message to user.
     *
     * @return bool True on success, false otherwise
     */
    public function welcome()
    {
        $subject = $this->config('message_welcome_subject');
        $body = $this->config('message_welcome_body');
        return $this->_send($subject, $body);
    }

    /**
     * Notifies user when account is activated.
     *
     * @return bool True on success, false otherwise
     */
    public function activated()
    {
        if ($this->config('message_activation')) {
            $subject = $this->config('message_activation_subject');
            $body = $this->config('message_activation_body');
            return $this->_send($subject, $body);
        }

        return true;
    }

    /**
     * Notifies user when account is blocked.
     *
     * @return bool True on success, false otherwise
     */
    public function blocked()
    {
        if ($this->config('message_blocked')) {
            $subject = $this->config('message_blocked_subject');
            $body = $this->config('message_blocked_body');
            return $this->_send($subject, $body);
        }

        return true;
    }

    /**
     * Notifies user when account is canceled.
     *
     * @return bool True on success, false otherwise
     */
    public function cancelRequest()
    {
        $subject = $this->config('message_cancel_request_subject');
        $body = $this->config('message_cancel_request_body');
        return $this->_send($subject, $body);
    }

    /**
     * Notifies user when account is canceled.
     *
     * @return bool True on success, false otherwise
     */
    public function canceled()
    {
        if ($this->config('message_canceled')) {
            $subject = $this->config('message_canceled_subject');
            $body = $this->config('message_canceled_body');
            return $this->_send($subject, $body);
        }

        return true;
    }

    /**
     * Sends instructions for the "password recovery" process.
     *
     * @return bool True on success, false otherwise
     */
    public function passwordRequest()
    {
        $subject = $this->config('message_password_recovery_subject');
        $body = $this->config('message_password_recovery_body');
        return $this->_send($subject, $body);
    }

    /**
     * Sends email message to user.
     *
     * @param string $subject Message's subject
     * @param string $body Message's body
     * @return bool True on success, false otherwise
     */
    protected function _send($subject, $body)
    {
        if ($this->config('updateToken') === true) {
            $this->_user = $this->Users->updateToken($this->_user);
        }

        $subject = $this->_parseVariables($subject);
        $body = $this->_parseVariables($body);

        if (empty($subject) || empty($body)) {
            return false;
        }

        $sender = new Email('default');
        $sent = false;
        try {
            $sent = $sender
                ->to($this->_user->email)
                ->subject($subject)
                ->send($body);
        } catch (\Exception $e) {
            return false;
        }

        return $sent;
    }

    /**
     * Looks for variables tags in the given message and replaces with their
     * corresponding values. For example, "[site:name] will be replaced with user's
     * real name.
     *
     * @param string $text Message where to look for tags.
     * @return string
     */
    protected function _parseVariables($text)
    {
        $user = $this->_user;
        return str_replace([
            '[user:name]',
            '[user:username]',
            '[user:email]',
            '[user:activation-url]',
            '[user:one-time-login-url]',
            '[user:cancel-url]',
            '[site:name]',
            '[site:url]',
            '[site:description]',
            '[site:slogan]',
            '[site:login-url]',
        ], [
            $user->name,
            $user->username,
            $user->email,
            Router::url(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'activate', 'prefix' => false, $user->token], true),
            Router::url(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'me', 'prefix' => false, 'token' => $user->token], true),
            Router::url(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'cancel', 'prefix' => false, $user->id, $user->cancel_code], true),
            option('site_title'),
            Router::url('/', true),
            option('site_description'),
            option('site_slogan'),
            Router::url(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'login', 'prefix' => false], true),
        ], $text);
    }
}
