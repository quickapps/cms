<?php
class FieldDateHookBehavior extends ModelBehavior {
	public function field_date_before_save($info) {
		return true;
	}

	public function field_date_after_save($info) {
		if (empty($info)) {
			return true;
		}

		$info['id'] = empty($info['id']) || !isset($info['id']) ? null : $info['id'];
		$data['FieldData'] = array(
			'id' => $info['id'], // update or create
			'field_id' => $info['field_id'],
			'data' => $info['data'],
			'belongsTo' => $info['entity']->alias,
			'foreignKey' => $info['entity']->id
		);

		ClassRegistry::init('Field.FieldData')->save($data);
		$info['entity']->indexField($info['data'], $info['field_id']);

		return true;
	}

	public function field_date_after_find(&$data) {
		$data['field']['FieldData'] = ClassRegistry::init('Field.FieldData')->find('first',
			array(
				'conditions' => array(
					'FieldData.field_id' => $data['field']['id'],
					'FieldData.belongsTo' => $data['entity']->alias,
					'FieldData.foreignKey' => $data['entity_id']
				)
			)
		);

		$data['field']['FieldData'] = Hash::extract((array)$data['field']['FieldData'], 'FieldData');
		$data['field']['FieldData'] = isset($data['field']['FieldData'][0]) ? $data['field']['FieldData'][0] : $data['field']['FieldData'];

		return;
	}

	public function field_date_before_validate($info) {
		$FieldInstance = ClassRegistry::init('Field.Field')->findById($info['field_id']);

		if ($FieldInstance['Field']['required'] == 1) {
			if (empty($info['data'])) {
				ClassRegistry::init('Field.FieldData')->invalidate(
					"FieldDate.{$info['field_id']}.data",
					__t('Date cannot be empty.')
				);

				return false;
			}
		}

		return true;
	}

	public function field_date_before_delete($info) {
		return true;
	}

	public function field_date_after_delete($info) {
		ClassRegistry::init('Field.FieldData')->deleteAll(
			array(
				'FieldData.belongsTo' => $info['Model']->alias,
				'FieldData.field_id' => $info['field_id'],
				'FieldData.foreignKey' => $info['Model']->id
			)
		);

		return true;
	}

	public function field_date_after_delete_instance($FieldModel) {
		ClassRegistry::init('Field.FieldData')->deleteAll(
			array(
				'FieldData.field_id' => $FieldModel->data['Field']['id']
			)
		);
	}
}