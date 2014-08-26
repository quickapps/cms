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
namespace Comment\Controller;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Error\ForbiddenException;
use Cake\Event\Event;
use Cake\ORM\Error\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Comment\Controller\Component\CommentComponent;
use Field\Utility\TextToolbox;
use QuickApps\Core\Plugin;

/**
 * Comment UI Trait.
 *
 * Other plugins may `extends` Comment plugin by using this trait in their
 * controllers.
 *
 * With this trait, Comment plugin provides an user friendly UI for manage
 * entity's comments. It provides a comment-manager user interface (UI) by
 * attaching a series of actions over a `clean` controller.
 *
 * # Usage:
 *
 * Beside adding `use CommentUIControllerTrait;` to your controller you MUST
 * also indicate the name of the Table being managed. Example:
 *
 *     uses Comment\Controller\CommentUIControllerTrait;
 *
 *     class MyCleanController extends AppController {
 *         use CommentUIControllerTrait;
 *         // underscored table alias. e.g.: "user_photos"
 *         protected $_manageTable = 'nodes';
 *     }
 *
 * In order to avoid trait collision you should always `extend` Comment UI using
 * this trait over a `clean` controller. This is, an empty controller class with
 * no methods defined. For instance, create a new controller class
 * `MyPlugin\Controller\MyTableCommentManagerController` and use this trait to
 * handle comments for "MyTable" database table.
 *
 * ## _inResponseTo() method
 *
 * Also, your controller must implement the `_inResponseTo()` method. This method
 * must return a string value describing the entity that the given comment is
 * attached to. For example:
 *
 *     protected function _inResponseTo(\Comment\Model\Entity\Comment $comment) {
 *         $this->loadModel('MyPlugin.Persons');
 *         $person = $this->Persons->get($comment->entity_id);
 *         return "{$person->name}<br />{$person->email}";
 *     }
 *
 * # Requirements
 *
 * - This trait should only be used over a clean controller.
 * - You must define `$_manageTable` property in your controller.
 * - Your Controller must be a backend-controller (under `Controller\Admin` namespace).
 * - Your Controller must implement the `_inResponseTo()` method described above.
 *
 * @author Christopher Castro <chris@quickapps.es>
 */
