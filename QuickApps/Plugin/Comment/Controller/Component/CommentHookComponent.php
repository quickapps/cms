<?php
/**
 * Hook Component
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Comment.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class CommentHookComponent extends Component {
	public $Controller;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
	public function initialize(Controller $Controller) {
		$this->Controller = $Controller;
		$data = $this->Controller->data;

		if (Configure::read('Modules.Comment.settings.use_recaptcha') &&
			Configure::read('Modules.Comment.settings.recaptcha.private_key') &&
			Configure::read('Modules.Comment.settings.recaptcha.public_key') &&
			isset($data['Comment']) &&
			isset($data['recaptcha_challenge_field']) &&
			isset($data['recaptcha_response_field'])
		) {
			$data['Comment']['recaptcha_challenge_field'] = $data['recaptcha_challenge_field'];
			$data['Comment']['recaptcha_response_field'] = $data['recaptcha_response_field'];

			$this->Controller->data = $data;
		}
	}
}