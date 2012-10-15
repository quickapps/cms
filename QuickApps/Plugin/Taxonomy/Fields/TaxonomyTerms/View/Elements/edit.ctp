<?php
	$data['field']['FieldData'] = !isset($data['field']['FieldData']) ? array() : $data['field']['FieldData'];
	$data['field']['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $data['field']['FieldData']);
	$options = array();

	$data['field']['settings'] = array_merge(
		array(
			'vocabulary' => 0,
			'type' => 'checkbox',
			'max_values' => 0
		),
		$data['field']['settings']
	);

	if ($data['field']['settings']['vocabulary'] > 0) {
		$options = ClassRegistry::init('Taxonomy.Term')->generateTreeList(
			array(
				'Term.vocabulary_id' => $data['field']['settings']['vocabulary']
			), null, null, '&nbsp;&nbsp;&nbsp;|-&nbsp;'
		);
	} else {
		echo "<label>{$data['field']['label']}</label>";
	   echo __t('You must <a href="%s">select a vocabulary</a> for this field!', Router::url("/admin/node/types/field_settings/{$data['field']['id']}"));

	   return;
	}

	if (isset($this->data['FieldData']['TaxonomyTerms'][$data['field']['id']]['data'])) {
		$selected = $this->data['FieldData']['TaxonomyTerms'][$data['field']['id']]['data'];
	} else {
		$selected = explode(',', (string)$data['field']['FieldData']['data']);
	}

	// max_values > 1
	$Options = array(
		'escape' => false,
		'type' => 'select',
		'label' => $data['field']['label'],
		'multiple' => ($data['field']['settings']['type'] === 'checkbox' ? 'checkbox' : true),
		'options' => $options,
		'value' => $selected
	);

	if (in_array($data['field']['settings']['type'], array('select', 'checkbox'))) {
		if ($data['field']['settings']['type'] == 'select' && $data['field']['settings']['max_values'] == 1) {
			$Options['multiple'] = false;
		} elseif ($data['field']['settings']['type'] == 'checkbox' && $data['field']['settings']['max_values'] == 1) {
			$Options['type'] = 'radio';
			$Options['separator'] = '<br />';
			$Options['legend'] = $data['field']['label'];
			$Options['value'] = @$selected[0];

			unset($Options['multiple']);
		}
	} else {
		$Options['type'] = 'text';
		$Options['class'] = 'tags';

		unset($Options['multiple'], $Options['options']);
		echo $this->Layout->hook('taxonomy_terms_render_autocomplete', $data['field']);
	}

	echo $this->Form->input("FieldData.TaxonomyTerms.{$data['field']['id']}.data", $Options);
	echo $this->Form->hidden("FieldData.TaxonomyTerms.{$data['field']['id']}.id", array('value' => $data['field']['FieldData']['id']));

	if (!empty($data['field']['description'])) {
		echo $this->Form->helpBlock($data['field']['description']);
	}