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
namespace Installer\Shell\Task;

use Cake\Console\Shell;
use QuickApps\Core\Plugin;
use QuickApps\Event\HookAwareTrait;

/**
 * Task for switching site's theme.
 *
 */
class ThemeActivationTask extends Shell
{

    use HookAwareTrait;
    use ListenerHandlerTrait;

    /**
     * Removes the welcome message.
     *
     * @return void
     */
    public function startup()
    {
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
            ->description(__d('installer', "Changes site's theme."))
            ->addOption('theme', [
                'short' => 't',
                'help' => __d('system', 'Name of the theme to change to.'),
            ])
            ->addOption('no-callbacks', [
                'short' => 'c',
                'help' => __d('installer', 'Theme events will not be trigged.'),
                'boolean' => true,
                'default' => false,
            ]);
        return $parser;
    }

    /**
     * Switch site's theme.
     *
     * @return void
     */
    public function main()
    {
        if (empty($this->params['theme'])) {
            $this->err(__d('installer', 'You must provide a theme.'));
            return false;
        }

        if (!Plugin::exists($this->params['theme'])) {
            $this->err(__d('installer', 'Theme "{0}" was not found.', $this->params['theme']));
            return false;
        }

        $plugin = Plugin::get($this->params['theme']);
        if (!$plugin->isTheme) {
            $this->err(__d('installer', '"{0}" is not a theme.', $plugin->human_name));
            return false;
        }

        if (in_array($this->params['theme'], [option('front_theme'), option('back_theme')])) {
            $this->err(__d('installer', 'Theme "{0}" is already active.', $plugin->human_name));
            return false;
        }

        // MENTAL NOTE: As theme is "inactive" its listeners are not attached to the
        // system, so we need to manually attach them in order to trigger callbacks.
        if (!$this->params['no-callbacks']) {
            $this->_attachListeners("{$plugin->path}/src/Event");

            try {
                $event = $this->trigger("Plugin.{$plugin->name}.beforeActivate");
                if ($event->isStopped() || $event->result === false) {
                    $this->err(__d('installer', 'Task was explicitly rejected by the theme.'));
                    $this->_detachListeners();
                    return false;
                }
            } catch (\Exception $ex) {
                $this->err(__d('installer', 'Internal error, theme did not respond to "beforeActivate" callback properly.'));
                $this->_detachListeners();
                return false;
            }
        }

        if (isset($plugin->composer['extra']['admin']) && $plugin->composer['extra']['admin']) {
            $prefix = 'back_';
            $previousTheme = option('back_theme');
        } else {
            $prefix = 'front_';
            $previousTheme = option('front_theme');
        }

        $this->loadModel('System.Options');
        if ($this->Options->update("{$prefix}theme", $this->params['theme'])) {
            $this->_copyBlockPositions($this->params['theme'], $previousTheme);
        } else {
            $this->err(__d('installer', 'Internal error, the option "{0}" could not be persisted on database.', "{$prefix}theme"));
            $this->_detachListeners();
            return false;
        }

        if (!$this->params['no-callbacks']) {
            try {
                $this->trigger("Plugin.{$plugin->name}.afterActivate");
            } catch (\Exception $e) {
                $this->err(__d('installer', 'Theme did not respond to "afterActivate" callback.'));
            }
        }

        return true;
    }

    /**
     * If $src has any region in common with $dst this method will make
     * $dst have these blocks in the same regions as $src.
     *
     * @param string $dst Destination theme name
     * @param string $src Source theme name
     * @return void
     */
    protected function _copyBlockPositions($dst, $src)
    {
        $this->loadModel('Block.BlockRegions');
        $dstTheme = Plugin::get($dst);
        $newRegions = isset($dstTheme->composer['extra']['regions']) ? array_keys($dstTheme->composer['extra']['regions']) : [];

        if (empty($newRegions)) {
            return;
        }

        $existingPositions = $this->BlockRegions
            ->find()
            ->where(['theme' => $src])
            ->all();
        foreach ($existingPositions as $position) {
            if (in_array($position->region, $newRegions)) {
                $newPosition = $this->BlockRegions->newEntity([
                    'block_id' => $position->block_id,
                    'theme' => $dstTheme->name,
                    'region' => $position->region,
                    'ordering' => $position->ordering,
                ]);
                $this->BlockRegions->save($newPosition);
            }
        }
    }
}
