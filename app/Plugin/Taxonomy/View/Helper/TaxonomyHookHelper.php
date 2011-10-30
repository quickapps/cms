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

    public function beforeLayout($layoutFile) {
        if (Router::getParam('admin') &&
            $this->request->params['plugin'] == 'taxonomy' &&
            $this->request->params['controller'] == 'vocabularies' &&
            $this->request->params['action'] == 'admin_index'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar') . '<!-- TaxonomyHookHelper -->' ), 'toolbar');
        }

        return true;
    }

    public function taxonomy_vocabularies($block) {
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
                        $count = Cache::read("count_term_{$term['Term']['id']}");

                        if (!$count) {
                            $count = ClassRegistry::init('NodesTerms')->find('count', 
                                array(
                                    'conditions' => array('NodesTerms.term_id' => $term['Term']['id'])
                                )
                            );

                            Cache::write("count_term_{$term['Term']['id']}", $count);
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
                    $count = Cache::read("count_term_{$term['Term']['id']}");

                    if (!$count) {
                        $count = ClassRegistry::init('NodesTerms')->find('count', 
                            array(
                                'conditions' => array('NodesTerms.term_id' => $term['Term']['id'])
                            )
                        );

                        Cache::write("count_term_{$term['Term']['id']}", $count);
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

    public function taxonomy_vocabularies_settings($data) {
        return $this->_View->element('taxonomy_vocabularies_settings', array('block' => $data), array('plugin' => 'Taxonomy'));
    }
}