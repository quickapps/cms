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
namespace Content\Error;

use Cake\Network\Exception\HttpException;

/**
 * Exception raised when a content type (content type) is not found.
 */
class ContentTypeNotFoundException extends HttpException
{

    /**
     * Template string that has attributes sprintf()'ed into it.
     *
     * @var string
     */
    protected $_messageTemplate = 'The specified content type (%s) does not exists.';

    /**
     * Constructor
     *
     * @param int $message Status code, defaults to 401
     */
    public function __construct($message = null, $code = 404)
    {
        parent::__construct($message, $code);
    }
}
