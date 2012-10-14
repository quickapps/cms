<?php
/**
 * Install Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
App::uses('Controller', 'Controller');

class InstallController extends Controller {
	public $name = 'Install';
	public $uses = array();
	public $components = array('HookCollection', 'Session');
	public $helpers = array('HookCollection', 'Layout', 'Html', 'Form');
	private $__defaultDbConfig = array(
		'name' => 'default',
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'quickapps',
		'schema' => null,
		'prefix' => 'qa_',
		'encoding' => 'UTF8',
		'port' => '3306'
	);

	public function beforeFilter() {
		$this->viewClass = 'View';
		$this->layout = 'install';

		// already installed ?
		if (file_exists(ROOT . DS . 'Config' . DS . 'database.php') && file_exists(ROOT . DS . 'Config' . DS . 'install')) {
			$this->redirect('/');
		}

		if (!CakeSession::read('Config.language')) {
			Configure::write('Config.language', 'eng');
		}
	}

/**
 * Step 0, Select language.
 *
 * @return void
 */
	public function index() {
		App::uses('I18n', 'I18n');

		if (isset($this->params['named']['lang']) && preg_match('/^[a-z]{3}$/', $this->params['named']['lang'])) {
			CakeSession::write('Config.language', $this->params['named']['lang']);
			$this->redirect('/install/license');
		}

		$Folder = new Folder(ROOT . DS . 'Locale' . DS);
		$langs = $Folder->read(true, false);
		$languages = array();

		foreach ($langs[0] as $l) {
			$file = ROOT . DS . 'Locale' . DS . $l . DS . 'LC_MESSAGES' . DS . 'default.po';

			if ($l != 'eng' && file_exists($file)) {
				$languages[$l] = array(
					'welcome' => I18n::translate('Welcome to QuickApps CMS', null, null, 6, null, $l),
					'action' => I18n::translate('Click here to install in English', null, null, 6, null, $l)
				);
			}
		}

		if (empty($languages)) {
			CakeSession::write('Config.language', 'eng');
			$this->redirect('/install/license');
		}

		$this->set('languages', $languages);
	}

/**
 * Step 1, License agreement
 *
 * @return void
 */
	public function license() {
		if (isset($this->data['License'])) {
			$this->__stepSuccess('license');
			$this->redirect('/install/server_test');
		}
	}

/**
 * Step 2, Server test
 *
 * @return void
 */
	public function server_test() {
		if (!$this->__stepSuccess('license', true)) {
			$this->redirect('/install/license');
		}

		if (!empty($this->data['Test'])) {
			$this->__stepSuccess('server_test');
			$this->redirect('/install/database');
		}

		$tests = array(
			'php' => array(
				'test' => version_compare(PHP_VERSION, '5.2.8', '>='),
				'msg' => __t('Your php version is not supported. check that your version is 5.2.8 or newer.')
			),
			'mysql' => array(
				'test' => (extension_loaded('mysql') || extension_loaded('mysqli')),
				'msg' => __t('MySQL extension is not loaded on your server.')
			),
			'no_safe_mode' => array(
				'test' => (ini_get('safe_mode') == false || ini_get('safe_mode') == '' || strtolower(ini_get('safe_mode')) == 'off'),
				'msg' => __t('Your server has SafeMode on, please turn it off before continuing.')
			),
			'tmp_writable' => array(
				'test' => is_writable(TMP),
				'msg' => __t('tmp folder is not writable.')
			),
			'cache_writable' => array(
				'test' => is_writable(TMP . 'cache'),
				'msg' => __t('tmp/cache folder is not writable.')
			),
			'installer_writable' => array(
				'test' => is_writable(TMP . 'cache' . DS . 'installer'),
				'msg' => __t('tmp/cache/installer folder is not writable.')
			),
			'models_writable' => array(
				'test' => is_writable(TMP . 'cache' . DS . 'models'),
				'msg' => __t('tmp/cache/models folder is not writable.')
			),
			'persistent_writable' => array(
				'test' => is_writable(TMP . 'cache' . DS . 'persistent'),
				'msg' => __t('tmp/cache/persistent folder is not writable.')
			),
			'i18n_writable' => array(
				'test' => is_writable(TMP . 'cache' . DS . 'i18n'),
				'msg' => __t('tmp/cache/i18n folder is not writable.')
			),
			'Config_writable' => array(
				'test' => is_writable(ROOT . DS . 'Config'),
				'msg' => __t('Config folder is not writable.')
			),
			'core.php_writable' => array(
				'test' => is_writable(ROOT . DS . 'Config' . DS . 'core.php'),
				'msg' => __t('Config/core.php file is not writable.')
			)
		);

		$results = array_unique(Hash::extract($tests, '{s}.test'));

		if (!(count($results) === 1 && $results[0] === true)) {
			$this->set('success', false);
			$this->set('tests', $tests);
		} else {
			$this->set('success', true);
		}
	}

