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
namespace Installer\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\Utility\File;
use Cake\Utility\Folder;
use Cake\Utility\Inflector;
use Cake\Validation\Validation;
use QuickApps\Core\Plugin;
use QuickApps\Utility\HookTrait;

/**
 * Plugin installer handler.
 *
 * ## Usage:
 *
 *     $success = $this->Installer
 *         ->download('http://example.com/package.zip')
 *         ->install();
 *
 * If something went wrong during the installation you can get all error messages
 * using the `errors()` method:
 *
 *     $arrayOfMessages = $this->Installer->errors();
 */
class InstallerComponent extends Component {

	use HookTrait;

/**
 * ZIP package source within server file system.
 * 
 * @var string
 */
	protected $_packagePath = null;

/**
 * Full path to the unzip'ed package.
 * 
 * @var string
 */
	protected $_extractedPath = null;

/**
 * Holds the name of the plugins being installed.
 * 
 * @var string
 */
	protected $_pluginName = null;

/**
 * Full path to plugin's directory: SITE_ROOT . 'plugins/<PluginName>'.
 * 
 * @var string
 */
	protected $_pluginPath = null;

/**
 * Plugin's composer.json in array format.
 * 
 * @var array
 */
	protected $_pluginJson = [];

/**
 * Holds all errors messages during installation.
 *
 * @var array
 */
	protected $_errors = [];

/**
 * Instance of PluginsTable.
 *
 * @var array
 */
	public $Plugins = null;

/**
 * List of accepted mime-types for package files.
 *
 * @var array
 */
	protected $_validMimes = [
		'application/zip',
		'application/x-zip-compressed',
		'multipart/x-zip',
    ];

/**
 * Constructor.
 *
 * @param \Cake\Controller\ComponentRegistry $collection A ComponentRegistry
 * for this component
 * @param array $config
 * @return void
 */
	public function __construct(ComponentRegistry $collection, array $config = array()) {
		$this->Plugins = TableRegistry::get('System.Plugins');
		parent::__construct($collection, $config);
	}

/**
 * Gets a ZIP package at the given file system path.
 * 
 * @param string $filePath A valid path
 * @return \System\Controller\Component\InstallerComponent This instance
 */
	public function file($filePath) {
		if (file_exists($filePath) && !is_dir($filePath)) {
			if ($this->_validateZip($filePath)) {
				$this->_packagePath = $filePath;
			}
		} else {
			$this->_error(__d('system', 'The file <code>{0}</code> was not found.', $filePath));
		}
		return $this;
	}

/**
 * Uploads a ZIP package to the server.
 * 
 * @param mixed $package ZIP coming from form POST request.
 * @return \System\Controller\Component\InstallerComponent This instance
 */
	public function upload($package) {
		// TODO: Installer::upload()
	}

/**
 * Downloads package from given URL.
 * 
 * @param string $package A valid URL
 * @return \System\Controller\Component\InstallerComponent This instance
 */
	public function download($package) {
		if (!Validation::url($package)) {
			$this->_error(__d('system', 'Invalid URL given.'));
			return $this;
		}

		try {
			$http = new Client(['redirect' => 3]);
			$response = $http->get($package, [], ['headers' => ['X-Requested-With' => 'XMLHttpRequest']]);
		} catch(\Exception $e) {
			$response = false;
		}
		
		if ($response && $response->isOk()) {
			$fileName = substr(md5($package), 24) . '.zip';
			$file = new File(TMP . $fileName);

			if (file_exists($file->pwd())) {
				$file->delete();
			}

			if (
				!empty($response->body()) &&
				$file->create() &&
				$file->write($response->body(), 'w+', true)
			) {
				$file->close();
				if ($this->_validateZip($file->pwd())) {
					$this->_packagePath = $file->pwd();
				}
			} else {
				$this->_error(__d('system', 'Unable to download the file, check write permission on <code>{0}</code> directory.', TMP));
			}
		} else {
			$this->_error(__d('system', 'Could not download the package, no .ZIP file was found at the given URL.'));
		}

		return $this;
	}

/**
 * Starts the installation process of the uploaded/downloaded package.
 *
 * This method should me used after a package has been uploaded or downloaded.
 * to the server.
 *
 * This method automatically detects if the plugin being installed is a Theme.
 *
 * @return bool True on success, false otherwise
 */
	public function install() {
		if (!empty($this->_errors)) {
			$this->_rollback();
			return false;
		}

		if (!$this->_packagePath) {
			$this->_rollback();
			$this->_error(__d('system', 'You must set a package before try to install.'));
			return false;
		}

		if (!$this->_unzip()) {
			$this->_rollback();
			return false;
		}

		if (!$this->_validateContent()) {
			$this->_rollback();
			return false;
		}

		if (!$this->_movePackage()) {
			$this->_rollback();
			return false;
		}

		$this->_loadEvents();
		$beforeInstallEvent = $this->invoke("Plugin.{$this->_pluginName}.beforeInstall", $this);

		if ($beforeInstallEvent->isStopped() || $beforeInstallEvent->result === false) {
			$this->_rollback();
			return false;
		}

		$entity = $this->Plugins->newEntity([
			'name' => $this->_pluginName,
			'package' => $this->_pluginJson['name'],
			'settings' => [],
			'status' => true,
			'ordering' => 0,
		]);

		if (!$this->Plugins->save($entity, ['atomic' => true])) {
			$this->_rollback();
			return false;
		}

		$this->invoke("Plugin.{$this->_pluginName}.afterInstall", $this);
		$this->_finish();
		return true;
	}

/**
 * Gets a list of all errors during installation.
 * 
 * @return array
 */
	public function errors() {
		return $this->_errors;
	}

/**
 * Registers a new error message.
 * 
 * @param string $message
 * @return void
 */
	protected function _error($message) {
		$this->_errors[] = $message;
	}

/**
 * After installation is completed.
 * 
 * @return void
 */
	protected function _finish() {
		$this->_rollback();
	}

/**
 * Discards the install operation. Restores this class's status
 * to previous its initial state.
 * 
 * @return void
 */
	protected function _rollback() {
		if ($this->_extractedPath) {
			$source = new Folder($this->_extractedPath);
			$source->delete();
		}

		if ($this->_packagePath) {
			$zip = new File($this->_packagePath);
			$zip->delete();
		}

		$this->_packagePath =
		$this->_extractedPath =
		$this->_pluginName =
		$this->_pluginPath =
		$this->_pluginJson = null;
	}

/**
 * Extracts the current ZIP package.
 *
 * @return bool TRUE on success, FALSE otherwise
 */
	protected function _unzip() {
		include_once Plugin::classPath('System') . 'Lib/pclzip.lib.php';
		$file = new File($this->_packagePath);
		$to = $file->folder()->pwd() . '/' . $file->name() . '_unzip/';

		if (file_exists($to)) {
			$folder = new Folder($to);
			$folder->delete();
		} else {
			$folder = new Folder($to, true);
		}

		$PclZip = new \PclZip($this->_packagePath);
		$PclZip->delete(PCLZIP_OPT_BY_EREG, '/__MACOSX/');
		$PclZip->delete(PCLZIP_OPT_BY_EREG, '/\.DS_Store$/');

		if (!$PclZip->extract(PCLZIP_OPT_PATH, $to)) {
			$this->_error(__d('system', 'Unzip error: {0}', $PclZip->errorInfo(true)));
			return false;
		} else {
			list($directories, $files) = $folder->read(false, false, true);
			if (count($directories) === 1) {
				$container = new Folder($directories[0]);
				$container->move(['to' => $to]);
			}
			$this->_extractedPath = $to;
		}

		return true;
	}

/**
 * Validates ZIP before extracting it.
 *
 * @param string Full path to the ZIP file
 * @return bool TRUE on success, FALSE otherwise
 */
	protected function _validateZip($filePath) {
		$errors = [];
		if (str_ends_with(strtolower($filePath), '.zip')) {
			$file = new File($filePath);
			$mime = $file->mime();
			if (!$mime || !in_array($mime, $this->_validMimes)) {
				$errors[] = __d('system', 'Invalid file, the given file is not in ZIP format.');
				$file->delete();
			}
			$file->close();
		} else {
			$errors[] = __d('system', 'Invalid file extension, package file must be a ZIP file.');
		}

		foreach ($errors as $message) {
			$this->_error($message);
		}

		return empty($errors);
	}

/**
 * Validates the content of the extracted ZIP.
 * 
 * @return bool TRUE on success, FALSE otherwise
 */
	protected function _validateContent() {
		if (!$this->_extractedPath) {
			return false;
		}

		$errors = [];
		if (!file_exists($this->_extractedPath . 'src') || !is_dir($this->_extractedPath . 'src')) {
			$errors[] = __d('system', 'Invalid package, missing "src" directory.');
		}

		if (!file_exists($this->_extractedPath . 'composer.json')) {
			$errors[] = __d('system', 'Invalid package, missing file "composer.json".');
		} else {
			$jsonErrors = Plugin::validateJson($this->_extractedPath . 'composer.json', true);
			if (!empty($jsonErrors)) {
				$errors[] = __d('system', 'Invalid "composer.json".');
				foreach ($jsonErrors as $e) {
					$errors[] = $e;
				}
			} else {
				$json = (new File($this->_extractedPath . 'composer.json'))->read();
				$json = json_decode($json, true);
				$this->_pluginName = pluginName($json['name']);

				if (str_ends_with($this->_pluginName, 'Theme')) {
					if (!file_exists("{$this->_extractedPath}webroot/screenshot.png")) {
						$errors[] = __d('system', 'Missing "screenshot.png" file.');
					} else {
						$screenshot = new File("{$this->_extractedPath}webroot/screenshot.png");
						if ($screenshot->mime() !== 'image/png') {
							$errors[] = __d('system', 'Invalid "screenshot.png" file, it is not a PNG file.');
						}
					}
				}

				$exists = $this->Plugins
					->find()
					->where(['name' => $this->_pluginName])
					->count();

				if ($exists) {
					$errors[] = __d('system', 'Plugin "{0}" is already installed.', $this->_pluginName);
				}

				// dependencies, the fun part
				if (!Plugin::checkDependency($json)) {
					$required = [];
					foreach ($json['require'] as $p => $v) {
						$p = pluginName($p);
						$p = $p === '__QUICKAPPS__' ? 'QuickApps CMS' : $p;
						$p = $p === '__PHP__' ? 'PHP' : $p;
						$required[] = "{$p} ({$v})";
					}
					$errors[] = __d('system', 'Plugin "{0}" depends on other packages that were not found: {0}', $this->_pluginName, implode(', ', $required));
				}
			}
		}

		if (
			!file_exists(SITE_ROOT . '/plugins') ||
			!is_dir(SITE_ROOT . '/plugins') ||
			!is_writable(SITE_ROOT . '/plugins')
		) {
			$errors[] = __d('system', 'Write permissions required for directory: {0}.', SITE_ROOT . '/plugins/');
		}

		foreach ($errors as $message) {
			$this->_error($message);
		}
		$this->_pluginJson = $json;
		return empty($errors);
	}

/**
 * Moves the extracted package to its final destination.
 * 
 * @return bool TRUE on success, FALSE otherwise
 */
	protected function _movePackage() {
		$source = new Folder($this->_extractedPath);
		if (file_exists(SITE_ROOT. "/plugins/{$this->_pluginName}/")) {
			$this->_error(__d('system', 'Destination directory already exists, please delete manually this directory: {0}', SITE_ROOT . "/plugins/{$this->_pluginName}/"));
			return false;
		}

		if (!$source->move(['to' => SITE_ROOT. "/plugins/{$this->_pluginName}/"])) {
			$this->_error(__d('system', 'Error when moving package content.'));
			return false;
		}

		$this->_pluginPath = SITE_ROOT. "/plugins/{$this->_pluginName}/";
		return true;
	}

/**
 * Loads and registers plugin's Hook classes so plugins may respond
 * to `beforeInstall`, `afterInstall`, etc.
 * 
 * @return void
 */
	protected function _loadEvents() {
		global $classLoader;

		if (file_exists("{$this->_pluginPath}src/Event")) {
			$EventManager = EventManager::instance();
			$eventsFolder = new Folder("{$this->_pluginPath}src/Event");

			foreach ($eventsFolder->read(false, false, true)[1] as $classPath) {
				$className = preg_replace('/\.php$/i', '', basename($classPath));

				if (str_ends_with($className, 'Hook')) {
					$classLoader->addPsr4('Hook\\', dirname($classPath), true);
					$class = 'Hook\\' . $className;

					if (class_exists($class)) {
						$EventManager->attach(new $class);
					}
				} elseif (str_ends_with($className, 'Hooktag')) {
					$classLoader->addPsr4('Hooktag\\', dirname($classPath), true);
					$class = 'Hooktag\\' . $className;

					if (class_exists($class)) {
						$EventManager->attach(new $class);
					}
				}
			}
		}
	}

}
