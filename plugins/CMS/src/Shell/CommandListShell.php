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
        if (empty($this->params['xml'])) {
            $this->out(__('<info>Current Paths:</info>'), 2);
            $this->out(__('* QuickApps Core: {path}', ['path' => normalizePath(QUICKAPPS_CORE)]));
            $this->out(__('* CakePHP Core:   {path}', ['path' => normalizePath(CORE_PATH)]));
            $this->out(__('* Site Path:      {path}', ['path' => normalizePath(SITE_ROOT)]));
            $this->out('');

            $this->out(__('<info>Available Shells:</info>'), 2);
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

            $this->out(__('[<info>{0}</info>] {1}', $plugin, implode(', ', $commands)));
        }

        $this->out();
        $this->out(__('To run an QuickAppsCMS or CakePHP command, type <info>"qs shell_name [args]"</info>'));
        $this->out(__('To run a Plugin command, type <info>"qs PluginName.shell_name [args]"</info>'));
        $this->out(__('To get help on a specific command, type <info>"qs shell_name --help"</info>'), 2);
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->description(__('Get the list of available shells for this QuickAppsCMS installation.'))
            ->addOption('xml', [
                'help' => __('Get the listing as XML.'),
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
        $this->out();
        $this->out(__('<info>Welcome to QuickApps CMS v{0} Console</info>', quickapps('version')));
        $this->hr();
        $this->out(__('Site Title: {0}', option('site_title')));
        $this->hr();
        $this->out();
    }
}
