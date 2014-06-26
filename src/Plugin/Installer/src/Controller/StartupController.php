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
namespace Installer\Controller;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\Utility\Folder;
use Cake\Utility\Hash;

/**
 * Controller for handling new QuickAppsCMS installations.
 *
 * This controller starts the installation process for
 * a new QuickAppsCMS setup.
 */
class StartupController extends InstallerAppController {

/**
 * {@inheritdoc}
 *
 * @var string
 */
	public $theme = false;

/**
 * {@inheritdoc}
 *
 * @var string
 */
	public $layout = 'Installer.startup';

/**
 * {@inheritdoc}
 *
 * @var string
 */
	public $components = ['Session'];

/**
 * {@inheritdoc}
 *
 * @param \Cake\Network\Request $request Request object for this controller. Can be null for testing,
 *  but expect that features that use the request parameters will not work.
 * @param \Cake\Network\Response $response Response object for this controller.
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$this->_prepareLayout();

		Router::addUrlFilter(function ($params, $request) {
			if (
				isset($request->query['locale']) &&
				$request->params['action'] !== 'language'
			) {
				$params['locale'] = $request->query['locale'];
			} elseif (
				!$request->params['action'] !== 'language' &&
				!isset($request->query['locale'])
			) {
				$params['locale'] = 'eng';
			}

			return $params;
		});
	}

/**
 * Main action.
 *
 * We redirect to first step if the installation process: `language`.
 *
 * @return void
 */
	public function index() {
		$this->redirect(['plugin' => 'installer', 'controller' => 'startup', 'action' => 'language']);
	}

/**
 * First step of the installation process.
 *
 * User must select the language they want to use for the installation process.
 *
 * @return void
 */
	public function language() {
		$Folder = new Folder(App::pluginPath('Installer') . 'Locale');
		$languages = [
			'eng' => [
				'url' => ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'requirements', 'locale' => 'eng'],
				'welcome' => 'Welcome to QuickApps CMS',
				'action' => 'Click here to install in English'
			]
		];

		foreach ($Folder->read(false, false, true)[0] as $path) {
			$code = basename($path);
			$file = $path . DS . 'LC_MESSAGES' . DS . 'installer.po';

			if (file_exists($file)) {
				$languages[$code] = array(
					'url' => ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'requirements', 'locale' => $code],
					'welcome' => I18n::translate('Welcome to QuickApps CMS', null, 'installer', 6, null, $code),
					'action' => I18n::translate('Click here to install in English', null, 'installer', 6, null, $code)
				);
			}
		}

		$this->set('title_for_layout', 'Welcome to QuickApps CMS');
		$this->set('languages', $languages);
		$this->_step();
	}

/**
 * Second step of the installation process.
 *
 * We check server requirements here.
 *
 * @return void
 */
	public function requirements() {
		if (!$this->_step('language')) {
			$this->redirect(['plugin' => 'installer', 'controller' => 'startup', 'action' => 'index']);
		}

		$tests = array(
			'php' => array(
				'test' => version_compare(PHP_VERSION, '5.4', '>='),
				'msg' => __('Your php version is not supported. check that your version is 5.4 or newer.')
			),
			'no_safe_mode' => array(
				'test' => (ini_get('safe_mode') == false || ini_get('safe_mode') == '' || strtolower(ini_get('safe_mode')) == 'off'),
				'msg' => __('Your server has SafeMode on, please turn it off before continuing.')
			),
			'tmp_writable' => array(
				'test' => is_writable(TMP),
				'msg' => __('tmp folder is not writable.')
			),
			'cache_writable' => array(
				'test' => is_writable(TMP . 'cache'),
				'msg' => __('tmp/cache folder is not writable.')
			),
			'models_writable' => array(
				'test' => is_writable(TMP . 'cache' . DS . 'models'),
				'msg' => __('tmp/cache/models folder is not writable.')
			),
			'persistent_writable' => array(
				'test' => is_writable(TMP . 'cache' . DS . 'persistent'),
				'msg' => __('tmp/cache/persistent folder is not writable.')
			),
			'Config_writable' => array(
				'test' => is_writable(SITE_ROOT . DS . 'Config'),
				'msg' => __('Config folder is not writable.')
			)
		);

		$results = array_unique(Hash::extract($tests, '{s}.test'));

		if (!(count($results) === 1 && $results[0] === true)) {
			$this->set('success', false);
			$this->set('tests', $tests);
		} else {
			$this->set('success', true);
			$this->_step();
		}

		$this->set('title_for_layout', __('Server Requirements'));
	}

