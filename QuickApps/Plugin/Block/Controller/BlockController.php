<?php
/**
 * Block Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Block.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class BlockController extends BlockAppController {
	public $name = 'Block';
	public $uses = array('Block.Block');

	public function admin_index() {
		$this->redirect('/admin/block/manage');
	}
}