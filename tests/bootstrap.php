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
define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);
define('CAKE_CORE_INCLUDE_PATH', VENDOR_INCLUDE_PATH . 'cakephp' . DS . 'cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);

$classLoader = require VENDOR_INCLUDE_PATH . 'autoload.php';

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use QuickApps\View\ViewModeRegistry;
use \DirectoryIterator;

/**
 * Overwrites core's snapshot() function and emulates its real behavior.
 * 
 * @return void
 */
function snapshot() {
    $snapshot = [
        'version' => '2.0',
        'node_types' => ['article', 'page'],
        'plugins' => [],
        'options' => [
            'back_theme' => 'BackendTheme',
            'default_language' => 'en-us',
            'front_theme' => 'FrontendTheme',
            'site_description' => 'Open Source CMS built on CakePHP 3.0',
            'site_email' => 'demo@email.com',
            'site_maintenance' => '0',
            'site_maintenance_ip' => '192.168.0.1',
            'site_maintenance_message' => 'We sincerely apologize for the inconvenience.<br/>Our site is currently undergoing scheduled maintenance and upgrades, but will return shortly.<br/>Thanks you for your patience.',
            'site_nodes_home' => '5',
            'site_slogan' => 'Open Source CMS built on CakePHP 3.0',
            'site_title' => 'My QuickApps CMS Site',
            'url_locale_prefix' => '1',
        ],
        'languages' => [
            'en-us' => [
                'name' => 'English',
                'code' => 'en-us',
                'iso' => 'en',
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
                $status = true;
                $eventListeners = [];

                if (is_dir($eventsPath)) {
                    $Folder = new Folder($eventsPath);
                    foreach ($Folder->read(false, false, true)[1] as $classFile) {
                        $className = basename(preg_replace('/\.php$/', '', $classFile));
                        $namespace = "{$name}\Event\\";
                        $eventListeners[$namespace . $className] = [
                            'namespace' => $namespace,
                            'path' => dirname($classFile),
                        ];
                    }
                }

                $snapshot['plugins'][$name] = [
                    'name' => $name,
                    'human_name' => $humanName,
                    'package' => $package,
                    'isTheme' => $isTheme,
                    'isCore' => $isCore,
                    'hasHelp' => file_exists($pluginPath . '/src/Template/Element/Help/help.ctp'),
                    'hasSettings' => file_exists($pluginPath . '/src/Template/Element/settings.ctp'),
                    'eventListeners' => $eventListeners,
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
function mockUserSession() {
    $session = [
        'Auth' => [
            'User' => [
                'id' => 1,
                'name' => 'QuickApps CMS',
                'username' => 'admin',
                'email' => 'chris@quickapps.es',
                'web' => 'http://quickapps.es',
                'locale' => 'en-us',
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

require QA_CORE . '/config/bootstrap.php';

ViewModeRegistry::addViewMode([
    'default' => [
        'name' => __d('node', 'Default'),
        'description' => __d('node', 'Default is used as a generic view mode if no other view mode has been defined for your content.'),
    ],
    'teaser' => [
        'name' => __d('node', 'Teaser'),
        'description' => __d('node', 'Teaser is a really short format that is typically used in main the main page, such as "last news", etc.'),
    ],
    'search-result' => [
        'name' => __d('node', 'Search Result'),
        'description' => __d('node', 'Search Result is a short format that is typically used in lists of multiple content items such as search results.'),
    ],
    'rss' => [
        'name' => __d('node', 'RSS'),
        'description' => __d('node', 'RSS is similar to "Search Result" but intended to be used when rendering content as part of a RSS feed list.'),
    ],
    'full' => [
        'name' => __d('node', 'Full'),
        'description' => __d('node', 'Full content is typically used when the content is displayed on its own page.'),
    ],
]);
