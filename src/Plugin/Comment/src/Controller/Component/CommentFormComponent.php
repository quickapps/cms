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
use Cake\Utility\Inflector;

/**
 * Manages entity's comment form.
 *
 * You must use this Component in combination with
 * `Commentable` behavior.
 */
class CommentFormComponent extends Component {

/**
 * The controller this component is attached to.
 *
 * @var \Cake\Controller\Controller
 */
	protected $_controller;

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
		$this->_controller->set('_newComment', new \Comment\Model\Entity\Comment());
	}

/**
 * Adds a new comment to the given entity.
 *
 * On validation failure this method sets a `_newComment`
 * view-variable with the "invalidated entity". This entity is used to properly fills the
 * `post new comment` form and show all error messages.
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
 * @see \Cake\Controller\Controller::redirect()
 */
	public function post($entity, $options = []) {
		$options += [
			'redirectOnSuccess' => true,
			'successMessage' => __d('comment', 'Comment saved!'),
			'errorMessage' => __d('comment', 'Your comment could not be saved, please check your information.'),
			'data' => [],
			'validate' => 'default',
		];

		if (!empty($this->_controller->request->data['comment'])) {
			$data = $this->_controller->request->data['comment'];
			$data['entity_id'] = $entity->id;
			$data['table_alias'] = Inflector::underscore($entity->source());
			$data = array_merge($data, $options['data']);
			$comment = new \Comment\Model\Entity\Comment($data);
			$entitySource = TableRegistry::get($entity->source());

			if ($entity->has($entitySource->primaryKey())) {
				$Comments = TableRegistry::get('Comment.Comments');
				$scope = [
					'entity_id' => $data['entity_id'],
					'table_alias' => $data['table_alias'],
				];

				if ($Comments->validate($comment, ['validate' => $options['validate']])) {
					$Comments->addBehavior('Tree', ['scope' => $scope]);

					if ($Comments->save($comment)) {
						$successMessage = $options['successMessage'];
						if (is_callable($successMessage)) {
							$successMessage = $options['successMessage']($comment, $this->_controller);
						}

						$this->_controller->alert($successMessage, 'success');

						if ($options['redirectOnSuccess']) {
							$redirectTo = $options['redirectOnSuccess'] === true ? $this->_controller->referer() : $options['redirectOnSuccess'];
							$this->_controller->redirect($redirectTo);
						}

						return true;
					} else {
						$this->_controller->set('_newComment', $comment);
					}
				} else {
					$this->_controller->set('_newComment', $comment);
				}
			}

			$errorMessage = $options['errorMessage'];
			if (is_callable($errorMessage)) {
				$errorMessage = $options['errorMessage']($comment, $this->_controller);
			}

			$this->_controller->alert($errorMessage, 'danger');
			return false;
		}
	}

}
