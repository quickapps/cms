<?php
/**
 * Translation Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Locale.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Translation extends LocaleAppModel {
	public $name = 'Translation';
	public $useTable = "translations";
	public $primaryKey = 'id';
	public $validate = array(
		'original' => array(
			'notEmpty' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => 'notEmpty',
				'message' => 'Original text can not be empty.'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Original text already exists.'
			)
		)
	);

	public $hasMany = array(
		'I18n' => array(
			'className' => 'Locale.Internationalization',
			'model' => 'Translation',
			'foreignKey' => 'foreign_key',
			'dependent' => true
		)
	);

	public function afterSave() {
		if (!isset($this->data['Translation']['original']) && isset($this->data['Translation']['id'])) {
			$original = $this->find('first',
				array(
					'conditions' => array(
						'Translation.id' => $this->data['Translation']['id']
					),
					'fields' => array('id', 'original'),
					'recursive' => -1
				)
			);
			$original = $original['Translation']['original'];
		} else {
			$original = $this->data['Translation']['original'];
		}

		$cacheID = md5($original);

		// delete fuzzy entry if exists
		Cache::delete("fuzzy_{$cacheID}", 'i18n');

		foreach ($this->data['I18n'] as $t) {
			Cache::delete("{$cacheID}_{$t['locale']}", 'i18n');
			Cache::write("{$cacheID}_{$t['locale']}", $t['content'], 'i18n');
		}

		return true;
	}

	public function beforeDelete() {
		$original = $this->field('original');
		$cacheID = md5($original);

		foreach (Configure::read('Variable.languages') as $l) {
			Cache::delete("{$cacheID}_{$l['Language']['code']}", 'i18n');
		}

		return true;
	}
}