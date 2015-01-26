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
namespace System\Controller\Admin;

use Cake\Network\Exception\NotFoundException;
use Cake\ORM\Entity;
use QuickApps\Core\Plugin;
use System\Controller\AppController;

/**
 * Controller for handling plugin tasks.
 *
 * Here is where can install new plugin or remove existing ones.
 */
class PluginsController extends AppController
{

    /**
     * An array containing the names of components controllers uses.
     *
     * @var array
     */
    public $components = ['Installer.Installer'];

    /**
     * Main action.
     *
     * @return void
     */
    public function index()
    {
        $collection = Plugin::collection(true)->match(['isTheme' => false]);
        $plugins = $collection->toArray();
        $enabled = count($collection->match(['status' => true])->toArray());
        $disabled = count($collection->match(['status' => false])->toArray());

        $this->set(compact('plugins', 'all', 'enabled', 'disabled'));
        $this->Breadcrumb->push('/admin/system/plugins');
    }

    /**
     * Install a new theme.
     *
     * @return void
     */
    public function install()
    {
        if ($this->request->data) {
            $activate = false;
            if (isset($this->request->data['activate'])) {
                $activate = (bool)$this->request->data['activate'];
            }

            if (isset($this->request->data['download'])) {
                $task = $this->Installer
                    ->task('install')
                    ->config(['activate' => $activate, 'packageType' => 'plugin'])
                    ->download($this->request->data['url']);
            } else {
                $task = $this->Installer
                    ->task('install')
                    ->config(['activate' => $activate, 'packageType' => 'plugin'])
                    ->upload($this->request->data['file']);
            }

            $success = $task->run();
            if ($success) {
                if ($task->errors()) {
                    $this->Flash->set(__d('system', 'Plugins installed but some errors occur'), [
                        'element' => 'System.installer_errors',
                        'params' => ['errors' => $task->errors(), 'type' => 'warning'],
                    ]);
                } else {
                    $this->Flash->success(__d('system', 'Plugins successfully installed!'));
                }
                $this->redirect($this->referer());
            } else {
                $this->Flash->set(__d('system', 'Plugins could not be installed'), [
                    'element' => 'System.installer_errors',
                    'params' => ['errors' => $task->errors()],
                ]);
            }
        }

        $this->Breadcrumb
            ->push('/admin/system/plugins')
            ->push(__d('system', 'Install new plugin'), '#');
    }

    /**
     * Install a new plugin.
     *
     * @param string $pluginName Plugin's name
     * @return void Redirects to previous page
     */
    public function delete($pluginName)
    {
        $plugin = Plugin::info($pluginName, true);
        $task = $this->Installer->task('uninstall', ['plugin' => $pluginName]);
        $success = $task->run();

        if ($success) {
            $this->Flash->success(__d('system', 'Plugin was successfully removed!'));
        } else {
            $this->Flash->set(__d('system', 'Plugins could not be removed'), [
                'element' => 'System.installer_errors',
                'params' => ['errors' => $task->errors()],
            ]);
        }

        header('Location:' . $this->referer());
        exit();
    }

    /**
     * Enables the given theme.
     *
     * @param string $pluginName Plugin's name
     * @return void Redirects to previous page
     */
    public function enable($pluginName)
    {
        $plugin = Plugin::info($pluginName, true);
        $task = $this->Installer
            ->task('toggle')
            ->enable($pluginName);
        $success = $task->run();
        if ($success) {
            $this->Flash->success(__d('system', 'Plugin was successfully enabled!'));
        } else {
            $this->Flash->set(__d('system', 'Plugins could not be enabled'), [
                'element' => 'System.installer_errors',
                'params' => ['errors' => $task->errors()],
            ]);
        }

        header('Location:' . $this->referer());
        exit();
    }

    /**
     * Disables the given theme.
     *
     * @param string $pluginName Plugin's name
     * @return void Redirects to previous page
     */
    public function disable($pluginName)
    {
        $plugin = Plugin::info($pluginName, true);
        $task = $this->Installer
            ->task('toggle')
            ->disable($pluginName);
        $success = $task->run();
        if ($success) {
            $this->Flash->success(__d('system', 'Plugin was successfully disabled!'));
        } else {
            $this->Flash->set(__d('system', 'Plugins could not be disabled'), [
                'element' => 'System.installer_errors',
                'params' => ['errors' => $task->errors()],
            ]);
        }

        header('Location:' . $this->referer());
        exit();
    }

    /**
     * Handles plugin's specifics settings.
     *
     * When saving plugin's information `PluginsTable` will trigger the
     * following events:
     *
     * - `Plugin.<PluginName>.beforeValidate`
     * - `Plugin.<PluginName>.afterValidate`
     * - `Plugin.<PluginName>.beforeSave`
     * - `Plugin.<PluginName>.afterSave`
     *
     * Check `PluginsTable` documentation for more details.
     *
     * Additionally plugins may define default values for each input, to do this they
     * must catch the event:
     *
     * - `Plugin.<PluginName>.settingsDefaults`
     *
     * They must return an associative array of default values for each input in
     * the form.
     *
     * Validation rules can be applied to settings, plugins must simply catch the
     * event:
     *
     * - `Plugin.<PluginName>.settingsValidate`
     *
     * @param string $pluginName Plugin's name
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When plugin do not exists
     */
    public function settings($pluginName)
    {
        $plugin = Plugin::info($pluginName, true);
        $arrayContext = [
            'schema' => [],
            'defaults' => [],
            'errors' => [],
        ];

        if (!$plugin['hasSettings'] || $plugin['isTheme']) {
            throw new NotFoundException(__d('system', 'The requested page was not found.'));
        }

        if (!empty($this->request->data)) {
            $this->loadModel('System.Plugins');
            $settingsEntity = new Entity($this->request->data);
            $settingsEntity->set('_plugin_name', $pluginName);
            $errors = $this->Plugins->validator('settings')->errors($settingsEntity->toArray());

            if (empty($errors)) {
                $pluginEntity = $this->Plugins->get($pluginName);
                $pluginEntity->set('settings', $this->request->data);

                if ($this->Plugins->save($pluginEntity)) {
                    $this->Flash->success(__d('system', 'Plugin settings saved!'));
                    $this->redirect($this->referer());
                }
            } else {
                $this->Flash->danger(__d('system', 'Plugin settings could not be saved.'));
                foreach ($errors as $field => $message) {
                    $arrayContext['errors'][$field] = $message;
                }
            }
        } else {
            $arrayContext['defaults'] = (array)$plugin['settings'];
            $this->request->data = $arrayContext['defaults'];
        }

        $this->set(compact('arrayContext', 'plugin'));
        $this->Breadcrumb
            ->push('/admin/system/plugins')
            ->push(__d('system', 'Settings for {0} plugin', $plugin['name']), '#');
    }
}
