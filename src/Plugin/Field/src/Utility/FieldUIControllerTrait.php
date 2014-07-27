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
namespace Field\Utility;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Error\NotFoundException;
use Cake\Event\Event;
use Cake\ORM\Error\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use QuickApps\Utility\ViewModeTrait;

/**
 * Field UI Trait.
 *
 * Other plugins may `extends` Field plugin by using this trait
 * in their controllers.
 *
 * With this trait, Field plugin provides an user friendly UI for manage entity's
 * custom fields. It provides a field-manager user interface (UI) by attaching a series of
 * actions over a `clean` controller.
 *
 * # Usage:
 *
 * Beside adding `use FieldUIControllerTrait;` to your controller
 * you MUST also indicate the name of the Table being managed. Example:
 *
 *     uses Field\Controller\FieldUIControllerTrait;
 *
 *     class MyCleanController extends <Plugin>AppController {
 *         use FieldUIControllerTrait;
 *         protected $_manageTable = 'nodes'; // <- underscored table alias. e.g.: "user_photos"
 *     }
 *
 * In order to avoid trait collision you should always `extend`
 * Field UI using this trait over a `clean` controller. This is, a empty controller class
 * with no methods defined. For instance, create a new controller class `MyPlugin\Controller\MyTableFieldManagerController`
 * and use this trait to handle custom fields for "MyTable" database table.
 *
 * # Requirements
 *
 * - This trait should only be used over a clean controller.
 * - You must define `$_manageTable` property in your controller.
 * - Your Controller must be a backend-controller (under `Controller\Admin` namespace).
 *
 * @author Christopher Castro <chris@quickapps.es>
 */
trait FieldUIControllerTrait {

