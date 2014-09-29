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
namespace Taxonomy\Controller\Admin;

use Taxonomy\Controller\AppController;

/**
 * Taxonomy manager controller.
 *
 * Used by TaxonomyField for creating tag suggestions.
 *
 * @see \Hook\TaxonomyField
 */
class TaggerController extends AppController {

/**
 * Shows a list of matching terms.
 *
 * @param int $vocabularyId Vocabulary's ID for which render its terms
 * @return void
 */
	public function search($vocabularyId) {
		$this->loadModel('Taxonomy.Terms');
		$out = [];
		$text = $this->request->query['q'];
		$terms = $this->Terms
			->find()
			->select(['id', 'name'])
			->where(['name LIKE' => "%%{$text}%%", 'vocabulary_id' => $vocabularyId])
			->limit(10)
			->all();

		foreach ($terms as $term) {
			$out[] = ['id' => $term->id, 'name' => $term->name];
		}

		die(json_encode($out));
	}

}
