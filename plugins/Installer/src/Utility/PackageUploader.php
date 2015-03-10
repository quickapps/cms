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
namespace Installer\Utility;

use Cake\Filesystem\File;

/**
 * Used to upload plugin packages to the server.
 *
 */
class PackageUploader
{

    /**
     * List of error messages.
     *
     * @var array
     */
    protected $_errors = [];

    /**
     * Information from file's POST form.
     *
     * @var array
     */
    protected $_package = [];

    /**
     * Full path to uploaded ZIP package.
     *
     * @var string
     */
    protected $_dst = '';

    /**
     * Constructor.
     *
     * @param array $package Incoming data from POST
     */
    public function __construct($package)
    {
        $this->_package = $package;
    }

    /**
     * Uploads a ZIP package to the server.
     *
     * @return bool True on success
     */
    public function upload()
    {
        if (!isset($this->_package['tmp_name']) || !file_exists($this->_package['tmp_name'])) {
            $this->_error(__d('installer', 'Invalid package.'));
            return false;
        } elseif (!isset($this->_package['name']) || !str_ends_with(strtolower($this->_package['name']), '.zip')) {
            $this->_error(__d('installer', 'Invalid package format, it is not a ZIP package.'));
            return false;
        } else {
            $dst = normalizePath(TMP . $this->_package['name']);
            if (file_exists($dst)) {
                $file = new File($dst);
                $file->delete();
            }

            if (move_uploaded_file($this->_package['tmp_name'], $dst)) {
                $this->_dst = $dst;
                return true;
            }
        }

        $this->_error(__d('installer', 'Package could not be uploaded, please check write permissions on /tmp directory.'));
        return false;
    }

    /**
     * Gets the full path to the uploaded package.
     *
     * @return string
     */
    public function dst()
    {
        return $this->_dst;
    }

    /**
     * Returns a list of all error messages.
     *
     * @return array
     */
    public function errors()
    {
        return $this->_errors;
    }

    /**
     * Registers an error message.
     *
     * @param string $message The message
     * @return void
     */
    protected function _error($message)
    {
        $this->_errors[] = $message;
    }
}
