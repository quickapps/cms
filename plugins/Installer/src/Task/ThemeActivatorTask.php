<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Installer\Task;

use Cake\Filesystem\Folder;
use QuickApps\Core\Plugin;

/**
 * Represents a single toggle task.
 *
 * Allows to enable or disable a plugin.
 *
 * ## Usage Examples:
 *
 * Using `InstallerComponent` on any controller:
 *
 *     $task = $this->Installer
 *         ->task('activate_theme')
 *         ->activate('DarkOceanTheme');
 *
 *     // or:
 *     $task = $this->Installer
 *         ->task('activate_theme', ['theme' => 'DarkOceanTheme'])
 *         ->enable();
 *
 *     if ($task->run()) {
 *         $this->Flash->success('Enabled!');
 *     } else {
 *         $errors = $task->errors();
 *     }
 */
class ThemeActivatorTask extends BaseTask
{

    /**
     * Default config
     *
     * These are merged with user-provided configuration when the task is used.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'theme' => false,
        'callbacks' => true,
    ];

    /**
     * Invoked before "start()".
     *
     * @return void
     */
    public function init()
    {
        $this->plugin($this->config('theme'));
    }

    /**
     * Starts the activation process of the given theme.
     *
     * @return bool True on success, false otherwise
     */
    public function start()
    {
        try {
            $info = Plugin::info($this->plugin(), true);
        } catch (\Exception $e) {
            $info = null;
        }

        if (!$info) {
            $this->error(__d('installer', 'Theme "{0}" was not found.', $this->plugin()));
            return false;
        }

        if (!$info['isTheme']) {
            $this->error(__d('installer', '"{0}" is not a theme.', $info['human_name']));
            return false;
        }

        if (in_array($this->plugin(), [option('front_theme'), option('back_theme')])) {
            $this->error(__d('installer', 'Theme "{0}" is already active.', $info['human_name']));
            return false;
        }

        // MENTAL NOTE: As theme is "inactive" its listeners are not attached to the system, so we need
        // to manually attach them in order to trigger callbacks.
        if ($this->config('callbacks')) {
            $this->attachListeners("{$info['path']}/src/Event");
        }

        if ($this->config('callbacks')) {
            try {
                $beforeEvent = $this->trigger("Plugin.{$info['name']}.beforeActivate");
                if ($beforeEvent->isStopped() || $beforeEvent->result === false) {
                    $this->error(__d('installer', 'Task was explicitly rejected by the theme.'));
                    return false;
                }
            } catch (\Exception $e) {
                $this->error(__d('installer', 'Internal error, theme did not respond to "beforeActivate" callback correctly.'));
                return false;
            }
        }

        $this->loadModel('System.Options');
        if ($info['composer']['extra']['admin']) {
            $prefix = 'back_';
            $previousTheme = option('back_theme');
        } else {
            $prefix = 'front_';
            $previousTheme = option('front_theme');
        }

        if ($this->Options->update("{$prefix}theme", $this->plugin())) { // update() automatically regenerates snapshot
            $this->_copyBlockPositions($this->plugin(), $previousTheme);
        } else {
            $this->error(__d('installer', 'Internal error, the option "{0}" was not found.', "{$prefix}theme"));
            return false;
        }

        if ($this->config('callbacks')) {
            try {
                $this->trigger("Plugin.{$info['name']}.afterActivate");
            } catch (\Exception $e) {
                $this->error(__d('installer', 'Theme did not respond to "afterActivate" callback.'));
            }
        }

        return true;
    }

    /**
     * Indicates this task should activate the given theme.
     *
     * @param string|null $themeName Theme's name
     * @return \Installer\Task\ThemeActivatorTask This instance
     */
    public function activate($themeName = null)
    {
        if ($themeName) {
            $this->config('theme', $themeName);
            $this->plugin($themeName);
        }
        return $this;
    }

    /**
     * If $theme2 has any region in common with $theme1 will make $theme1 have these
     * blocks in the same regions as well.
     *
     * @param string $theme1 Theme name
     * @param string $theme2 Theme name
     * @return void
     */
    protected function _copyBlockPositions($theme1, $theme2)
    {
        $this->loadModel('Block.BlockRegions');
        $theme1 = Plugin::info($theme1, true);
        $newRegions = array_keys($theme1['composer']['extra']['regions']);
        $existingPositions = $this->BlockRegions
            ->find()
            ->where(['theme' => $theme2])
            ->all();
        foreach ($existingPositions as $position) {
            if (in_array($position->region, $newRegions)) {
                $newPosition = $this->BlockRegions->newEntity([
                    'block_id' => $position->block_id,
                    'theme' => $theme1['name'],
                    'region' => $position->region,
                    'ordering' => $position->ordering,
                ]);
                $this->BlockRegions->save($newPosition);
            }
        }
    }
}
