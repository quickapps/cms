<?php
/**
 * User Application Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class UserAppController extends AppController {
	public $components = array('User.Mailer');

	public function beforeFilter() {
		$this->Security->unlockedFields[] = 'Items.id';

		parent::beforeFilter();
	}
}