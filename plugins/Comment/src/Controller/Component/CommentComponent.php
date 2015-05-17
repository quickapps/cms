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
namespace Comment\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Validation\Validator;
use Captcha\CaptchaManager;
use CMS\Core\Plugin;
use Comment\Model\Entity\Comment;
use Field\Utility\TextToolbox;
use User\Model\Entity\User;

/**
 * Manages entity's comments.
 *
 * You must use this Component in combination with `Commentable` behavior and
 * `CommentHelper`. CommentHelper is automatically attached to your controller
 * when this component is attached.
 *
 * When this component is attached you can render entity's comments using the
 * CommentHelper:
 *
 * ```php
 * // in any view:
 * $this->Comment->config('visibility', 1);
 * $this->Comment->render($entity);
 *
 * // in any controller
 * $this->Comment->config('visibility', 1);
 * ```
 *
 * You can set `visibility` using this component at controller side, or using
 * CommentHelper as example above, accepted values are:
 *
 * - 0: Closed; can't post new comments nor read existing ones. (by default)
 * - 1: Read & Write; can post new comments and read existing ones.
 * - 2: Read Only; can't post new comments but can read existing ones.
 */
class CommentComponent extends Component
{

    /**
     * The controller this component is attached to.
     *
     * @var \Cake\Controller\Controller
     */
    protected $_controller;

    /**
     * Default configuration.
     *
     * - redirectOnSuccess: Set to true to redirect to `referer` page on success.
     *   Set to false for no redirection, or set to an array|string compatible with
     *   `Controller::redirect()` method.
     *
     * - successMessage: Custom success alert-message. Or a callable method which
     *   must return a customized message.
     *
     * - errorMessage: Custom error alert-message. Or a callable method which must
     *   return a customized message.
     *
     * - arrayContext: Information for the ArrayContext provider used by FormHelper
     *   when rendering comments form.
     *
     * - validator: A custom validator object, if not provided it automatically
     *   creates one for you using the information below:
     *
     * - settings: Array of additional settings parameters, will be merged with
     *   those coming from Comment Plugin's configuration panel (at backend).
     *
     * When defining `successMessage` or `errorMessage` as callable functions you
     * should expect two arguments. A comment entity as first argument and the
     * controller instance this component is attached to as second argument:
     *
     * ```php
     * $options['successMessage'] = function ($comment, $controller) {
     *     return 'My customized success message';
     * }
     *
     * $options['errorMessage'] = function ($comment, $controller) {
     *     return 'My customized error message';
     * }
     * ```
     *
     * @var array
     */
    protected $_defaultConfig = [
        'redirectOnSuccess' => true,
        'successMessage' => 'Comment saved!',
        'errorMessage' => 'Your comment could not be saved, please check your information.',
        'arrayContext' => [
            'schema' => [
                'comment' => [
                    'parent_id' => ['type' => 'integer'],
                    'author_name' => ['type' => 'string'],
                    'author_email' => ['type' => 'string'],
                    'author_web' => ['type' => 'string'],
                    'subject' => ['type' => 'string'],
                    'body' => ['type' => 'string'],
                ]
            ],
            'defaults' => [
                'comment' => [
                    'parent_id' => null,
                    'author_name' => null,
                    'author_email' => null,
                    'author_web' => null,
                    'subject' => null,
                    'body' => null,
                ]
            ],
            'errors' => [
                'comment' => []
            ]
        ],
        'validator' => false,
        'settings' => [], // auto-filled with Comment plugin's settings
    ];

    /**
     * Constructor.
     *
     * @param \Cake\Controller\ComponentRegistry $collection A ComponentRegistry
     *  for this component
     * @param array $config Array of configuration options to merge with defaults
     */
    public function __construct(ComponentRegistry $collection, array $config = [])
    {
        $this->_defaultConfig['settings'] = plugin('Comment')->settings();
        $this->_defaultConfig['settings']['visibility'] = 0;
        $this->_defaultConfig['errorMessage'] = __d('comment', 'Your comment could not be saved, please check your information.');
        $this->_defaultConfig['successMessage'] = function () {
            if ($this->config('settings.auto_approve') ||
                $this->_controller->request->is('userAdmin')
            ) {
                return __d('comment', 'Comment saved!');
            }
            return __d('comment', 'Your comment is awaiting moderation.');
        };
        parent::__construct($collection, $config);
        $this->_controller = $this->_registry->getController();
        $this->_loadSettings();
    }

