<?php
class UploadifyController extends FieldFileAppController {
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

	public function delete($file, $field_id) {
		$field = $this->Field->findById($field_id);

		if ($field) {
			$folder = str_replace('/', DS, $field['Field']['settings']['upload_folder']);
			$path = WWW_ROOT . 'files' . DS . $folder . DS;
			$file = str_replace(DS . DS, DS, $path . $file);

			if (file_exists($file)) {
				@unlink($file);
			}
		}
	}

	public function upload($field_id) {
		$field = $this->Field->findById($field_id);

		if (!$field) {
			header("HTTP/1.1 500 File Upload Error");
			die('Invalid field instance');
		}

		App::import('Vendor', 'Upload');
		App::import('Lib', 'FieldFile.FieldFile');

		$Upload = new Upload($this->params['form']['Filedata']);

		if (!in_array(strtolower($Upload->file_src_name_ext), explode(',', $field['Field']['settings']['extensions']))) {
			header("HTTP/1.1 500 Invalid file extension");
			die(__t('Invalid file extension.'));
		}

		$Upload->file_overwrite = false;
		$Upload->no_script = false;
		$folder = WWW_ROOT . 'files' . DS;
		$folder .= isset($field['Field']['settings']['upload_folder']) ? str_replace('/', DS, $field['Field']['settings']['upload_folder']) : '';
		$url = '/files/';
		$url .= isset($field['Field']['settings']['upload_folder']) ? $field['Field']['settings']['upload_folder'] : '';
		$url = preg_replace('/\/{2,}/', '/',  "{$url}//");

		$Upload->Process($folder);

		if ($Upload->processed) {
			$return = array(
				'file_url' => Router::url($url . $Upload->file_dst_name, true),
				'file_size' => FieldFile::bytesToSize($Upload->file_src_size),
				'mime_icon' => FieldFile::fileIcon($Upload->file_src_mime),
				'file_name' => $Upload->file_dst_name
			);

			echo json_encode($return);
		} else {
			header("HTTP/1.1 500 File Upload Error");
			echo __t('Error: %s', $Upload->error);
		}

		die;
	}
}