/**
 * Third step of the installation process.
 *
 * License agreement.
 *
 * @return void
 */
	public function license() {
		if (!$this->_step('requirements')) {
			$this->redirect(['plugin' => 'installer', 'controller' => 'startup', 'action' => 'index']);
		}

		$this->set('title_for_layout', __('License Agreement'));
		$this->_step();
	}

/**
 * Fourth step of the installation process.
 *
 * User must introduce database connection information.
 *
 * @return void
 */
	public function database() {
		if (!$this->_step('license')) {
			$this->redirect(['plugin' => 'installer', 'controller' => 'startup', 'action' => 'index']);
		}

		if (!empty($this->request->data)) {
			$data = $this->request->data;
			$config = [
				'className' => 'Cake\Database\Connection',
				'driver' => 'Cake\Database\Driver\\' . $data['driver'],
				'database' => $data['name'],
				'login' => $data['username'],
				'password' => $data['password'],
				'host' => $data['host'],
				'encoding' => 'utf8',
				'timezone' => 'UTC'
			];

			$connected = false;

			try {
				// TODO: upload database
				$conn = ConnectionManager::create('startup', $config);
				$conn->connect();
				$schema = $conn->schemaCollection();
				$tables = $schema->listTables();

				$connected = true;
			} catch (\Exception $e) {
				$this->alert(__('Unable to connect to database, please check your information. Details: %s', '<p>' . $e->getMessage(). '</p>'), 'danger');
			}

			if ($connected) {
				$this->_step();
				$this->redirect(['plugin' => 'installer', 'controller' => 'startup', 'action' => 'account']);
			}
		}
	}

/**
 * Fifth step of the installation process.
 *
 * Create a new administrator user account.
 *
 * @return void
 */
	public function account() {
	}

/**
 * Last step of the installation process.
 *
 * Here we say "thanks" and redirect to site's frontend or backend.
 *
 * @return void
 */
	public function finish() {
	}

/**
 * Check if the given step name was completed.
 * Or marks current step as completed.
 *
 * If $check is set to false, we mark current step (controller's action name) as completed.
 * If $check is set to a string, we check if that step was completed before.
 *
 * This allows steps to control user navigation, so users can not pass to the next step
 * without completing all previous steps.
 *
 * @param boolean|string $check
 * @return boolean
 */
	protected function _step($check = false) {
		$_steps = (array)$this->Session->read('Startup._steps');

		if ($check === false) {
			$_steps[] = $this->request->params['action'];
			$_steps = array_unique($_steps);
			$this->Session->write('Startup._steps', $_steps);
		} else {
			return in_array($check, $_steps);
		}
	}

/**
 * Sets some view-variables used across all steps.
 *
 * @return void
 */
	protected function _prepareLayout() {
		$menu = [
			__('Welcome') => [
				'url' => ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'language'],
				'active' => ($this->request->action === 'language')
			],
			__('System Requirements') => [
				'url' => ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'requirements'],
				'active' => ($this->request->action === 'requirements')
			],
			__('License Agreement') => [
				'url' => ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'license'],
				'active' => ($this->request->action === 'license')
			],
			__('Database Setup') => [
				'url' => ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'database'],
				'active' => ($this->request->action === 'database')
			],
			__('Your Account') => [
				'url' => ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'account'],
				'active' => ($this->request->action === 'account')
			],
			__('Finish') => [
				'url' => ['plugin' => 'installer', 'controller' => 'startup', 'action' => 'finish'],
				'active' => ($this->request->action === 'finish')
			],
		];

		$this->set('menu', $menu);
	}

}
