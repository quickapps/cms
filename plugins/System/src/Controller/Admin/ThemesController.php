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
use QuickApps\Core\Plugin;
use System\Controller\AppController;

/**
 * Controller for handling plugin tasks.
 *
 * Here is where can install new plugin or remove existing ones.
 */
class ThemesController extends AppController
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
        $themes = Plugin::collection(true)->match(['isTheme' => true]);
        $frontThemes = $themes
            ->match(['composer.extra.admin' => false])
            ->sortBy(function ($theme) {
                if ($theme['name'] === option('front_theme')) {
                    return 0;
                }
                return 1;
            }, SORT_ASC);
        $backThemes = $themes
            ->match(['composer.extra.admin' => true])
            ->sortBy(function ($theme) {
                if ($theme['name'] === option('back_theme')) {
                    return 0;
                }
                return 1;
            }, SORT_ASC);
        $frontCount = count($frontThemes->toArray());
        $backCount = count($backThemes->toArray());

        $this->set(compact('frontCount', 'backCount', 'frontThemes', 'backThemes'));
        $this->Breadcrumb->push('/admin/system/themes');
    }

    /**
     * Install a new theme.
     *
     * @return void
     */
    public function install()
    {
        if ($this->request->data) {
            if (isset($this->request->data['download'])) {
                $task = $this->Installer
                    ->task('install')
                    ->config(['packageType' => 'theme', 'activate' => true])
                    ->download($this->request->data['url']);
            } else {
                $task = $this->Installer
                    ->task('install')
                    ->config(['packageType' => 'theme', 'activate' => true])
                    ->upload($this->request->data['file']);
            }

            $success = $task->run();
            if ($success) {
                $this->Flash->success(__d('system', 'Theme successfully installed!'));
                $this->redirect($this->referer());
            } else {
                $this->Flash->set(__d('system', 'Theme could not be installed'), [
                    'element' => 'System.installer_errors',
                    'params' => ['errors' => $task->errors()],
                ]);
            }
        }
        $this->Breadcrumb
            ->push('/admin/system/themes')
            ->push(__d('system', 'Install new theme'), '#');
    }

    /**
     * Removes the given theme.
     *
     * @param string $themeName Theme's name
     * @return void
     */
    public function uninstall($themeName)
    {
        $theme = Plugin::info($themeName, true);

        if (!in_array($themeName, [option('front_theme'), option('back_theme')])) {
            if ($theme['isCore']) {
                $this->Flash->danger(__d('system', 'You cannot remove a core theme!'));
            } else {
                $task = $this->Installer->task('uninstall', ['plugin' => $themeName]);
                $success = $task->run();
                if ($success) {
                    $this->Flash->success(__d('system', 'Theme successfully removed!'));
                } else {
                    $this->Flash->set(__d('system', 'Theme could not be removed'), [
                        'element' => 'System.installer_errors',
                        'params' => ['errors' => $task->errors()],
                    ]);
                }
            }
        } else {
            $this->Flash->danger(__d('system', 'This theme cannot be removed as it is in use.'));
        }

        $this->redirect($this->referer());
    }

    /**
     * Detailed theme's information.
     *
     * @param string $themeName Theme's name
     * @return void
     */
    public function activate($themeName)
    {
        $theme = Plugin::info($themeName, true);

        if (!in_array($themeName, [option('front_theme'), option('back_theme')])) {
            $task = $this->Installer
                ->task('activate_theme')
                ->activate($themeName);
            $success = $task->run();
            if ($success) {
                $this->Flash->success(__d('system', 'Theme successfully activated!'));
            } else {
                $this->Flash->set(__d('system', 'Theme could not be activated'), [
                    'element' => 'System.installer_errors',
                    'params' => ['errors' => $task->errors()],
                ]);
            }
        } else {
            $this->Flash->danger(__d('system', 'This theme is already active.'));
        }

        $this->redirect($this->referer());
    }

    /**
     * Detailed theme's information.
     *
     * @param string $themeName Theme's name
     * @return void
     */
    public function details($themeName)
    {
        $theme = Plugin::info($themeName, true);

        $this->set(compact('theme'));
        $this->Breadcrumb
            ->push('/admin/system/themes')
            ->push($theme['human_name'], '#')
            ->push(__d('system', 'Details'), '#');
    }

    /**
     * Renders theme's "screenshot.png"
     *
     * @param string $themeName Theme's name
     * @return \Cake\Network\Response
     */
    public function screenshot($themeName)
    {
        $info = Plugin::info($themeName);
        $this->response->file("{$info['path']}/webroot/screenshot.png");
        return $this->response;
    }

    /**
     * Handles theme's specifics settings.
     *
     * When saving theme's information `PluginsTable` will trigger the
     * following events:
     *
     * - `Plugin.<PluginName>.beforeValidate`
     * - `Plugin.<PluginName>.afterValidate`
     * - `Plugin.<PluginName>.beforeSave`
     * - `Plugin.<PluginName>.afterSave`
     *
     * Check `PluginsTable` documentation for more details.
     *
     * Additionally theme may define default values for each input, to do this they
     * must catch the event:
     *
     * - `Plugin.<PluginName>.settingsDefaults`
     *
     * They must return an associative array of default values for each input in
     * the form.
     *
     * Validation rules can be applied to settings, theme must simply catch the
     * event:
     *
     * - `Plugin.<PluginName>.settingsValidate`
     *
     * @param string $themeName Theme's name
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When plugin do not exists
     */
    public function settings($themeName)
    {
        $theme = Plugin::info($themeName, true);
        $arrayContext = [
            'schema' => [],
            'defaults' => [],
            'errors' => [],
        ];

        if (!$theme['hasSettings'] || !$theme['isTheme']) {
            throw new NotFoundException(__d('system', 'The requested page was not found.'));
        }

        if (!empty($this->request->data)) {
            $this->loadModel('System.Plugins');
            $settingsEntity = new Entity($this->request->data);
            $settingsEntity->set('_plugin_name', $themeName);
            $errors = $this->Plugins->validator('settings')->errors($settingsEntity->toArray());

            if (empty($errors)) {
                $pluginEntity = $this->Plugins->get($themeName);
                $pluginEntity->set('settings', $this->request->data);

                if ($this->Plugins->save($pluginEntity)) {
                    $this->Flash->success(__d('system', 'Theme settings saved!'));
                    $this->redirect($this->referer());
                }
            } else {
                $this->Flash->danger(__d('system', 'Theme settings could not be saved.'));
                foreach ($errors as $field => $message) {
                    $arrayContext['errors'][$field] = $message;
                }
            }
        } else {
            $this->request->data = $plugin['settings'];
        }

        $this->set(compact('arrayContext', 'theme'));
        $this->Breadcrumb
            ->push('/admin/system/themes')
            ->push(__d('system', 'Settings for {0} theme', $theme['name']), '#');
    }
}
