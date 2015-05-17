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

use Cake\Console\ConsoleOutput;

/**
 * Wrapper for Cake's ConsoleOutput.
 *
 * Allows to convert CLI prints into web echos commands.
 */
class WebConsoleOutput extends ConsoleOutput
{

    /**
     * Construct the output object.
     *
     * @param string $stream The identifier of the stream to write output to.
     */
    public function __construct($stream = null)
    {
        $this->_output = null;
    }

    /**
     * Prints a message.
     *
     * @param string $message Message to print.
     * @return bool success
     */
    protected function _write($message)
    {
        print $message;
        return strlen($message);
    }
}
