<?php
/**
 * Translations Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Locale.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class TranslationsController extends LocaleAppController {
    public $name = 'Translations';
    public $uses = array('Locale.Translation');

    public function admin_index() {
        $this->redirect('/admin/locale/translations/list');
    }

    public function admin_list() {
        $this->Translation->unbindModel(
            array(
                'hasMany' => array('I18n')
            )
        );

        $results = $this->paginate('Translation');

        foreach ($results as &$result) {
            $result['Translation']['original'] = $this->__textSnippet($result['Translation']['original'], 80);
        }

        $this->set('results', $results);
        $this->setCrumb('/admin/locale');
        $this->setCrumb( array(__t('Translatable entries'), ''));
        $this->title(__t('Translatable Entries'));
    }

    public function admin_edit($id) {
        if (isset($this->data['Translation'])) {
            if ($this->Translation->saveAll($this->data, array('validate' => false))) {
                $this->flashMsg(__t('Entry has been saved'), 'success');
                $this->redirect($this->referer());
            } else {
                $this->flashMsg(__t('Entry could not be saved. Please, try again.'),  'error');
            }
        }

        $this->data = $this->Translation->findById($id) or $this->redirect('/admin/locale/translations/list');
        $this->setCrumb('/admin/locale');
        $this->setCrumb(array(__t('Translations entries'), ''));
        $this->title(__t('Editing entry'));
    }

    public function admin_add() {
        if (isset($this->data['Translation'])) {
            if ($this->Translation->saveAll($this->data)) {
                $this->flashMsg(__t('Entry has been saved'), 'success');
                $this->redirect("/admin/locale/translations/edit/{$this->Translation->id}");
            } else {
                $this->flashMsg(__t('Entry could not be saved. Please, try again.'), 'error');
            }
        }

        $this->setCrumb('/admin/locale');
        $this->setCrumb(array(__t('Translatable entries'), '/admin/locale/translations'));
        $this->title(__t('Add new entry'));
    }

    public function admin_delete($id) {
        $this->Translation->delete($id);
        $this->redirect('/admin/locale/translations/list');
    }

    private function __textSnippet($string, $max_len = 180, $cutter = '...') {
        if (strlen($string) < $max_len) {
            return $string;
        }

        return substr($string, 0, $max_len) . $cutter;
    }
}