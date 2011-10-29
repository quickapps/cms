<?php
/**
 * Modules Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.System.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class ModulesController extends SystemAppController {
    public $name = 'Modules';
    public $uses = array('System.Module');
    public $components = array('Installer');

    public function admin_index() {
    }

    public function admin_settings($module) {
        if (!Configure::read('Modules.' . $module)) {
            $this->redirect('/admin/system/modules');
        }

        if (isset($this->data['Module']['name']) || isset($this->data['Variable'])) {
            if (isset($this->data['Module']['name'])) {
                $this->Module->save($this->data);
                Cache::delete('Modules'); # regenerate modules cache
                $this->Quickapps->loadModules();
            }

            if (isset($this->data['Variable'])) {
                $this->Variable->save($this->data);
            }

            $this->flashMsg(__t('Module changes has been saved!'), 'success');
            $this->redirect($this->referer());
        }

        $data = array();
        $data['Module'] = Configure::read("Modules.{$module}");
        $this->data = $data;

        $this->setCrumb('/admin/system/modules');
        $this->setCrumb( array($data['Module']['yaml']['name']));
        $this->setCrumb( array(__t('Settings')));
        $this->title(__t('Configure Module'));
    }

    public function admin_toggle($plugin) {
        $plugin = Inflector::camelize($plugin);

        if (!$plugin) {
            $this->redirect('/admin/system/modules');
        }

        if (!in_array($plugin, Configure::read('coreModules'))) {
            $to = Configure::read("Modules.{$plugin}.status") == 1 ? 0 : 1;
            $this->Install = $this->Components->load(Inflector::camelize($plugin) . '.Install');

            if ($to == 1) { # enable
                if (method_exists($this->Install, 'beforeEnable')) {
                    $this->Install->beforeEnable();
                    $this->flashMsg(__t("Module '%s' has been enabled", $plugin), 'success');
                }
            } else { # disable
                if (method_exists($this->Install, 'beforeDisable')) {
                    $this->Install->beforeDesactivate();
                }

                $dep = $this->Installer->checkReverseDependency($plugin);

                if (count($dep)) {
                    $req_by = implode('<br />', Set::extract('{n}.name', $dep));
                    $this->flashMsg(__t('This module can not be disabled, because it is required by: %s', $req_by), 'alert');
                    $this->redirect('/admin/system/modules');
                } else {
                    $this->flashMsg(__t("Module '%s' has been disabled", $plugin), 'success');
                }
            }

            # turn on/off related blocks
            ClassRegistry::init('Block.Block')->updateAll(
                array('Block.status' => $to),
                array('Block.status <>' => 0, 'Block.module' => $plugin)
            );

            # turn on/off related menu links
            ClassRegistry::init('Menu.MenuLink')->updateAll(
                array('MenuLink.status' => $to),
                array('MenuLink.status <>' => 0, 'MenuLink.module' => $plugin)
            );

            # turn on/off module
            $this->Module->updateAll(
                array('Module.status' => $to),
                array('Module.name' => $plugin)
            );

            Cache::delete('Modules'); # regenerate modules cache
            $this->Quickapps->loadModules();

            if ($to == 1) {
                if (method_exists($this->Install, 'afterEnable')) {
                    $this->Install->afterEnable();
                }
            } else {
                if (method_exists($this->Install, 'afterDisable')) {
                    $this->Install->afterDisable();
                }
            }
        }

        $this->redirect($this->referer());
    }

    public function admin_uninstall($plugin) {
        $plugin = Inflector::camelize($plugin);

        if (!$plugin) {
            $this->redirect('/admin/system/modules');
        }

        $dep = $this->Installer->checkReverseDependency($plugin);

        if (count($dep)) {
            $req_by = implode('<br />', Set::extract('{n}.name', $dep));
            $this->flashMsg(__t('This module can not be uninstalled, because it is required by: %s', $req_by), 'alert');
            $this->redirect('/admin/system/modules');
        }

        if ($this->Installer->uninstall($plugin)) {
            $this->flashMsg(__t("Module '%s' has been uninstalled", $plugin), 'success');
        } else {
            $this->flashMsg(__t("Error uninstalling module '%s'", $plugin), 'error');
        }

        $this->redirect('/admin/system/modules');
    }

    public function admin_install() {
        if (!isset($this->data['Package']['data'])) {
            $this->redirect('/admin/system/modules');
        }

        if (!$this->Installer->install($this->data, array('type' => 'module'))) {
            $errors = implode('', $this->Installer->errors);
            $this->flashMsg($errors, 'error');
        } else {
            $this->flashMsg(__t('Module has been installed'), 'success');
        }

        $this->redirect('/admin/system/modules');
    }
}