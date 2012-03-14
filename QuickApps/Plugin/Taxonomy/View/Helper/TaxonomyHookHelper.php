<?php
/**
 * Taxonomy View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Taxonomy.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
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
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar') . '<!-- TaxonomyHookHelper -->'), 'toolbar');
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
        $lc = Configure::read('Variable.language.code');

        Cache::config('terms_cache', array('engine' => 'File', 'duration' => $cd));

        $block['Block']['settings'] = Set::merge(
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
                $url = "/s/{$prefix}vocabulary:{$vocabulary['Vocabulary']['slug']}";
                $body .= '<li>' . $this->_View->Html->link($vocabulary['Vocabulary']['title'], $url);
                $terms = ClassRegistry::init('Taxonomy.Term')->find('all',
                    array(
                        'conditions' => array('Term.vocabulary_id' => $vocabulary['Vocabulary']['id']),
                        'order' => array('Term.lft' => 'ASC')
                    )
                );

                foreach ($terms as &$term) {
                    $prefix = isset($block['Block']['settings']['url_prefix']) && !empty($block['Block']['settings']['url_prefix']) ? trim($block['Block']['settings']['url_prefix']) . ' ' : '';
                    $term['Term']['router_path'] = "/s/{$prefix}term:{$term['Term']['slug']}";

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

                        $term['Term']['name'] = $term['Term']['name'] . " ({$count})";
                    }
                }

                if ($terms) {
                    $body .= $this->Menu->generate($terms, array('model' => 'Term', 'alias' => 'name', 'id' => "{$vocabulary['Vocabulary']['slug']}-terms"));
                }

                $body .= '</li>';
            }
        } else {
            $terms = ClassRegistry::init('Taxonomy.Term')->find('all',
                array(
                    'conditions' => array('Term.vocabulary_id' => $block['Block']['settings']['vocabularies']),
                    'order' => array('Term.lft' => 'ASC')
                )
            );

            foreach ($terms as &$term) {
                $prefix = isset($block['Block']['settings']['url_prefix']) && !empty($block['Block']['settings']['url_prefix']) ? trim($block['Block']['settings']['url_prefix']) . ' ' : '';
                $term['Term']['router_path'] = "/s/{$prefix}term:{$term['Term']['slug']}";

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

                    $term['Term']['name'] = $term['Term']['name'] . " ({$count})";
                }
            }

            $body .= $this->Menu->generate($terms, array('model' => 'Term', 'alias' => 'name'));
        }

        $body .= '</ul>';
        $Block = array(
            'body' => $body
        );

        return $Block;
    }

/**
 * Block settings: Vocabularies.
 *
 * @return string HTML element
 */
    public function taxonomy_vocabularies_settings($data) {
        return $this->_View->element('Taxonomy.taxonomy_vocabularies_settings', array('block' => $data));
    }
}