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
?>

<?php
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Routing\Router;

error_reporting(0);
$pp = PLugin::classPath('Wysiwyg');
require $pp . 'Vendor/elFinderConnector.class.php';
require $pp . 'Vendor/elFinder.class.php';
require $pp . 'Vendor/elFinderVolumeDriver.class.php';
require $pp . 'Vendor/elFinderVolumeLocalFileSystem.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {
	return
		strpos(basename($path), '.') === 0        // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')  // set read+write to false, other (locked+hidden) set to true
		:  null;                                  // else elFinder decide it itself
}

$opts = array(
	'debug' => (Configure::read('debug') > 0),
	'roots' => array(
		array(
			'alias' => __d('wysiwyg', 'Files'),
			'driver' => 'LocalFileSystem',
			'path' => SITE_ROOT . DS . 'webroot' . DS . 'files',
			'URL' => $this->Html->url('/files', true),
			'accessControl' => 'access',
			'tmbCrop' => false,
			'dateFormat' => __d('mediamanager', 'j M Y H:i')
		),
		array(
			'alias' => __d('wysiwyg', 'Themes'),
			'driver' => 'LocalFileSystem',
			'path' => SITE_ROOT . DS . 'Theme' . DS . 'Themed',
			'URL' => Router::url('/admin/wysiwyg/elfinder/get_file/' . base64_encode(SITE_ROOT . DS . 'Themes' . DS . 'Themed') . '/?type=theme&file=', true),
			'accessControl' => 'access',
			'tmbCrop' => false,
			'dateFormat' => __d('mediamanager', 'j M Y H:i')
		),
		array(
			'alias' => __d('wysiwyg', 'Plugins'),
			'driver' => 'LocalFileSystem',
			'path' => SITE_ROOT . DS . 'Plugin',
			'URL' => Router::url('/admin/wysiwyg/elfinder/get_file/' . base64_encode(SITE_ROOT . DS . 'Plugin') . '/?type=plugin&file=', true),
			'accessControl' => 'access',
			'tmbCrop' => false,
			'dateFormat' => __d('wysiwyg', 'j M Y H:i')
		)
	)
);

$connector = new elFinderConnector(new elFinder($opts));
$connector->run();
die();
