<?php
/**
 * BlockRegion Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Block.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class BlockRegion extends BlockAppModel {
	public $name = 'BlockRegion';
	public $useTable = 'block_regions';
	public $order = array('BlockRegion.ordering' => 'ASC');
	public $primaryKey = 'id';

	public function beforeSave($options = array()) {
		if (!isset($this->data['BlockRegion']['id'])) {
			$r = $this->data['BlockRegion']['region'];
			$t = $this->data['BlockRegion']['theme'];
			$c = $this->find('count', array('conditions' => array('BlockRegion.theme' => $t, 'BlockRegion.region' => $r)));
			$this->data['BlockRegion']['ordering'] = $c + 1;
		}

		return true;
	}

	public function move($id, $dir = 'up') {
		if (!$record = $this->findById($id)) {
			return false;
		}

		$nodes = $this->find('all',
			array(
				'conditions' => array(
					'BlockRegion.theme' => $record['BlockRegion']['theme'],
					'BlockRegion.region' => $record['BlockRegion']['region']
				),
				'order' => array('BlockRegion.ordering' => 'ASC'),
				'fields' => array('id', 'ordering'),
				'recursive' => -1
			)
		);
		$ids = Hash::extract($nodes, '{n}.BlockRegion.id');

		if (($dir == 'down' && $ids[count($ids) - 1] == $record['BlockRegion']['id']) ||
			($dir == 'up' && $ids[0] == $record['BlockRegion']['id'])
		) { // edge => cant go down/up
			return false;
		}

		$position = array_search($record['BlockRegion']['id'], $ids);
		$key = $dir == 'up' ? $position - 1 : $position + 1;
		$tmp = $ids[$key];
		$ids[$key] = $ids[$position];
		$ids[$position] = $tmp;

		$i = 1;

		foreach ($ids as $id) {
			$this->id = $id;
			$this->saveField('ordering', $i, false);
			$i++;
		}
	}

}