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
namespace CMS\Console;

use Cake\Console\ConsoleInput;
use Cake\Routing\Router;

/**
 * Wrapper for Cake's ConsoleInput.
 *
 * Allows to simulate CLI reads over POST data. It uses incoming POST array as a
 * **stack**. Check the `read()` method for further information.
 */
class WebConsoleInput extends ConsoleInput
{

    /**
     * POST data incoming from current request. Indexed by name.
     *
     * ### Example:
     *
     * ```php
     * [
     *     'input1' => 'value1',
     *     'input2' => 'value2',
     *     ...
     *     'inputn' => 'valuen',
     * ]
     * ```
     *
     * @var array
     */
    protected $_postData = [];

    /**
     * Constructor
     *
     * @param string $handle The location of the stream to use as input.
     */
    public function __construct($handle = null)
    {
        $this->_canReadline = false;
        $this->_input = null;
    }

    /**
     * Read a value from POST data.
     *
     * It reads the first input and removes that value from the stack,
     * so consecutive readings are supported as follow:
     *
     * **Incoming POST data array**
     *
     * ```php
     * [
     *     'input1' => 'value1',
     *     'input2' => 'value2',
     *     'input3' => 'value3',
     * ]
     * ```
     *
     * **Reading from POST**:
     *
     * ```php
     * $this->read(); // returns "value1"
     * $this->read(); // returns "value2"
     * $this->read(); // returns "value3"
     * $this->read(); // returns false
     * ```
     *
     * @return mixed The value from POST data
     */
    public function read()
    {
        $request = Router::getRequest();
        if (!empty($request) && empty($this->_postData)) {
            $this->_postData = (array)$request->data();
        }

        if (!empty($this->_postData)) {
            $keys = array_keys($this->_postData);
            $first = array_shift($keys);
            $value = $this->_postData[$first];
            unset($this->_postData[$first]);
            return $value;
        }

        return false;
    }
}
