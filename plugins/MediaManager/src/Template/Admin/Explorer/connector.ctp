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

use Cake\Core\Configure;
use CMS\Core\Plugin;

$pluginPath = Plugin::classPath('MediaManager');
require $pluginPath . 'Lib/ElFinder/elFinderConnector.class.php';
require $pluginPath . 'Lib/ElFinder/elFinder.class.php';
require $pluginPath . 'Lib/ElFinder/elFinderVolumeDriver.class.php';
require $pluginPath . 'Lib/ElFinder/elFinderVolumeLocalFileSystem.class.php';

/**
 * Simple function to demonstrate how to control file access using "accessControl"
 * callback. This method will disable accessing files/folders starting from  '.'
 * (dot)
 *
 * @param string $attr Attribute name (read|write|locked|hidden)
 * @param string $path File path relative to volume root directory started with
 *  directory separator
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
        'alias' => __d('media_manager', 'Site Files'),
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
        'dateFormat' => __d('media_manager', 'j M Y H:i'),
        'defaults' => ['read' => true, 'write' => true],
        'icon' => $this->Url->build('/media_manager/img/volume_icon_local.png'),
    ], [
        'alias' => __d('media_manager', 'Site Templates'),
        'driver' => 'LocalFileSystem',
        'path' => SITE_ROOT . '/templates/',
        'mimeDetect' => 'internal',
        'tmbPath' => '.tmb',
        'utf8fix' => true,
        'tmbCrop' => false,
        'tmbSize' => 200,
        'acceptedName'    => '/^[^\.].*$/',
        'dateFormat' => __d('media_manager', 'j M Y H:i'),
        'defaults' => ['read' => true, 'write' => true],
        'icon' => $this->Url->build('/media_manager/img/volume_icon_local.png'),
    ], [
        'alias' => __d('media_manager', 'Plugins'),
        'driver' => 'LocalFileSystem',
        'path' => SITE_ROOT . '/plugins/',
        'URL' => $this->Url->build(['plugin' => 'MediaManager', 'controller' => 'explorer', 'action' => 'plugin_file', 'file' => '#'], true),
        'mimeDetect' => 'internal',
        'tmbPath' => '.tmb',
        'utf8fix' => true,
        'tmbCrop' => false,
        'tmbSize' => 200,
        'acceptedName'    => '/^[^\.].*$/',
        'accessControl' => 'access',
        'dateFormat' => __d('media_manager', 'j M Y H:i'),
        'defaults' => ['read' => true, 'write' => true],
        'icon' => $this->Url->build('/media_manager/img/volume_icon_local.png'),
    ]]
];

header('Access-Control-Allow-Origin: *');
$connector = new elFinderConnector(new elFinder($opts), true);
$connector->run();
