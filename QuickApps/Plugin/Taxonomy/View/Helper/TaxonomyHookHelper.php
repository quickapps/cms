<?php
/**
 * Taxonomy View Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Taxonomy.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class TaxonomyHookHelper extends AppHelper {
/**
 * Toolbar menu for section: `Structure/Taxonomy`.
 *
 * @return void
 */
	public function beforeLayout($layoutFile) {
		if (Router::getParam('admin') &&
			$this->request->params['plugin'] == 'taxonomy' &&
			$this->request->params['controller'] == 'vocabularies' &&
			$this->request->params['action'] == 'admin_index'
		) {
			$this->_View->Block->push(array('body' => $this->_View->element('toolbar') . '<!-- TaxonomyHookHelper -->'), 'toolbar');
		}

		return true;
	}

/**
 * Block: Vocabularies.
 *
 * @return array formatted block array
 */
	public function taxonomy_vocabularies($block) {
		$cd = isset($block['Block']['settings']['terms_cache_duration']) ? $block['Block']['settings']['terms_cache_duration'] : '+10 minutes';

		Cache::config('terms_cache', array('engine' => 'File', 'duration' => $cd));

		$block['Block']['settings'] = Hash::merge(
			array(
				'vocabularies' => array(),
				'content_counter' => 0,
				'show_vocabulary' => 0
			),
			(array)$block['Block']['settings']
		);

		if (empty($block['Block']['settings']['vocabularies'])) {
			return false;
		}

		$vocabularies = Classregistry::init('Taxonomy.Vocabulary')->find('all',
			array(
				'conditions' => array('Vocabulary.id' => $block['Block']['settings']['vocabularies']),
				'recursive' => -1
			)
		);

		$body = '<ul id="taxonomy-vocabularies-' . $block['Block']['id'] . '" class="taxonomy-vocabularies">';

		if ($block['Block']['settings']['show_vocabulary']) {
			foreach ($vocabularies as $vocabulary) {
				$prefix = isset($block['Block']['settings']['url_prefix']) && !empty($block['Block']['settings']['url_prefix']) ? trim($block['Block']['settings']['url_prefix']) . ' ' : '';
				$url = "/search/{$prefix}vocabulary:{$vocabulary['Vocabulary']['slug']}";
				$body .= '<li>' . $this->_View->Html->link($vocabulary['Vocabulary']['title'], $url);
				$terms = ClassRegistry::init('Taxonomy.Term')->find('threaded',
					array(
						'conditions' => array('Term.vocabulary_id' => $vocabulary['Vocabulary']['id']),
						'order' => array('Term.lft' => 'ASC')
					)
				);

				foreach ($terms as &$term) {
					$this->__proccessTerm($term, $block, $count);
				}

				if ($terms) {
					$body .= $this->Menu->render($terms, array('model' => 'Term', 'id' => "{$vocabulary['Vocabulary']['slug']}-terms"));
				}

				$body .= '</li>';
			}
		} else {
			$terms = ClassRegistry::init('Taxonomy.Term')->find('threaded',
				array(
					'conditions' => array('Term.vocabulary_id' => $block['Block']['settings']['vocabularies']),
					'order' => array('Term.lft' => 'ASC')
				)
			);

			foreach ($terms as &$term) {
				$this->__proccessTerm($term, $block, $count);
			}

			$body .= $this->Menu->render($terms, array('model' => 'Term'));
		}

		$body .= '</ul>';

		return $body;
	}

	private function __proccessTerm(&$term, &$block, &$count) {
		$lc = Configure::read('Variable.language.code');
		$prefix = isset($block['Block']['settings']['url_prefix']) && !empty($block['Block']['settings']['url_prefix']) ? trim($block['Block']['settings']['url_prefix']) . ' ' : '';
		$term['Term']['router_path'] = "/search/{$prefix}term:{$term['Term']['slug']}";

		if ($block['Block']['settings']['content_counter']) {
			$count = Cache::read("count_term_{$term['Term']['id']}_{$lc}", 'terms_cache');

			if (!$count) {
				$count = ClassRegistry::init('Node')->find('count',
					array(
						'conditions' => array(
							'OR' => array(
								array('Node.terms_cache LIKE' => "{$term['Term']['id']}:%"),
								array('Node.terms_cache LIKE' => "%|{$term['Term']['id']}:%")
							),
							'Node.language' => array(null, '', $lc)
						)
					)
				);

				Cache::write("count_term_{$term['Term']['id']}_{$lc}", $count, 'terms_cache');
			}

			$term['Term']['title'] = $term['Term']['name'] . " ({$count})";
		}

		if (!empty($term['children'])) {
			foreach ($term['children'] as &$t) {
				$this->__proccessTerm($t, $block, $count);
			}
		}
	}
}