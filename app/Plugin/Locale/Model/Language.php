<?php
/**
 * Language Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Locale.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class Language extends LocaleAppModel {
    public $name = 'Language';
    public $useTable = "languages";
    public $primaryKey = 'id';
    public $order = array('Language.ordering' => 'ASC');
    public $validate = array(
        'code' => array(
            'len' => array(
                'rule' => '/^[a-z]{3,3}$/s',
                'message' => 'Code must be 3 letters (lowercase) long.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Language code already exists'
            )
        ),
        'name' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'English name can not be empty.'),
        'native' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Native name can not be empty.'),
        'direction' => array('required' => true, 'allowEmpty' => false, 'rule' => '/^(ltr|rtl)$/s', 'message' => 'Invalid language direction.')
    );

    public function beforeValidate() {
        if (!isset($this->data['Language']['id']) && !isset($this->data['Language']['addCustom'])) { # new language -> determinate name&native
            if (isset($this->data['Language']['code'])) {
                App::import('Lib', 'Locale.Locale');
                $l = Locale::languages();

                if (!isset($this->data['Language']['name'])) {
                    $this->data['Language']['name'] = @$l[$this->data['Language']['code']];
                }

                if (!isset($this->data['Language']['native'])) {
                    $this->data['Language']['native'] = @$l[$this->data['Language']['code']];
                }

                if (!isset($this->data['Language']['direction'])) {
                    $this->data['Language']['direction'] = Locale::language_direction();
                }
            }
        }

        return true;
    }

    public function beforeSave() {
        if (isset($this->data['Language']['custom_icon']) && trim($this->data['Language']['custom_icon']) !== '') {
            $this->data['Language']['icon'] = $this->data['Language']['custom_icon'];
        }

        if (!isset($this->data['Language']['code'])) {
            return true;
        }

        if ($this->data['Language']['code'] == Configure::read('Variable.default_language') ||
            (isset($this->data['Language']['id']) && $this->data['Language']['id'] == 1) #id=1=eng
        ) {
            $this->data['Language']['status'] = 1; # prevent desactivation
        }

        return true;
    }

    public function beforeDelete() {
        if (in_array($this->id, array(1, $this->__languageIdByCode(Configure::read('Variable.default_language'))))) {
            return false;
        }

        $language = $this->read();
        $i18n = ClassRegistry::init('Locale.Internationalization');

        $i18n->deleteAll(
            array(
                'locale' => $language['Language']['code'],
                'model' => 'Locale.Translation'
            )
        );

        return true;
    }

    private function __languageIdByCode($code) {
        $l = Configure::read('Variable.languages');
        $l = Set::extract("/Language[code={$code}]/..", $l);

        if (isset($l[0]['Language']['id'])) {
            return $l[0]['Language']['id'];
        }

        return false;
    }
}