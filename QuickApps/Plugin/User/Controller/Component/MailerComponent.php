<?php
/**
 * User Mailer Component
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class MailerComponent extends Component {
	public $Controller;
	public $components = array('Email');
	public $errors = array();

	public function initialize(Controller $Controller) {
		$this->Controller = $Controller;

		return true;
	}

/**
 * Send email notification to user. It can send a preset message by indicating the preset type,
 * or send custom message by giving an associtive array with body and subject of the message.
 *
 * @param mixed $user_id
 *	Integer ID of the user to send the message.
 *	Or User array information result of Model::find().
 * @param mixed $type
 *	It may be a string or integer indicating one of the preset messages:
 *	- `blocked` (0): Message notifying that the account has been BLOCKED.
 *	- `activation` (1): Message notifying that account has been UNBLOCKED.
 *	- `canceled` (2): Message notifying that account has been DELETED.
 *	- `password_recovery` (3): Message notifying PASSWORD RECOVERY proccess.
 *	- `welcome` (4): WELCOME message, after user registration but before activation.
 *
 * Or an associative array with keys `body` and `subject`
 *
 *    array(
 *        'body' => "Your message's body",
 *        'subject' => "your message's subject",
 *        'layout' => 'layout_to_use',
 *        'params' => array('param1' => 'value', ...)
 *    )
 *
 * Optionally you can indicate a layout to use to enclose email body, as well a list of parameters
 * to be passed to it.
 *
 * If you want to send email using layouts in a plugin you can use the familiar plugin syntax.  
 * e.g.: `User.email_message` This would use layout from the `User` module.
 *
 * @return boolean
 *	TRUE on send success. FALSE otherwise.
 *	All error messages are stored in self::$errors.
 */
	public function send($user_id, $type) {
		$user = is_numeric($user_id) ? ClassRegistry::init('User.User')->findById($user_id) : $user_id;

		if (!$user) {
			$this->errors[] = __t('User not found.');

			return false;
		}

		$this->Email->sendAs = 'both';
		$this->Email->to = $user['User']['email'];
		$this->Email->from = Configure::read('Variable.site_name') . ' <' . Configure::read('Variable.site_mail') . '>';

		if (!is_array($type)) {
			if (is_integer($type)) {
				switch ($type) {
					case 0: $type = 'blocked'; break;
					case 1: $type = 'activation'; break;
					case 2: $type = 'canceled'; break;
					case 3: $type = 'password_recovery'; break;
					case 4: $type = 'welcome'; break;
				}
			}

			$variables = $this->mailVariables();

			if (isset($variables["user_mail_{$type}_body"]) && isset($variables["user_mail_{$type}_subject"])) {
				if (isset($variables["user_mail_{$type}_notify"]) && !$variables["user_mail_{$type}_notify"]) {
					$this->errors[] = __t('This message has been marked as "do not notify".');

					return false;
				}

				$this->Email->subject = $this->parseVariables($user, $variables["user_mail_{$type}_subject"]);

				try {
					$message = $this->parseVariables($user, $variables["user_mail_{$type}_body"]);

					$this->Email->send($message, 'default', null);

					return true;
				} catch (Exception $error) {
					$this->errors[] = __t('Email could not be sent.');

					return false;
				}
			} else {
				$this->errors[] = __t('Invalid message preset.');

				return false;
			}
		} else {
			if (isset($type['subject']) && isset($type['body'])) {
				$layout = isset($type['layout']) && !empty($type['layout']) ? $type['layout'] : null;
				$message = $this->parseVariables($user, $type['body']);
				$this->Email->subject = $this->parseVariables($user, $type['subject']);

				if (isset($type['params']) && !empty($type['params'])) {
					foreach ($type['params'] as $name => $value) {
						$this->Controller->viewVars[$name] = $value;
					}
				}

				if ($this->Email->send($message, 'default', $layout)) {
					return true;
				} else {
					$this->errors[] = __t('Email could not be sent.');
				}
			} else {
				$this->errors[] = __t('Invalid message preset.');
			}

			return false;
		}

		return false;
	}

/**
 * Get all email template messages from DB.
 *
 * @return array Associative array `email_variable_name` => `text`
 */
	public function mailVariables() {
		$v = array();
		$variables = ClassRegistry::init('System.Variable')->find('all',
			array(
				'conditions' => array(
					'Variable.name LIKE' => 'user_mail_%'
				)
			)
		);

		foreach ($variables as $var) {
			$v[$var['Variable']['name']] = $var['Variable']['value'];
		}

		return $v;
	}

/**
 * Find and replace User's special tags in the given string.
 *
 * @param array $user User's associative Array Model::find() structure
 * @param string $text Text where to find and replace
 * @return string Replaced text
 */
	public function parseVariables($user, $text) {
		if (is_numeric($user)) {
			$user = ClassRegistry::init('User.User')->findById($user);
		}

		if (!isset($user['User']) || empty($text)) {
			return false;
		}

		preg_match_all('/\[user_(.+)\]/iUs', $text, $userVars);
		foreach ($userVars[1] as $var) {
			if (isset($user['User'][$var])) {
				$text = str_replace("[user_{$var}]", $user['User'][$var], $text);
			} else {
				switch($var) {
					case 'activation_url':
						$text = str_replace("[user_{$var}]", Router::url("/user/activate/{$user['User']['id']}/{$user['User']['key']}", true), $text);
					break;

					case 'cancel_url':
						$text = str_replace("[user_{$var}]", Router::url("/user/cancell/{$user['User']['id']}/{$user['User']['key']}", true), $text);
					break;
				}
			}
		}

		preg_match_all('/\[site_(.+)\]/iUs', $text, $siteVars);
		foreach ($siteVars[1] as $var) {
			if ($v = Configure::read("Variable.site_{$var}")) {
				$text = str_replace("[site_{$var}]", $v, $text);
			} else {
				switch($var) {
					case 'url':
						$text = str_replace("[site_{$var}]", Router::url("/", true), $text);
					break;

					case 'login_url':
						$text = str_replace("[site_{$var}]", Router::url("/user/login", true), $text);
					break;
				}
			}
		}

		if (Configure::read('Variable.url_language_prefix') && isset($user['User']['language']) && !empty($user['User']['language'])) {
			preg_match_all('/\/([a-z]{3})\//s', $text, $lang);

			foreach ($lang[0] as $m) {
				$text = str_replace($m, "/{$user['User']['language']}/", $text);
			}
		}

		return $text;
	}
}