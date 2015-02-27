<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Field\Controller;

use Cake\Network\Exception\NotFoundException;
use Field\Utility\ImageToolbox;
use QuickApps\Core\Plugin;

/**
 * Handles file uploading by "Image Field Handler".
 *
 * @property \Field\Model\Table\FieldInstancesTable $FieldInstances
 */
class ImageHandlerController extends FileHandlerController
{

    /**
     * {@inheritDoc}
     */
    public function upload($instanceSlug, $uploader = null)
    {
        $instance = $this->_getInstance($instanceSlug);

        if (!is_object($uploader)) {
            require_once Plugin::classPath('Field') . 'Lib/class.upload.php';
            $uploader = new \upload($this->request->data['Filedata']);
        }

        if (!empty($instance->settings['min_width'])) {
            $uploader->image_min_width = $instance->settings['min_width'];
        }

        if (!empty($instance->settings['min_height'])) {
            $uploader->image_min_height = $instance->settings['min_height'];
        }

        if (!empty($instance->settings['max_width'])) {
            $uploader->image_max_width = $instance->settings['max_width'];
        }

        if (!empty($instance->settings['max_height'])) {
            $uploader->image_max_height = $instance->settings['max_height'];
        }

        if (!empty($instance->settings['min_ratio'])) {
            $uploader->image_min_ratio = $instance->settings['min_ratio'];
        }

        if (!empty($instance->settings['max_ratio'])) {
            $uploader->image_max_ratio = $instance->settings['max_ratio'];
        }

        if (!empty($instance->settings['min_pixels'])) {
            $uploader->image_min_pixels = $instance->settings['min_pixels'];
        }

        if (!empty($instance->settings['max_pixels'])) {
            $uploader->image_max_pixels = $instance->settings['max_pixels'];
        }

        $uploader->allowed = 'image/*';
        parent::upload($instanceSlug, $uploader);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($instanceSlug)
    {
        parent::delete($instanceSlug);
        $this->loadModel('Field.FieldInstances');
        $instance = $this->FieldInstances
            ->find()
            ->select(['slug', 'settings'])
            ->where(['slug' => $instanceSlug])
            ->limit(1)
            ->first();

        ImageToolbox::deleteThumbnails(WWW_ROOT . "/files/{$instance->settings['upload_folder']}/{$this->request->query['file']}");
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
     * @param string $instanceSlug Filed instance's machine-name
     * @return \Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When field instance
     *  is not found.
     */
    public function thumbnail($instanceSlug)
    {
        $this->loadModel('Field.FieldInstances');
        $instance = $this->FieldInstances
            ->find()
            ->where(['slug' => $instanceSlug])
            ->limit(1)
            ->first();

        if (!$instance) {
            throw new NotFoundException(__d('field', 'Invalid field instance.'), 400);
        }

        if (empty($this->request->query['file'])) {
            throw new NotFoundException(__d('field', 'Invalid file name.'), 400);
        }

        if (empty($this->request->query['size'])) {
            throw new NotFoundException(__d('field', 'Invalid image size.'), 400);
        }

        $imagePath = normalizePath(WWW_ROOT . "/files/{$instance->settings['upload_folder']}/{$this->request->query['file']}");
        $tmb = ImageToolbox::thumbnail($imagePath, $this->request->query['size']);

        if ($tmb !== false) {
            $this->response->file($tmb);
            return $this->response;
        }

        throw new NotFoundException(__d('field', 'Thumbnail could not be found, check write permissions?'), 500);
    }
}