/**
 * Step 3, Database
 *
 * @return void
 */
	public function database($skip = false) {
		if (!$this->__stepSuccess(array('license', 'server_test'), true)) {
			$this->redirect('/install/license');
		}

		$config_exists = file_exists(ROOT . DS . 'Config' . DS . 'database.php');

		$this->set('config_exists', $config_exists);

		if (!empty($this->data) || ($config_exists && $skip)) {
			App::uses('ConnectionManager', 'Model');

			$continue = true;

			if (!$config_exists) {
				$data = $this->data;
				$data['datasource'] = 'Database/Mysql';
				$data['persistent'] = false;
				$data = Hash::merge($this->__defaultDbConfig, $data);
				$continue = $this->__writeDatabaseFile($data);
			}

			if ($continue) {
				try {
					$db = ConnectionManager::getDataSource('default');
					$data = $db->config;
				} catch (Exception $e) {
					$this->Session->setFlash(__t('Could not connect to database.'), 'default', 'error');

					if (!$config_exists) {
						$this->__removeDatabaseFile();
					}

					return;
				}

				App::uses('Model', 'Model');
				App::uses('CakeSchema', 'Model');

				$schema = new CakeSchema(array('name' => 'QuickApps', 'file' => 'QuickApps.php'));
				$schema = $schema->load();
				$execute = array();
				$sources = $db->listSources();

				foreach (array_keys($schema->tables) as $table) {
					if (in_array($data['prefix'] . $table, $sources)) {
						$this->Session->setFlash(__t('A previous installation of QuickApps CMS already exists, please drop your database or change the prefix.'), 'default', 'error');

						if (!$config_exists) {
							$this->__removeDatabaseFile();
						}

						return;
					}
				}

				foreach ($schema->tables as $table => $fields) {
					$create = $db->createSchema($schema, $table);
					$execute[] = $db->execute($create);

					$db->reconnect();
				}

				$dataPath = APP . 'Config' . DS . 'Schema' . DS . 'data' . DS;
				$modelDataObjects = App::objects('class', $dataPath, false);

				foreach ($modelDataObjects as $model) {
					include_once $dataPath . $model . '.php';

					$model = new $model;
					$Model = new Model(
						array(
							'name' => get_class($model),
							'table' => $model->table,
							'ds' => 'default'
						)
					);
					$Model->cacheSources = false;

					if (isset($model->records) && !empty($model->records)) {
						foreach ($model->records as $record) {
							$Model->create($record);
							$execute[] = $Model->save();
						}
					}
				}

				if (!in_array(false, array_values($execute), true)) {
					App::uses('Security', 'Utility');
					App::load('Security');

					$salt = Security::generateAuthKey();
					$seed = mt_rand() . mt_rand();
					$file = new File(ROOT . DS . 'Config' . DS . 'core.php');
					$contents = $file->read();
					$contents = preg_replace('/(?<=Configure::write\(\'Security.salt\', \')([^\' ]+)(?=\'\))/', $salt, $contents);
					$contents = preg_replace('/(?<=Configure::write\(\'Security.cipherSeed\', \')(\d+)(?=\'\))/', $seed, $contents);

					$file->write($contents);
					Cache::write('QaInstallDatabase', 'success'); // fix: Security keys change
					$this->redirect('/install/user_account');
				} else {
					$this->Session->setFlash(__t('Could not dump database.'), 'default', 'error');
				}
			} else {
				$this->Session->setFlash(__t('Could not write database.php file.'), 'default', 'error');
			}
		}
	}

