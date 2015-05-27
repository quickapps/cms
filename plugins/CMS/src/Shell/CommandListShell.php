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
namespace CMS\Shell;

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
        if (!defined('SITE_ROOT')) {
            return parent::main();
        }

        if (empty($this->params['xml'])) {
            $this->out(__d('cms', '<info>Current Paths:</info>'), 2);
            $this->out(__d('cms', '* QuickApps Core: {path}', ['path' => normalizePath(QUICKAPPS_CORE)]));
            $this->out(__d('cms', '* CakePHP Core:   {path}', ['path' => normalizePath(CORE_PATH)]));
            $this->out(__d('cms', '* Site Path:      {path}', ['path' => normalizePath(SITE_ROOT)]));
            $this->out('');

            $this->out(__d('cms', '<info>Available Shells:</info>'), 2);
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
        if (!defined('SITE_ROOT')) {
            return parent::_asText($shellList);
        }

        foreach ($shellList as $plugin => $commands) {
            sort($commands);
            if ($plugin == 'app') {
                $plugin = 'QUICKAPPS';
            } elseif ($plugin == 'CORE') {
                $plugin = 'CAKEPHP';
            }

            $this->out(__d('cms', '[<info>{0}</info>] {1}', $plugin, implode(', ', $commands)));
        }

        $this->out();
        $this->out(__d('cms', 'To run an QuickAppsCMS or CakePHP command, type <info>"qs shell_name [args]"</info>'));
        $this->out(__d('cms', 'To run a Plugin command, type <info>"qs PluginName.shell_name [args]"</info>'));
        $this->out(__d('cms', 'To get help on a specific command, type <info>"qs shell_name --help"</info>'), 2);
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        if (!defined('SITE_ROOT')) {
            return parent::getOptionParser();
        }

        $parser = parent::getOptionParser();
        $parser
            ->description(__d('cms', 'Get the list of available shells for this QuickAppsCMS installation.'))
            ->addOption('xml', [
                'help' => __d('cms', 'Get the listing as XML.'),
                'boolean' => true,
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
        if (!defined('SITE_ROOT')) {
            return parent::_welcome();
        }

        $this->out();
        $this->out(__d('cms', '<info>Welcome to QuickApps CMS v{0} Console</info>', quickapps('version')));
        $this->hr();
        $this->out(__d('cms', 'Site Title: {0}', option('site_title')));
        $this->hr();
        $this->out();
    }
}
