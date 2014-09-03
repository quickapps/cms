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
namespace Field\Controller;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\ForbiddenException;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\ORM\Exception\RecordNotFoundException;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Field\Model\Entity\FieldViewMode;
use QuickApps\View\ViewModeTrait;

/**
 * Field UI Trait.
 *
 * Other plugins may `extends` Field plugin by using this trait in their controllers.
 *
 * With this trait, Field plugin provides an user friendly UI for manage entity's
 * custom fields. It provides a field-manager user interface (UI) by attaching a
 * series of actions over a `clean` controller.
 *
 * # Usage:
 *
 * Beside adding `use FieldUIControllerTrait;` to your controller you MUST also
 * indicate the name of the Table being managed. Example:
 *
 *     uses Field\Controller\FieldUIControllerTrait;
 *
 *     class MyCleanController extends <Plugin>AppController {
 *         use FieldUIControllerTrait;
 *         // underscored table alias. e.g.: "user_photos"
 *         protected $_manageTable = 'nodes';
 *     }
 *
 * In order to avoid trait collision you should always `extend` Field UI using this
 * trait over a `clean` controller. This is, a empty controller class with no
 * methods defined. For instance, create a new controller class
 * `MyPlugin\Controller\MyTableFieldManagerController` and use this trait to handle
 * custom fields for "MyTable" database table.
 *
 * # Requirements
 *
 * - This trait should only be used over a clean controller.
 * - You must define `$_manageTable` property in your controller.
 * - Your Controller must be a backend-controller (under `Controller\Admin` namespace).
 */
trait FieldUIControllerTrait {

