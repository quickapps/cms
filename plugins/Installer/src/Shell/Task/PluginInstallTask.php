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
namespace Installer\Shell\Task;

use Cake\Console\Shell;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Network\Http\Client;
use Cake\Validation\Validation;
use QuickApps\Core\Package\PluginPackage;
use QuickApps\Core\Package\Rule\RuleChecker;
use QuickApps\Core\Plugin;
use QuickApps\Event\HookAwareTrait;
use User\Utility\AcoManager;

/**
 * Plugin installer.
 *
 * @property \System\Model\Table\PluginsTable $Plugins
 * @property \System\Model\Table\OptionsTable $Options
 */
class PluginInstallTask extends Shell
{

    use HookAwareTrait;
    use ListenerHandlerTrait;

    /**
     * List of option names added during installation. Used to rollback.
     *
     * @var array
     */
    protected $_addedOptions = [];

    /**
     * Flag that indicates the source package is a ZIP file.
     */
    const TYPE_ZIP = 'zip';

    /**
     * Flag that indicates the source package is a URL.
     */
    const TYPE_URL = 'url';

    /**
     * Flag that indicates the source package is a directory.
     */
    const TYPE_DIR = 'dir';

    /**
     * Contains tasks to load and instantiate.
     *
     * @var array
     */
    public $tasks = [
        'Installer.PluginToggle',
    ];

    /**
     * Path to package's extracted directory.
     *
     * @var string
     */
    protected $_workingDir = null;

    /**
     * The type of the package's source.
     *
     * @var string
     */
    protected $_sourceType = null;

    /**
     * Represents the plugins being installed.
     *
     * @var array
     */
    protected $_plugin = [
        'name' => '',
        'packageName' => '',
        'type' => '',
        'composer' => [],
    ];

    /**
     * Removes the welcome message.
     *
     * @return void
     */
    public function startup()
    {
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->description(__d('installer', 'Install a new plugin or theme.'))
            ->addOption('source', [
                'short' => 's',
                'help' => __d('system', 'Either a full path within filesystem to a ZIP file, or path to a directory representing an extracted ZIP file, or an URL from where download plugin package.'),
            ])
            ->addOption('theme', [
                'short' => 't',
                'help' => __d('installer', 'Indicates that the plugin being installed should be treated as a theme.'),
                'boolean' => true,
                'default' => false,
            ])
            ->addOption('activate', [
                'short' => 'a',
                'help' => __d('installer', 'Enables the plugin after installation.'),
                'boolean' => true,
                'default' => false,
            ])
            ->addOption('no-callbacks', [
                'short' => 'c',
                'help' => __d('installer', 'Plugin events will not be trigged.'),
                'boolean' => true,
                'default' => false,
            ]);
        return $parser;
    }

    /**
     * Task main method.
     *
     * @return bool
     */
    public function main()
    {
        $connection = ConnectionManager::get('default');
        $result = $connection->transactional(function ($conn) {
            try {
                $result = $this->_runTransactional();
            } catch (\Exception $ex) {
                $this->err(__d('install', 'Something went wrong. Details: {0}', $ex->getMessage()));
                $result = false;
            }

            if (!$result) {
                $this->_rollbackCopyPackage();
                $this->_reset();
            }

            return $result;
        });

        // ensure snapshot
        snapshot();
        return $result;
    }

