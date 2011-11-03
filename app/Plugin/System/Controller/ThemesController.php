<?php
/**
 * Themes Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.System.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class ThemesController extends SystemAppController {
    public $name = 'Themes';
    public $uses = array('Block.Block');
    public $components = array('Installer');

    public function admin_index() {
        $this->set('themes', $this->__availableThemes());
    }

    public function admin_set_theme($theme_name) {
        $data['Variable'] = array(
            'name' => 'site_theme',
            'value' => $theme_name
        );

        $themes = $this->__availableThemes();

        if (in_array($theme_name, array_keys($themes))) {
            $data['Variable']['name'] = strpos($theme_name, 'Admin') !== false ? 'admin_theme' : 'site_theme';

            $this->Variable->save($data);
            Cache::delete('Variable');
            $this->Quickapps->loadVariables(); # IMPORTANT! regenerate cache
        }

        $this->redirect('/admin/system/themes');
    }

    public function admin_settings($theme_name) {
        $themes = $this->__availableThemes();

        if (!in_array($theme_name, array_keys($themes))) {
            $this->redirect('/admin/system/themes');
        }

        if (isset($this->data['Module'])) {
            $this->Module->save($this->data);
            Cache::delete('Modules');
            $this->Quickapps->loadModules();
            $this->redirect($this->referer());
        }

        $data['Module'] = Configure::read('Modules.' . 'Theme' . $theme_name);
        $this->data = $data;

        $this->setCrumb('/admin/system/themes');
        $this->setCrumb(array(array(__t('Theme settings'), '')));
        $this->title(__t('Configure Theme'));
        $this->set('theme_name', $theme_name);
    }

    public function admin_uninstall($theme) {
        $Theme = "Theme{$theme}";

        if (!in_array($theme, array('Default', 'AdminDefault'))) {
            if ($this->Installer->uninstall($Theme)) {
                $this->flashMsg(__t("Theme '%s' has been uninstalled", $theme), 'success');
            } else {
                $this->flashMsg(__t("Error uninstalling theme '%s'", $theme), 'error');
            }
        }

        $this->redirect('/admin/system/themes');
    }

    public function admin_install() {
        if (!isset($this->data['Package']['data'])) {
            $this->redirect('/admin/system/themes');
        }

        if (!$this->Installer->install($this->data, array('type' => 'theme'))) {
            $errors = implode('<br />', $this->Installer->errors);
            $this->flashMsg("<b>" . __t('Theme could not been installed') . ":</b><br/>{$errors}", 'error');
        } else {
            $this->flashMsg(__t('Theme has been installed'), 'success');
        }

        $this->redirect('/admin/system/themes');
    }

    # render thumbnail of any theme in themed folder
    public function admin_theme_tn($theme_name) {
        /* Manual rendering */
        /* For some unknown reason, cakephp MediaView rendering fails */
        $filename = THEMES . $theme_name . DS . 'thumbnail.png';
        $specs = getimagesize($filename);

        header('Content-type: ' . $specs['mime']);
        header('Content-length: ' . filesize($filename));
        header('Cache-Control: public');
        header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('+1 year')));
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)));

        die(file_get_contents($filename));
    }

/*
 * Return all available themes (in themed folder)
 */
    private function __availableThemes() {
        $_themes = $this->Module->find('all', array('conditions' => array('Module.type' => 'theme')));
        $themes = array();

        foreach ($_themes as $theme) {
            $ThemeName = Inflector::camelize($theme['Module']['name']);
            $folder = str_replace('Theme', '', $ThemeName);
            $themes[$folder] = $theme['Module'];
            $yaml = THEMES . $folder . DS ."{$folder}.yaml";

            if (file_exists($yaml)) {
                $themes[$folder] = Set::merge($themes[$folder], Spyc::YAMLLoad($yaml));
            }
        }

        return $themes;
    }
}