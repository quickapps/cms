<?php
/**
 * Node Application Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Node.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class NodeAppController extends AppController {
	public function beforeFilter() {
		$this->Security->unlockedFields[] = 'Items.id';

		parent::beforeFilter();
	}
}