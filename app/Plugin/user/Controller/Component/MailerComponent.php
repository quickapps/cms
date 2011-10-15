<?php
/**
 * User Mailer Component
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.User.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class MailerComponent extends Component {
    public $Controller;
    public $components = array('Email');
    public $errors = array();

    public function initialize(&$Controller) {
        $this->Controller =& $Controller;
        return true;
    }

/**
 * Send email notification to user. 
 * It can send a preset message by indicating the preset type,
 * or send custom message by giving an associtive array with
 * body and subject of the message.
 * 
 * @param integer $user_id Id of the user to send the message.
 * @param mixed $type 
 *  It may be a string indicating one of the preset messages:
 *  - `blocked`: Message notifying that the account has been BLOCKED.
 *  - `activation`: Message notifying that account has been UNBLOCKED.
 *  - `canceled`: Message notifying that account has been DELETED.
 *  - `password_recovery`: Message notifying PASSWORD RECOVERY proccess.
 *  - `welcome`: WELCOME message, after user registration but before activation.
 * 
 * Or an associative array with keys `body` and `subject`
 *  {{{
 *      array(
 *          'body' => 'Your message's body',
 *          'subject' => 'your message's subject'
 *      )
 *  }}}
 * @return boolean True on send success. False otherwise.
 *                  All error messages are stored in array $errors
 */
    public function send($user_id, $type) {
        $user = is_numeric($user_id) ? ClassRegistry::init('User.User')->findById($user_id) : $user_id;

        if (!$user) {
            $this->errors[] = __d('user', 'User not found.');

            return false;
        }

        $this->Email->to = $user['User']['email'];
        $this->Email->from = Configure::read('Variable.site_name') . ' <' . Configure::read('Variable.site_mail') . '>';

        if (!is_array($type)) {
            $variables = $this->mailVariables();

            if (isset($variables["user_mail_{$type}_body"]) && isset($variables["user_mail_{$type}_subject"])) {
                if (isset($variables["user_mail_{$type}_notify"]) && !$variables["user_mail_{$type}_notify"]) {
                    $this->errors[] = __d('user', 'This message has been marked as `do not notify`.');

                    return false;
                }

                $this->Email->subject = $this->parseVariables($user, $variables["user_mail_{$type}_subject"]);

                if ($this->Email->send($this->parseVariables($user, $variables["user_mail_{$type}_body"]))) {
                    return true;
                } else {
                    $this->errors[] = __d('user', 'Email could not be send.');

                    return false;
                }
            } else {
                $this->errors[] = __d('user', 'Invalid message preset.');

                return false;
            }
        } else {
            if (isset($type['subject']) && isset($type['body'])) {
                $this->Email->subject   = $this->parseVariables($user, $type['subject']);

                if ($this->Email->send($this->parseVariables($user, $type['body']))) {
                    return true;
                } else {
                    $this->errors[] = __d('user', 'Email could not be send.');
                }
            }

            $this->errors[] = __d('user', 'Invalid message preset.');

            return false;
        }

        return false;
    }

/**
 * Get all email template messages from DB.
 * 
 * @return array Associative array `email_variable_name => text`
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
 * Find and replace User's special tags (and optionally HookTags) 
 * for the given string.
 * 
 * @param array $user User's associative array (User::find() structure)
 * @param string $text Text where to find and replace
 * @param boolean $doHookTags Set to true for find and replace HookTags
 * @return string Replaced text
 */
    public function parseVariables($user, $text, $doHooktags = true) {
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

        if ($doHooktags) {
            $text = $this->Controller->Hook->hookTags($text);
        }

        return $text;
    }
}