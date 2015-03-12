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
namespace User\Notification\Message;

use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\ModelAwareTrait;
use Cake\Network\Email\Email;
use Cake\Routing\Router;
use User\Model\Entity\User;

/**
 * Base class for user messages, all message types should extend this class.
 *
 * @property \User\Model\Table\UsersTable $Users
 */
class BaseMessage
{

    use InstanceConfigTrait;
    use ModelAwareTrait;

    /**
     * Default configuration.
     *
     * ### Options:
     *
     * - updateToken: Whether to update user's token before any message is sent,
     *   defaults to true.
     *
     * - emailConfig: Name of the transport configuration to use when sending
     *   emails. This can be define in site's "settings.php" file. Defaults to
     *   `default`.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'updateToken' => true,
        'emailConfig' => 'default',
    ];

    /**
     * User for which this message will be sent.
     *
     * @var \User\Model\Entity\User
     */
    protected $_user = null;

    /**
     * Message's subject.
     *
     * @var string
     */
    protected $_subject = '';

    /**
     * Message's body.
     *
     * @var string
     */
    protected $_body = '';

    /**
     * Message constructor.
     *
     * @param \User\Model\Entity\User $user The user for which send this message
     * @param array $config Options for message sender
     */
    public function __construct(User $user, array $config = [])
    {
        $this->_user = $user;
        $this->config($config);
        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
    }

    /**
     * Gets or sets message's subject.
     *
     * @param string $subject Subject
     * @return $this|string When new value is set, $this is returned for allowing
     *  method chaining. When getting value a string will be returned
     */
    public function subject($subject = null)
    {
        if ($subject !== null) {
            $this->_subject = $subject;
            return $this;
        }
        return $this->_subject;
    }

    /**
     * Gets or sets message's body.
     *
     * @param string $body Body
     * @return $this|string When new value is set, $this is returned for allowing
     *  method chaining. When getting value a string will be returned
     */
    public function body($body = null)
    {
        if ($body !== null) {
            $this->_body = $body;
            return $this;
        }
        return $this->_body;
    }

    /**
     * Sends email message to user.
     *
     * @return bool True on success, false otherwise
     */
    public function send()
    {
        if ($this->config('updateToken') === true) {
            $this->loadModel('User.Users');
            $this->_user = $this->Users->updateToken($this->_user);
        }

        $subject = $this->_parseVariables((string)$this->subject());
        $body = $this->_parseVariables((string)$this->body());

        if (empty($subject) || empty($body)) {
            return false;
        }

        $sender = new Email($this->config('emailConfig'));
        $sent = false;
        try {
            $sent = $sender
                ->to($this->_user->get('email'))
                ->subject($subject)
                ->send($body);
        } catch (\Exception $e) {
            return false;
        }

        return $sent;
    }

    /**
     * Looks for variables tags in the given message and replaces with their
     * corresponding values. For example, "{{site:name}} will be replaced with
     * user's real name.
     *
     * Message classes can overwrite this method and add their own logic for parsing
     * variables.
     *
     * @param string $text Message where to look for tags.
     * @return string
     */
    protected function _parseVariables($text)
    {
        $user = $this->_user;
        return str_replace([
            '{{user:name}}',
            '{{user:username}}',
            '{{user:email}}',
            '{{user:activation-url}}',
            '{{user:one-time-login-url}}',
            '{{user:cancel-url}}',
            '{{site:name}}',
            '{{site:url}}',
            '{{site:description}}',
            '{{site:slogan}}',
            '{{site:login-url}}',
        ], [
            $user->get('name'),
            $user->get('username'),
            $user->get('email'),
            Router::url(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'activate', 'prefix' => false, $user->get('token')], true),
            Router::url(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'me', 'prefix' => false, 'token' => $user->get('token')], true),
            Router::url(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'cancel', 'prefix' => false, $user->id, $user->get('cancelCode')], true),
            option('site_title'),
            Router::url('/', true),
            option('site_description'),
            option('site_slogan'),
            Router::url(['plugin' => 'User', 'controller' => 'gateway', 'action' => 'login', 'prefix' => false], true),
        ], $text);
    }
}
