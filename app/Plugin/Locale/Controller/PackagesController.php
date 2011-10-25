<?php
/**
 * Packages Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Locale.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class PackagesController extends LocaleAppController {
    public $name = 'Packages';
    public $uses = array();

    public function admin_index() {
        Configure::write('debug', 0);
        $poFolders = array();

        # Core .po
        $Locale = new Folder(APP . 'Locale' . DS);
        $f = $Locale->read(); $f = $f[0];
        foreach ($f as $langF) {
            if (file_exists(APP . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'core.po')) {
                $poFolders['Core'][$langF] = APP . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'core.po';
            }
        }

        # Plugins .po
        foreach (App::objects('plugin') as $plugin) {
            $ppath = CakePlugin::path($plugin);

            if (strpos($ppath, DS . 'Fields' . DS)) {
                continue;
            }

            $Locale = new Folder($ppath . 'Locale' . DS);
            $f = $Locale->read(); $f = $f[0];

            foreach ($f as $langF) {
                $poFolders[$plugin][$langF] = $ppath . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'core.po';
            }
        }

        $modules['core'] = __t('Core');

        foreach (Configure::read('Modules') as $module) {
            $modules[$module['name']] = (strpos($module['name'], 'theme_') !== false) ? __t('Theme: %s', $module['yaml']['info']['name']) : __t('Module: %s', $module['yaml']['name']);
        }

        $this->set('modules', $modules);
        $this->set('languages', $this->__languageList());
        $this->set('packages', $poFolders);
        $this->setCrumb('/admin/locale');
        $this->setCrumb( array(__t('Translation packages'), ''));
        $this->title(__t('Translation Packages'));
    }

    public function admin_download_package($plugin, $language) {
        $err = false;
        $ppath = $plugin === 'Core' ? APP : CakePlugin::path($plugin);

        if ($ppath) {
            $poPath = $ppath . 'Locale' . DS . $language . DS . 'LC_MESSAGES' . DS;

            if (file_exists($poPath . 'core.po')) {
                $this->viewClass = 'Media';
                $params = array(
                    'id' => 'core.po',
                    'name' => 'core.po',
                    'download' => true,
                    'extension' => 'po',
                    'path' => $poPath
                );
                $this->set($params);
            } else {
                $err = true;
            }
        } else {
            $err = true;
        }

        if ($err) {
            throw new NotFoundException(__t('Package not found'));
        }
    }

    public function admin_uninstall($plugin, $language) {
        $ppath = strtolower($plugin) !== 'core' ? CakePlugin::path(Inflector::camelize($this->data['Package']['module'])) : APP;

        if (file_exists($ppath . 'Locale' . DS . $language . DS . 'LC_MESSAGES' . DS . 'core.po')) {
            $Folder = new Folder($ppath . 'Locale' . DS . $language . DS);

            if (!$Folder->delete()) {
                $this->flashMsg(__t("Could not delete package folder. Please check folder permissions for '%s'.", $ppath . 'Locale' . DS . $language . DS), 'error');
            } else {
                $this->flashMsg(__t('Language package removed!'), 'success');
            }
        } else {
            $this->flashMsg(__t('Invalid module or language '), 'error');
        }

        $this->redirect('/admin/locale/packages');
    }

    public function admin_install() {
        if (!isset($this->data['Package']['po'])) {
            $this->redirect('/admin/locale/packages');
        }

        $ppath = $this->data['Package']['module'] !== 'core' ? CakePlugin::path(Inflector::camelize($this->data['Package']['module'])) : APP;

        if (file_exists($ppath)) {
            if (in_array($this->data['Package']['language'], array_keys($this->__languageList()))) {
                App::import('Vendor', 'Upload');
                $destFolder = $ppath . 'Locale' . DS . $this->data['Package']['language'] . DS . 'LC_MESSAGES' . DS;
                $Folder = new Folder;
                $Upload = new Upload($this->data['Package']['po']);
                $Upload->file_overwrite = true;
                $Upload->file_new_name_ext  = 'po';
                $Upload->file_new_name_body  = 'core';
                $Upload->Process($destFolder);

                if (!$Upload->processed) {
                    $this->flashMsg($Upload->error, 'error');
                } else {
                    $this->flashMsg(__t('Language package upload success'), 'success');
                }
            }
        } else {
            $this->flashMsg(__t('Invalid package'), 'error');
        }

        $this->redirect('/admin/locale/packages');
    }

    private function __languageList() {
        $list = array();
        $_languages = Configure::read('Variable.languages');

        foreach ($_languages as $l) {
            $list[$l['Language']['code']] = $l['Language']['native'];
        }

        return $list;
    }
}