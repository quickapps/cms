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
        $poFolders = $modules = array();
        $modules['Site'] = __t('Site Domain');
        $field_modules = $this->hook('field_info', $this, array('collectReturn' => false));

        # Site core.po
        $Locale = new Folder(ROOT . DS . 'Locale' . DS);
        $f = $Locale->read(); $f = $f[0];

        foreach ($f as $langF) {
            if (file_exists(ROOT . DS . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'core.po')) {
                $poFolders['Site'][$langF] = ROOT . DS . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'core.po';
            }
        }

        # Core default.po
        $Locale = new Folder(APP . 'Locale' . DS);
        $f = $Locale->read(); $f = $f[0];

        foreach ($f as $langF) {
            if (file_exists(APP . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'default.po')) {
                $poFolders['Core'][$langF] = APP . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'default.po';
            }
        }

        # Plugins .po
        foreach (App::objects('plugin') as $plugin) {
            $ppath = CakePlugin::path($plugin);
            $Locale = new Folder($ppath . 'Locale' . DS);
            $f = $Locale->read(); $f = $f[0];

            foreach ($f as $langF) {
                $poFolders[$plugin][$langF] = $ppath . 'Locale' . DS . $langF . DS . 'LC_MESSAGES' . DS . 'core.po';
            }

            // Core apps cannot be overwritten
            if (strpos($ppath, APP) === false) {
                if (strpos($plugin, 'Theme') === 0) {
                    $modules[$plugin] = __t('Theme: %s', Configure::read("Modules.{$plugin}.yaml.info.name"));
                } elseif (strpos($ppath, DS . 'Fields' . DS) !== false) {
                    $modules[$plugin] = __t('Field: %s', $field_modules[$plugin]['name']);
                } else {
                    $modules[$plugin] = __t('Module: %s', Configure::read("Modules.{$plugin}.yaml.name"));
                }
            }
        }

        $this->set('field_modules', $field_modules);
        $this->set('modules', $modules);
        $this->set('languages', $this->__languageList());
        $this->set('packages', $poFolders);
        $this->setCrumb('/admin/locale');
        $this->setCrumb( array(__t('Translation packages'), ''));
        $this->title(__t('Translation Packages'));
    }

    public function admin_download_package($plugin, $language) {
        $plugin = Inflector::camelize($plugin);
        $err = false;

        switch ($plugin) {
            case 'Core':
                $ppath = APP;
            break;

            case 'Site':
                $ppath = ROOT . DS;
            break;

            default:
                $ppath = CakePlugin::path($plugin);
            break;
        }

        if ($ppath) {
            $file = $ppath . 'Locale' . DS . $language . DS . 'LC_MESSAGES' . DS;
            $file .= $plugin === 'Core' ? 'default.po' : 'core.po';

            if (file_exists($file)) {
                $this->viewClass = 'Media';
                $params = array(
                    'id' => basename($file),
                    'name' => basename($file),
                    'download' => true,
                    'extension' => 'po',
                    'path' => dirname($file) . DS
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
        $plugin = Inflector::camelize($plugin);
        $language = strtolower($language);

        switch ($plugin) {
            case 'Core':
                $ppath = APP;
            break;

            case 'Site':
                $ppath = ROOT . DS;
            break;

            default:
                $ppath = CakePlugin::path($plugin);
            break;
        }

        // Core apps's .po cannot be deleted
        if (strpos($ppath, APP) === false) {
            if ($plugin == 'Core') {
                $file = $ppath . 'Locale' . DS . $language . DS . 'LC_MESSAGES' . DS . 'default.po';
            } else {
                $file = $ppath . 'Locale' . DS . $language . DS . 'LC_MESSAGES' . DS . 'core.po';
            }

            if (file_exists($file)) {
                $Folder = new Folder($ppath . 'Locale' . DS . $language . DS);

                if (!$Folder->delete()) {
                    $this->flashMsg(__t("Could not delete package folder. Please check folder permissions for '%s'.", $ppath . 'Locale' . DS . $language . DS), 'error');
                } else {
                    $this->flashMsg(__t('Language package removed!'), 'success');
                }
            } else {
                $this->flashMsg(__t('Invalid module or language '), 'error');
            }
        }

        $this->redirect('/admin/locale/packages');
    }

    public function admin_install() {
        if (!isset($this->data['Package']['po']) || empty($this->data['Package']['module'])) {
            $this->redirect('/admin/locale/packages');
        }

        switch ($this->data['Package']['module']) {
            case 'Core':
                $ppath = APP;
            break;

            case 'Site':
                $ppath = ROOT . DS;
            break;

            default:
                $ppath = CakePlugin::path(Inflector::camelize($this->data['Package']['module']));
            break;
        }

        if (strpos($ppath, APP) !== false) {
            $this->flashMsg(__t('Invalid module'), 'error');
            $this->redirect('/admin/locale/packages');
        }

        if (file_exists($ppath)) {
            if (in_array($this->data['Package']['language'], array_keys($this->__languageList()))) {
                App::import('Vendor', 'Upload');
                $destFolder = $ppath . 'Locale' . DS . $this->data['Package']['language'] . DS . 'LC_MESSAGES' . DS;
                $Folder = new Folder;
                $Upload = new Upload($this->data['Package']['po']);
                $Upload->file_overwrite = true;
                $Upload->file_new_name_ext = 'po';
                $Upload->file_new_name_body = 'core';
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