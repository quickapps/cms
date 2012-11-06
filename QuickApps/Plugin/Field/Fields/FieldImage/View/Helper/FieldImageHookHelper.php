<?php
App::uses('FieldImage', 'FieldImage.Lib');

class FieldImageHookHelper extends AppHelper {
	private $__instancesCount = 0;

	public function form_create_alter(&$data) {
		if (isset($this->_View->viewVars['Layout']['fields'][$data['model']]) &&
			in_array('FieldImage', $this->_View->viewVars['Layout']['fields'][$data['model']])
		) {
			$data['options']['enctype'] = 'multipart/form-data';
		}
	}

	public function field_image_libs() {
		$out = '';

		if (!$this->__instancesCount) {
			$this->_View->Layout->css('/field_file/js/uploadify/uploadify.css');
			$this->_View->Layout->css('/field_image/css/field_image.css');
			$this->_View->jQueryUI->add('sortable');
			$this->_View->Layout->script('/system/js/json.js');
			$this->_View->Layout->script('/field_image/js/field_image.js');
			$this->_View->Layout->script('/field_image/js/locale.js');
			$this->_View->Layout->script('/field_file/js/uploadify/swfobject.js');
			$this->_View->Layout->script('/field_file/js/uploadify/jquery.uploadify.v2.1.4.min.js');
			$this->_View->Layout->script("
			$(document).ready(function() {
				QuickApps.field_image.uploader = '" . Router::url('/field_file/js/uploadify/uploadify.swf') . "';
				QuickApps.field_image.session_id = '" . CakeSession::id() . "';
				QuickApps.field_image.cancelImg = '" . Router::url('/field_file/js/uploadify/cancel.png') . "';
			});", 'inline');
		}

		return $out;
	}

	public function field_image_formatter($data) {
		$__default = array(
			'field_id' => 0,
			'content' => array(
				'files' => array()
			),
			'settings' => array(),
			'format' => array(
				'type' => ''
			)
		);
		$data = Hash::merge($__default, $data);
		$content = '';
		$node = $this->_View->Node->workingNode();

		if (isset($data['format']['image_style']) && !empty($data['format']['image_style'])) {
			$image_style = FieldImage::$previews[$data['format']['image_style']];
			$width = $image_style['width'];
			$height = $image_style['height'];
		} else {
			$image_style = false;
		}

		foreach ($data['content']['files'] as $file) {
			$title = @$file['image_title'];
			$alt = @$file['image_alt'];
			$img_url = $image_style ? "/field_image/uploadify/preview/{$data['field_id']}/{$file['file_name']}/{$width}/{$height}" : '/files/' . $data['settings']['upload_folder'] . $file['file_name'];

			switch($data['format']['type']) {
				case 'content':
					$link_to = $node ? "/{$node['Node']['node_type_id']}/{$node['Node']['slug']}.html" : '#';
					$content .= $this->_View->Html->image($img_url, array('url' => $link_to, 'alt' => $alt, 'title' => $title));
				break;

				case 'file':
					$link_to = '/files/' . $data['settings']['upload_folder'] . $file['file_name'];
					$content .= $this->_View->Html->image($img_url, array('url' => $link_to, 'alt' => $alt, 'title' => $title));
				break;

				case '':
					$content .= $this->_View->Html->image($img_url, array('alt' => $alt, 'title' => $title));
				break;

				default:
					$content .= $this->hook("field_image_formatter_{$data['format']['type']}", $__hookData = compact('file', 'base_url', 'img_url', 'data'));
				break;
			}
		}

		return $content;
	}
}