/**
 * Step 4, User account
 *
 * @return void
 */
	public function user_account() {
		if (Cache::read('QaInstallDatabase') == 'success' ||
			$this->__stepSuccess(array('license', 'server_test', 'database'), true)
		) {
			$this->__stepSuccess('license');
			$this->__stepSuccess('server_test');
			$this->__stepSuccess('database');

			Cache::delete('QaInstallDatabase');
		} else {
			$this->redirect('/install/license');
		}

		if (isset($this->data['User'])) {
			$this->loadModel('User.User');
			$data = $this->data;
			$data['User']['status'] = 1;
			$data['Role']['Role'] = array(1);

			if ($this->User->save($data)) {
				$this->__stepSuccess('user_account');
				$this->redirect('/install/finish');
			} else {
				$errors = '';

				foreach ($this->User->validationErrors as $field => $error) {
					$errors .= "<b>{$field}:</b> {$error[0]}<br/>";
				}

				$this->Session->setFlash(
					'<b>' . __t('Could not create new user, please try again.') . "</b><br/>" .
					$errors
				, 'default', 'error');
			}
		}
	}

/**
 * Step 5, Finish
 *
 * @return void
 */
	public function finish() {
		if (!$this->__stepSuccess(array('license', 'server_test', 'database', 'user_account'), true)) {
			$this->redirect('/install/license');
		}

		App::import('Utility', 'File');

		$file = new File(ROOT . DS . 'Config' . DS . 'install', true);

		if ($file->write(time())) {
			$this->__stepSuccess('finish');
			$this->Session->delete('QaInstall');
			CakeSession::write('Config.language', 'eng');
			clearCache('', '');
			$this->redirect('/admin');
		} else {
			$this->Session->setFlash(__t("Could not write 'install' file. Check file/folder permissions and refresh this page."), 'default', 'error');
		}
	}

	private function __writeDatabaseFile($data) {
		App::import('Utility', 'File');

		if (!copy(APP . 'Config' . DS . 'database.php.install', ROOT . DS . 'Config' . DS . 'database.php')) {
			return false;
		}

		$file = new File(ROOT . DS . 'Config' . DS . 'database.php', true);
		$dbSettings = $file->read();
		$dbSettings = str_replace(
			array(
				'{db_datasource}',
				'{db_persistent}',
				'{db_host}',
				'{db_login}',
				'{db_password}',
				'{db_database}',
				'{db_prefix}'
			),
			array(
				$data['datasource'],
				($data['persistent'] ? 'true' : 'false'),
				$data['host'],
				$data['login'],
				$data['password'],
				$data['database'],
				$data['prefix']
			),
			$dbSettings
		);

		$r = $file->write($dbSettings);
		$file->close();

		return $r;
	}

	private function __removeDatabaseFile() {
		@unlink(ROOT . DS . 'Config' . DS . 'database.php');
	}

	private function __stepSuccess($step, $check = false) {
		if (!$check) {
			return $this->Session->write("QaInstall.{$step}", 'success');
		}

		if (is_array($step)) {
			foreach ($step as $s) {
				if (!$this->Session->check("QaInstall.{$s}")) {
					return false;
				}
			}

			return true;
		} else {
			return $this->Session->check("QaInstall.{$step}");
		}

		return false;
	}
}