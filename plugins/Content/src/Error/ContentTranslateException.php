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
 * Exception raised when current user is not allowed to TRANSLATE contents of certain
 * type.
 */
class ContentTranslateException extends HttpException
{

    /**
     * Template string that has attributes sprintf()'ed into it.
     *
     * @var string
     */
    protected $_messageTemplate = 'You are not allowed to translate contents of this type (%s).';

    /**
     * Constructor
     *
     * @param int $code Status code, defaults to 401
     */
    public function __construct($message = null, $code = 401)
    {
        parent::__construct($message, $code);
    }
}
