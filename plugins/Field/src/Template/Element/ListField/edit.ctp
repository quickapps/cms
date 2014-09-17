<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<?php
	$options = [];
	if (!empty($field->metadata->settings['options'])) {
		foreach (explode("\n", $field->metadata->settings['options']) as $option) {
			$option = explode('|', $option);
			$value = $option[0];
			$label = isset($option[1]) ? $option[1] : $option[0];
			$options[$value] = $label;
		}
	}

	if (!empty($field->metadata->errors) && isset($this->request->data[":{$field->name}"])) {
		$selected = $this->request->data[":{$field->name}"];
	} else {
		$selected = $field->raw;
	}

	if ($field->metadata->settings['type'] === 'checkbox') {
		$inputOptions = [
			'type' => 'select',
			'label' => $field->label,
			'multiple' => 'checkbox',
			'options' => (array)$options,
			'value' => $selected,
		];
	} else {
		$inputOptions = [
			'type' => 'radio',
			'options' => $options,
			'legend' => $field->label,
			'value' => (is_array($selected) ? array_pop($selected) : $selected), // user may change from checkbox to radio
		];
	}

	echo $this->Form->input(":{$field->name}", $inputOptions);
?>

<?php if (!empty($field->metadata->description)): ?>
<em class="help-block"><?php echo $this->hooktags($field->metadata->description); ?></em>
<?php endif; ?>