<?php
/**
 * User Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class UserHelper extends AppHelper {
/**
 * Generates user's avatar image.
 *
 * @param array $user Optional user data, current logged user data will be used otherwise
 * @param array $options extra Options for Html->image()
 * @return HTML <img>
 */
	public function avatar($user = false, $options = array()) {
		$__options = array(
			'class' => 'user-avatar'
		);

		if (!$user) {
			$user = $this->Session->read('Auth.User');
		}

		if (!isset($user['User']) && is_array($user)) {
			$user['User'] = $user;
		}

		if (isset($user['User']['avatar']) && !empty($user['User']['avatar'])) {
			$avatar = $user['User']['avatar'];
		} else {
			if (Configure::read('Variable.user_use_gravatar')) {
				if (isset($user['User']['email']) && !empty($user['User']['email'])) {
					$hash = md5(strtolower(trim("{$user['User']['email']}")));
				} else {
					$hash = md5(strtolower(trim("")));
				}

				$args = array();

				if (Configure::read('Variable.user_gravatar_size')) {
					$args[] = 's=' . Configure::read('Variable.user_gravatar_size');
				}

				if (Configure::read('Variable.user_gravatar_default')) {
					$args[] = 'd=' . Configure::read('Variable.user_gravatar_default');
				}

				if (Configure::read('Variable.user_gravatar_rating')) {
					$args[] = 'r=' . Configure::read('Variable.user_gravatar_rating');
				}

				if (Configure::read('Variable.user_gravatar_force_default') == 'y') {
					$args[] = 'f=' . Configure::read('Variable.user_gravatar_force_default');
				}

				$avatar = "http://www.gravatar.com/avatar/{$hash}?" . implode('&', $args);
			} else {
				$avatar = Configure::read('Variable.user_default_avatar');
			}
		}

		$options = array_merge($__options, $options);
		$html = $this->_View->Html->image($avatar, $options);

		$this->hook('after_render_user_avatar', $html);

		return $html;
	}
}