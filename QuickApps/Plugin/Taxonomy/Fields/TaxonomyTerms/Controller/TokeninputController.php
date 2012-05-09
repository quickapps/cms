<?php
class TokeninputController extends AppController {
	public $uses = array('Taxonomy.Term');

	public function admin_suggest($vocabulary_id) {
		$__out = array();
		$text = $this->request->query['q'];
		$terms = $this->Term->find('all',
			array(
				'conditions' => array(
					'Term.name LIKE' => "%%{$text}%%",
					'Term.vocabulary_id' => $vocabulary_id
				),
				'limit' => 10,
				'recursive' => -1
			)
		);

		$out = '';
		$out .= '[';

			foreach ($terms as $term) {
				$__out[] = "{\"id\": \"{$term['Term']['id']}\", \"name\": \"{$term['Term']['name']}\"}";
			}

		$out .= implode(",\n ", $__out);
		$out .= ']';

		die($out);
	}
}