	use ViewModeTrait;

/**
 * Validation rules.
 *
 * @param \Cake\Event\Event $event The event instance.
 * @return void
 * @throws \Cake\Network\Exception\ForbiddenException When
 *  - $_manageTable is not defined.
 *  - trait is used in non-controller classes.
 *  - the controller is not a backend controller.
 */
	public function beforeFilter(Event $event) {
		$requestParams = $event->subject->request->params;

		if (!isset($this->_manageTable) || empty($this->_manageTable)) {
			throw new ForbiddenException(__d('field', 'FieldUIControllerTrait: The property $_manageTable was not found or is empty.'));
		} elseif (!($this instanceof \Cake\Controller\Controller)) {
			throw new ForbiddenException(__d('field', 'FieldUIControllerTrait: This trait must be used on instances of Cake\Controller\Controller.'));
		} elseif (!isset($requestParams['prefix']) || strtolower($requestParams['prefix']) !== 'admin') {
			throw new ForbiddenException(__d('field', 'FieldUIControllerTrait: This trait must be used on backend-controllers only.'));
		}

		$this->_manageTable = Inflector::underscore($this->_manageTable);
	}

/**
 * Fallback for template location when extending Field UI API.
 *
 * If controller tries to render an unexisting template under its Template
 * directory, then we try to find that view under `Field/Template/FieldUI` directory.
 *
 * ### Example:
 *
 * Suppose you are using this trait to manage fields attached to `Persons` entities.
 * You would probably have a `Person` plugin and a `clean` controller as follow:
 *
 *     // http://example.com/admin/person/fields_manager
 *     Person\Controller\FieldsManagerController::index()
 *
 * The above controller action will try to render
 * `/plugins/Person/Template/CommentsManager/index.ctp`. But if does not exists then
 * `<QuickAppsCorePath>/plugins/Comment/Template/CommentUI/index.ctp`
 * will be used instead.
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
		$templatePath = Plugin::classPath($plugin) . "Template/{$controller}/{$action}.ctp";

		if (!file_exists($templatePath)) {
			$alternativeTemplatePath = Plugin::classPath('Field') . 'Template/FieldUI';

			if (file_exists("{$alternativeTemplatePath}/{$action}.ctp")) {
				$this->view = "{$alternativeTemplatePath}/{$action}.ctp";
			}
		}

		parent::beforeRender($event);
	}

/**
 * Field UI main action.
 *
 * Shows all the fields attached to the Table being managed.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('Field.FieldInstances');
		$instances = $this->FieldInstances
			->find()
			->where(['table_alias' => $this->_manageTable])
			->order(['ordering' => 'ASC'])
			->all();

		if (count($instances) == 0) {
			$this->Flash->warning(__d('field', 'There are no field attached yet.'));
		}

		$this->set('instances', $instances);
	}

/**
 * Handles a single field instance configuration parameters.
 *
 * In FormHelper, all fields prefixed with `_` will be considered as columns values
 * of the instance being edited. Any other input element will be considered as part
 * of the `settings` column.
 * 
 * For example: `_label`, `_required` and `description` maps to `label`, `required`
 * and `description`. And `some_input`, `another_input` maps to `settings.some_input`,
 * `settings.another_input`
 *
 * @param integer $id The field instance ID to manage
 * @return void
 * @throws \Cake\ORM\Exception\RecordNotFoundException When no field instance
 *  was found
 */
	public function configure($id) {
		$instance = $this->_getOrThrow($id, ['locked' => false]);
		$arrayContext = [
			'schema' => [],
			'defaults' => [],
			'errors' => [],
		];

		if ($this->request->data) {
			$instance->accessible('*', true);
			$instance->accessible(['id', 'table_alias', 'handler', 'ordering'], false);

			foreach ($this->request->data as $k => $v) {
				if (str_starts_with($k, '_')) {
					$instance->set(str_replace_once('_', '', $k), $v);
					unset($this->request->data[$k]);
				}
			}

			$settingsEntity = new Entity($this->request->data);
			$settingsEntity->set('_field_handler', $instance->handler);

			if ($this->FieldInstances->validate($settingsEntity, ['validate' => 'settings'])) {
				$instance->set('settings', $this->request->data);
				$save = $this->FieldInstances->save($instance);

				if ($save) {
					$this->Flash->success(__d('field', 'Field information was saved.'));
					$this->redirect($this->referer());
				} else {
					$this->Flash->danger(__d('field', 'Your information could not be saved.'));
				}
			} else {
				$this->Flash->danger(__d('field', 'Field settings could not be saved.'));
				$errors = $settingsEntity->errors();

				if (!empty($errors)) {
					foreach ($errors as $field => $message) {
						$arrayContext['errors'][$field] = $message;
					}
				}
			}
		} else {
			$arrayContext['defaults'] = (array)$instance->settings;
			$this->request->data = $arrayContext['defaults'];
		}

		$this->set(compact('arrayContext', 'instance'));
	}

/**
 * Attach action.
 *
 * Attaches a new Field to the table being managed.
 *
 * @return void
 */
	public function attach() {
		$this->loadModel('Field.FieldInstances');

		if (!empty($this->request->data)) {
			$data = $this->request->data;
			$data['table_alias'] = $this->_manageTable;
			$fieldInstance = $this->FieldInstances->newEntity($data);

			if ($this->FieldInstances->save($fieldInstance)) {
				$this->Flash->success(__d('field', 'Field attached!'));
				$this->redirect($this->referer());
			} else {
				$this->Flash->danger(__d('field', 'Field could not be attached'));
			}
		} else {
			$fieldInstance = $this->FieldInstances->newEntity();
		}

		$fieldsInfoCollection = $this->hook('Field.info')->result;
		$fieldsList = $fieldsInfoCollection->combine('handler', 'name')->toArray(); // for form select
		$fieldsInfo = $fieldsInfoCollection->toArray(); // for help-blocks

		$this->set('fieldsList', $fieldsList);
		$this->set('fieldsInfo', $fieldsInfo);
		$this->set('fieldInstance', $fieldInstance);
	}

/**
 * Detach action.
 *
 * Detaches a Field from table being managed.
 *
 * @param integer $id ID of the instance to detach
 * @return void
 */
	public function detach($id) {
		$instance = $this->_getOrThrow($id, ['locked' => false]);
		$this->loadModel('Field.FieldInstances');

		if ($this->FieldInstances->delete($instance)) {
			$this->Flash->success(__d('field', 'Field detached successfully!'));
		} else {
			$this->Flash->danger(__d('field', 'Field could not be detached'));
		}

		$this->redirect($this->referer());
	}

/**
 * View modes.
 *
 * Shows the list of fields for corresponding view mode.
 *
 * @param string $viewMode View mode slug. e.g. `rss` or `default`
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException When given view mode
 *  does not exists
 */
	public function view_mode_list($viewMode) {
		$this->_validateViewMode($viewMode);
		$this->loadModel('Field.FieldInstances');
		$instances =$this->FieldInstances
			->find()
			->where(['table_alias' => $this->_manageTable])
			->order(['ordering' => 'ASC'])
			->all();

		if (count($instances) === 0) {
			$this->Flash->warning(__d('field', 'There are no field attached yet.'));
		} else {
			$instances = $instances->sortBy(function ($fieldInstance) use($viewMode) {
				if (isset($fieldInstance->view_modes[$viewMode]['ordering'])) {
					return $fieldInstance->view_modes[$viewMode]['ordering'];
				}

				return 0;
			}, SORT_ASC);
		}

		$this->set('instances', $instances);
		$this->set('viewMode', $viewMode);
		$this->set('viewModeInfo', $this->viewModes($viewMode));
	}

/**
 * Handles field instance rendering settings for a particular view mode.
 *
 * @param string $viewMode View mode slug
 * @param integer $id The field instance ID to manage
 * @return void
 * @throws \Cake\ORM\Exception\RecordNotFoundException When no field
 *  instance was found
 * @throws \Cake\Network\Exception\NotFoundException When given view
 *  mode does not exists
 */
	public function view_mode_edit($viewMode, $id) {
		$this->_validateViewMode($viewMode);
		$instance = $this->_getOrThrow($id);
		$arrayContext = [
			'schema' => [
				'label_visibility' => ['type' => 'string'],
				'hooktags' => ['type' => 'boolean'],
				'hidden' => ['type' => 'boolean'],
			],
			'defaults' => [
				'label_visibility' => 'hidden',
				'hooktags' => false,
				'hidden' => false,
			],
			'errors' => []
		];
		$viewModeInfo = $this->viewModes($viewMode);

		if ($this->request->data) {
			$settingsEntity = new Entity($this->request->data);
			$settingsEntity->set('_field_handler', $instance->handler);

			if ($this->FieldInstances->validate($settingsEntity, ['validate' => 'viewMode'])) {
				$instance->accessible('*', true);
				$viewModes = $instance->get('view_modes');
				$viewModes[$viewMode] = array_merge($viewModes[$viewMode], $this->request->data);
				$instance->set('view_modes', $viewModes);

				if ($this->FieldInstances->save($instance)) {
					$this->Flash->success(__d('field', 'Field information was saved.'));
					$this->redirect($this->referer());
				} else {
					$this->Flash->danger(__d('field', 'Your information could not be saved.'));
				}
			} else {
				$this->Flash->danger(__d('field', 'View mode settings could not be saved.'));
				$errors = $settingsEntity->errors();

				if (!empty($errors)) {
					foreach ($errors as $field => $message) {
						$arrayContext['errors'][$field] = $message;
					}
				}
			}
		} else {
			$arrayContext['defaults'] = (array)$instance->view_modes[$viewMode];
			$this->request->data = $arrayContext['defaults'];
		}

		$instance->accessible('settings', true);
		$this->set(compact('arrayContext', 'viewMode', 'viewModeInfo', 'instance'));
	}

/**
 * Moves a field up or down within a view mode.
 *
 * The ordering indicates the position they are displayed when entities are rendered
 * in a specific view mode.
 *
 * @param string $viewMode View mode slug
 * @param integer $id Field instance id
 * @param string $direction Direction, 'up' or 'down'
 * @return void Redirects to previous page
 * @throws \Cake\ORM\Exception\RecordNotFoundException When no field
 *  instance was found
 * @throws \Cake\Network\Exception\NotFoundException When given view mode
 *  does not exists
 */
	public function view_mode_move($viewMode, $id, $direction) {
		$this->_validateViewMode($viewMode);
		$instance = $this->_getOrThrow($id);
		$unordered = [];
		$position = false;
		$k = 0;
		$list = $this->FieldInstances->find()
			->select(['id', 'view_modes'])
			->where(['table_alias' => $instance->table_alias])
			->order(['ordering' => 'ASC'])
			->all()
			->sortBy(function ($fieldInstance) use($viewMode) {
				if (isset($fieldInstance->view_modes[$viewMode]['ordering'])) {
					return $fieldInstance->view_modes[$viewMode]['ordering'];
				}

				return 0;
			}, SORT_ASC);

		foreach ($list as $field) {
			if ($field->id === $instance->id) {
				$position = $k;
			}

			$unordered[] = $field;
			$k++;
		}

		if ($position !== false) {
			$ordered = array_move($unordered, $position, $direction);
			$before = md5(serialize($unordered));
			$after = md5(serialize($ordered));

			if ($before != $after) {
				foreach ($ordered as $k => $field) {
					$view_modes = $field->view_modes;
					$view_modes[$viewMode]['ordering'] = $k;
					$field->set('view_modes', $view_modes);
					$this->FieldInstances->save($field, ['validate' => false]);
				}
			}
		}

		$this->redirect($this->referer());
	}

/**
 * Moves a field up or down.
 *
 * The ordering indicates the position they are displayed on entity's
 * editing form.
 *
 * @param integer $id Field instance id
 * @param string $direction Direction, 'up' or 'down'
 * @return void Redirects to previous page
 */
	public function move($id, $direction) {
		$instance = $this->_getOrThrow($id);
		$unordered = [];
		$direction = !in_array($direction, ['up', 'down']) ? 'up' : $direction;
		$position = false;
		$list = $this->FieldInstances->find()
			->select(['id', 'label', 'ordering'])
			->where(['table_alias' => $instance->table_alias])
			->order(['ordering' => 'ASC'])
			->all();

		foreach ($list as $k => $field) {
			if ($field->id === $instance->id) {
				$position = $k;
			}

			$unordered[] = $field;
		}

		if ($position !== false) {
			$ordered = array_move($unordered, $position, $direction);
			$before = md5(serialize($unordered));
			$after = md5(serialize($ordered));

			if ($before != $after) {
				foreach ($ordered as $k => $field) {
					$field->set('ordering', $k);
					$this->FieldInstances->save($field, ['validate' => false]);
				}
			}
		}

		$this->redirect($this->referer());
	}

/**
 * Gets the given field instance by ID or throw if not exists.
 *
 * @param integer $id Field instance ID
 * @param array $conditions Additional conditions for the WHERE query
 * @return \Field\Model\Entity\FieldInstance The instance
 * @throws \Cake\ORM\Exception\RecordNotFoundException When instance
 *  was not found
 */
	protected function _getOrThrow($id, $conditions = []) {
		$this->loadModel('Field.FieldInstances');
		$conditions = array_merge(['id' => $id], $conditions);
		$instance = $this->FieldInstances
			->find()
			->where($conditions)
			->limit(1)
			->first();

		if (!$instance) {
			throw new RecordNotFoundException(__d('field', 'The requested field does not exists.'));
		}

		return $instance;
	}

/**
 * Throws if the given view modes does not exists.
 *
 * @param string $viewMode The view mode to validate
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException When given view mode
 *  does not exists
 */
	protected function _validateViewMode($viewMode) {
		if (!in_array($viewMode, $this->viewModes())) {
			throw new NotFoundException(__d('field', 'The requested view mode does not exists.'));
		}
	}

}
