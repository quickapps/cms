<?php
class FieldTermsHookHelper extends AppHelper {
    public function field_terms_formatter($data) {
        $terms = ClassRegistry::init('Taxonomy.Term')->find('all',
            array(
                'conditions' => array(
                    'Term.id' => explode("|", $data['content'])
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
                    $data['content'][] = $this->_View->Html->link(__t($term['Term']['name']), "/s/term:{$term['Term']['slug']}");
                break;

                case 'plain-localized':
                    $data['content'][]= __t($term['Term']['name']);
                break;
            }
        }

        return implode(', ', (array)$data['content']);
    }
}