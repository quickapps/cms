<?php
/**
 * Language Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Locale.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Language extends LocaleAppModel {
	public $name = 'Language';
	public $useTable = "languages";
	public $primaryKey = 'id';
	public $order = 'Language.ordering ASC';
	public $validate = array(
		'code' => array(
			'len' => array('rule' => '/^[a-z]{3,3}$/s', 'message' => 'Code must be 3 letters (lowercase) long.'),
			'unique' => array('rule' => 'isUnique', 'message' => 'Language code already exists.')
		),
		'name' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'English name can not be empty.'),
		'native' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Native name can not be empty.'),
		'direction' => array('required' => true, 'allowEmpty' => false, 'rule' => '/^(ltr|rtl)$/s', 'message' => 'Invalid language direction.')
	);

	public function beforeValidate($options = array()) {
		if (!isset($this->data['Language']['id']) && !isset($this->data['Language']['addCustom'])) {
			// new language => determinate name&native
			if (isset($this->data['Language']['code'])) {
				App::import('Lib', 'Locale.QALocale');
				$l = QALocale::languages();

				if (!isset($this->data['Language']['name']) && isset($l[$this->data['Language']['code']])) {
					$this->data['Language']['name'] = $l[$this->data['Language']['code']];
				}

				if (!isset($this->data['Language']['native']) && isset($l[$this->data['Language']['code']])) {
					$this->data['Language']['native'] = $l[$this->data['Language']['code']];
				}

				if (!isset($this->data['Language']['direction'])) {
					$this->data['Language']['direction'] = QALocale::languageDirection();
				}
			}
		}

		return true;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['Language']['custom_icon']) && trim($this->data['Language']['custom_icon']) !== '') {
			$this->data['Language']['icon'] = $this->data['Language']['custom_icon'];
		}

		if (!isset($this->data['Language']['code'])) {
			return true;
		}

		if ($this->data['Language']['code'] == Configure::read('Variable.default_language') ||
			(isset($this->data['Language']['id']) && $this->data['Language']['id'] == 1) // id = 1 = eng
		) {
			// prevent desactivation
			$this->data['Language']['status'] = 1;
		}

		return true;
	}

	public function beforeDelete($cascade = true) {
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

	public function move($id, $dir = 'up') {
		if (!($record = $this->findById($id))) {
			return false;
		}

		// get brothers
		$nodes = $this->find('all',
			array(
				'order' => array('Language.ordering' => 'ASC'),
				'fields' => array('id', 'ordering'),
				'recursive' => -1
			)
		);

		$ids = Hash::extract($nodes, '{n}.Language.id');

		if (($dir == 'down' && $ids[count($ids) - 1] == $record['Language']['id']) ||
			($dir == 'up' && $ids[0] == $record['Language']['id'])
		) {
			// edge => cant go down/up
			return false;
		}

		$position = array_search($record['Language']['id'], $ids);
		$key = ($dir == 'up') ? $position - 1 : $position + 1;
		$tmp = $ids[$key];
		$ids[$key] = $ids[$position];
		$ids[$position] = $tmp;
		$i = 0;
		$prev_id = $this->id;

		foreach ($ids as $id) {
			$this->id = $id;
			$i++;

			$this->saveField('ordering', $i, false);
		}

		$this->id = $prev_id;

		return true;
	}

	private function __languageIdByCode($code) {
		$l = Configure::read('Variable.languages');
		$l = Hash::extract($l, "{n}.Language[code={$code}]");

		if (isset($l[0]['id'])) {
			return $l[0]['id'];
		}

		return false;
	}
}
