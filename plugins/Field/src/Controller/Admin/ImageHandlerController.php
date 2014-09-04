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

use Cake\Network\Exception\NotFoundException;
use Field\Utility\ImageToolbox;
use QuickApps\Core\Plugin;

/**
 * Handles file uploading by "Image Field Handler".
 *
 */
class ImageHandlerController extends FileHandlerController {

/**
 * {@inheritDoc}
 */
	public function upload($instance_slug, $uploader = null) {
		$field = $this->_getInstance($instance_slug);

		if (!is_object($uploader)) {
			require_once Plugin::classPath('Field') . 'Lib/class.upload.php';
			$uploader = new \upload($this->request->data['Filedata']);
		}

		if (!empty($field->settings['min_width'])) {
			$uploader->image_min_width = $field->settings['min_width'];
		}

		if (!empty($field->settings['min_height'])) {
			$uploader->image_min_height = $field->settings['min_height'];
		}

		if (!empty($field->settings['max_width'])) {
			$uploader->image_max_width = $field->settings['max_width'];
		}

		if (!empty($field->settings['max_height'])) {
			$uploader->image_max_height = $field->settings['max_height'];
		}

		if (!empty($field->settings['min_ratio'])) {
			$uploader->image_min_ratio = $field->settings['min_ratio'];
		}

		if (!empty($field->settings['max_ratio'])) {
			$uploader->image_max_ratio = $field->settings['max_ratio'];
		}

		if (!empty($field->settings['min_pixels'])) {
			$uploader->image_min_pixels = $field->settings['min_pixels'];
		}

		if (!empty($field->settings['max_pixels'])) {
			$uploader->image_max_pixels = $field->settings['max_pixels'];
		}

		$uploader->allowed = 'image/*';
		parent::upload($instance_slug, $uploader);
	}

/**
 * {@inheritDoc}
 */
	public function delete($instance_slug) {
		parent::delete($instance_slug);
		$this->loadModel('Field.FieldInstances');
		$field = $this->FieldInstances
			->find()
			->select(['slug', 'settings'])
			->where(['slug' => $instance_slug])
			->limit(1)
			->first();

		ImageToolbox::deleteThumbnails($this->request->query['file'], WWW_ROOT . "/files/{$field->settings['upload_folder']}/.tmb/");
	}

/**
 * Returns an scaled version of the given file image.
 *
 * The following GET variables must be set on request:
 *
 * - file: The image's file name to scale.
 * - size: A preview size name, sett `ImageToolbox::getPreviews()`
 *
 * If any of these variables is not present an exception will be throw.
 * 
 * @param string $instance_slug Filed instance's machine-name
 * @return void
 */
	public function thumbnail($instance_slug) {
		$this->loadModel('Field.FieldInstances');
		$field = $this->FieldInstances
			->find()
			->where(['slug' => $instance_slug])
			->limit(1)
			->first();

		if (!$field) {
			throw new NotFoundException(__d('field', 'Invalid field instance.'), 400);
		}

		if (empty($this->request->query['file'])) {
			throw new NotFoundException(__d('field', 'Invalid file name.'), 400);
		}

		if (empty($this->request->query['size'])) {
			throw new NotFoundException(__d('field', 'Invalid image size.'), 400);
		}

		$imagePath = normalizePath(WWW_ROOT . "/files/{$field->settings['upload_folder']}/{$this->request->query['file']}");
		$tmb = ImageToolbox::thumbnail($imagePath, $this->request->query['size']);

		if ($tmb) {
			$this->response->file($tmb);
			return $this->response;
		}

		throw new NotFoundException(__d('field', 'Thumbnail could not be found, check write permissions?'), 500);
	}

}
