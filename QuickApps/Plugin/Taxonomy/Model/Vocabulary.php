<?php
/**
 * Vocabulary Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Taxonomy.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Vocabulary extends TaxonomyAppModel {
	public $useTable = 'vocabularies';
	public $order = array('Vocabulary.ordering' => 'ASC');
	public $actsAs = array('Sluggable');
	public $validate = array(
		'title' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Vocabulary title can not be empty.'),
	);

	public $hasMany = array(
		'Term' => array(
			'className' => 'Taxonomy.Term',
			'foreignKey' => 'vocabulary_id'
		)
	);

	public function move($id, $dir = 'up') {
		if (!($record = $this->findById($id))) {
			return false;
		}

		$nodes = $this->find('all',
			array(
				'order' => array("Vocabulary.ordering" => 'ASC'),
				'fields' => array('id', 'ordering'),
				'recursive' => -1
			)
		);

		$ids = Hash::extract($nodes, '{n}.Vocabulary.id');

		if (($dir == 'down' && $ids[count($ids)-1] == $record['Vocabulary']['id']) ||
			($dir == 'up' && $ids[0] == $record['Vocabulary']['id'])
		) {
			// edge => cant go down/up
			return false;
		}

		$position = array_search($record['Vocabulary']['id'], $ids);
		$key = ($dir == 'up') ? $position-1 : $position+1;
		$tmp = $ids[$key];
		$ids[$key] = $ids[$position];
		$ids[$position] = $tmp;

		$i = 1;
		$prev_id = $this->id;

		foreach ($ids as $id) {
			$this->id = $id;
			$this->saveField('ordering', $i, false);
			$i++;
		}

		$this->id = $prev_id;

		return true;
	}
}