	use ViewModeTrait;

/**
 * Validation rules.
 *
 * @param \Cake\Event\Event $event The event instance.
 * @return void
 * @throws \Cake\Error\ForbiddenException When
 * - $_manageTable is not defined.
 * - trait is used in non-controller classes
 * - the controller is not a backend controller.
 */
	public function beforeFilter(Event $event) {
		$requestParams = $event->subject->request->params;

		if (!isset($this->_manageTable) || empty($this->_manageTable)) {
			throw new Error\ForbiddenException(__d('field', 'FieldUIControllerTrait: The property $_manageTable was not found or is empty.'));
		} elseif (!($this instanceof \Cake\Controller\Controller)) {
			throw new Error\ForbiddenException(__d('field', 'FieldUIControllerTrait: This trait must be used on instances of Cake\Controller\Controller.'));
		} elseif (!isset($requestParams['prefix']) || strtolower($requestParams['prefix']) !== 'admin') {
			throw new Error\ForbiddenException(__d('field', 'FieldUIControllerTrait: This trait must be used on backend-controllers only.'));
		}

		$this->_manageTable = Inflector::underscore($this->_manageTable);
	}

/**
 * Fallback for template location when extending Field UI API.
 *
 * If controller tries to render an unexisting template under its Template directory,
 * then we try to find that view under `Field/Template/FieldUI` directory.
 *
 * ### Example:
 *
 * Suppose you are using this trait to manage fields attached to
 * `Persons` entities. You would probably have a `Person` plugin and
 * a `clean` controller as follow:
 *
 *     // http://example.com/admin/user/field_manager
 *     User\FieldsManagerController::index()
 *
 * The above controller action will try to render `/Plugin/User/Template/FieldsManager/index.ctp`.
 * But if does not exists then `<QuickAppsCorePath>/Plugin/Field/Template/FieldUI/index.ctp`
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
		$templatePath = Plugin::classPath($plugin) . implode(DS, ['Template', $controller, "{$action}.ctp"]);

		if (!file_exists($templatePath)) {
			$alternativeTemplatePath = Plugin::classPath('Field') . 'Template' . DS . 'FieldUI';

			if (file_exists($alternativeTemplatePath . DS . "{$action}.ctp")) {
				$this->view = $alternativeTemplatePath . DS . "{$action}.ctp";
			}
		}

		parent::beforeRender($event);
	}

/**
 * FieldUI main action.
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
			$this->alert(__d('field', 'There are no field attached yet.'), 'warning');
		}

		$this->set('instances', $instances);
	}

/**
 * Handles a single field instance configuration parameters.
 *
 * @param integer $id The field instance ID to manage
 * @return void
 * @throws \Cake\ORM\Error\RecordNotFoundException When no field instance was found
 */
	public function configure($id) {
		$instance = $this->_getOrThrow($id, ['locked' => false]);

		if ($this->request->data) {
			$instance->accessible('*', true);
			$instance->set($this->request->data);

			$save = $this->FieldInstances->save($instance);

			if ($save) {
				$this->alert(__d('field', 'Field information was saved.'));
			} else {
				$this->alert(__d('field', 'Your information could not be saved.'), 'danger');
			}
		}

		$instance->accessible('settings', true);
		$this->set('instance', $instance);
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
				$this->alert(__d('field', 'Field attached!'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->alert(__d('field', 'Field could not be attached'), 'danger');
			}
		} else {
			$fieldInstance = $this->FieldInstances->newEntity();
		}

		$fieldsList = []; // for form select
		$fieldsInfo = []; // for help-blocks
		$fieldsHolder = []; // tmp array
		foreach ((array)Hash::extract(Configure::read('QuickApps.plugins'), '{s}.events.fields') 
			as $pluginIndex => $pluginFields) {
			if (!empty($pluginFields)) {
				$fieldsHolder = array_merge($fieldsHolder, array_keys($pluginFields));
			}
		}

		foreach ($fieldsHolder as $k => $f) {
			$parts = explode('\\', $f);
			$fieldHandler = array_pop($parts);
			$invoke = $this->invoke("Field.{$fieldHandler}.Instance.info")->result;

			if (!empty($invoke['name']) && (empty($f['hidden']) || $f['hidden'] === false)) {
				$fieldsInfo[$fieldHandler] = $invoke;
				$fieldsList[$fieldHandler] = $invoke['name'];
			}
		}

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
			$this->alert(__d('field', 'Field detached successfully!'), 'success');
		} else {
			$this->alert(__d('field', 'Field could not be detached'), 'danger');
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
 * @throws \Cake\Error\NotFoundException When given view mode does not exists
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
			$this->alert(__d('field', 'There are no field attached yet.'), 'warning');
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
	}

/**
 * Handles field instance rendering settings for a particular view mode.
 *
 * @param string $viewMode View mode slug
 * @param integer $id The field instance ID to manage
 * @return void
 * @throws \Cake\ORM\Error\RecordNotFoundException When no field instance was found
 * @throws \Cake\Error\NotFoundException When given view mode does not exists
 */
	public function view_mode_edit($viewMode, $id) {
		$this->_validateViewMode($viewMode);
		$instance = $this->_getOrThrow($id);

		if ($this->request->data) {
			$instance->accessible('*', true);
			$instance->set($this->request->data);

			$save = $this->FieldInstances->save($instance);

			if ($save) {
				$this->alert(__d('field', 'Field information was saved.'));
			} else {
				$this->alert(__d('field', 'Your information could not be saved.'), 'danger');
			}
		}

		$instance->accessible('settings', true);
		$this->set('viewMode', $viewMode);
		$this->set('instance', $instance);
	}

/**
 * Moves a field up or down within a view mode.
 *
 * The ordering indicates the position they are displayed when
 * entities are rendered in a specific view mode.
 *
 * @param string $viewMode View mode slug
 * @param integer $id Field instance id
 * @param string $direction Direction, 'up' or 'down'
 * @return void Redirects to previous page
 * @throws \Cake\ORM\Error\RecordNotFoundException When no field instance was found
 * @throws \Cake\Error\NotFoundException When given view mode does not exists
 */
	public function view_mode_move($viewMode, $id, $direction) {
		$this->_validateViewMode($viewMode);
		$instance = $this->_getOrThrow($id);
		$unordered = [];
		$direction = !in_array($direction, ['up', 'down']) ? 'up' : $direction;
		$position = false;
		$k = 0;
		$list = $this->FieldInstances->find()
			->select(['id', 'view_modes'])
			->where(['table_alias' => $instance->table_alias])
			->order(['ordering' => 'ASC'])
			->all()
			->sortBy(function ($fieldInstance) use($viewMode) {
				if (isset($fieldInstance->view_modes->{$viewMode}['ordering'])) {
					return $fieldInstance->view_modes->{$viewMode}['ordering'];
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
			$ordered = $this->_move($unordered, $position, $direction);
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
 * The ordering indicates the position they are displayed on
 * entity's editing form.
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
			->select(['id', 'ordering'])
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
			$ordered = $this->_move($unordered, $position, $direction);
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
 * Moves the given element by index from a list array of elements.
 *
 * @param array $list Numeric indexed array list of elements
 * @param integer $position The index position of the element you want to move
 * @param string $direction Direction, 'up' or 'down'
 * @return array Reordered original list.
 */
	protected function _move(array $list, $position, $direction) {
		if ($direction == 'down') {
			if (count($list) - 1 > $position) {
				$b = array_slice($list, 0, $position, true);
				$b[] = $list[$position + 1];
				$b[] = $list[$position];
				$b += array_slice($list, $position + 2, count($list), true);

				return $b;
			} else {
				return $list;
			}
		} elseif ($direction = 'up') {
			if ($position > 0 and $position < count($list)) {
				$b = array_slice($list, 0, ($position - 1), true);
				$b[] = $list[$position];
				$b[] = $list[$position - 1];
				$b += array_slice($list, ($position + 1), count($list), true);

				return $b;
			} else {
				return $list;
			}
		}

		return $list;
	}

/**
 * Gets the given field instance by ID or throw if not exists.
 *
 * @param integer $id Field instance ID
 * @param array $conditions Additional conditions for the WHERE query
 * @return \Field\Model\Entity\FieldInstance The instance
 * @throws \Cake\ORM\Error\RecordNotFoundException When instance was not found
 */
	protected function _getOrThrow($id, $conditions = []) {
		$this->loadModel('Field.FieldInstances');
		$conditions = array_merge(['id' => $id], $conditions);
		$instance = $this->FieldInstances->find('all')->where($conditions)->first();

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
 * @throws \Cake\Error\NotFoundException When given view mode does not exists
 */
	protected function _validateViewMode($viewMode) {
		if (!in_array($viewMode, $this->viewModes())) {
			throw new NotFoundException(__d('field', 'The requested view mode does not exists.'));
		}
	}

}
