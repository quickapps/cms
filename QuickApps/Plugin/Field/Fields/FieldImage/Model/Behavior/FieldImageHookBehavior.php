<?php
App::uses('FieldImage', 'FieldImage.Lib');

class FieldImageHookBehavior extends ModelBehavior {
	public function field_image_before_save($info) {
		return true;
	}

	public function field_image_before_save_instance(&$Model) {
		// new instance, then merge default settings
		if (!isset($Model->data['Field']['id']) || empty($Model->data['Field']['id'])) {
			$__default = array(
				'extensions' => 'png,gif,jpg,jpeg',
				'multi' => 1,
				'upload_folder' => 'field_image',
				'preview' => '',
				'description' => 0
			);

			$Model->data['Field']['settings'] = Hash::merge($__default, $Model->data['Field']['settings']);
		}

		$_ext = explode(',', str_replace(' ', '', $Model->data['Field']['settings']['extensions']));
		$ext = array();

		foreach ($_ext as $e) {
			if (!empty($e) && !in_array($e, $ext)) {
				$ext[] = strtolower($e);
			}
		}

		$ext = empty($ext) ? array('txt') : $ext;
		$Model->data['Field']['settings']['extensions'] = implode(',', $ext);

		if (!isset($Model->data['Field']['settings']['alt'])) {
			$Model->data['Field']['settings']['alt'] = 0;
		}

		if (!isset($Model->data['Field']['settings']['title'])) {
			$Model->data['Field']['settings']['title'] = 0;
		}

		if (!isset($Model->data['Field']['settings']['preview'])) {
			$Model->data['Field']['settings']['preview'] = '';
		}

		if (!isset($Model->data['Field']['settings']['upload_folder'])) {
			$Model->data['Field']['settings']['upload_folder'] = 'field_image';
		} else {
			$upload_folder = '/' . $Model->data['Field']['settings']['upload_folder'] . '/';
			$upload_folder = str_replace(DS, '/', $upload_folder);
			$upload_folder = preg_replace('/\/{2,}/', '/', $upload_folder);

			if ($upload_folder[0] === '/') {
				$upload_folder = substr($upload_folder, 1);
			}

			$Model->data['Field']['settings']['upload_folder'] = $upload_folder;
		}

		return true;
	}

	public function field_image_after_save($info) {
		if (empty($info)) {
			return;
		}

		$_searchIndex = '';
		$__defaultData = array('files' => array());
		$info['id'] =  empty($info['id']) || !isset($info['id']) ? null : $info['id'];
		$info['data'] = Hash::merge($__defaultData, @$info['data']);
		$instance = ClassRegistry::init('Field.Field')->findById($info['field_id']);
		$files = array();
		$i = 0;

		// fix fields array keys
		foreach ($info['data']['files'] as $key => $field_post) {
			$files[$i] = $field_post;

			if (isset($field_post['file_name'])) {
				$_searchIndex .= ' ' . $field_post['file_name'];
			}

			if (isset($field_post['image_title'])) {
				$_searchIndex .= ' ' . $field_post['image_title'];
			}

			if (isset($field_post['image_alt'])) {
				$_searchIndex .= ' ' . $field_post['image_alt'];
			}

			$i++;
		}

		$info['data']['files'] = $files;

		// delete old files
		if (is_numeric($info['id'])) {
			$data = ClassRegistry::init('Field.FieldData')->field('data', array('FieldData.id' => $info['id']));
			$data = !is_array($data) ? @unserialize($data) : $data;
			$data = Hash::merge($__defaultData, $data);

			$new_files = Hash::extract($info['data'], 'files.{n}.file_name');
			$old_files = Hash::extract($data, 'files.{n}.file_name');

			foreach ($old_files as $file_name) {
				if (!in_array($file_name, $new_files)) {
					$file_path = WWW_ROOT . 'files' . DS . str_replace('/', DS, $instance['Field']['settings']['upload_folder']) . DS . $file_name;

					FieldImage::unlink($file_path);
				}
			}
		}

		$_data = array(
			'id' => $info['id'], // storage update or create
			'field_id' => $info['field_id'],
			'data' => @serialize($info['data']),
			'belongsTo' => $info['entity']->alias,
			'foreignKey' => $info['entity']->id
		);

		ClassRegistry::init('Field.FieldData')->save($_data);
		$info['entity']->indexField($_searchIndex, $info['field_id']);

		return true;
	}

	public function field_image_after_find(&$data) {
		$data['field']['FieldData'] = ClassRegistry::init('Field.FieldData')->find('first',
			array(
				'conditions' => array(
					'FieldData.field_id' => $data['field']['id'],
					'FieldData.belongsTo' => $data['entity']->alias,
					'FieldData.foreignKey' => $data['entity_id']
				),
				'recursive' => -1
			)
		);

		$data['field']['FieldData'] = Hash::extract((array)$data['field']['FieldData'], 'FieldData');
		$data['field']['FieldData'] = isset($data['field']['FieldData'][0]) ? $data['field']['FieldData'][0] : $data['field']['FieldData'];

		if (isset($data['field']['FieldData']['data'])) {
			$data['field']['FieldData']['data'] = @unserialize($data['field']['FieldData']['data']);
		}

		return;
	}

	public function field_image_before_validate($info) {
		$FieldInstance = ClassRegistry::init('Field.Field')->findById($info['field_id']);

		if ($FieldInstance['Field']['required'] == 1) {
			$info['data']['files'] = !isset($info['data']['files']) ? array() : $info['data']['files'];
			$info['data']['files'] = Hash::filter($info['data']['files']);

			if (!count($info['data']['files'])) { // at leats one field required
				ClassRegistry::init('Field.FieldData')->invalidate(
					"FieldImage.{$info['field_id']}.uploader",
					__t('You must upload at least one file.')
				);

				return false;
			}
		}

		return true;
	}

	public function field_image_before_delete($info) {
		return true;
	}

	public function field_image_after_delete($info) {
		ClassRegistry::init('Field.FieldData')->deleteAll(
			array(
				'FieldData.belongsTo' => $info['entity']->alias,
				'FieldData.field_id' => $info['field_id'],
				'FieldData.foreignKey' => $info['entity']->id
			)
		);

		return true;
	}

	public function field_image_after_delete_instance($FieldModel) {
		ClassRegistry::init('Field.FieldData')->deleteAll(
			array(
				'FieldData.field_id' => $FieldModel->data['Field']['id']
			)
		);
	}
}