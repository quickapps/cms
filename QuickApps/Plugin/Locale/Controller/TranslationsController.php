<?php
/**
 * Translations Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Locale.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class TranslationsController extends LocaleAppController {
	public $name = 'Translations';
	public $uses = array('Locale.Translation', 'Locale.Fuzzy');
	public $helpers = array('Text');

	public function admin_index() {
		$this->redirect('/admin/locale/translations/list');
	}

	public function admin_list() {
		$this->Translation->unbindModel(array('hasMany' => array('I18n')));

		$paginationScope = array();

		if (isset($this->data['Translation']['filter']) || $this->Session->check('Translation.filter')) {
			if (isset($this->data['Translation']['filter']) && empty($this->data['Translation']['filter'])) {
				$this->Session->delete('Translation.filter');
			} else {
				$filter = isset($this->data['Translation']['filter']) ? $this->data['Translation']['filter'] : $this->Session->read('Translation.filter');

				foreach ($filter as $field => $value) {
					if ($value !== '') {
						$field = "{$field} LIKE";
						$value = str_replace('*', '%', $value);
						$paginationScope[$field] = "%{$value}%";
					}
				}

				$this->Session->write('Translation.filter', $filter);
			}
		}

		$results = $this->paginate('Translation', $paginationScope);

		$this->set('results', $results);
		$this->setCrumb(
			'/admin/locale',
			array(__t('Translatable entries'))
		);
		$this->title(__t('Translatable Entries'));
	}

	public function admin_fuzzy_list() {
		if (isset($this->data['Fuzzy']['update']) && isset($this->data['Items']['id'])) {
			switch ($this->data['Fuzzy']['update']) {
				case 'hide':
					foreach ($this->data['Items']['id'] as $id) {
						$this->Fuzzy->toggle($id, 1);
					}
				break;

				case 'unhide':
					foreach ($this->data['Items']['id'] as $id) {
						$this->Fuzzy->toggle($id, 0);
					}
				break;

				case 'delete':
					foreach ($this->data['Items']['id'] as $id) {
						$this->Fuzzy->delete($id);
					}
				break;

				case 'export':
					$ids = array();

					foreach ($this->data['Items']['id'] as $id) {
						$ids[] = $id;
					}

					if (!empty($ids)) {
						$this->admin_export($ids);

						return;
					}
				break;
			}

			$this->redirect($this->referer());
		}

		if (
			isset($this->request->params['named']['hide']) ||
			isset($this->request->params['named']['unhide'])
		) {
			$id = isset($this->request->params['named']['hide']) ? $this->request->params['named']['hide'] : $this->request->params['named']['unhide'];
			$to = isset($this->request->params['named']['hide']) ? 1 : 0;

			$this->Fuzzy->toggle($id, $to);
			$this->redirect($this->referer());
		}

		if (isset($this->request->params['named']['clear'])) {
			clearCache('fuzzy_', 'i18n', '*');

			$this->redirect($this->referer());
		}

		$paginationScope = array(
			'Fuzzy.language' => Configure::read('Config.language')
		);

		if (isset($this->data['Fuzzy']['filter']) || $this->Session->check('Fuzzy.filter')) {
			if (isset($this->data['Fuzzy']['filter']) && empty($this->data['Fuzzy']['filter'])) {
				$this->Session->delete('Fuzzy.filter');
			} else {
				$filter = isset($this->data['Fuzzy']['filter']) ? $this->data['Fuzzy']['filter'] : $this->Session->read('Fuzzy.filter');

				foreach ($filter as $field => $value) {
					if ($value !== '') {
						$field = "Fuzzy.{$field}";
						$doLike = strpos($field, 'original') !== false || strpos($field, 'file') !== false;
						$field = $doLike ? "{$field} LIKE" : $field;
						$value = str_replace('*', '%', $value);
						$paginationScope[$field] = $doLike ? "%{$value}%" : $value;
					}
				}

				$this->Session->write('Fuzzy.filter', $filter);
			}
		}

		$results = $this->paginate('Fuzzy', $paginationScope);
		$results = isset($results['Fuzzy']) && empty($results['Fuzzy']) ? array() : $results;

		App::uses('QALocale', 'Locale.Lib');
		$this->set('results', $results);
		$this->setCrumb(
			'/admin/locale',
			array(__t('Translatable entries'), '/admin/locale/translations/list'),
			array(__t('Fuzzy entries'))
		);
	}

	public function admin_fuzzy_delete($id = false) {
		if ($id) {
			if ($this->Fuzzy->delete($id)) {
				$this->flashMsg(__t('Fuzzy entry has been deleted.'));
			} else {
				$this->flashMsg(__t('Fuzzy entry was not found.'), 'error');
			}
		}

		$this->redirect($this->referer());
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
		$this->setCrumb(
			'/admin/locale',
			array(__t('Translatable entries'))
		);
		$this->title(__t('Editing entry'));
	}

	public function admin_add() {
		if (isset($this->data['Translation'])) {
			if ($this->Translation->saveAll($this->data)) {
				$this->flashMsg(__t('Entry has been saved'), 'success');

				if (isset($this->request->params['named']['fuzzy'])) {
					Cache::delete("fuzzy_{$this->request->params['named']['fuzzy']}", 'i18n');
				}

				$this->redirect("/admin/locale/translations/edit/{$this->Translation->id}");
			} else {
				$this->flashMsg(__t('Entry could not be saved. Please, try again.'), 'error');
			}
		}

		if (isset($this->request->params['named']['fuzzy'])) {
			if ($cache = Cache::read("fuzzy_{$this->request->params['named']['fuzzy']}", 'i18n')) {
				$data = $this->data;
				$data['Translation']['original'] = $cache['original'];
				$this->data = $data;
			}
		}

		$this->setCrumb(
			'/admin/locale',
			array(__t('Translatable entries'), '/admin/locale/translations')
		);
		$this->title(__t('Add new entry'));
	}

	public function admin_regenerate($id) {
		$t = $this->Translation->findById($id);

		if ($t) {
			$this->Translation->save($t);
		}

		$this->redirect('/admin/locale/translations/list');
	}

	public function admin_delete($id) {
		$this->Translation->delete($id);
		$this->redirect('/admin/locale/translations/list');
	}

	public function admin_import() {
		if (
			isset($this->data['Package']['data']) &&
			isset($this->data['Package']['language']) &&
			preg_match('/^[a-z]{3}$/s', $this->data['Package']['language'])
		) {
			App::import('Vendor', 'Upload');
			App::uses('I18n', 'I18n');

			$Upload = new Upload($this->data['Package']['data']);
			$Upload->file_overwrite = true;
			$Upload->file_src_name_ext = 'pot';
			$Upload->file_new_name_body = 'fuzzy_import';

			$Upload->Process(CACHE . 'i18n' . DS);

			if (!$Upload->processed) {
				$this->flashMsg($Upload->error, 'error');
			} else {
				$path = $Upload->file_dst_pathname;
				$content = file_get_contents($path);
				$content = str_replace("msgid \"\"\nmsgstr \"\"", '', $content);

				file_put_contents($path, $content);

				$imported = 0;
				$language = $this->data['Package']['language'];
				$entries = I18n::loadPo($path);

				foreach ($entries as $msgid => $msgstr) {
					if (!empty($msgid)) {
						$entryId = false;
						$exists = $this->Translation->find('first',
							array(
								'conditions' => array(
									'Translation.original' => $msgid
								)
							)
						);

						// register new entry
						if (!$exists) {
							$save = array(
								'Translation' => array(
									'original' => $msgid
								)
							);

							$this->Translation->create($save);

							if ($new = $this->Translation->save()) {
								$entryId = $new['Translation']['id'];
								$imported++;
							}
						} else {
							$entryId = $exists['Translation']['id'];
						}

						// register translation for the given language
						if ($entryId && !empty($msgstr)) {
							$conditions = array(
								'foreign_key' => $entryId,
								'model' => 'Locale.Translation',
								'locale' => $language
							);
							$i18nExists = $this->Translation->I18n->find('first',
								array(
									'conditions' => $conditions
								)
							);

							if ($i18nExists) {
								$i18nExists['content'] = $msgstr;
								$this->Translation->I18n->save($i18nExists);
							} else {
								$conditions['content'] = $msgstr;
								$this->Translation->I18n->create($conditions);
								$this->Translation->I18n->save();
							}
						}
					}
				}

				$this->flashMsg(__t('%s entries has been imported.', $imported));
				$this->redirect($this->referer());
			}
		}

		$this->set('languages', $this->_languageList());
		$this->setCrumb(
			'/admin/locale',
			array(__t('Translatable entries'), '/admin/locale/translations/list'),
			array(__t('Import entries'))
		);
		$this->title(__t('Import Entries'));
	}

	public function admin_export($fuzzy = false) {
		App::uses('QALocale', 'Locale.Lib');

		if ($fuzzy) {
			$conditions = array();

			if (!is_array($fuzzy)) {
				if (!CakeSession::read('Fuzzy.filter.hidden')) {
					$conditions['Fuzzy.hidden'] = 0;
				}
			} else {
				$conditions['Fuzzy.id'] = $fuzzy;
			}

			$entries = $this->Fuzzy->find('all', array('conditions' => $conditions));
		} else {
			$entries = $this->Translation->find('all');
		}

		$key = $fuzzy ? 'Fuzzy' : 'Translation';
		$fileId = Inflector::underscore("{$key}Export") . '.pot';
		$output  = "# LANGUAGE translation of QuickApps CMS Site\n";
		$output .= "# Copyright YEAR NAME <EMAIL@ADDRESS>\n";
		$output .= "#\n";
		$output .= "#, fuzzy\n";
		$output .= "msgid \"\"\n";
		$output .= "msgstr \"\"\n";
		$output .= "\"Project-Id-Version: PROJECT VERSION\\n\"\n";
		$output .= "\"POT-Creation-Date: " . date("Y-m-d H:iO") . "\\n\"\n";
		$output .= "\"PO-Revision-Date: YYYY-mm-DD HH:MM+ZZZZ\\n\"\n";
		$output .= "\"Last-Translator: NAME <EMAIL@ADDRESS>\\n\"\n";
		$output .= "\"Language-Team: LANGUAGE <EMAIL@ADDRESS>\\n\"\n";
		$output .= "\"MIME-Version: 1.0\\n\"\n";
		$output .= "\"Content-Type: text/plain; charset=utf-8\\n\"\n";
		$output .= "\"Content-Transfer-Encoding: 8bit\\n\"\n";
		$output .= "\"Plural-Forms: nplurals=INTEGER; plural=EXPRESSION;\\n\"\n\n";

		foreach ($entries as $entry) {
			if ($fuzzy) {
				$file = str_replace(ROOT, '', $entry[$key]['file']);
				$output .= "#: {$file}:{$entry[$key]['line']}\n";
			}

			$msgid = str_replace('"', '\"', $entry[$key]['original']);
			$output .= "msgid \"{$msgid}\"\n";
			$output .= "msgstr \"\"\n\n";
		}

		file_put_contents(CACHE . 'i18n' . DS . $fileId, $output);

		$this->viewClass = 'Media';
		$params = array(
			'id'		=> $fileId,
			'name'	  => strtolower($key),
			'download'  => true,
			'extension' => 'pot',
			'path'	  => CACHE . 'i18n' . DS
		);
		$this->set($params);
	}
}