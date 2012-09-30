<?php
class TaxonomyTermsHookHelper extends AppHelper {
	private $__tmp = array(
		'autocompleteCount' => 0
	);

	public function taxonomy_terms_formatter($data) {
		$terms = ClassRegistry::init('Taxonomy.Term')->find('all',
			array(
				'conditions' => array(
					'Term.id' => explode(",", $data['content'])
				)
			)
		);

		if (isset($data['format']['type']) && $data['format']['type'] == 'hidden') {
			return '';
		}

		$data['content'] = array();

		foreach ($terms as $term) {
			switch($data['format']['type']) {
				case 'plain':
					default:
						$data['content'][]= "{$term['Term']['name']}";
				break;

				case 'link-localized':
					if(isset($data['format']['url_prefix']) && !empty($data['format']['url_prefix'])) {
						$prefix = trim($data['format']['url_prefix']) . ' ';
					} else {
						$prefix = '';
					}

					$data['content'][] = $this->_View->Html->link(__t($term['Term']['name']), "/search/{$prefix}term:{$term['Term']['slug']}");
				break;

				case 'plain-localized':
					$data['content'][]= __t($term['Term']['name']);
				break;
			}
		}

		return implode(', ', (array)$data['content']);
	}

	public function taxonomy_terms_render_autocomplete($field) {
		$out = "\n ";
		$prePopulate = array();

		if (!$this->__tmp['autocompleteCount']) {
			$this->_View->Layout->css('/taxonomy_terms/css/token-input.css');
			$this->_View->Layout->css('/taxonomy_terms/css/token-input-facebook.css');
			$this->_View->Layout->script('/taxonomy_terms/js/jquery.tokeninput.js');
			$this->__tmp['autocompleteCount']++;
		}

		$ids = explode(',', $field['FieldData']['data']);
		$field_id = Inflector::camelize("FieldDataTaxonomyTerms{$field['id']}Data");

		foreach ($ids as $id) {
			if ($id) {
				$term = ClassRegistry::init('Taxonomy.Term')->find('first',
					array(
						'conditions' => array(
							'Term.id' => $id
						),
						'fields' => array('Term.id', 'Term.name'),
						'recursive' => -1
					)
				);

				if (!empty($term['Term']['id']) && !empty($term['Term']['name'])) {
					$prePopulate[] = "{id: {$term['Term']['id']}, name: \"{$term['Term']['name']}\"}";
				}
			}
		}

		$prePopulate = "\n " . implode(",\n ", $prePopulate) . "\n ";
		$tokenLimit = !$field['settings']['max_values'] ? '' : "tokenLimit: {$field['settings']['max_values']},";
		$out .= "\n<script type=\"text/javascript\">\n";
		$out .= "$(document).ready(function() {\n";
		$out .= "$('#{$field_id}').tokenInput('" . Router::url("/admin/taxonomy_terms/tokeninput/suggest/{$field['settings']['vocabulary']}", true) . "',
			{
				allowNewItems: true,
				hintText: '" . __t('Type in a search term') . "',
				noResultsText: '" . __t('No results') . "',
				searchingText: '" . __t('Searching...') . "',
				deleteText: '" . __t('x') . "',
				{$tokenLimit}
				theme: 'facebook',
				preventDuplicates: true,
				prePopulate: [{$prePopulate}]
			}
		);";
		$out .= "});\n";
		$out .= "</script>\n";

		return $out;
	}
}