trait CommentUIControllerTrait {

/**
 * Validation rules.
 *
 * @param \Cake\Event\Event $event The event instance.
 * @return void
 * @throws \Cake\Error\ForbiddenException When
 * - $_manageTable is not defined.
 * - trait is used in non-controller classes.
 * - the controller is not a backend controller.
 * - the "_inResponseTo()" is not implemented.
 */
	public function beforeFilter(Event $event) {
		$requestParams = $event->subject->request->params;

		if (!isset($this->_manageTable) || empty($this->_manageTable)) {
			throw new ForbiddenException(__d('field', 'CommentUIControllerTrait: The property $_manageTable was not found or is empty.'));
		} elseif (!($this instanceof \Cake\Controller\Controller)) {
			throw new ForbiddenException(__d('field', 'CommentUIControllerTrait: This trait must be used on instances of Cake\Controller\Controller.'));
		} elseif (!isset($requestParams['prefix']) || strtolower($requestParams['prefix']) !== 'admin') {
			throw new ForbiddenException(__d('field', 'CommentUIControllerTrait: This trait must be used on backend-controllers only.'));
		} elseif (!method_exists($this, '_inResponseTo')) {
			throw new ForbiddenException(__d('field', 'CommentUIControllerTrait: This trait needs you to implement the "_inResponseTo()" method.'));
		}

		$this->_manageTable = Inflector::underscore($this->_manageTable);
		$this->addComponent('Paginator');
		$this->addComponent('Comment.Comment');
		$this->helpers[] = 'Time';
		$this->helpers['Paginator'] = [
			'className' => 'QuickApps\View\Helper\PaginatorHelper',
			'templates' => 'System.paginator-templates.php',
		];
		$this->paginate['limit'] = 10;
	}

/**
 * Fallback for template location when extending Comment UI API.
 *
 * If controller tries to render an unexisting template under its Template
 * directory, then we try to find that view under `Comment/Template/CommentUI`
 * directory.
 *
 * ### Example:
 *
 * Suppose you are using this trait to manage comments attached to `Persons`
 * entities. You would probably have a `Person` plugin and a `clean` controller
 * as follow:
 *
 *     // http://example.com/admin/person/comments_manager
 *     Person\Controller\CommentsManagerController::index()
 *
 * The above controller action will try to render
 * `/plugins/Person/Template/CommentsManager/index.ctp`. But if does not exists
 * then `<QuickAppsCorePath>/plugins/Comment/Template/CommentUI/index.ctp` will
 * be used instead.
 *
 * Of course you may create your own template and skip this fallback functionality.
 *
 * @param \Cake\Event\Event $event the event instance.
 * @return void
 */
	public function beforeRender(Event $event) {
		$plugin = Inflector::camelize($event->subject->request->params['plugin']);
		$controller = Inflector::camelize($event->subject->request->params['controller']);
		$action = $event->subject->request->params['action'];
		$prefix = '';

		if (!empty($event->subject->request->params['prefix'])) {
			$prefix = Inflector::camelize($event->subject->request->params['prefix']) . '/';
		}

		$templatePath = Plugin::classPath($plugin) . "Template/{$prefix}{$controller}/{$action}.ctp";
		if (!file_exists($templatePath)) {
			$alternativeTemplatePath = Plugin::classPath('Comment') . 'Template/CommentUI';

			if (file_exists("{$alternativeTemplatePath}/{$action}.ctp")) {
				$this->view = "{$alternativeTemplatePath}/{$action}.ctp";
			}
		}

		parent::beforeRender($event);
	}

/**
 * Field UI main action.
 *
 * Shows all the fields attached to the Table being managed. Possibles values
 * for status are:
 *
 * - `all`: Comments marked as `pending` or `approved`. (by default)
 * - `pending`: Comments awaiting for moderation.
 * - `spam`: Comments marked as SPAM by Akismet.
 * - `trash`: Comments that were sent to trash bin.
 *
 * @param string Filter comments by `status`, see list above
 * @return void
 */
	public function index($status = 'all') {
		$this->loadModel('Comment.Comments');
		$this->_setCounters();
		$search = ''; // fills form's input
		$conditions = ['table_alias' => $this->_manageTable];

		if (in_array($status , ['pending', 'approved', 'spam', 'trash'])) {
			$conditions['Comments.status'] = $status;
		} else {
			$status = 'all';
			$conditions['Comments.status IN'] = ['pending', 'approved'];
		}

		if (!empty($this->request->query['search'])) {
			$search = $this->request->query['search'];
			$conditions['OR'] = [
				'Comments.subject LIKE' => "%{$this->request->query['search']}%",
				'Comments.body LIKE' => "%{$this->request->query['search']}%",
			];
		}

		$comments = $this->Comments
			->find()
			->contain(['Users'])
			->where($conditions)
			->order(['Comments.created' => 'DESC'])
			->formatResults(function ($results) {
				return $results->map(function($comment) {
					$comment->set('entity', $this->_inResponseTo($comment));
					$comment->set('body',
						TextToolbox::trimmer(
							TextToolbox::plainProcessor(
								TextToolbox::stripHtmlTags($comment->body)
							), 
							180
						)
					);
					return $comment;
				});
			});

		$this->set('search', $search);
		$this->set('filterBy', $status);
		$this->set('comments', $this->paginate($comments));
	}

/**
 * Edit form for given comment.
 * 
 * @param integer $id Comment id
 * @return void Redirects to previous page
 */
	public function edit($id) {
		$this->loadModel('Comment.Comments');
		$_settings = Hash::merge(CommentComponent::$defaultSettings, Plugin::info('Comment', true)['settings']);
		$comment = $this->Comments
			->find()
			->contain(['Users'])
			->where(['Comments.id' => $id, 'Comments.table_alias' => $this->_manageTable])
			->first();

		if (!$comment) {
			throw new RecordNotFoundException(__d('comment', 'Comment could not be found.'));
		}

		if (!empty($this->request->data)) {
			$comment->accessible('*', false);
			$comment->accessible(['subject', 'body', 'author_name', 'author_email', 'author_web', 'status'], true);
			$comment->set($this->request->data);

			if ($this->Comments->save($comment, ['validate' => 'update'])) {
				$this->Flash->success(__d('comment', 'Comment saved!.'));
				$this->redirect($this->referer());
			} else {
				$this->Flash->danger(__d('comment', 'Comment could not be saved, please check your information.'));
			}
		}

		$this->set('_settings', $_settings);
		$this->set('comment', $comment);
	}

/**
 * Changes the status of the given comment.
 * 
 * @param integer $id Comment id
 * @param string $status New status for the comment
 * @return void Redirects to previous page
 */
	public function status($id, $status) {
		if (in_array($status, ['pending', 'approved', 'spam', 'trash'])) {
			$this->loadModel('Comment.Comments');
			if ($comment = $this->Comments->get($id)) {
				$comment->set('status', $status);
				$this->Comments->save($comment, ['validate' => false]);
			}
		}

		$this->redirect($this->referer());
	}

/**
 * Permanently deletes the given comment.
 * 
 * @param integer $id Comment id
 * @return void Redirects to previous page
 */
	public function delete($id) {
		$this->loadModel('Comment.Comments');
		$comment = $this->Comments
			->find()
			->where(['Comments.id' => $id, 'Comments.table_alias' => $this->_manageTable])
			->first();

		if ($comment) {
			if ($this->Comments->delete($comment)) {
				$this->Flash->success(__d('comment', 'Comment was successfully deleted!'));
			} else {
				$this->Flash->danger(__d('comment', 'Comment could not be deleted, please try again.'));
			}
		} else {
			$this->Flash->danger(__d('comment', 'Invalid comment, comment was not found.'));
		}

		$this->redirect($this->referer());
	}

/**
 * Permanently deletes all comments marked as "trash".
 *
 * @return void Redirects to previous page
 */
	public function empty_trash() {
		$this->loadModel('Comment.Comments');
		$this->Comments->deleteAll(['Comments.status' => 'trash', 'Comments.table_alias' => $this->_manageTable]);
		$this->Flash->success(__d('comment', 'All comments in trash were successfully removed!'));
		$this->redirect($this->referer());
	}

/**
 * Sets a few view-variables holding counters for
 * each status ("pending", "approved", "spam" or "trash").
 *
 * @return void
 */
	protected function _setCounters() {
		$this->loadModel('Comment.Comments');
		$pending = $this->Comments->find()->where(['status' => 'pending'])->count();
		$approved = $this->Comments->find()->where(['status' => 'approved'])->count();
		$spam = $this->Comments->find()->where(['status' => 'spam'])->count();
		$trash = $this->Comments->find()->where(['status' => 'trash'])->count();
		$this->set('pendingCounter', $pending);
		$this->set('approvedCounter', $approved);
		$this->set('spamCounter', $spam);
		$this->set('trashCounter', $trash);
	}

}
