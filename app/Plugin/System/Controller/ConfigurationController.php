<?php
/**
 * Configuration Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.System.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class ConfigurationController extends AppController {
    public $name = 'Configuration';
    public $uses = array();

    public function admin_index() {
        if (isset($this->data['Variable'])) {
            $err = false;

            foreach ($this->data['Variable'] as $name => $value) {
                if ($name == 'site_name' && empty($value)) {
                    $this->Variable->invalidate('site_name', 'Site name can not be blank');

                    $err = true;
                    break;
                } elseif ($name == 'site_mail' && (empty($value) || !Validation::email($value))) {
                    $this->Variable->invalidate('site_mail', 'Invalid site email');

                    $err = true;
                    break;
                } else {
                    $this->Variable->save( array('name' => $name, 'value' => $value));
                }
            }

            if (!$err) {
                $this->flashMsg(__t('Configuration has been saved.'), 'success');
            } else {
                $this->flashMsg(__t('Configuration could not be saved. Please, try again.'), 'error');
            }

            $this->redirect('/admin/system/configuration');
        } else {
            $this->__setLangs();

            $data = array();
            $data['Variable'] = Configure::read('Variable');
            $this->data = $data;
        }
    }

    private function __setLangs() {
        $languages = array();

        foreach (Configure::read('Variable.languages') as $l) {
            $languages[$l['Language']['code']] = $l['Language']['native'];
        }

        $this->set('languages', $languages);
    }
}