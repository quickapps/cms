<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Installer\Task;

use Cake\Event\EventManager;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Network\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Validation\Validation;
use QuickApps\Core\Plugin;
use User\Utility\AcoManager;

/**
 * Represents a single install task.
 *
 * ## Basic Usage:
 *
 * Using `InstallerComponent` on any controller:
 *
 *     $task = $this->Installer
 *         ->task('install', ['activate' => true, 'isTheme' => true])
 *         ->download('http://example.com/theme-package.zip'); // or: ->file(), ->upload()
 *
 *     if ($task->run()) {
 *         $this->Flash->success('Installed!');
 *     } else {
 *         $errors = $task->errors();
 *     }
 */
class InstallTask extends BaseTask
{

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
     * Full path to plugin's directory: SITE_ROOT . '/plugins/<PluginName>'.
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
     * List of accepted mime-types for package files.
     *
     * @var array
     */
    protected $_validMimes = [
        'application/zip',
        'application/x-zip-compressed',
        'multipart/x-zip',
        'application/gzip',
    ];

    /**
     * Default config.
     *
     * These are merged with user-provided configuration when the task is used.
     *
     * ### Valid options are:
     *
     * - `callbacks`: Set to true to fire `beforeInstall`, `afterInstall` events.
     * - `activate`: Activate plugin after installation ? This will trigger
     *   `beforeEnable` and `afterDisable` callbacks if `callbacks` is set to true.
     * - `packageType`: "plugin" indicates whether the package must be a plugin or
     *    not, set to "theme" to indicate that package must be a theme. False
     *    will not check the package type. Defaults to false.
     * - `validateMime`: Whether or not to check package's mime-type, default to
     *    true.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'callbacks' => true,
        'activate' => true,
        'packageType' => false,
        'validateMime' => true,
    ];

    /**
     * Invoked before "start()".
     *
     * @return void
     */
    public function init()
    {
        if (!$this->_packagePath) {
            $this->_rollback();
            $this->error(__d('installer', 'No valid package was found.'));
        } elseif (!$this->_unzip()) {
            $this->_rollback();
        } elseif (!$this->_validateContent()) {
            $this->_rollback();
        } elseif ($this->_exists()) {
            $this->_rollback();
            $this->error(__d('installer', 'This plugin is already installed.'));
        }

        // it will be stopped on start()
        if (!$this->plugin()) {
            $this->plugin('__DUMMY__');
        }
    }

    /**
     * Starts the installation process of the uploaded/downloaded package.
     *
     * This method should me used after a package has been uploaded or
     * downloaded to the server. An error will be registered otherwise.
     *
     * ### Events triggered:
     *
     * - `beforeInstall`: Before plugins is registered on DB and before plugin's
     *    directory is moved to "/plugins"
     * - `afterInstall`: After plugins was registered in DB and after plugin's
     *    directory was moved to "/plugins"
     *
     * @return bool True on success, false otherwise
     */
    public function start()
    {
        if (!empty($this->_errors)) {
            $this->_rollback();
            return false;
        }

        if ($this->config('callbacks')) {
            // "before" events occurs even before plugins is moved to its destination
            $this->attachListeners("{$this->_extractedPath}src/Event");
            try {
                $beforeInstallEvent = $this->trigger('Plugin.' . $this->plugin() . '.beforeInstall');
                if ($beforeInstallEvent->isStopped() || $beforeInstallEvent->result === false) {
                    $this->error(__d('installer', 'Task was explicitly rejected by the plugin.'));
                    $this->_rollback();
                    return false;
                }
            } catch (\Exception $e) {
                $this->error(__d('installer', 'Internal error, plugin did not respond to "beforeInstall" callback correctly.'));
                $this->_rollback();
                return false;
            }
        }

        if (!$this->_movePackage()) {
            $this->_rollback();
            return false;
        }

        $this->loadModel('System.Plugins');
        $entity = $this->Plugins->newEntity([
            'name' => $this->plugin(),
            'package' => $this->_pluginJson['name'],
            'settings' => [],
            'status' => 0,
            'ordering' => 0,
        ]);

        if (!$this->Plugins->save($entity, ['atomic' => true])) {
            $this->_rollback();
            return false;
        }

        if ($this->config('callbacks')) {
            try {
                $this->trigger('Plugin.' . $this->plugin() . '.afterInstall');
            } catch (\Exception $e) {
                $this->error(__d('installer', 'Plugin was installed but some errors occur.'));
            }
        }

        $activate = (bool)$this->config('activate');
        $pluginName = $this->plugin();
        $this->_finish();

        if ($activate) {
            $newTask = $this
                ->newTask('toggle')
                ->enable($pluginName);

            if (!$newTask->run()) {
                $this->error(__d('installer', 'Plugin was installed but it could not be activated after installation.'));
                $this->error($newTask->errors());
            }
        }

        return true;
    }

