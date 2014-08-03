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
namespace Comment\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use Comment\Model\Entity\Comment;
use QuickApps\Utility\Plugin;
use QuickApps\Utility\DetectorTrait;

/**
 * Manages entity's comment form.
 *
 * You must use this Component in combination with `Commentable` behavior and `CommentHelper`.
 * CommentHelper is automatically attached to your controller when this component is attached.
 *
 * When this component is attached you can render entity's comments using the CommentHelper:
 *
 *     $this->Comment->config('visibility', 1);
 *     $this->Comment->render($entity);
 *
 * You can set `visibility` using this component at controller side, 
 * or using CommentHelper as example above, accepted values are:
 *
 * - 0: Closed; can't post new comments nor read existing ones.
 * - 1: Read & Write; can post new comments and read existing ones.
 * - 2: Read Only; can't post new comments but can read existing ones.
 */
class CommentComponent extends Component {

	use DetectorTrait;

/**
 * Default settings.
 *
 * @var array
 */
	protected $_defaultSettings = [
		'visibility' => 0,
		'auto_approve' => false,
		'allow_anonymous' => false,
		'anonymous_name' => false,
		'anonymous_name_required' => true,
		'anonymous_email' => false,
		'anonymous_email_required' => true,
		'anonymous_web' => false,
		'anonymous_web_required' => true,
		'use_ayah' => false,
		'ayah_publisher_key' => '',
		'ayah_scoring_key' => '',
		'use_akismet' => false,
		'akismet_key' => '',
		'akismet_action' => 'mark',
	];

/**
 * The controller this component is attached to.
 *
 * @var \Cake\Controller\Controller
 */
	protected $_controller;

/**
 * Default configuration.
 *
 * - `redirectOnSuccess`: Set to true to redirect to `referer` page
 *    on success. Set to false for no redirection, or set to an array|string compatible
 *    with `Controller::redirect()` method.
 * - `successMessage`: Custom success alert-message. Or a callable method which must return a customized message.
 * - `errorMessage`: Custom error alert-message. Or a callable method which must return a customized message.
 *    from the form's submit. e.g.: `['status' => 0]` will set the new comment as `unapproved`.
 * - `arrayContext`: Information for the ArrayContext provider used by FormHelper when rendering comments form.
 * - `autoapprove`: Auto approve comments, true will mark new comments as approved.
 * - `validator`: A custom validator object, if not provided it automatically creates one for you
 *    using the information below:
 * - `allow_anonymous`: Set to true to allow anonymous users to create new comments.
 * - `anonymous_name`: Set to true to create a form input where anonymous users must/may enter their name.
 * - `anonymous_name_required`: Set to true or false to make author's name input required or optional.
 * - `anonymous_email`: Set to true to create a form input where anonymous users must/may enter their email.
 * - `anonymous_email_required`: Set to true or false to make author's email input required or optional.
 * - `anonymous_web`: Set to true to create a form input where anonymous users must/may enter their website URL.
 * - `anonymous_web_required`: Set to true or false to make author's website input required or optional.
 * - `settings`: Array of additional settings parameters, will be merged with those coming from Comment Plugin's 
 *    configuration panel (at backend).
 *
 * When defining `successMessage` or `errorMessage` as callable functions you should expect two arguments.
 * A comment entity as first argument and the controller instance this component is attached to as second argument:
 *
 *     $options['successMessage'] = function ($comment, $controller) {
 *         return 'My customized success message';
 *     }
 *
 *     $options['errorMessage'] = function ($comment, $controller) {
 *         return 'My customized error message';
 *     } 
 *
 * @var array
 */
	protected $_defaultConfig = [
		'redirectOnSuccess' => true,
		'successMessage' => 'Comment saved!',
		'errorMessage' => 'Your comment could not be saved, please check your information.',
		'arrayContext' => [
			'schema' => [
				'_comment_parent_id' => ['type' => 'integer'],
				'_comment_user_id' => ['type' => 'integer'],
				'_comment_author_name' => ['type' => 'string'],
				'_comment_author_email' => ['type' => 'string'],
				'_comment_author_web' => ['type' => 'string'],
				'_comment_subject' => ['type' => 'string'],
				'_comment_body' => ['type' => 'string'],
				'_comment_captcha' => ['type' => 'string'],
			],
			'defaults' => [
				'_comment_parent_id' => null,
				'_comment_user_id' => null,
				'_comment_author_name' => null,
				'_comment_author_email' => null,
				'_comment_author_web' => null,
				'_comment_subject' => null,
				'_comment_body' => null,
				'_comment_captcha' => null,
			]
		],
		'validator' => false,
		'settings' => [],
	];

/**
 * Constructor.
 *
 * @param \Cake\Controller\ComponentRegistry $collection A ComponentRegistry for this component
 * @param array $config
 * @return void
 */
	public function __construct(ComponentRegistry $collection, array $config = array()) {
		$this->_defaultConfig['successMessage'] = __d('comment', 'Comment saved!');
		$this->_defaultConfig['errorMessage'] = __d('comment', 'Your comment could not be saved, please check your information.');
		parent::__construct($collection, $config);
		$this->_loadSettings();
	}

/**
 * Called before the controller's beforeFilter method.
 *
 * @param Event $event
 * @return void
 */
	public function initialize($event) {
		$this->_controller = $event->subject;
		$this->_controller->set('__commentComponentLoaded__', true);
		$this->_controller->set('_commentFormContext', $this->config('arrayContext'));

		if ($this->config('settings.use_ayah') &&
			$this->config('settings.ayah_publisher_key') &&
			$this->config('settings.ayah_scoring_key')
		) {
			define('AYAH_PUBLISHER_KEY', $this->config('settings.ayah_publisher_key'));
			define('AYAH_SCORING_KEY', $this->config('settings.ayah_scoring_key'));
			define('AYAH_WEB_SERVICE_HOST', 'ws.areyouahuman.com');
			define('AYAH_TIMEOUT', 0);
			define('AYAH_DEBUG_MODE', FALSE);
			define('AYAH_USE_CURL', TRUE);
		}
	}

/**
 * Called after the controller executes the requested action.
 *
 * @param Event $event
 * @return void
 */
	public function beforeRender($event) {
		$this->_controller->helpers['Comment.Comment'] = $this->config('settings');
	}

/**
 * Adds a new comment to the given entity.
 *
 * @param \Cake\ORM\Entity $entity
 * @return boolean TRUE on success, FALSE otherwise
 */
	public function post($entity) {
		if (!empty($this->_controller->request->data['_comment_body'])) {
			$pk = TableRegistry::get($entity->source())->primaryKey();

			if ($entity->has($pk)) {
				$this->_controller->loadModel('Comment.Comments');
				$Comments = $this->_controller->Comments;
				$data = $this->_getRequestData();
				$data['entity_id'] = $entity->id;
				$data['table_alias'] = Inflector::underscore($entity->source());
				$data['status'] = $this->config('settings.auto_approve') ? 1 : 0;
				$comment = $Comments->newEntity($data);
				$Comments->validator('commentValidation', $this->_createValidator());

				if ($Comments->validate($comment, ['validate' => 'commentValidation'])) {
					$Comments->addBehavior('Tree', [
						'scope' => [
							'entity_id' => $data['entity_id'],
							'table_alias' => $data['table_alias'],
						]
					]);

					if ($Comments->save($comment)) {
						$successMessage = $this->config('successMessage');
						if (is_callable($successMessage)) {
							$successMessage = $successMessage($comment, $this->_controller);
						}
						$this->_controller->alert($successMessage, 'success');
						if ($this->config('redirectOnSuccess')) {
							$redirectTo = $this->config('redirectOnSuccess') === true ? $this->_controller->referer() : $this->config('redirectOnSuccess');
							$this->_controller->redirect($redirectTo);
						}
						return true;
					} else {
						$this->_setErrors($comment);
					}
				} else {
					$this->_setErrors($comment);
				}
			}

			$errorMessage = $this->config('errorMessage');
			if (is_callable($errorMessage)) {
				$errorMessage = $errorMessage($comment, $this->_controller);
			}
			$this->_controller->alert($errorMessage, 'danger');
			return false;
		}
	}

/**
 * Prepares error messages for FormHelper.
 * 
 * @param \Comment\Model\Entity\Comment $comment The invalidated comment entity to extract error messages
 * @return void
 */
	protected function _setErrors(Comment $comment) {
		$arrayContext = $this->config('arrayContext');
		foreach ($comment->errors() as $field => $msg) {
			$arrayContext['errors']["_comment_{$field}"] = $msg;
		}
		$this->config('arrayContext', $arrayContext);
		$this->_controller->set('_commentFormContext', $this->config('arrayContext'));
	}

/**
 * Extract comment-form's data.
 *
 * @return array
 */
	protected function _getRequestData() {
		$data = [];
		if (!empty($this->_controller->request->data)) {
			foreach ($this->_controller->request->data as $field => $value) {
				if (str_starts_with($field, '_comment_')) {
					$data[str_replace_once('_comment_', '', $field)] = $value;
				}
			}
		}

		if (
			$this->config('settings.use_recaptcha') &&
			!empty($this->config('settings.recaptcha_private_key')) &&
			!empty($this->config('settings.recaptcha_public_key')) &&
			isset($this->_controller->request->data['recaptcha_challenge_field']) &&
			isset($this->_controller->request->data['recaptcha_response_field'])
		) {
			$data['recaptcha_challenge_field'] = $this->_controller->request->data['recaptcha_challenge_field'];
			$data['recaptcha_response_field'] = $this->_controller->request->data['recaptcha_response_field'];
		}

		return $data;
	}

/**
 * Fetch settings from data base and merges
 * with this component's configuration.
 * 
 * @return array
 */
	protected function _loadSettings() {
		$settings = Hash::merge($this->_defaultSettings, Plugin::info('Comment', true)['settings']);

		foreach ($settings as $k => $v) {
			$this->config("settings.{$k}", $v);
		}
	}

/**
 * Creates a validation object on the fly.
 *
 * @return Cake\Validation\Validator
 */
	protected function _createValidator() {
		$config = $this->config();
		if ($config['validator'] instanceof Validator) {
			return $config['validator'];
		}

		$this->_controller->loadModel('Comment.Comments');
		$Comments = $this->_controller->Comments;
		$validator = $Comments->validationDefault(new Validator());

		if ($this->is('user.logged')) {
			// logged user posting
			$validator
				->notEmpty('user_id', __d('comment', 'Invalid author.'))
				->add('user_id', 'checkUserId', [
					'rule' => function ($value, $context) {
						if (!empty($value)) {
							$valid = TableRegistry::get('User.Users')->find()
								->where(['id' => $value])
								->count() === 1;

							if ($valid) {
								$context['providers']['entity']->set('author_name', null);
								$context['providers']['entity']->set('author_email', null);
								$context['providers']['entity']->set('author_web', null);
							}

							return $valid;
						}

						return true;
					},
					'message' => __d('comment', 'Invalid author.'),
					'provider' => 'table',
				]);
		} elseif ($this->config('settings.allow_anonymous')) {
			// anonymous user posting
			if ($this->config('settings.anonymous_name')) {
				$validator
					->validatePresence('author_name')
					->add('author_name', [
						'rule' => ['minLength', 3],
						'message' => __d('comment', 'Your name need to be at least 3 characters long.'),
						'provider' => 'table',
					]);

				if ($this->config('settings.anonymous_name_required')) {
					$validator->notEmpty('author_name', __d('comment', 'You must provide your name.'));
				} else {
					$validator->allowEmpty('author_name');
				}
			}

			if ($this->config('settings.anonymous_email')) {
				$validator
					->validatePresence('author_email')
					->add('author_email', [
						'rule' => 'email',
						'message' => __d('comment', 'e-Mail must be valid.'),
						'provider' => 'table',
					]);

				if ($this->config('settings.anonymous_email_required')) {
					$validator->notEmpty('author_email', __d('comment', 'You must provide an email.'));
				} else {
					$validator->allowEmpty('anonymous_email');
				}
			}

			if ($this->config('settings.anonymous_web')) {
				$validator
					->validatePresence('author_web')
					->add('author_web', [
						'rule' => 'url',
						'message' => __d('comment', 'Website must be a valid URL.'),
						'provider' => 'table',
					]);

				if ($this->config('settings.anonymous_web_required')) {
					$validator->notEmpty('author_web', __d('comment', 'You must provide a website URL.'));
				} else {
					$validator->allowEmpty('author_web');
				}
			}
		}

		if ($this->config('settings.use_ayah') &&
			$this->config('settings.ayah_publisher_key') &&
			$this->config('settings.ayah_scoring_key')
		) {
			require_once Plugin::classPath('Comment') . 'Vendor/ayah.php';
			$ayah = new \AYAH();
			$validator
				->add('body', 'humanCheck', [
					'rule' => function ($value, $context) use ($ayah) {
						return $ayah->scoreResult();
					},
					'message' => __d('comment', 'We were not able to verify you as human. Please try again.'),
					'provider' => 'table',
				]);
		}

		return $validator;
	}

}
