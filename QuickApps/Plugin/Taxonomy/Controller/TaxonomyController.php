<?php
/**
 * Taxonomy Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Taxonomy.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class TaxonomyController extends TaxonomyAppController {
	public $name = 'Taxonomy';
	public $uses = array();

	public function admin_index() {
		$this->redirect('/admin/taxonomy/vocabularies');
	}
}