    /**
     * Gets a ZIP package at the given file system path.
     *
     * @param string $filePath A valid path
     * @return \Installer\Task\InstallTask This instance
     */
    public function file($filePath)
    {
        if (file_exists($filePath) && !is_dir($filePath)) {
            if ($this->_validateZip($filePath)) {
                $this->_packagePath = $filePath;
            }
        } else {
            $this->error(__d('installer', 'The file <code>{0}</code> was not found.', $filePath));
        }
        return $this;
    }

    /**
     * Uploads a ZIP package to the server.
     *
     * @param mixed $package ZIP coming from the form POST request
     * @return $this
     */
    public function upload($package)
    {
        if (!isset($package['tmp_name']) || !file_exists($package['tmp_name'])) {
            $this->error(__d('installer', 'Invalid package.'));
        } elseif (!isset($package['type']) ||
            !in_array($package['type'], array_merge($this->_validMimes, ['application/octet-stream'])) ||
            !isset($package['name']) ||
            !str_ends_with(strtolower($package['name']), '.zip')
        ) {
            $this->error(__d('installer', 'Invalid package format, it is not a ZIP package.'));
        } else {
            $dst = TMP . $package['name'];
            if (file_exists($dst)) {
                $file = new File($dst);
                $file->delete();
            }

            if (!move_uploaded_file($package['tmp_name'], $dst)) {
                $this->error(__d('installer', 'Package could not be uploaded, please check write permissions on /tmp directory.'));
            } else {
                $this->_packagePath = $dst;
            }
        }
        return $this;
    }

    /**
     * Downloads package from given URL.
     *
     * @param string $url A valid URL
     * @return $this
     */
    public function download($url)
    {
        if (!Validation::url($url)) {
            $this->error(__d('installer', 'Invalid URL given.'));
            return $this;
        }

        try {
            $http = new Client(['redirect' => 3]); // follow up to 3 redirections
            $response = $http->get($url, [], ['headers' => ['X-Requested-With' => 'XMLHttpRequest']]);
        } catch (\Exception $e) {
            $response = false;
        }

        if ($response && $response->isOk()) {
            $fileName = substr(md5($url), 24) . '.zip';
            $file = new File(TMP . $fileName);
            $responseBody = $response->body();

            if (file_exists($file->pwd())) {
                $file->delete();
            }

            if (!empty($responseBody) &&
                $file->create() &&
                $file->write($responseBody, 'w+', true)
            ) {
                $file->close();
                if ($this->_validateZip($file->pwd())) {
                    $this->_packagePath = $file->pwd();
                }
            } else {
                $this->error(__d('installer', 'Unable to download the file, check write permission on <code>{0}</code> directory.', TMP));
            }
        } else {
            $this->error(__d('installer', 'Could not download the package, no .ZIP file was found at the given URL.'));
        }

        return $this;
    }

    /**
     * After installation is completed.
     *
     * @return void
     */
    protected function _finish()
    {
        global $classLoader; // composer's class loader instance
        snapshot();
        // trick: makes plugin visible to AcoManager
        $classLoader->addPsr4($this->plugin() . "\\", normalizePath(SITE_ROOT . '/plugins/' . $this->plugin() . '/src'), true);
        AcoManager::buildAcos($this->plugin());
        $this->_rollback();
    }

    /**
     * Discards the install operation. Restores this class's status
     * to its initial state.
     *
     * @return void
     */
    protected function _rollback()
    {
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

        $this->detachListeners();
    }

