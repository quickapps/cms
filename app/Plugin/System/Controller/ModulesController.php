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

    public function admin_toggle($plugin = false) {
        if (!$plugin) {
            $this->redirect('/admin/system/modules');
        }

        $plugin = Inflector::camelize($plugin);
        $to = Configure::read("Modules.{$plugin}.status") == 1 ? 0 : 1;

        if ($to) {
            if ($this->Installer->enableModule($plugin)) {
                $this->flashMsg(__t("Module '%s' has been enabled", $plugin), 'success');
            } else {
                $this->flashMsg(implode('<br />', $this->Installer->errors), 'alert');
            }
        } else {
            if ($this->Installer->disableModule($plugin)) {
                $this->flashMsg(__t("Module '%s' has been disabled", $plugin), 'success');
            } else {
                $this->flashMsg(implode('<br />', $this->Installer->errors), 'alert');
            }
        }

        $this->redirect($this->referer());
    }

    public function admin_uninstall($plugin = false) {
        if (!$plugin) {
            $this->redirect('/admin/system/modules');
        } elseif ($this->Installer->uninstall($plugin)) {
            $this->flashMsg(__t("Module '%s' has been uninstalled", $plugin), 'success');
        } else {
            $this->flashMsg(implode('<br />', $this->Installer->errors), 'error');
        }

        $this->redirect('/admin/system/modules');
    }

    public function admin_install() {
        if (!isset($this->data['Package']['data'])) {
            $this->redirect('/admin/system/modules');
        }

        if (!$this->Installer->install($this->data, array('type' => 'module', 'status' => $this->data['Package']['activate']))) {
            $errors = implode('', $this->Installer->errors);
            $this->flashMsg($errors, 'error');
        } else {
            $this->flashMsg(__t('Module has been installed'), 'success');
        }

        $this->redirect('/admin/system/modules');
    }
}