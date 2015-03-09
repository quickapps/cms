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

use Cake\Event\EventManager;
use Cake\Filesystem\Folder;

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
     * Loads and registers plugin's event listeners classes so plugins may respond
     * to `beforeInstall`, `afterInstall`, etc.
     *
     * @param string $plugin Name of the plugin for which attach listeners
     * @param string $path Where to look for listener classes
     * @return void
     * @throws \Cake\Error\FatalErrorException On illegal usage of this method
     */
    protected function _attachListeners($plugin, $path)
    {
        global $classLoader;

        if (file_exists($path) && is_dir($path)) {
            $EventManager = EventManager::instance();
            $eventsFolder = new Folder($path);

            foreach ($eventsFolder->read(false, false, true)[1] as $classPath) {
                $className = preg_replace('/\.php$/i', '', basename($classPath));
                $namespace = $plugin . '\Event\\';
                $classLoader->addPsr4($namespace, dirname($classPath), true);
                $fullClassName = $namespace . $className;

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
