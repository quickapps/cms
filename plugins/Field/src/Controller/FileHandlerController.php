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
namespace Field\Controller;

use Cake\Filesystem\File;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Field\Utility\FileToolbox;
use QuickApps\Core\Plugin;

/**
 * Handles file uploading by "File Field Handler".
 *
 */
class FileHandlerController extends AppController
{

/**
 * Uploads a new file for the given FileField instance.
 *
 * @param string $instanceSlug Machine-name of the instance, a.k.a "slug"
 * @param \upload $uploader Instance of uploader class to use, useful when
 *  extending this controller
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException When invalid slug is given,
 *  or when upload process could not be completed
 */
    public function upload($instanceSlug, $uploader = null)
    {
        $field = $this->_getInstance($instanceSlug);

        if (!is_object($uploader)) {
            require_once Plugin::classPath('Field') . 'Lib/class.upload.php';
            $uploader = new \upload($this->request->data['Filedata']);
        }

        if (!empty($field->settings['extensions'])) {
            $exts = explode(',', $field->settings['extensions']);
            $exts = array_map('trim', $exts);
            $exts = array_map('strtolower', $exts);

            if (!in_array(strtolower($uploader->file_src_name_ext), $exts)) {
                $this->_error(__d('field', 'Invalid file extension.'), 501);
            }
        }

        $response = '';
        $uploader->file_overwrite = false;
        $folder = normalizePath(WWW_ROOT . "/files/{$field->settings['upload_folder']}/");
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
            $this->_error(__d('field', 'File upload error, details: {0}', $uploader->error), 502);
        }

        $this->layout = 'ajax';
        $this->set(compact('response'));
    }

/**
 * Deletes a file for the given FileField instance.
 *
 * File name must be passes as `file` GET parameter.
 *
 * @param string $instanceSlug Machine-name of the instance, a.k.a "slug"
 * @return void
 * @throws \Cake\Network\Exception\NotFoundException When invalid slug is given
 */
    public function delete($instanceSlug)
    {
        $this->loadModel('Field.FieldInstances');
        $field = $this->FieldInstances
            ->find()
            ->where(['slug' => $instanceSlug])
            ->limit(1)
            ->first();

        if ($field && !empty($this->request->query['file'])) {
            $file = normalizePath(WWW_ROOT . "/files/{$field->settings['upload_folder']}/{$this->request->query['file']}", DS);
            $file = new File($file);
            $file->delete();
        } else {
            $this->_error(__d('field', 'Invalid field instance or file name.'), 503);
        }

        $response = '';
        $this->layout = 'ajax';
        $this->set(compact('response'));
    }

/**
 * Get field instance information.
 *
 * @param string $slug Filed instance slug
 * @return \Field\Model\Entity\FieldInstance
 * @throws \Cake\Network\Exception\NotFoundException When no instance could be found
 */
    protected function _getInstance($slug)
    {
        $this->loadModel('Field.FieldInstances');
        $field = $this->FieldInstances
            ->find()
            ->where(['slug' => $slug])
            ->limit(1)
            ->first();

        if (!$field) {
            $this->_error(__d('field', 'Invalid field instance.'), 504);
        }

        return $field;
    }

/**
 * Sends a JSON message error.
 *
 * @param string $message The message
 * @param mixed $code A unique code identifier for this message
 * @return void Stops scripts execution
 */
    protected function _error($message, $code)
    {
        header("HTTP/1.0 {$code} {$message}");
        echo $message;
        exit(0);
    }
}
