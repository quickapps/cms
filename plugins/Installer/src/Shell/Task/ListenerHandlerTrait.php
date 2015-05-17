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

use Cake\Core\ClassLoader;
use Cake\Event\EventManager;
use Cake\Filesystem\Folder;
use CMS\Core\Plugin;

/**
 * Provides methods for attach and detach listener objects of concrete plugins.
 */
trait ListenerHandlerTrait
{

    /**
     * List of all loaded listeners using "_attachListeners()" method.
     *
     * @var array
     */
    protected $_listeners = [];

    /**
     * Loads and registers plugin's namespace and loads its event listeners classes.
     *
     * This is used to allow plugins being installed to respond to events before
     * they are integrated to the system. Events such as `beforeInstall`,
     * `afterInstall`, etc.
     *
     * @param string $plugin Name of the plugin for which attach listeners
     * @param string $path Path to plugin's root directory (which contains "src")
     * @throws \Cake\Error\FatalErrorException On illegal usage of this method
     */
    protected function _attachListeners($plugin, $path)
    {
        $path = normalizePath("{$path}/");
        $eventsPath = normalizePath("{$path}/src/Event/");

        if (is_readable($eventsPath) && is_dir($eventsPath)) {
            $EventManager = EventManager::instance();
            $eventsFolder = new Folder($eventsPath);
            Plugin::load($plugin, [
                'autoload' => true,
                'bootstrap' => false,
                'routes' => false,
                'path' => $path,
                'classBase' => 'src',
                'ignoreMissing' => true,
            ]);

            foreach ($eventsFolder->read(false, false, true)[1] as $classPath) {
                $className = preg_replace('/\.php$/i', '', basename($classPath));
                $fullClassName = implode('\\', [$plugin, 'Event', $className]);

                if (class_exists($fullClassName)) {
                    $handler = new $fullClassName;
                    $this->_listeners[] = $handler;
                    $EventManager->on($handler);
                }
            }
        }
    }

    /**
     * Unloads all registered listeners that were attached using the
     * "_attachListeners()" method.
     *
     * @return void
     */
    protected function _detachListeners()
    {
        $EventManager = EventManager::instance();
        foreach ($this->_listeners as $listener) {
            $EventManager->detach($listener);
        }
    }
}
