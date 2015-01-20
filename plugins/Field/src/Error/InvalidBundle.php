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
namespace Field\Error;

use Cake\Core\Exception\Exception;

/**
 * Exception raised when an incorrect bundle was given.
 *
 */
class InvalidBundle extends Exception
{

	/**
	 * Template string that has attributes sprintf()'ed into it.
	 *
	 * @var string
	 */
    protected $_messageTemplate = 'Invalid bundle.';
}
