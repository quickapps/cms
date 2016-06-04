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
 * Controller for handling themes tasks.
 *
 * Here is where can install new themes, remove existing ones or change site's in
 * use theme.
 *
 * @property \System\Model\Table\PluginsTable $Plugins
 */
class ThemesController extends AppController
{

    /**
     * Main action.
     *
     * @return void
     */
    public function index()
    {
        $themes = plugin()
            ->filter(function ($plugin) {
                return $plugin->isTheme;
            });

        $frontThemes = $themes
            ->filter(function ($theme) {
                return !isset($theme->composer['extra']['admin']) || !$theme->composer['extra']['admin'];
            })
            ->sortBy(function ($theme) {
                if ($theme->name() === option('front_theme')) {
                    return 0;
                }
                return 1;
            }, SORT_ASC);

        $backThemes = $themes
            ->filter(function ($theme) {
                return isset($theme->composer['extra']['admin']) && $theme->composer['extra']['admin'];
            })
            ->sortBy(function ($theme) {
                if ($theme->name() === option('back_theme')) {
                    return 0;
                }
                return 1;
            }, SORT_ASC);

        $frontCount = count($frontThemes->toArray());
        $backCount = count($backThemes->toArray());

        $this->title(__d('system', 'Themes'));
        $this->_awaitingPlugins('theme');
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
        if ($this->request->data()) {
            $task = false;
            $uploadError = false;

            if (isset($this->request->data['download'])) {
                $task = (bool)WebShellDispatcher::run("Installer.plugins install -s \"{$this->request->data['url']}\" --theme -a");
            } elseif (isset($this->request->data['file_system'])) {
                $task = (bool)WebShellDispatcher::run("Installer.plugins install -s \"{$this->request->data['path']}\" --theme -a");
            } else {
                $uploader = new PackageUploader($this->request->data['file']);
                if ($uploader->upload()) {
                    $task = (bool)WebShellDispatcher::run('Installer.plugins install -s "' . $uploader->dst() . '" --theme -a');
                } else {
                    $uploadError = true;
                    $this->Flash->set(__d('system', 'Plugins installed but some errors occur'), [
                        'element' => 'System.installer_errors',
                        'params' => ['errors' => $uploader->errors(), 'type' => 'warning'],
                    ]);
                }
            }

            if ($task) {
                $this->Flash->success(__d('system', 'Theme successfully installed!'));
                $this->redirect($this->referer());
            } elseif (!$task && !$uploadError) {
                $this->Flash->set(__d('system', 'Theme could not be installed'), [
                    'element' => 'System.installer_errors',
                    'params' => ['errors' => WebShellDispatcher::output()],
                ]);
            }
        }

        $this->title(__d('system', 'Install Theme'));
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
        $theme = plugin($themeName); // throws
        if (!in_array($themeName, [option('front_theme'), option('back_theme')])) {
            if (!$theme->requiredBy()->isEmpty()) {
                $this->Flash->danger(__d('system', 'You cannot remove this theme!'));
            } else {
                $task = (bool)WebShellDispatcher::run("Installer.plugins uninstall -p {$theme->name}");
                if ($task) {
                    $this->Flash->success(__d('system', 'Theme successfully removed!'));
                } else {
                    $this->Flash->set(__d('system', 'Theme could not be removed'), [
                        'element' => 'System.installer_errors',
                        'params' => ['errors' => WebShellDispatcher::output()],
                    ]);
                }
            }
        } else {
            $this->Flash->danger(__d('system', 'This theme cannot be removed as it is currently being used.'));
        }

        $this->title(__d('system', 'Uninstall Theme'));
        $this->redirect($this->referer());
    }

    /**
     * Marks as active the provided theme.
     *
     * @param string $themeName Theme's name
     * @return void
     */
    public function activate($themeName)
    {
        $theme = plugin($themeName); // throws
        if (!in_array($themeName, [option('front_theme'), option('back_theme')])) {
            $task = (bool)WebShellDispatcher::run("Installer.themes change -t {$theme->name}");
            if ($task) {
                $this->Flash->success(__d('system', 'Theme successfully activated!'));
            } else {
                $this->Flash->set(__d('system', 'Theme could not be activated'), [
                    'element' => 'System.installer_errors',
                    'params' => ['errors' => WebShellDispatcher::output()],
                ]);
            }
        } else {
            $this->Flash->danger(__d('system', 'This theme is already active.'));
        }

        $this->title(__d('system', 'Activate Theme'));
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
        $theme = plugin($themeName); // throws

        $this->title(__d('system', 'Theme Information'));
        $this->set(compact('theme'));
        $this->Breadcrumb
            ->push('/admin/system/themes')
            ->push($theme->humanName, '#')
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
        $theme = plugin($themeName); // throws
        $this->response->file("{$theme->path}/webroot/screenshot.png");
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
     * They must return an associative array of default values for each input in the
     * form.
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
        $info = plugin($themeName);
        $this->loadModel('System.Plugins');
        $theme = $this->Plugins->get($themeName, ['flatten' => true]);

        if (!$info->hasSettings || !$info->isTheme) {
            throw new NotFoundException(__d('system', 'The requested page was not found.'));
        }

        if ($this->request->data()) {
            $theme = $this->Plugins->patchEntity($theme, $this->request->data(), ['entity' => $theme]);
            if (!$theme->errors()) {
                if ($this->Plugins->save($theme)) {
                    $this->Flash->success(__d('system', 'Theme settings saved!'));
                    $this->redirect($this->referer());
                }
            } else {
                $this->Flash->danger(__d('system', 'Theme settings could not be saved.'));
            }
        }

        $this->title(__d('system', 'Theme’s Settings'));
        $this->set(compact('info', 'theme'));
        $this->Breadcrumb
            ->push('/admin/system/themes')
            ->push(__d('system', 'Settings for {0} theme', $info->name), '#');
    }
}
