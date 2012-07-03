<?php
/**
 * Fuzzy entries Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Locale.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */

App::uses('ArraySource', 'Locale.Model/Datasource');
ConnectionManager::create('fuzzy_entries', array('datasource' => 'Locale.ArraySource'));

class Fuzzy extends LocaleAppModel {
/**
 * Database Configuration
 *
 * @var string
 */
	public $useDbConfig = 'fuzzy_entries';

/**
 * Set recursive
 *
 * @var integer
 */
	public $recursive = -1;

/**
 * List of records to emulate.
 *
 * @var array
 */
	public $records = null;

/**
 * Loads all fuzzy entries from cache to be emulated by
 * `ArraySource` datasource.
 *
 * @param boolean $show_hidden Load hidden entries as well
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		$Folder = new Folder(CACHE . 'i18n' . DS);
		$language = Configure::read('Config.language');
		$files = $Folder->find(".*_fuzzy_[a-z0-9]{1,}_{$language}");

		foreach($files as $file) {
			preg_match('/^.*_fuzzy_(.*)$/i', $file, $matches);

			$this->records[] = Cache::read("fuzzy_{$matches[1]}", 'i18n');
		}

		parent::__construct();
	}

/**
 * Hide/Unhide the given fuzzy entry.
 *
 * @param string $hash ID of entry to toggle
 * @param integer $to New status. 1 = Hidden, 0 = Not hidden
 * @return boolean TRUE on success, FALSE otherwise
 */
	public function toggle($id, $to = 1) {
		$data = Cache::read("fuzzy_{$id}", 'i18n');

		if (!empty($data)) {
			$data['hidden'] = $to;

			Cache::write("fuzzy_{$id}", $data, 'i18n');

			return true;
		}

		return false;
	}

	public function delete($id) {
		$cache = Cache::read("fuzzy_{$id}", 'i18n');

		if ($cache) {
			return Cache::delete("fuzzy_{$id}", 'i18n');
		}

		return false;
	}
}