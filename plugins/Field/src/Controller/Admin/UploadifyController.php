<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Field\Controller\Admin;

use Cake\Core\Configure;
use Cake\Filesystem\File;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Field\Controller\AppController;
use Field\Utility\FileToolbox;
use QuickApps\Core\Plugin;

/**
 * Handles file uploading by "File Field Handler".
 *
 */
class UploadifyController extends AppController {

/**
 * Uploads a new file for the given FileField instance.
 * 
 * @param string $instance_slug Machine-name of the instance, a.k.a "slug"
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException When invalid slug is given,
 *  or when upload process could not be completed
 */
	public function upload($instance_slug) {
		$this->loadModel('Field.FieldInstances');
		$field = $this->FieldInstances
			->find()
			->where(['slug' => $instance_slug])
			->limit(1)
			->first();

		$this->response->httpCodes([
			400 => __d('field', 'Invalid field instance.'),
			406 => __d('field', 'Invalid file extension.'),
			500 => __d('field', 'Error while uploading the file.'),
		]);

		if (!$field) {
			throw new NotFoundException(__d('field', 'Invalid field instance.'), 400);
		}

		require_once Plugin::classPath('Field') . 'Lib/class.upload.php';
		$uploader = new \Upload($this->request->data['Filedata']);

		if (!empty($field->settings['extensions'])) {
			$exts = explode(',', $field->settings['extensions']);
			$exts = array_map('trim', $exts);
			$exts = array_map('strtolower', $exts);

			if (!in_array(strtolower($uploader->file_src_name_ext), $exts)) {
				throw new NotFoundException(__d('field', 'Invalid file extension.'), 422);
			}
		}

		$response = '';
		$uploader->file_overwrite = false;
		$uploader->no_script = false;
		$folder = normalizePath(WWW_ROOT . "/files/{$field->settings['upload_folder']}/", DS);
		$url = normalizePath("/files/{$field->settings['upload_folder']}/", '/');

		$uploader->process($folder);
		if ($uploader->processed) {
			$response = json_encode([
				'file_url' => Router::url($url . $uploader->file_dst_name, true),
				'file_size' => FileToolbox::bytesToSize($uploader->file_src_size),
				'file_name' => $uploader->file_dst_name,
				'mime_icon' => FileToolbox::fileIcon($uploader->file_src_mime),
			]);
		} else {
			throw new NotFoundException(__d('field', 'File upload error: {0}', $uploader->error), 500);
		}

		$this->layout = 'ajax';
		$this->set(compact('response'));
	}

/**
 * Deletes a file for the given FileField instance.
 *
 * File name must be passes as `file` GET parameter.
 * 
 * @param string $instance_slug Machine-name of the instance, a.k.a "slug"
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException When invalid slug is given
 */
	public function delete($instance_slug) {
		$this->loadModel('Field.FieldInstances');
		$field = $this->FieldInstances
			->find()
			->where(['slug' => $instance_slug])
			->limit(1)
			->first();

		if ($field && !empty($this->request->query['file'])) {
			$file = normalizePath(WWW_ROOT . "/files/{$field->settings['upload_folder']}/{$this->request->query['file']}", DS);
			$file = new File($file);
			$file->delete();
		} else {
			throw new NotFoundException(__d('field', 'Invalid field instance or file name.'));
		}

		die;
	}

}
