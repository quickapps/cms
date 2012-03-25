<?php
/**
 * Languages Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Locale.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 */
class LanguagesController extends LocaleAppController {
    public $name = 'Languages';
    public $uses = array('Locale.Language');

    public function admin_index() {
        if (isset($this->data['Language']['update'])) {
            $redirect = $this->referer();

            if (isset($this->data['Items']['id'])) {
                $update = (!in_array($this->data['Language']['update'], array('delete')));
                switch ($this->data['Language']['update']) {
                    case 'enable':
                        default:
                            $data = array('field' => 'status', 'value' => 1);
                    break;

                    case 'disable':
                        $data = array('field' => 'status', 'value' => 0);
                    break;
                }

                foreach ($this->data['Items']['id'] as $key => $id) {
                    if (in_array($id, array(1, $this->__languageIdByCode(Configure::read('Variable.default_language'))))) {
                        continue;
                    }

                    if (($c = $this->__languageCodeById($id)) == Configure::read('Variable.language.code')) {
                        $redirect = str_replace('/' . $c . '/', '/' . Configure::read('Variable.default_language') . '/', $redirect);
                    }

                    if ($update) {
                        // update langs
                        $this->Language->id = $id;
                        $this->Language->saveField($data['field'], $data['value'], false);
                    } else {
                        // delete node
                        switch ($this->data['Language']['update']) {
                            case 'delete':
                                $this->admin_delete($id);
                            break;
                        }
                    }
                }
            }

            $this->redirect($redirect);
        }

        $this->__setLangs();
        $this->setCrumb('/admin/locale');
        $this->set('results', $this->Language->find('all'));
    }

    public function admin_set_default($id) {
        $language = $this->Language->findById($id) or $this->redirect($this->referer());

        if (!$language['Language']['status']) {
            $this->redirect($this->referer());
        }

        $this->Variable->save(
            array(
                'name' => 'default_language',
                'value' => $language['Language']['code']
            )
        );
        Cache::delete('Variable');
        $this->QuickApps->loadVariables();
        $this->redirect($this->referer());
    }

    public function admin_add() {
        if (isset($this->data['Language']) && isset($this->data['Language']['custom_code'])) {
            $data = $this->data;
            $data['Language']['code'] = $data['Language']['custom_code'];
            if ($this->Language->save($data)) {
                $this->flashMsg(__t('New language has been saved'), 'success');
                $this->redirect("/admin/locale/languages/edit/{$this->Language->id}");
            } else {
                $this->flashMsg(__t('Language could not be saved. Please, try again.'), 'error');
            }
        } elseif (isset($this->data['Language'])) {
            if ($this->Language->save($this->data)) {
                $this->flashMsg(__t('New language has been saved'), 'success');
                $this->redirect("/admin/locale/languages/edit/{$this->Language->id}");
            } else {
                $this->flashMsg(__t('Language could not be saved. Please, try again.'), 'error');
            }
        }
        $this->redirect('/admin/locale/languages');
    }

    public function admin_edit($id) {
        if (isset($this->data['Language']['id'])) {
            $data = $this->data;
            if ($this->Language->save($data)) {
                $this->flashMsg(__t('Language has been saved'), 'success');
                $this->redirect($this->referer());
            } else {
                $this->flashMsg(__t('Language could not be saved. Please, try again.'), 'error');
            }
        }

        $this->data = $this->Language->findById($id) or $this->redirect($this->referer());
        $Folder = new Folder(CakePlugin::path('Locale') . 'webroot' . DS . 'img' . DS . 'flags');
        $__icons = $Folder->read();

        foreach ($__icons[1] as $gif) {
            $icons[$gif] = str_replace('.gif', '', $gif);
        }

        $this->set('flags', $icons);
        $this->setCrumb(
            '/admin/locale',
            array(__t('Editing language'))
        );
        $this->title(__t('Editing language'));
    }

    public function admin_move($id, $dir) {
        $this->Language->move($id, $dir);
        $this->redirect($this->referer());
    }

    public function admin_delete($id) {
        return $this->Language->delete($id);
    }

    private function __languageCodeById($id) {
        $l = Configure::read('Variable.languages');
        $l = Set::extract("/Language[id={$id}]/..", $l);

        if (isset($l[0]['Language']['code'])) {
            return $l[0]['Language']['code'];
        }

        return false;
    }

    private function __languageIdByCode($code) {
        $l = Configure::read('Variable.languages');
        $l = Set::extract("/Language[code={$code}]/..", $l);

        if (isset($l[0]['Language']['id'])) {
            return $l[0]['Language']['id'];
        }

        return false;
    }

    private function __setLangs() {
        App::import('Lib', 'Locale.QALocale');
        $this->set('languages', QALocale::languages());
        QALocale::language_direction('eng');
    }
}
