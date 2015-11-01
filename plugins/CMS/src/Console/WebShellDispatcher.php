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

use Cake\Console\ConsoleIo;
use Cake\Console\ShellDispatcher;
use CMS\Console\WebConsoleInput;
use CMS\Console\WebConsoleOutput;

/**
 * Wrapper for CLI shells.
 *
 * Allows to use CLI shells on HTTP environments. Use with caution as interactive
 * shell may not work properly.
 *
 * ### Usage
 *
 * ```php
 * $success = WebShellDispatcher::run('MyPlugin.MyShell subcommand --arg1 --arg2');
 *
 * if ($success) {
 *     echo "OK";
 * } else {
 *     echo "ERROR, see below:<br />";
 *     echo '<pre>' . WebShellDispatcher::output() . '</pre>';
 * }
 * ```
 */
class WebShellDispatcher extends ShellDispatcher
{

    /**
     * Holds the output of the last shell process.
     *
     * @var string
     */
    protected static $_out = '';

    /**
     * Run the dispatcher.
     *
     * @param string $args Commands to run
     * @param array $extra Extra parameters
     * @return int Result of the shell process. 1 on success, 0 otherwise.
     */
    public static function run($args, $extra = [])
    {
        static::$_out = '';
        $argv = explode(' ', "dummy-shell.php {$args}");
        $dispatcher = new WebShellDispatcher($argv);

        ob_start();
        $response = $dispatcher->dispatch($extra);
        static::$_out = ob_get_clean();
        return (int)($response === 0);
    }

    /**
     * Returns the output result of the last shell process executed using run().
     *
     * @return string
     */
    public static function output()
    {
        return static::$_out;
    }

    /**
     * Create the given shell name, and set the plugin property.
     *
     * @param string $className The class name to instantiate
     * @param string $shortName The plugin-prefixed shell name
     * @return \Cake\Console\Shell A shell instance.
     */
    protected function _createShell($className, $shortName)
    {
        $instance = parent::_createShell($className, $shortName);
        $webIo = new ConsoleIo(
            new WebConsoleOutput(),
            new WebConsoleOutput(),
            new WebConsoleInput()
        );
        $instance->io($webIo);
        return $instance;
    }
}
