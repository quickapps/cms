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
namespace QuickApps\Console;

use Cake\Console\ConsoleInput;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOutput;
use Cake\Console\ShellDispatcher;

/**
 * Wrapper for CLI shell.
 *
 * Allows to use CLI shells on HTTP environments. Use with caution as interactive
 * shell may not work properly.
 */
class WebShellDispatcher extends ShellDispatcher
{

    /**
     * Run the dispatcher
     *
     * @param string $args Commands to run
     * @return string Output buffer of the shell process.
     */
    public static function run($args)
    {
        $argv = explode(' ', "dummy-shell.php {$args}");
        $dispatcher = new WebShellDispatcher($argv);
        return $dispatcher->_dispatch();
    }

    /**
     * Dispatch a request.
     *
     * @return string Output buffer
     * @throws \Cake\Console\Exception\MissingShellMethodException
     */
    protected function _dispatch()
    {
        ob_start();
        $response = parent::_dispatch();
        $out = ob_get_clean();
        return $out;
    }

    /**
     * Create the given shell name, and set the plugin property
     *
     * @param string $className The class name to instantiate
     * @param string $shortName The plugin-prefixed shell name
     * @return \Cake\Console\Shell A shell instance.
     */
    protected function _createShell($className, $shortName)
    {
        $instance = parent::_createShell($className, $shortName);
        $webIo = new ConsoleIo(
            new ConsoleOutput('php://output'),
            new ConsoleOutput('php://output'),
            new ConsoleInput('php://input')
        );
        $instance->io($webIo);
        return $instance;
    }
}