    /**
     * Runs installation logic inside a safe transactional thread. This prevent
     * DB inconsistencies on install failure.
     *
     * @return bool True on success, false otherwise
     */
    protected function _runTransactional()
    {
        // to avoid any possible issue
        snapshot();

        if (!$this->_init()) {
            return $this->_reset();
        }

        if (!$this->params['no-callbacks']) {
            // "before" events occurs even before plugins is moved to its destination
            $this->_attachListeners($this->_plugin['name'], "{$this->_workingDir}/");
            try {
                $event = $this->trigger("Plugin.{$this->_plugin['name']}.beforeInstall");
                if ($event->isStopped() || $event->result === false) {
                    $this->err(__d('installer', 'Task was explicitly rejected by the {type, select, theme{theme} other{plugin}}.', ['type' => $this->_plugin['type']]));
                    return $this->_reset();
                }
            } catch (\Exception $ex) {
                $this->err(__d('installer', 'Internal error, {type, select, theme{theme} other{plugin}} did not respond to "beforeInstall" callback correctly.', ['type' => $this->_plugin['type']]));
                return $this->_reset();
            }
        }

        $this->loadModel('System.Plugins');
        $entity = $this->Plugins->newEntity([
            'name' => $this->_plugin['name'],
            'package' => $this->_plugin['packageName'],
            'settings' => [],
            'status' => 0,
            'ordering' => 0,
        ], ['validate' => false]);

        // do not move this lines
        if (!$this->_copyPackage()) {
            return $this->_reset();
        }

        if (!$this->_addOptions()) {
            $this->_rollbackCopyPackage();
            return $this->_reset();
        }

        if (!$this->Plugins->save($entity)) {
            $this->_rollbackCopyPackage();
            return $this->_reset();
        }

        // hold these values as _finish() erases them
        $pluginName = $this->_plugin['name'];
        $pluginType = $this->_plugin['type'];
        $this->_finish();

        if ($this->params['activate']) {
            $this->dispatchShell("Installer.plugins toggle -p {$pluginName} -s enable");
        }

        if (!$this->params['no-callbacks']) {
            try {
                $event = $this->trigger("Plugin.{$pluginName}.afterInstall");
            } catch (\Exception $ex) {
                $this->err(__d('installer', '{type, select, theme{The theme} other{The plugin}} was installed but some errors occur.', ['type' => $pluginType]));
            }
        }

        return true;
    }

    /**
     * Deletes the directory that was copied to its final destination.
     *
     * @return void
     */
    protected function _rollbackCopyPackage()
    {
        if (!empty($this->_plugin['name'])) {
            $destinationPath = normalizePath(SITE_ROOT . "/plugins/{$this->_plugin['name']}/");
            if (is_dir($destinationPath) && is_writable($destinationPath)) {
                $dst = new Folder($destinationPath);
                $dst->delete();
            }
        }
    }

