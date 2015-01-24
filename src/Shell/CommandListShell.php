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
namespace QuickApps\Shell;

use Cake\Shell\CommandListShell as CakeCommandListShell;

/**
 * Shows a list of commands available from the console.
 *
 */
class CommandListShell extends CakeCommandListShell
{

    /**
     * Main function Prints out the list of shells.
     *
     * @return void
     */
    public function main()
    {
        if (empty($this->params['xml'])) {
            $this->out("<info>Current Paths:</info>", 2);
            $this->out("* QuickApps Core: " . rtrim(QUICKAPPS_CORE, DS));
            $this->out("* CakePHP Core:   " . rtrim(CORE_PATH, DS));
            $this->out("* Site Path:      " . rtrim(SITE_ROOT, DS));
            $this->out("");

            $this->out("<info>Available Shells:</info>", 2);
        }

        $shellList = $this->Command->getShellList();
        if (empty($shellList)) {
            return;
        }

        if (empty($this->params['xml'])) {
            $this->_asText($shellList);
        } else {
            $this->_asXml($shellList);
        }
    }

    /**
     * Output text.
     *
     * @param array $shellList The shell list.
     * @return void
     */
    protected function _asText($shellList)
    {
        foreach ($shellList as $plugin => $commands) {
            sort($commands);
            if ($plugin == 'app') {
                $plugin = 'QUICKAPPS';
            } elseif ($plugin == 'CORE') {
                $plugin = 'CAKEPHP';
            }

            $this->out(sprintf('[<info>%s</info>] %s', $plugin, implode(', ', $commands)));
        }

        $this->out();
        $this->out("To run an quickapps or cakephp command, type <info>`qs shell_name [args]`</info>");
        $this->out("To run a plugin command, type <info>`qs Plugin.shell_name [args]`</info>");
        $this->out("To get help on a specific command, type <info>`qs shell_name --help`</info>", 2);
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->description(
            'Get the list of available shells for this QuickApps CMS installation.'
        )->addOption('xml', [
            'help' => 'Get the listing as XML.',
            'boolean' => true
        ]);

        return $parser;
    }

    /**
     * Displays a header for the shell
     *
     * @return void
     */
    protected function _welcome()
    {
        $this->out();
        $this->out(sprintf('<info>Welcome to QuickApps CMS %s Console</info>', 'v' . quickapps('version')));
        $this->hr();
        $this->out(sprintf('Site Title: %s', option('site_title')));
        $this->hr();
        $this->out();
    }
}