    /**
     * Called before the controller's beforeFilter method.
     *
     * @param Event $event The event that was triggered
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->_controller->set('__commentComponentLoaded__', true);
        $this->_controller->set('_commentFormContext', $this->config('arrayContext'));
    }

    /**
     * Called after the controller executes the requested action.
     *
     * @param Event $event The event that was triggered
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $this->_controller->helpers['Comment.Comment'] = $this->config('settings');
    }

    /**
     * Reads/writes settings for this component or for CommentHelper class.
     *
     * @param string|array|null $key The key to get/set, or a complete array of configs.
     * @param mixed|null $value The value to set.
     * @param bool $merge Whether to merge or overwrite existing config, defaults to true.
     * @return mixed Config value being read, or the object itself on write operations.
     * @throws \Cake\Core\Exception\Exception When trying to set a key that is invalid.
     */
    public function config($key = null, $value = null, $merge = true)
    {
        if ($key !== null && in_array($key, array_keys($this->_defaultConfig['settings']))) {
            $key = "settings.{$key}";
        }

        if (!$this->_configInitialized) {
            $this->_config = $this->_defaultConfig;
            $this->_configInitialized = true;
        }

        if (is_array($key) || func_num_args() >= 2) {
            $this->_configWrite($key, $value, $merge);
            return $this;
        }

        return $this->_configRead($key);
    }

    /**
     * Adds a new comment for the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity where to attach new comment
     * @return bool True on success, false otherwise
     */
    public function post(EntityInterface $entity)
    {
        $pk = (string)TableRegistry::get($entity->source())->primaryKey();
        if (empty($this->_controller->request->data['comment']) ||
            $this->config('settings.visibility') !== 1 ||
            !$entity->has($pk)
        ) {
            return false;
        }

        $this->_controller->loadModel('Comment.Comments');
        $data = $this->_getRequestData($entity);
        $this->_controller->Comments->validator('commentValidation', $this->_createValidator());
        $comment = $this->_controller->Comments->newEntity($data, ['validate' => 'commentValidation']);
        $errors = $comment->errors();
        $errors = !empty($errors);

        if (!$errors) {
            $persist = true;
            $saved = true;
            $this->_controller->Comments->addBehavior('Tree', [
                'scope' => [
                    'entity_id' => $data['entity_id'],
                    'table_alias' => $data['table_alias'],
                ]
            ]);

            if ($this->config('settings.use_akismet')) {
                $newStatus = $this->_akismetStatus($data);
                $comment->set('status', $newStatus);

                if ($newStatus == 'spam' &&
                    $this->config('settings.akismet_action') != 'mark'
                ) {
                    $persist = false;
                }
            }

            if ($persist) {
                $saved = $this->_controller->Comments->save($comment);
            }

            if ($saved) {
                $this->_afterSave($comment);
                return true; // all OK
            } else {
                $errors = true;
            }
        }

        if ($errors) {
            $this->_setErrors($comment);
            $errorMessage = $this->config('errorMessage');
            if (is_callable($errorMessage)) {
                $errorMessage = $errorMessage($comment, $this->_controller);
            }
            $this->_controller->Flash->danger($errorMessage, ['key' => 'commentsForm']);
        }

        return false;
    }

    /**
     * Calculates comment's status using akismet.
     *
     * @param array $data Comment's data to be validated by Akismet
     * @return string Filtered comment's status
     */
    protected function _akismetStatus($data)
    {
        require_once Plugin::classPath('Comment') . 'Lib/Akismet.php';

        try {
            $akismet = new \Akismet(Router::url('/'), $this->config('settings.akismet_key'));

            if (!empty($data['author_name'])) {
                $akismet->setCommentAuthor($data['author_name']);
            }

            if (!empty($data['author_email'])) {
                $akismet->setCommentAuthorEmail($data['author_email']);
            }

            if (!empty($data['author_web'])) {
                $akismet->setCommentAuthorURL($data['author_web']);
            }

            if (!empty($data['body'])) {
                $akismet->setCommentContent($data['body']);
            }

            if ($akismet->isCommentSpam()) {
                return 'spam';
            }
        } catch (\Exception $ex) {
            return 'pending';
        }

        return $data['status'];
    }

    /**
     * Logic triggered after comment was successfully saved.
     *
     * @param \Cake\Datasource\EntityInterface $comment Comment that was just saved
     * @return void
     */
    protected function _afterSave(EntityInterface $comment)
    {
        $successMessage = $this->config('successMessage');
        if (is_callable($successMessage)) {
            $successMessage = $successMessage($comment, $this->_controller);
        }

        $this->_controller->Flash->success($successMessage, ['key' => 'commentsForm']);
        if ($this->config('redirectOnSuccess')) {
            $redirectTo = $this->config('redirectOnSuccess') === true ? $this->_controller->referer() : $this->config('redirectOnSuccess');
            $this->_controller->redirect($redirectTo);
        }
    }

