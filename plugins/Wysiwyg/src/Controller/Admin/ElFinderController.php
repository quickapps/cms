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
namespace Wysiwyg\Controller\Admin;

use Wysiwyg\Controller\AppController;

/**
 * File manager for elFinder CKeditor Add-on.
 *
 */
class ElFinderController extends AppController {

/**
 * Renders elFinder's UI.
 *
 * @return void
 */
	public function index() {
		$this->layout = 'elfinder';
	}

/**
 * elFinder UI connector.
 *
 * @return void
 */
	public function connector() {
	}

/**
 * Returns the given file.
 *
 * @param string $path Encoded full base directory to the file file name must
 * be passed as a $_GET parameter
 * @return void
 */
	public function getFile($path) {
		$path = base64_decode($path) . DS;
		$file = str_replace('/', DS, $this->request->query['file']);
		$fullPath = str_replace(DS . DS, DS, $path . $file);
		$this->response->file($fullPath);

		return $this->response;
	}

}
