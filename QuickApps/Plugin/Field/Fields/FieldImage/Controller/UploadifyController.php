<?php
class UploadifyController extends FieldImageAppController {
	public $uses = array('Field.Field');

	public function __construct($request = null, $response = null) {
		$params = Router::getParams();

		if ($params['action'] == 'upload') {
			App::uses('CakeSession', 'Model/Datasource');
			CakeSession::id($params['named']['session_id']);
			CakeSession::start();
		}

		parent::__construct($request, $response);
	}

	public function beforeFilter() {
		$this->autoRender = false;

		parent::beforeFilter();
		$this->QuickApps->disableSecurity();
	}

	public function delete($file, $field_id) {debug("here");
		App::uses('FieldImage', 'FieldImage.Lib');

		$field = $this->Field->findById($field_id);

		if ($field) {
			$folder = str_replace('/', DS, $field['Field']['settings']['upload_folder']);
			$path = WWW_ROOT . 'files' . DS . $folder . DS;
			$file = str_replace(DS . DS, DS, $path . $file);

			if (file_exists($file)) {
				FieldImage::unlink($file);
			}
		}
	}

	public function upload($field_id) {
		$field = $this->Field->findById($field_id);

		if (!$field) {
			header("HTTP/1.1 500 File Upload Error");
			die('Invalid field instance');
		}

		App::uses('Upload', 'Vendor');
		App::uses('FieldImage', 'FieldImage.Lib');

		$Upload = new Upload($this->params['form']['Filedata']);

		if (!in_array(strtolower($Upload->file_src_name_ext), explode(',', $field['Field']['settings']['extensions']))) {
			header("HTTP/1.1 500 Invalid file extension");
			die(__t('Invalid file extension.'));
		}

		$Upload->file_overwrite = false;

		if (
			$this->__validateOption($field['Field']['settings'], 'min_width') &&
			$this->__validateOption($field['Field']['settings'], 'min_height')
		) {
			$Upload->image_min_width = $field['Field']['settings']['min_width'];
			$Upload->image_min_height = $field['Field']['settings']['min_height'];
		}

		if (
			$this->__validateOption($field['Field']['settings'], 'max_width') &&
			$this->__validateOption($field['Field']['settings'], 'max_height')
		) {
			$Upload->image_max_width = $field['Field']['settings']['max_width'];
			$Upload->image_max_height = $field['Field']['settings']['max_height'];
		}

		if ($this->__validateOption($field['Field']['settings'], 'min_ratio')) {
			$Upload->image_min_ratio = $field['Field']['settings']['min_ratio'];
		}

		if ($this->__validateOption($field['Field']['settings'], 'max_ratio')) {
			$Upload->image_max_ratio = $field['Field']['settings']['max_ratio'];
		}

		if ($this->__validateOption($field['Field']['settings'], 'min_pixels')) {
			$Upload->image_min_pixels = $field['Field']['settings']['min_pixels'];
		}

		if ($this->__validateOption($field['Field']['settings'], 'max_pixels')) {
			$Upload->image_max_pixels = $field['Field']['settings']['max_pixels'];
		}

		$folder = WWW_ROOT . 'files' . DS;
		$folder .= isset($field['Field']['settings']['upload_folder']) && !empty($field['Field']['settings']['upload_folder']) ? str_replace('/', DS, $field['Field']['settings']['upload_folder']) : '';
		$url = '/files/';
		$url .= isset($field['Field']['settings']['upload_folder']) ? $field['Field']['settings']['upload_folder'] : '';
		$url = preg_replace('/\/{2,}/', '/',  "{$url}//");

		$Upload->Process($folder);

		if ($Upload->processed) {
			$return = array(
				'file_url' => Router::url($url . $Upload->file_dst_name, true),
				'file_size' => FieldImage::bytesToSize($Upload->file_src_size),
				'mime_icon' => FieldImage::fileIcon($Upload->file_src_mime),
				'file_name' => $Upload->file_dst_name
			);

			if (isset($field['Field']['settings']['preview']) && $field['Field']['settings']['preview']) {LogError($folder . $Upload->file_dst_name);
				list($nw, $nh) = FieldImage::getImageSize($folder . $Upload->file_dst_name, $field['Field']['settings']['preview']);
				$return['preview_width'] = $nw;
				$return['preview_height'] = $nh;
			}

			echo json_encode($return);
		} else {
			header("HTTP/1.1 500 File Upload Error");
			echo __t('Error: %s', $Upload->error);
		}

		die;
	}

	public function preview($field_id, $file_name, $width, $height) {
		$field = $this->Field->findById($field_id);

		if (!$field) {
			header("HTTP/1.1 500 File Upload Error");
			die('Invalid field instance');
		}

		App::uses('FieldImage', 'FieldImage.Lib');

		$folder = WWW_ROOT . 'files' . DS;
		$folder .= isset($field['Field']['settings']['upload_folder']) ? str_replace('/', DS, $field['Field']['settings']['upload_folder']) : '';

		FieldImage::imageResize($folder . $file_name, $width, $height);
		die(' ');
	}

	private function __validateOption($settings, $opt) {
		return (
			isset($settings[$opt]) &&
			!empty($settings[$opt]) &&
			is_numeric($settings[$opt])
		);
	}
}