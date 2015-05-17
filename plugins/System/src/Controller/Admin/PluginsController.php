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
namespace System\Controller\Admin;

use Cake\Network\Exception\NotFoundException;
use CMS\Console\WebShellDispatcher;
use CMS\Core\Plugin;
use Installer\Utility\PackageUploader;
use System\Controller\AppController;

/**
 * Controller for handling plugin tasks.
 *
 * Here is where can install new plugin or remove existing ones.
 */
class PluginsController extends AppController
{

    /**
     * Main action.
     *
     * @return void
     */
    public function index()
    {
        $collection = plugin()->filter(function ($plugin) {
            return !$plugin->isTheme;
        });
        $plugins = $collection->toArray();
        $enabled = count($collection->filter(function ($plugin) {
            return $plugin->status;
        })->toArray());
        $disabled = count($collection->filter(function ($plugin) {
            return !$plugin->status;
        })->toArray());

        $this->title(__d('system', 'Plugins'));
        $this->set(compact('plugins', 'all', 'enabled', 'disabled'));
        $this->_awaitingPlugins();
        $this->Breadcrumb->push('/admin/system/plugins');
    }

    /**
     * Installs a new plugin.
     *
     * @return void
     */
    public function install()
    {
        if ($this->request->data()) {
            $task = false;
            $uploadError = false;
            $activate = !empty($this->request->data['activate']) ? ' -a' : '';

            if (isset($this->request->data['download'])) {
                $task = (bool)WebShellDispatcher::run("Installer.plugins install -s \"{$this->request->data['url']}\"{$activate}");
            } elseif (isset($this->request->data['file_system'])) {
                $task = (bool)WebShellDispatcher::run("Installer.plugins install -s \"{$this->request->data['path']}\"{$activate}");
            } else {
                $uploader = new PackageUploader($this->request->data['file']);
                if ($uploader->upload()) {
                    $task = (bool)WebShellDispatcher::run('Installer.plugins install -s "' . $uploader->dst() . '"' . $activate);
                } else {
                    $uploadError = true;
                    $this->Flash->set(__d('system', 'Plugins installed but some errors occur'), [
                        'element' => 'System.installer_errors',
                        'params' => ['errors' => $uploader->errors(), 'type' => 'warning'],
                    ]);
                }
            }

            if ($task) {
                $this->Flash->success(__d('system', 'Plugins successfully installed!'));
                $this->redirect($this->referer());
            } elseif (!$task && !$uploadError) {
                $this->Flash->set(__d('system', 'Plugins could not be installed'), [
                    'element' => 'System.installer_errors',
                    'params' => ['errors' => WebShellDispatcher::output()],
                ]);
            }
        }

        $this->title(__d('system', 'Install Plugin'));
        $this->Breadcrumb
            ->push('/admin/system/plugins')
            ->push(__d('system', 'Install new plugin'), '#');
    }

    /**
     * Uninstalls the given plugin.
     *
     * @param string $pluginName Plugin's name
     * @return void Redirects to previous page
     */
    public function delete($pluginName)
    {
        $plugin = plugin($pluginName); // throws if not exists
        $task = (bool)WebShellDispatcher::run("Installer.plugins uninstall -p {$plugin->name}");

        if ($task) {
            $this->Flash->success(__d('system', 'Plugin was successfully removed!'));
        } else {
            $this->Flash->set(__d('system', 'Plugins could not be removed'), [
                'element' => 'System.installer_errors',
                'params' => ['errors' => WebShellDispatcher::output()],
            ]);
        }

        $this->title(__d('system', 'Uninstall Plugin'));
        header('Location:' . $this->referer());
        exit();
    }

    /**
     * Enables the given plugin.
     *
     * @param string $pluginName Plugin's name
     * @return void Redirects to previous page
     */
    public function enable($pluginName)
    {
        $plugin = plugin($pluginName);
        $task = (bool)WebShellDispatcher::run("Installer.plugins toggle -p {$plugin->name} -s enable");

        if ($task) {
            $this->Flash->success(__d('system', 'Plugin was successfully enabled!'));
        } else {
            $this->Flash->set(__d('system', 'Plugin could not be enabled'), [
                'element' => 'System.installer_errors',
                'params' => ['errors' => WebShellDispatcher::output()],
            ]);
        }

        $this->title(__d('system', 'Enable Plugin'));
        header('Location:' . $this->referer());
        exit();
    }

    /**
     * Disables the given plugin.
     *
     * @param string $pluginName Plugin's name
     * @return void Redirects to previous page
     */
    public function disable($pluginName)
    {
        $plugin = plugin($pluginName);
        $task = (bool)WebShellDispatcher::run("Installer.plugins toggle -p {$plugin->name} -s disable");

        if ($task) {
            $this->Flash->success(__d('system', 'Plugin was successfully disabled!'));
        } else {
            $this->Flash->set(__d('system', 'Plugin could not be disabled'), [
                'element' => 'System.installer_errors',
                'params' => ['errors' => WebShellDispatcher::output()],
            ]);
        }

        $this->title(__d('system', 'Disable Plugin'));
        header('Location:' . $this->referer());
        exit();
    }

    /**
     * Handles plugin's specifics settings.
     *
     * When saving plugin's information `PluginsTable` will trigger the
     * following events:
     *
     * - `Plugin.<PluginName>.settingsValidate`
     * - `Plugin.<PluginName>.beforeSave`
     * - `Plugin.<PluginName>.afterSave`
     *
     * Check `PluginsTable` documentation for more details.
     *
     * Additionally plugins may define default values for each input, to do this
     * they must catch the event:
     *
     * - `Plugin.<PluginName>.settingsDefaults`
     *
     * They must return an associative array of default values for each input in the
     * form.
     *
     * Validation rules can be applied to settings, plugins must simply catch the
     * event:
     *
     * - `Plugin.<PluginName>.validate`
     *
     * @param string $pluginName Plugin's name
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When plugin do not exists
     */
    public function settings($pluginName)
    {
        $info = plugin($pluginName);
        $this->loadModel('System.Plugins');
        $plugin = $this->Plugins->get($pluginName, ['flatten' => true]);

        if (!$info->hasSettings || $info->isTheme) {
            throw new NotFoundException(__d('system', 'The requested page was not found.'));
        }

        if ($this->request->data()) {
            $plugin = $this->Plugins->patchEntity($plugin, $this->request->data(), ['entity' => $plugin]);
            if (!$plugin->errors()) {
                if ($this->Plugins->save($plugin)) {
                    $this->Flash->success(__d('system', 'Plugin settings saved!'));
                    $this->redirect($this->referer());
                }
            } else {
                $this->Flash->danger(__d('system', 'Plugin settings could not be saved.'));
            }
        }

        $this->title(__d('system', 'Plugin’s Settings'));
        $this->set(compact('plugin', 'info'));
        $this->Breadcrumb
            ->push('/admin/system/plugins')
            ->push(__d('system', 'Settings for "{0}" plugin', $info->name), '#');
    }
}
