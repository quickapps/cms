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
?>

<?php
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Routing\Router;

//error_reporting(0);
$pp = Plugin::classPath('Wysiwyg');
require $pp . 'Lib/elFinderConnector.class.php';
require $pp . 'Lib/elFinder.class.php';
require $pp . 'Lib/elFinderVolumeDriver.class.php';
require $pp . 'Lib/elFinderVolumeLocalFileSystem.class.php';

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param string $attr attribute name (read|write|locked|hidden)
 * @param string $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume)
{
    if (strpos(basename($path), '.') === 0) {
        return !($attr == 'read' || $attr == 'write');
    }
    if (strpos($path, 'plugins' . DS) !== false) {
        $path = normalizePath($path);
        $parts = explode('plugins' . DS, $path);
        if (!empty($parts[1])) {
            $validPlugins = Plugin::loaded();
            $pluginName = explode(DS, $parts[1])[0];
            if (!in_array($pluginName, $validPlugins)) {
                return !($attr == 'write' || $attr != 'hidden');
            }
        }
    }
    if (!is_dir($path) && strpos($path, 'webroot') === false) {
        return !($attr == 'write' || $attr != 'hidden');
    }
}

$opts = [
    'locale' => 'en_US.UTF-8',
    'debug' => Configure::read('debug'),
    'roots' => [[
        'alias' => __d('wysiwyg', 'Site Files'),
        'driver' => 'LocalFileSystem',
        'path' => SITE_ROOT . '/webroot/files/',
        'URL' => $this->Url->build('/files', true),
        'mimeDetect' => 'internal',
        'tmbPath' => '.tmb',
        'utf8fix' => true,
        'tmbCrop' => false,
        'tmbSize' => 200,
        'acceptedName'    => '/^[^\.].*$/',
        'accessControl' => 'access',
        'dateFormat' => __d('wysiwyg', 'j M Y H:i'),
        'defaults' => ['read' => true, 'write' => true],
    ], [
        'alias' => __d('wysiwyg', 'Plugins'),
        'driver' => 'LocalFileSystem',
        'path' => SITE_ROOT . '/plugins/',
        'URL' => $this->Url->build(['plugin' => 'Wysiwyg', 'controller' => 'finder', 'action' => 'plugin_file', 'file' => '#'], true),
        'mimeDetect' => 'internal',
        'tmbPath' => '.tmb',
        'utf8fix' => true,
        'tmbCrop' => false,
        'tmbSize' => 200,
        'acceptedName'    => '/^[^\.].*$/',
        'accessControl' => 'access',
        'dateFormat' => __d('wysiwyg', 'j M Y H:i'),
        'defaults' => ['read' => true, 'write' => true],
    ]]
];

header('Access-Control-Allow-Origin: *');
$connector = new elFinderConnector(new elFinder($opts), true);
$connector->run();