    /**
     * Extracts the current ZIP package.
     *
     * @return bool True on success, false otherwise
     */
    protected function _unzip()
    {
        include_once Plugin::classPath('Installer') . 'Lib/pclzip.lib.php';
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
            $this->error(__d('installer', 'Unzip error: {0}', $PclZip->errorInfo(true)));
            return false;
        } else {
            list($directories, $files) = $folder->read(false, false, true);
            if (count($directories) === 1 && empty($files)) {
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
     * @param string $filePath Full path to the ZIP file
     * @return bool True on success, false otherwise
     */
    protected function _validateZip($filePath)
    {
        $errors = [];
        if (str_ends_with(strtolower($filePath), '.zip')) {
            if ($this->config('validateMime')) {
                $file = new File($filePath);
                $mime = $file->mime();

                if (!$mime || !in_array($mime, $this->_validMimes)) {
                    $errors[] = __d('installer', 'Invalid file, the given file is not in ZIP format.');
                    $file->delete();
                }
                $file->close();
            }
        } else {
            $errors[] = __d('installer', 'Invalid file extension, package file must be a ZIP file.');
        }

        foreach ($errors as $message) {
            $this->error($message);
        }

        return empty($errors);
    }

    /**
     * Validates the content of the extracted ZIP.
     *
     * @return bool True on success, false otherwise
     */
    protected function _validateContent()
    {
        if (!$this->_extractedPath) {
            return false;
        }

        $errors = [];
        if (!file_exists($this->_extractedPath . 'src') || !is_dir($this->_extractedPath . 'src')) {
            $errors[] = __d('installer', 'Invalid package, missing "src" directory.');
        }

        if (!file_exists($this->_extractedPath . 'composer.json')) {
            $errors[] = __d('installer', 'Invalid package, missing file "composer.json".');
        } else {
            $jsonErrors = Plugin::validateJson($this->_extractedPath . 'composer.json', true);
            if (!empty($jsonErrors)) {
                $errors[] = __d('installer', 'Invalid "composer.json".');
                foreach ($jsonErrors as $e) {
                    $errors[] = $e;
                }
            } else {
                $json = (new File($this->_extractedPath . 'composer.json'))->read();
                $json = json_decode($json, true);
                $this->plugin(pluginName($json['name']));
                $this->_pluginJson = $json;

                if ($this->config('packageType') === 'theme' && !str_ends_with($this->plugin(), 'Theme')) {
                    $this->error(__d('installer', 'The given package is not a valid theme.'));
                    return false;
                } elseif ($this->config('packageType') === 'plugin' && str_ends_with($this->plugin(), 'Theme')) {
                    $this->error(__d('installer', 'The given package is not a valid plugin.'));
                    return false;
                }

                if (str_ends_with($this->plugin(), 'Theme')) {
                    if (!file_exists("{$this->_extractedPath}webroot/screenshot.png")) {
                        $errors[] = __d('installer', 'Missing "screenshot.png" file.');
                        false;
                    } else {
                        $screenshot = new File("{$this->_extractedPath}webroot/screenshot.png");
                        if ($screenshot->mime() !== 'image/png') {
                            $errors[] = __d('installer', 'Invalid "screenshot.png" file, it is not a PNG file.');
                            false;
                        }
                    }
                }

                // dependencies: the fun part
                if (!Plugin::checkDependency($json)) {
                    $required = [];
                    foreach ($json['require'] as $p => $v) {
                        $p = pluginName($p);
                        $p = $p === '__QUICKAPPS__' ? 'QuickApps CMS' : $p;
                        $p = $p === '__PHP__' ? 'PHP' : $p;
                        $p = $p === '__CAKEPHP__' ? 'CakePHP' : $p;
                        $required[] = "{$p} ({$v})";
                    }
                    $errors[] = __d('installer', 'Plugin "{0}" depends on other packages that were not found: {0}', $this->plugin(), implode(', ', $required));
                }
            }
        }

        if (!file_exists(SITE_ROOT . '/plugins') ||
            !is_dir(SITE_ROOT . '/plugins') ||
            !is_writable(SITE_ROOT . '/plugins')
        ) {
            $errors[] = __d('installer', 'Write permissions required for directory: {0}.', SITE_ROOT . '/plugins/');
        }

        foreach ($errors as $message) {
            $this->error($message);
        }

        return empty($errors);
    }

    /**
     * Check if the plugins is already installed or not.
     *
     * @return bool True if plugins is installed.
     */
    protected function _exists()
    {
        try {
            $info = Plugin::info($this->plugin(), true);
        } catch (\Exception $e) {
            $info = [];
        }

        return !empty($info);
    }

    /**
     * Moves the extracted package to its final destination.
     *
     * @param bool $clearDestination Set to true to delete the destination directory if
     * already exists. Defaults to false, an error will occur if destination already exists
     * @return bool True on success, false otherwise
     */
    protected function _movePackage($clearDestination = false)
    {
        $source = new Folder($this->_extractedPath);
        $destinationPath = SITE_ROOT . '/plugins/' . $this->plugin() . '/';
        if (!$clearDestination && file_exists($destinationPath)) {
            $this->error(__d('installer', 'Destination directory already exists, please delete manually this directory: {0}', $destinationPath));
            return false;
        } elseif ($clearDestination && file_exists($destinationPath)) {
            $destination = new Folder($destinationPath);
            if (!$destination->delete()) {
                $this->error(__d('installer', 'Destination directory could not be cleared, please check write permissions: {0}', $destinationPath));
            }
        }

        if (!$source->move(['to' => $destinationPath])) {
            $this->error(__d('installer', 'Error when moving package content.'));
            return false;
        }

        $this->_pluginPath = $destinationPath;
        return true;
    }
}
