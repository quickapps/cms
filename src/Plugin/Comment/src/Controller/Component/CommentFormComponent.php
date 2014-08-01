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
use Comment\Model\Entity\Comment;

/**
 * Manages entity's comment form.
 *
 * You must use this Component in combination with `Commentable` behavior and `CommentFormHelper`.
 * CommentFormHelper is automatically attached to your controller.
 */
class CommentFormComponent extends Component {

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
 * on success. Set to false for no redirection, or set to an array|string compatible
 * with `Controller::redirect()` method.
 * - `successMessage`: Custom success alert-message. Or a callable method which must return a customized message.
 * - `errorMessage`: Custom error alert-message. Or a callable method which must return a customized message.
 * - `data`: Array of additional properties values to merge with those coming
 * from the form's submit. e.g.: `['status' => 0]` will set the new comment as `unapproved`.
 * - `validate`: Specify which validation-set to use when saving new comment.
 * - `arrayContext`: Information for the ArrayContext provider used by FormHelper when rendering comments form.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'redirectOnSuccess' => true,
		'successMessage' => 'Comment saved!',
		'errorMessage' => 'Your comment could not be saved, please check your information.',
		'data' => [],
		'validate' => 'default',
		'arrayContext' => [
			'schema' => [
				'_comment_parent_id' => ['type' => 'integer'],
				'_comment_user_id' => ['type' => 'integer'],
				'_comment_author_name' => ['type' => 'string'],
				'_comment_author_email' => ['type' => 'string'],
				'_comment_author_web' => ['type' => 'string'],
				'_comment_subject' => ['type' => 'string'],
				'_comment_body' => ['type' => 'string'],
			],
			'defaults' => [
				'_comment_parent_id' => null,
				'_comment_user_id' => null,
				'_comment_author_name' => null,
				'_comment_author_email' => null,
				'_comment_author_web' => null,
				'_comment_subject' => null,
				'_comment_body' => null,
			]
		]
	];

/**
 * Constructor.
 *
 * @param \Cake\Controller\ComponentRegistry $collection A ComponentRegistry for this component
 * @param array $config Array of confi
 * @return void
 */
	public function __construct(ComponentRegistry $collection, array $config = array()) {
		$this->_defaultConfig['successMessage'] = __d('comment', 'Comment saved!');
		$this->_defaultConfig['errorMessage'] = __d('comment', 'Your comment could not be saved, please check your information.');
		parent::__construct($collection, $config);
	}

/**
 * Called before the controller's beforeFilter method.
 *
 * Sets the `_newComment` view-variable as a mock comment, used to
 * properly fills the `post new comment` form.
 *
 * @param Event $event
 * @param \Cake\Controller\Controller $controller
 */
	public function initialize($event) {
		$this->_controller = $event->subject;
		$this->_controller->set('_newComment', new Comment());
		$this->_controller->set('_commentFormContext', $this->config('arrayContext'));
		$this->_controller->helpers[] = 'Comment.CommentForm';
	}

/**
 * Adds a new comment to the given entity.
 *
 * Available options are:
 *
 * - `redirectOnSuccess`: Set to true to redirect to `referer` page
 * on success. Set to false for no redirection, or set to an array|string compatible
 * with `Controller::redirect()` method.
 * - `successMessage`: Custom success alert-message. Or a callable method which must return a customized message.
 * - `errorMessage`: Custom error alert-message. Or a callable method which must return a customized message.
 * - `data`: Array of additional properties values to merge with those coming
 * from the form's submit. e.g.: `['status' => 0]` will set the new comment as `unapproved`.
 * - `validate`: Specify which validation-set to use when saving new comment.
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
 * @param \Cake\ORM\Entity $entity
 * @param array $options Options this this method as described above
 * @return boolean TRUE on success, FALSE otherwise
 */
	public function post($entity, $options = []) {
		if (!empty($options)) {
			$this->config($options);
		}

		if (!empty($this->_controller->request->data['_comment_body'])) {
			$this->_controller->loadModel('Comment.Comments');
			$Comments = $this->_controller->Comments;
			$data = $this->_getRequestData();
			$data['entity_id'] = $entity->id;
			$data['table_alias'] = Inflector::underscore($entity->source());
			$data = Hash::merge($data, $this->config('data'));
			$comment = $Comments->newEntity($data);
			$pk = TableRegistry::get($entity->source())->primaryKey();

			if ($entity->has($pk)) {
				if ($Comments->validate($comment, ['validate' => $this->config('validate')])) {
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
		return $data;
	}

}
