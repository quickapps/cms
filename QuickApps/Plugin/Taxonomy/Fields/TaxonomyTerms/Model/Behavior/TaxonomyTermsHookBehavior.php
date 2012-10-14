<?php
class TaxonomyTermsHookBehavior extends ModelBehavior {
	private $__tmp = array();

/**
 * Create Entity's tags cache for search queries,
 * this feature is available only for `Node` entity.
 *
 * Only Nodes (contents) are alowed to appear in search results with tag filter option,
 * Means that only Nodes will be listed in search queries like:
 *
 *     http://www.example.com/s/term:my-tag,other-tag
 *
 * @param array $info Fieldable array data
 * @return boolean true always
 */
	public function taxonomy_terms_before_save($info) {
		$_searchIndex = '';

		foreach ($info['entity']->data['FieldData']['TaxonomyTerms'] as $field_instance_id => $post) {
			if (is_string($post['data'])) {
				$data = explode(',', $post['data']);

				foreach ($data as &$id) {
					if (!is_numeric($id)) {
						$field_instance = ClassRegistry::init('Field.Field')->find('first',
							array(
								'conditions' => array(
									'Field.id' => $field_instance_id
								),
								'recursive' => -1
							)
						);

						ClassRegistry::init('Taxonomy.Term')->create();

						$new_term = ClassRegistry::init('Taxonomy.Term')->save(
							array(
								'name' => $id,
								'vocabulary_id' => $field_instance['Field']['settings']['vocabulary']
							)
						);

						$id = $new_term['Term']['id'];
					}
				}

				$post['data'] = implode(',', $data);
				$info['entity']->data['FieldData']['TaxonomyTerms'][$field_instance_id] = $post;
			}
		}

		if (isset($info['entity']->data['FieldData']['TaxonomyTerms']) &&
			$info['entity']->alias == 'Node' &&
			!isset($this->__tmp['before_save_' . $info['entity']->alias])
		) {
			$info['entity']->bindModel(
				array(
					'hasAndBelongsToMany' => array(
						'Term' => array(
							'joinTable' => 'nodes_terms',
							'className' => 'Taxonomy.Term',
							'foreignKey' => 'node_id',
							'associationForeignKey' => 'term_id',
							'unique' => true,
							'dependent' => false
						)
					)
				), false
			);

			$terms_cache = array();
			$terms_ids = $_terms_ids = array();
			$_terms_ids = (array)Hash::extract($info['entity']->data, 'FieldData.TaxonomyTerms.{n}.data');
			$_terms_ids = (array)Hash::filter($_terms_ids);

			if (!empty($_terms_ids)) {
				foreach ($_terms_ids as $key => $ids) {
					if (is_string($ids)) {
						$ids = explode(',', $ids);
					}

					$terms_ids = array_merge($terms_ids, $ids);
				}
			}

			$terms = ClassRegistry::init('Taxonomy.Term')->find('all',
				array(
					'fields' => array('slug', 'id'),
					'conditions' => array('Term.id' => $terms_ids)
				)
			);

			foreach ($terms as $term) {
				$info['entity']->data['Term']['Term'][] = array('field_id' => $info['field_id'], 'term_id' => $term['Term']['id']);
				$terms_cache[] = "{$term['Term']['id']}:{$term['Term']['slug']}";
				$_searchIndex .= ' ' . $term['Term']['slug'];
			}

			$info['entity']->data['Node']['terms_cache'] = implode('|', $terms_cache);
			$this->__tmp['before_save_' . $info['entity']->alias] = true;
		}

		$info['entity']->indexField($_searchIndex, $info['field_id']);

		return true;
	}

	public function taxonomy_terms_after_save($info) {
		if (empty($info)) {
			return true;
		}

		$info['id'] = empty($info['id']) || !isset($info['id']) ? null : $info['id'];
		$data['FieldData'] = array(
			'id' => $info['id'],
			'field_id' => $info['field_id'],
			'data' => implode(',', (array)$info['data']),
			'belongsTo' => $info['entity']->alias,
			'foreignKey' => $info['entity']->id
		);

		ClassRegistry::init('Field.FieldData')->create();
		ClassRegistry::init('Field.FieldData')->save($data);

		return true;
	}

	public function taxonomy_terms_after_find(&$data) {
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

	public function taxonomy_terms_before_validate($info) {
		$FieldInstance = ClassRegistry::init('Field.Field')->findById($info['field_id']);

		if (isset($FieldInstance['Field']['settings']['max_values']) && $FieldInstance['Field']['settings']['max_values'] != 0) {
			if (is_array($info['data']) && count($info['data']) > $FieldInstance['Field']['settings']['max_values']) {
				ClassRegistry::init('Field.FieldData')->invalidate(
					"TaxonomyTerms.{$info['field_id']}.data",
					__t('This field cannot hold more than 2 values.')
				);

				return false;
			}
		}

		if ($FieldInstance['Field']['required'] == 1) {
			$info['data'] = is_array($info['data']) ? implode('', $info['data']) : $info['data'];
			$filtered = strip_tags($info['data']);
			if (empty($filtered)) {
				ClassRegistry::init('Field.FieldData')->invalidate(
					"TaxonomyTerms.{$info['field_id']}.data",
					__t('You must select at least one option.')
				);

				return false;
			}
		}

		return true;
	}

	public function taxonomy_terms_before_delete($info) {
		return true;
	}

	public function taxonomy_terms_after_delete($info) {
		ClassRegistry::init('Field.FieldData')->deleteAll(
			array(
				'FieldData.belongsTo' => $info['entity']->alias,
				'FieldData.field_id' => $info['field_id'],
				'FieldData.foreignKey' => $info['entity']->id
			)
		);

		return true;
	}

	public function taxonomy_terms_after_delete_instance($FieldModel) {
		ClassRegistry::init('Field.FieldData')->deleteAll(
			array(
				'FieldData.field_id' => $FieldModel->data['Field']['id']
			)
		);

		ClassRegistry::init('NodesTerm')->deleteAll(
			array(
				'NodesTerm.field_id' => $FieldModel->data['Field']['id']
			)
		);
	}
}