    /**
     * Register on "options" table any declared option is plugin's "composer.json".
     *
     * @return bool True on success, false otherwise
     */
    protected function _addOptions()
    {
        if (!empty($this->_plugin['composer']['extra']['options'])) {
            $this->loadModel('System.Options');
            foreach ($this->_plugin['composer']['extra']['options'] as $index => $option) {
                if (empty($option['name'])) {
                    $this->err(__d('installer', 'Unable to register {type, select, theme{theme} other{plugin}} option, invalid option #{index}.', ['type' => $this->_plugin['type'], 'index' => $index]));
                    return false;
                }

                $entity = $this->Options->newEntity([
                    'name' => $option['name'],
                    'value' => !empty($option['value']) ? $option['value'] : null,
                    'autoload' => isset($option['autoload']) ? (bool)$option['autoload'] : false,
                ]);
                $errors = $entity->errors();

                if (empty($errors)) {
                    if (!$this->Options->save($entity)) {
                        $this->err(__d('installer', 'Unable to register option "{name}".', ['name' => $option['name']]));
                        return false;
                    }
                    $this->_addedOptions[] = $option['name'];
                } else {
                    $this->err(__d('installer', 'Some errors were found while trying to register {type, select, theme{theme} other{plugin}} options values, see below:', ['type' => $this->_plugin['type']]));
                    foreach ($errors as $error) {
                        $this->err(__d('installer', '  - {error}', ['error' => $error]));
                    }
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Discards the install operation. Restores this class's status
     * to its initial state.
     *
     * ### Usage:
     *
     * ```php
     * return $this->_reset();
     * ```
     *
     * @return bool False always
     */
    protected function _reset()
    {
        if ($this->_sourceType !== self::TYPE_DIR && $this->_workingDir) {
            $source = new Folder($this->_workingDir);
            $source->delete();
        }

        $this->_addedOptions = [];
        $this->_workingDir = null;
        $this->_sourceType = null;
        $this->_plugin = [
            'name' => '',
            'packageName' => '',
            'type' => '',
            'composer' => [],
        ];

        Plugin::dropCache();
        $this->_detachListeners();
        return false;
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
        Plugin::dropCache();
        // trick: makes plugin visible to AcoManager
        $classLoader->addPsr4($this->_plugin['name'] . "\\", normalizePath(SITE_ROOT . "/plugins/{$this->_plugin['name']}/src"), true);
        AcoManager::buildAcos($this->_plugin['name']);
        $this->_reset();
    }

    /**
     * Copies the extracted package to its final destination.
     *
     * @param bool $clearDestination Set to true to delete the destination directory
     *  if already exists. Defaults to false; an error will occur if destination
     *  already exists. Useful for upgrade tasks
     * @return bool True on success
     */
    protected function _copyPackage($clearDestination = false)
    {
        $source = new Folder($this->_workingDir);
        $destinationPath = normalizePath(SITE_ROOT . "/plugins/{$this->_plugin['name']}/");

        // allow to install from destination folder
        if ($this->_workingDir === $destinationPath) {
            return true;
        }

        if (!$clearDestination && is_readable($destinationPath)) {
            $this->err(__d('installer', 'Destination directory already exists, please delete manually this directory: {0}', $destinationPath));
            return false;
        } elseif ($clearDestination && is_readable($destinationPath)) {
            $destination = new Folder($destinationPath);
            if (!$destination->delete()) {
                $this->err(__d('installer', 'Destination directory could not be cleared, please check write permissions: {0}', $destinationPath));
                return false;
            }
        }

        if ($source->copy(['to' => $destinationPath])) {
            return true;
        }

        $this->err(__d('installer', 'Error when moving package content.'));
        return false;
    }

    /**
     * Prepares this task and the package to be installed.
     *
     * @return bool True on success
     */
    protected function _init()
    {
        $this->params['source'] = str_replace('"', '', $this->params['source']);

        if (function_exists('ini_set')) {
            ini_set('max_execution_time', 300);
        } elseif (function_exists('set_time_limit')) {
            set_time_limit(300);
        }

        if (is_readable($this->params['source']) && is_dir($this->params['source'])) {
            $this->_sourceType = self::TYPE_DIR;
            return $this->_getFromDirectory();
        } elseif (is_readable($this->params['source']) && !is_dir($this->params['source'])) {
            $this->_sourceType = self::TYPE_ZIP;
            return $this->_getFromFile();
        } elseif (Validation::url($this->params['source'])) {
            $this->_sourceType = self::TYPE_URL;
            return $this->_getFromUrl();
        }

        $this->err(__d('installer', 'Unable to resolve the given source ({source}).', ['source' => $this->params['source']]));
        return false;
    }

    /**
     * Prepares install from given directory.
     *
     * @return bool True on success
     */
    protected function _getFromDirectory()
    {
        $this->_workingDir = normalizePath(realpath($this->params['source']) . '/');
        return $this->_validateContent();
    }

    /**
     * Prepares install from ZIP file.
     *
     * @return bool True on success
     */
    protected function _getFromFile()
    {
        $file = new File($this->params['source']);
        if ($this->_unzip($file->pwd())) {
            return $this->_validateContent();
        }

        $this->err(__d('installer', 'Unable to extract the package.'));
        return false;
    }

    /**
     * Prepares install from remote URL.
     *
     * @return bool True on success
     */
    protected function _getFromUrl()
    {
        try {
            $http = new Client(['redirect' => 3]); // follow up to 3 redirections
            $response = $http->get($this->params['source'], [], [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest'
                ]
            ]);
        } catch (\Exception $e) {
            $response = false;
        }

        if ($response && $response->isOk()) {
            $this->params['source'] = TMP . substr(md5($this->params['source']), 24) . '.zip';
            $file = new File($this->params['source']);
            $responseBody = $response->body();

            if (is_readable($file->pwd())) {
                $file->delete();
            }

            if (!empty($responseBody) &&
                $file->create() &&
                $file->write($responseBody, 'w+', true)
            ) {
                $file->close();
                return $this->_getFromFile();
                $this->err(__d('installer', 'Unable to extract the package.'));
                return false;
            }

            $this->err(__d('installer', 'Unable to download the file, check write permission on "{path}" directory.', ['path' => TMP]));
            return false;
        }

        $this->err(__d('installer', 'Could not download the package, no .ZIP file was found at the given URL.'));
        return false;
    }

    /**
     * Extracts the current ZIP package.
     *
     * @param  string $fule Full path to the ZIP package
     * @return bool True on success
     */
    protected function _unzip($file)
    {
        include_once Plugin::classPath('Installer') . 'Lib/pclzip.lib.php';
        $File = new File($file);
        $to = normalizePath($File->folder()->pwd() . '/' . $File->name() . '_unzip/');

        if (is_readable($to)) {
            $folder = new Folder($to);
            $folder->delete();
        } else {
            $folder = new Folder($to, true);
        }

        $PclZip = new \PclZip($file);
        $PclZip->delete(PCLZIP_OPT_BY_EREG, '/__MACOSX/');
        $PclZip->delete(PCLZIP_OPT_BY_EREG, '/\.DS_Store$/');

        if ($PclZip->extract(PCLZIP_OPT_PATH, $to)) {
            list($directories, $files) = $folder->read(false, false, true);
            if (count($directories) === 1 && empty($files)) {
                $container = new Folder($directories[0]);
                $container->move(['to' => $to]);
            }

            $this->_workingDir = $to;
            return true;
        }

        $this->err(__d('installer', 'Unzip error: {error}', ['error' => $PclZip->errorInfo(true)]));
        return false;
    }

    /**
     * Validates the content of working directory.
     *
     * @return bool True on success
     */
    protected function _validateContent()
    {
        if (!$this->_workingDir) {
            return false;
        }

        $errors = [];
        if (!is_readable("{$this->_workingDir}src") || !is_dir("{$this->_workingDir}src")) {
            $errors[] = __d('installer', 'Invalid package, missing "src" directory.');
        }

        if (!is_readable("{$this->_workingDir}composer.json")) {
            $errors[] = __d('installer', 'Invalid package, missing "composer.json" file.');
        } else {
            $jsonErrors = Plugin::validateJson("{$this->_workingDir}composer.json", true);
            if (!empty($jsonErrors)) {
                $errors[] = __d('installer', 'Invalid "composer.json".');
                $errors = array_merge($errors, (array)$jsonErrors);
            } else {
                $json = (new File("{$this->_workingDir}composer.json"))->read();
                $json = json_decode($json, true);
                list(, $pluginName) = packageSplit($json['name'], true);

                if ($this->params['theme'] && !str_ends_with($pluginName, 'Theme')) {
                    $this->err(__d('installer', 'The given package is not a valid theme.'));
                    return false;
                } elseif (!$this->params['theme'] && str_ends_with($pluginName, 'Theme')) {
                    $this->err(__d('installer', 'The given package is not a valid plugin.'));
                    return false;
                }

                $this->_plugin = [
                    'name' => $pluginName,
                    'packageName' => $json['name'],
                    'type' => str_ends_with($pluginName, 'Theme') ? 'theme' : 'plugin',
                    'composer' => $json,
                ];

                if (Plugin::exists($this->_plugin['name'])) {
                    $exists = Plugin::get($this->_plugin['name']);
                    if ($exists->status) {
                        $errors[] = __d('installer', '{type, select, theme{The theme} other{The plugin}} "{name}" is already installed.', ['type' => $this->_plugin['type'], 'name' => $this->_plugin['name']]);
                    } else {
                        $errors[] = __d('installer', '{type, select, theme{The theme} other{The plugin}} "{name}" is already installed but disabled, maybe you want try to enable it?.', ['type' => $this->_plugin['type'], 'name' => $this->_plugin['name']]);
                    }
                }

                if ($this->_plugin['type'] == 'theme' &&
                    !is_readable("{$this->_workingDir}webroot/screenshot.png")
                ) {
                    $errors[] = __d('installer', 'Missing "screenshot.png" file.');
                }

                if (isset($json['require'])) {
                    $checker = new RuleChecker($json['require']);
                    if (!$checker->check()) {
                        $errors[] = __d('installer', '{type, select, theme{The theme} other{The plugin}} "{name}" depends on other packages, plugins or libraries that were not found: {depends}', ['type' => $this->_plugin['type'], 'name' => $this->_plugin['name'], 'depends' => $checker->fail(true)]);
                    }
                }
            }
        }

        if (!file_exists(SITE_ROOT . '/plugins') ||
            !is_dir(SITE_ROOT . '/plugins') ||
            !is_writable(SITE_ROOT . '/plugins')
        ) {
            $errors[] = __d('installer', 'Write permissions required for directory: {path}.', ['path' => SITE_ROOT . '/plugins/']);
        }

        foreach ($errors as $message) {
            $this->err($message);
        }

        return empty($errors);
    }
}
