<?php
class FieldTextHookBehavior extends ModelBehavior {
	public function field_text_after_save($info) {
		if (empty($info)) {
			return true;
		}

		$field = ClassRegistry::init('Field.Field')->findById($info['field_id']);

		if (isset($field['Field']['settings']['text_processing']) && !empty($field['Field']['settings']['text_processing'])) {
			$info['entity']->hook('text_processing_' . $field['Field']['settings']['text_processing'], $info['data']);
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

	public function field_text_after_find(&$data) {
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

	public function field_text_before_validate($info) {
		$FieldInstance = ClassRegistry::init('Field.Field')->findById($info['field_id']);
		$errMsg = array();

		if (isset($FieldInstance['Field']['settings']['type']) &&
			$FieldInstance['Field']['settings']['type'] == 'text' &&
			isset($FieldInstance['Field']['settings']['max_len']) &&
			!empty($FieldInstance['Field']['settings']['max_len']) &&
			$FieldInstance['Field']['settings']['max_len'] > 0 &&
			strlen(trim($info['data'])) > $FieldInstance['Field']['settings']['max_len']
		) {
			$errMsg[] = __t('Max. %s characters length.', $FieldInstance['Field']['settings']['max_len']);
		}

		if ($FieldInstance['Field']['required'] == 1) {
			if (isset($FieldInstance['Field']['settings']['type']) && $FieldInstance['Field']['settings']['type'] == 'textarea') {
				$filtered = html_entity_decode(strip_tags($info['data']));
			} else {
				$filtered = strip_tags($info['data']);
			}

			if (empty($filtered)) {
				$errMsg[] = __t('Field required.');
			}
		}

		if (isset($FieldInstance['Field']['settings']['validation_rule']) && !empty($FieldInstance['Field']['settings']['validation_rule'])) {
			if (!preg_match($FieldInstance['Field']['settings']['validation_rule'], $info['data'])) {
				if (isset($FieldInstance['Field']['settings']['validation_message']) && !empty($FieldInstance['Field']['settings']['validation_rule'])) {
					$errMsg[] = __t($FieldInstance['Field']['settings']['validation_message']);
				} else {
					$errMsg[] = __t('Invalid field.');
				}
			}
		}

		if (!empty($errMsg)) {
			ClassRegistry::init('Field.FieldData')->invalidate(
				"FieldText.{$info['field_id']}.data",
				implode(", ", $errMsg)
			);

			return false;
		}

		return true;
	}

	public function field_text_before_delete($info) {
		return true;
	}

	public function field_text_after_delete($info) {
		ClassRegistry::init('Field.FieldData')->deleteAll(
			array(
				'FieldData.belongsTo' => $info['entity']->alias,
				'FieldData.field_id' => $info['field_id'],
				'FieldData.foreignKey' => $info['entity']->id
			)
		);

		return true;
	}

	public function field_text_after_delete_instance($FieldModel) {
		ClassRegistry::init('Field.FieldData')->deleteAll(
			array(
				'FieldData.field_id' => $FieldModel->data['Field']['id']
			)
		);
	}

	public function field_text_before_save_instance(&$Model) {
		if (!isset($Model->data['Field']['id']) || empty($Model->data['Field']['id'])) {
			$__default = array(
				'type' => 'text',
				'text_processing' => 'plain'
			);

			$Model->data['Field']['settings'] = Hash::merge($__default, $Model->data['Field']['settings']);
		}

		return true;
	}
}