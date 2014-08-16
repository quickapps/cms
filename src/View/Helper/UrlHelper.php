<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace QuickApps\View\Helper;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Cake\View\Helper;
use Cake\View\Helper\UrlHelper as CakeUrlHelper;
use QuickApps\Core\HookTrait;

/**
 * UrlHelper class for generating urls.
 */
class UrlHelper extends CakeUrlHelper {

	use HookTrait;

/**
 * {@inheritdoc}
 *
 * @param string|array $url Either a relative string url like `/products/view/23` or
 *    an array of URL parameters. Using an array for URLs will allow you to leverage
 *    the reverse routing features of CakePHP.
 * @param bool $full If true, the full base URL will be prepended to the result
 * @return string Full translated URL with base path.
 * @link http://book.cakephp.org/2.0/en/views/helpers.html
 */
	public function build($url = null, $full = false) {
		$this->alter('UrlHelper.build', $url, $full);
		return parent::build($url, $full);
	}

/**
 * {@inheritdoc}
 *
 * @param string|array $path Path string or URL array
 * @param array $options Options array. Possible keys:
 *   `fullBase` Return full URL with domain name
 *   `pathPrefix` Path prefix for relative URLs
 *   `ext` Asset extension to append
 *   `plugin` False value will prevent parsing path as a plugin
 * @return string Generated URL
 */
	public function assetUrl($path, array $options = array()) {
		$this->alter('UrlHelper.assetUrl', $path, $options);
		return parent::assetUrl($path, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $path The file path to timestamp, the path must be inside WWW_ROOT
 * @return string Path with a timestamp added, or not.
 */
	public function assetTimestamp($path) {
		$this->alter('UrlHelper.assetTimestamp', $path);
		return parent::assetTimestamp($path);
	}

/**
 * {@inheritdoc}
 *
 * @param string $file The file to create a webroot path to.
 * @return string Web accessible path to file.
 */
	public function webroot($file) {
		$this->alter('UrlHelper.webroot', $file);
		return parent::webroot($file);
	}

}
