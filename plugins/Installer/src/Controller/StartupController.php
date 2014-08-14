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
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\Utility\Folder;
use Cake\Utility\Hash;
use Installer\Controller\AppController;
use QuickApps\Core\Plugin;

/**
 * Controller for handling new QuickAppsCMS installations.
 *
 * This controller starts the installation process for a new QuickAppsCMS setup.
 */
class StartupController extends AppController {

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
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function beforeFilter(Event $event) {
		if (file_exists(SITE_ROOT . '/config/settings.php')) {
			$this->redirect('/');
		}

		$this->_prepareLayout();

		if (!empty($this->request->query['locale']) && !in_array($this->request->params['action'], ['language', 'index'])) {
			I18n::defaultLocale($this->request->query['locale']);
			$this->Session->write('installation.language', I18n::defaultLocale());
		} elseif ($this->Session->read('installation.language')) {
			I18n::defaultLocale($this->Session->read('installation.language'));
		}

		Router::addUrlFilter(function ($params, $request) {
			if (!in_array($request->params['action'], ['language', 'index'])) {
				$params['locale'] = I18n::defaultLocale();
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
		$this->redirect([
			'plugin' => 'Installer',
			'controller' => 'startup',
			'action' => 'language'
		]);
	}

/**
 * First step of the installation process.
 *
 * User must select the language they want to use for the installation process.
 *
 * @return void
 */
	public function language() {
		$Folder = new Folder(Plugin::classPath('Installer') . 'Locale');
		$languages = [
			'en-us' => [
				'url' => '/installer/startup/requirements?locale=en-us',
				'welcome' => 'Welcome to QuickApps CMS',
				'action' => 'Click here to install in English'
			]
		];

		foreach ($Folder->read(false, true, true)[0] as $path) {
			$code = basename($path);
			$file = $path . DS . 'LC_MESSAGES' . DS . 'installer.po';

			if (file_exists($file)) {
				I18n::defaultLocale($code); // trick for __d()
				$languages[$code] = array(
					'url' => "/installer/startup/requirements?locale={$code}",
					'welcome' => __d('installer', 'Welcome to QuickApps CMS'),
					'action' => __d('installer', 'Click here to install in English')
				);
			}
		}

		I18n::defaultLocale('en_US');
		$this->title('Welcome to QuickApps CMS');
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
			$this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'index']);
		}

		$tests = array(
			'php' => array(
				'test' => version_compare(PHP_VERSION, '5.4.19', '>='),
				'message' => __d('installer', 'Your php version is not supported. check that your version is 5.4.19 or newer.')
			),
			'mbstring' => array(
				'test' => extension_loaded('mbstring'),
				'message' => __d('installer', 'Missing extension: {0}', 'mbstring')
			),
			'mcrypt' => array(
				'test' => extension_loaded('mcrypt'),
				'message' => __d('installer', 'Missing extension: {0}', 'mcrypt')
			),
			'intl' => array(
				'test' => extension_loaded('intl'),
				'message' => __d('installer', 'Missing extension: {0}', 'intl')
			),
			'fileinfo' => array(
				'test' => extension_loaded('fileinfo'),
				'message' => __d('installer', 'Missing extension: {0}', 'fileinfo')
			),
			'pdo' => array(
				'test' => (extension_loaded('pdo') && defined('PDO::ATTR_DEFAULT_FETCH_MODE')),
				'message' => __d('installer', 'Missing extension: {0}', 'PDO')
			),
			'no_safe_mode' => array(
				'test' => (ini_get('safe_mode') == false || ini_get('safe_mode') == '' || strtolower(ini_get('safe_mode')) == 'off'),
				'message' => __d('installer', 'Your server has SafeMode on, please turn it off before continuing.')
			),
			'tmp_writable' => array(
				'test' => is_writable(TMP),
				'message' => __d('installer', 'tmp folder is not writable.')
			),
			'cache_writable' => array(
				'test' => is_writable(TMP . 'cache'),
				'message' => __d('installer', 'tmp/cache folder is not writable.')
			),
			'models_writable' => array(
				'test' => is_writable(TMP . 'cache/models'),
				'message' => __d('installer', 'tmp/cache/models folder is not writable.')
			),
			'persistent_writable' => array(
				'test' => is_writable(TMP . 'cache/persistent'),
				'message' => __d('installer', 'tmp/cache/persistent folder is not writable.')
			),
			'config_writable' => array(
				'test' => is_writable(SITE_ROOT . '/config'),
				'message' => __d('installer', '"config" folder is not writable.')
			)
		);

		$results = array_unique(Hash::extract($tests, '{s}.test'));

		if (count($results) !== 1 || $results[0] !== true) {
			$this->set('success', false);
			$this->set('tests', $tests);
		} else {
			$this->set('success', true);
			$this->_step();
		}

		$this->title(__d('installer', 'Server Requirements'));
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
			$this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'index']);
		}

		$this->title(__d('installer', 'License Agreement'));
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
			$this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'index']);
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
				'prefix' => $data['prefix'],
				'encoding' => 'utf8',
				'timezone' => 'UTC',
			];
			$dumpComplete = false;

			try {
				// TODO: upload database
				ConnectionManager::config('installation', $config);
				$db = ConnectionManager::get('installation');
				$db->connect();
				$schemaCollection = $db->schemaCollection();
				$tables = $schemaCollection->listTables();
				$dumpComplete = true;
				die;
			} catch (\Exception $e) {
				$this->Flash->danger(__d('installer', 'Unable to connect to database, please check your information. Details: {0}', '<p>' . $e->getMessage(). '</p>'));
			}

			if ($dumpComplete) {
				$this->_step();
				$this->redirect(['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'account']);
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
 * Check if the given step name was completed. Or marks current step as completed.
 *
 * If $check is set to false, we mark current step (controller's action name) as completed.
 * If $check is set to a string, we check if that step was completed before.
 *
 * This allows steps to control user navigation, so users can not pass to the next step
 * without completing all previous steps.
 *
 * @param boolean|string $check
 * @return bool
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
			__d('installer', 'Welcome') => [
				'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'language'],
				'active' => ($this->request->action === 'language')
			],
			__d('installer', 'System Requirements') => [
				'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'requirements'],
				'active' => ($this->request->action === 'requirements')
			],
			__d('installer', 'License Agreement') => [
				'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'license'],
				'active' => ($this->request->action === 'license')
			],
			__d('installer', 'Database Setup') => [
				'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'database'],
				'active' => ($this->request->action === 'database')
			],
			__d('installer', 'Your Account') => [
				'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'account'],
				'active' => ($this->request->action === 'account')
			],
			__d('installer', 'Finish') => [
				'url' => ['plugin' => 'Installer', 'controller' => 'startup', 'action' => 'finish'],
				'active' => ($this->request->action === 'finish')
			],
		];

		$this->set('menu', $menu);
	}

}