    /**
     * Extract data from request and prepares for inserting a new comment for
     * the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity Entity used to guess table name
     * @return array
     */
    protected function _getRequestData(EntityInterface $entity)
    {
        $pk = (string)TableRegistry::get($entity->source())->primaryKey();
        $data = [];
        $return = [
            'parent_id' => null,
            'subject' => '',
            'body' => '',
            'status' => 'pending',
            'author_ip' => $this->_controller->request->clientIp(),
            'entity_id' => $entity->get($pk),
            'table_alias' => $this->_getTableAlias($entity),
        ];

        if (!empty($this->_controller->request->data['comment'])) {
            $data = $this->_controller->request->data['comment'];
        }

        if ($this->_controller->request->is('userLoggedIn')) {
            $return['user_id'] = user()->id;
            $return['author_name'] = null;
            $return['author_email'] = null;
            $return['author_web'] = null;
        }

        if (!empty($data['subject'])) {
            $return['subject'] = h($data['subject']);
        }

        if (!empty($data['parent_id'])) {
            // this is validated at Model side
            $return['parent_id'] = $data['parent_id'];
        }

        if (!empty($data['body'])) {
            $return['body'] = TextToolbox::process($data['body'], $this->config('settings.text_processing'));
        }

        if ($this->config('settings.auto_approve') ||
            $this->_controller->request->is('userAdmin')
        ) {
            $return['status'] = 'approved';
        }

        return $return;
    }

    /**
     * Get table alias for the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity
     * @return string Table alias
     */
    protected function _getTableAlias(EntityInterface $entity)
    {
        $alias = $entity->source();
        if (mb_strpos($alias, '.') !== false) {
            $parts = explode('.', $alias);
            $alias = array_pop($parts);
        }

        return strtolower($alias);
    }

    /**
     * Prepares error messages for FormHelper.
     *
     * @param \Comment\Model\Entity\Comment $comment The invalidated comment entity
     * to extract error messages
     * @return void
     */
    protected function _setErrors(Comment $comment)
    {
        $arrayContext = $this->config('arrayContext');
        foreach ((array)$comment->errors() as $field => $msg) {
            $arrayContext['errors']['comment'][$field] = $msg;
        }
        $this->config('arrayContext', $arrayContext);
        $this->_controller->set('_commentFormContext', $this->config('arrayContext'));
    }

    /**
     * Fetch settings from data base and merges
     * with this component's configuration.
     *
     * @return array
     */
    protected function _loadSettings()
    {
        $settings = plugin('Comment')->settings();
        foreach ($settings as $k => $v) {
            $this->config("settings.{$k}", $v);
        }
    }

    /**
     * Creates a validation object on the fly.
     *
     * @return \Cake\Validation\Validator
     */
    protected function _createValidator()
    {
        $config = $this->config();
        if ($config['validator'] instanceof Validator) {
            return $config['validator'];
        }

        $this->_controller->loadModel('Comment.Comments');
        if ($this->_controller->request->is('userLoggedIn')) {
            // logged user posting
            $validator = $this->_controller->Comments->validationDefault(new Validator());
            $validator
                ->requirePresence('user_id')
                ->notEmpty('user_id', __d('comment', 'Invalid user.'))
                ->add('user_id', 'checkUserId', [
                    'rule' => function ($value, $context) {
                        if (!empty($value)) {
                            $valid = TableRegistry::get('User.Users')->find()
                                ->where(['Users.id' => $value, 'Users.status' => 1])
                                ->count() === 1;

                            return $valid;
                        }

                        return false;
                    },
                    'message' => __d('comment', 'Invalid user, please try again.'),
                    'provider' => 'table',
                ]);
        } elseif ($this->config('settings.allow_anonymous')) {
            // anonymous user posting
            $validator = $this->_controller->Comments->validationUpdate(new Validator());
        } else {
            // other case
            $validator = new Validator();
        }

        if ($this->config('settings.use_captcha')) {
            $validator
                ->add('body', 'humanCheck', [
                    'rule' => function ($value, $context) {
                        return CaptchaManager::adapter()->validate($this->_controller->request);
                    },
                    'message' => __d('comment', 'We were not able to verify you as human. Please try again.'),
                    'provider' => 'table',
                ]);
        }

        return $validator;
    }
}
