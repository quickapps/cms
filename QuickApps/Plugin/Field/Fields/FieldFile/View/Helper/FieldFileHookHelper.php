<?php
class FieldFileHookHelper extends AppHelper {
	private $__instancesCount = 0;

	public function form_create_alter(&$data) {
		if (isset($this->_View->viewVars['Layout']['fields'][$data['model']]) &&
			in_array('FieldFile', $this->_View->viewVars['Layout']['fields'][$data['model']])
		) {
			$data['options']['enctype'] = 'multipart/form-data';
		}
	}

	public function field_file_libs() {
		$out = '';

		if (!$this->__instancesCount) {
			$this->_View->Layout->css('/field_file/js/uploadify/uploadify.css');
			$this->_View->Layout->css('/field_file/css/field_file.css');
			$this->_View->jQueryUI->add('sortable');
			$this->_View->Layout->script('/system/js/json.js');
			$this->_View->Layout->script('/field_file/js/field_file.js');
			$this->_View->Layout->script('/field_file/js/locale.js');
			$this->_View->Layout->script('/field_file/js/uploadify/swfobject.js');
			$this->_View->Layout->script('/field_file/js/uploadify/jquery.uploadify.v2.1.4.min.js');
			$this->_View->Layout->script("
			$(document).ready(function() {
				QuickApps.field_file.uploader = '" . Router::url('/field_file/js/uploadify/uploadify.swf') . "';
				QuickApps.field_file.session_id = '" . CakeSession::id() . "';
				QuickApps.field_file.cancelImg = '" . Router::url('/field_file/js/uploadify/cancel.png') . "';
			});", 'inline');
		}

		return $out;
	}

	public function field_file_formatter($data) {
		$__default = array(
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
		$base_url = '/files/' . $data['settings']['upload_folder'];

		foreach ($data['content']['files'] as $file) {
			$title = '';
			$title .= isset($file['mime_icon']) && !empty($file['mime_icon']) ? $this->_View->Html->image("/field_file/img/icons/{$file['mime_icon']}", array('border' => 0)) : '';
			$title .= ' ' . $file['file_name'];
			$description = $data['settings']['description'] && isset($file['description']) ? "<div class=\"description\"><em>{$file['description']}</em></div>": '';

			switch($data['format']['type']) {
				case 'link':
					case '':
						$content .= '<p>';
							$content .= $this->_View->Html->link($title, $base_url . $file['file_name'], array('escape' => false, 'target' => '_blank'));
							$content .= $description;
						$content .= '</p>';
				break;

				case 'table':
					$content .= '<tr>
						<td align="left">' . $this->_View->Html->link($title, $base_url . $file['file_name'], array('escape' => false, 'target' => '_blank')) . $description . '</td>
						<td align="center">' . $file['file_size'] . '</td>
					</tr>';
				break;

				case 'url':
					$content .= "<p>{$base_url}{$file['file_name']}</p>";
				break;

				default:
					$content .= $this->hook("field_file_formatter_{$data['format']['type']}", $__hookData = compact('file', 'base_url', 'data'));
				break;
			}
		}

		if ($data['format']['type'] == 'table' && count($data['content']['files'])) {
			$content = "
			<table width=\"100%\">
				<thead>
					<tr>
						<th align=\"left\" width=\"75%\">" . __t('Attachment') . "</th>
						<th align=\"center\">" . __t('Size') . "</th>
					</tr>
				</thead>

				<tbody>
					{$content}
				</tbody>
			</table>";
		}

		return $content;
	}
}