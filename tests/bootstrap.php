<?php
/**
 * Constants & paths.
 */
define('DS', DIRECTORY_SEPARATOR);
define('SITE_ROOT', __DIR__ . DS . 'TestSite');
define('QA_CORE', dirname(__DIR__));
define('ROOT', dirname(__DIR__));

if (file_exists('../../../vendor/')) {
    define('VENDOR_INCLUDE_PATH', realpath('../../../vendor/') . DS);
} else {
    define('VENDOR_INCLUDE_PATH', realpath('vendor/') . DS);
}

define('APP_DIR', 'src');
define('WEBROOT_DIR', 'webroot');
define('APP', ROOT . DS . APP_DIR . DS);
define('CONFIG', ROOT . DS . 'config' . DS);
define('WWW_ROOT', SITE_ROOT . DS . WEBROOT_DIR . DS);
define('TESTS', ROOT . DS . 'tests' . DS);
define('TMP', SITE_ROOT . DS . 'tmp' . DS);
define('LOGS', SITE_ROOT . DS . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);
define('CAKE_CORE_INCLUDE_PATH', VENDOR_INCLUDE_PATH . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);

$classLoader = require VENDOR_INCLUDE_PATH . 'autoload.php';

use Cake\Cache\Cache;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use QuickApps\View\ViewModeRegistry;

/**
 * Overwrites core's snapshot() function and emulates its real behavior.
 *
 * @return void
 */
function snapshot()
{
    $snapshot = [
        'version' => '2.0.0-dev',
        'content_types' => ['article', 'page'],
        'plugins' => [],
        'options' => [
            'back_theme' => 'BackendTheme',
            'default_language' => 'en_US',
            'front_theme' => 'FrontendTheme',
            'site_description' => 'Open Source CMS built on CakePHP 3.0',
            'site_email' => 'demo@email.com',
            'site_maintenance' => '0',
            'site_maintenance_ip' => '192.168.0.1',
            'site_maintenance_message' => 'We sincerely apologize for the inconvenience.<br/>Our site is currently undergoing scheduled maintenance and upgrades, but will return shortly.<br/>Thanks you for your patience.',
            'site_contents_home' => '5',
            'site_slogan' => 'Open Source CMS built on CakePHP 3.0',
            'site_title' => 'My QuickApps CMS Site',
            'url_locale_prefix' => '0',
        ],
        'languages' => [
            'en_US' => [
                'name' => 'English',
                'locale' => 'en_US',
                'code' => 'en',
                'country' => 'US',
                'direction' => 'ltr',
                'icon' => 'us.gif',
            ]
        ]
    ];

    foreach (App::path('Plugin') as $pluginsPath) {
        if (!is_dir($pluginsPath)) {
            continue;
        }
        $dir = new DirectoryIterator($pluginsPath);
        foreach ($dir as $path) {
            if ($path->isDir() && !$path->isDot()) {
                $name = $path->getBaseName();
                $pluginPath = normalizePath($pluginsPath . $name);
                $humanName = Inflector::humanize(Inflector::underscore($name));
                $package = 'quickapps-cms/' . str_replace('_', '-', Inflector::underscore($name));
                $isTheme = (bool)preg_match('/Theme$/', $name);
                $isCore = strpos($pluginPath, 'cms' . DS . 'plugins') !== false;
                $eventsPath = "{$pluginPath}/src/Event/";
                $aspectsPath = "{$pluginPath}/src/Aspect/";
                $helpFiles = glob($pluginPath . '/src/Template/Element/Help/help*.ctp');
                $status = true; // all plugins enabled
                $aspects = [];
                $eventListeners = [];

                foreach ([$aspectsPath, $eventsPath] as $path) {
                    if (is_dir($path)) {
                        $Folder = new Folder($path);
                        foreach ($Folder->read(false, false, true)[1] as $classFile) {
                            $className = basename(preg_replace('/\.php$/', '', $classFile));
                            $subspace = $path == $aspectsPath ? 'Aspect' : 'Event';
                            $varname = $path == $aspectsPath ? 'aspects' : 'eventListeners';
                            $namespace = "{$name}\\{$subspace}\\";
                            ${$varname}[] = $namespace . $className;
                        }
                    }
                }

                $snapshot['plugins'][$name] = [
                    'name' => $name,
                    'humanName' => $humanName,
                    'package' => $package,
                    'isTheme' => $isTheme,
                    'isCore' => $isCore,
                    'hasHelp' => !empty($helpFiles),
                    'hasSettings' => is_readable($pluginPath . '/src/Template/Element/settings.ctp'),
                    'eventListeners' => $eventListeners,
                    'aspects' => $aspects,
                    'status' => $status,
                    'path' => $pluginPath,
                ];
            }
        }
    }

    $snapshot['plugins'] = Hash::sort($snapshot['plugins'], '{s}.name', 'asc');
    Configure::write('QuickApps', $snapshot);
    Configure::dump('snapshot', 'QuickApps', ['QuickApps']);
}

/**
 * Mocks an user session.
 *
 * Used for testing restricted areas of the app.
 *
 * @return array Auth session
 */
function mockUserSession()
{
    $session = [
        'Auth' => [
            'User' => [
                'id' => 1,
                'name' => 'QuickApps CMS',
                'username' => 'admin',
                'email' => 'demo@example.com',
                'web' => 'http://www.quickappscms.org',
                'locale' => 'en_US',
                'public_profile' => false,
                'public_email' => false,
                'token' => '',
                'status' => true,
                'last_login' => null,
                'created' => null,
                'roles' => [
                    0 => [
                        'id' => 1,
                        'slug' => 'administrator',
                        'name' => 'Administrator',
                    ]
                ],
                '_fields' => []
            ]
        ]
    ];

    return $session;
}

/**
 * Clear any previous information.
 */
try {
    $snapshot = new File(TMP . 'snapshot.php');
    $snapshot->delete();
    Cache::clear(false, '_cake_model_');
    Cache::clear(false, '_cake_core_');
} catch (\Exception $ex) {
    // fail
}

/**
 * Carbon test now()
 */
Carbon\Carbon::setTestNow(Carbon\Carbon::now());

/**
 * Include QuickAppsCMS's bootstrap
 */
require QA_CORE . '/config/bootstrap.php';
