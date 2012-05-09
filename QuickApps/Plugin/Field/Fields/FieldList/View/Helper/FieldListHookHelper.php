<?php
class FieldListHookHelper extends AppHelper {
	public function field_list_formatter($data) {
		$_options = $options = array();

		if (!empty($data['options'])) {
			$_options = explode("\n", $data['options']);

			foreach ($_options as $option) {
				$option = explode("|",$option);
				$value = $option[0];
				$label = isset($option[1]) ? $option[1] : $option[0];
				$options[$value] = $label;
			}
		}

		$content = explode("|", $data['content']);
		$data['content'] = '';

		foreach ($content as $key) {
			switch($data['format']['type']) {
				case 'key':
					$data['content'] .= "{$key}<br/>";
				break;

				case 'default':
					// Label
					default:
						$data['content'] .= @"{$options[$key]}<br/>";
				break;
			}
		}

		return $data['content'];
	}
}