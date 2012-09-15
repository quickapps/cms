<?php
/**
 * Comment Model
 *
 * PHP version 5
 *
 * @category
 * @package	 QuickApps.Plugin.Comment.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Comment extends CommentAppModel {
	public $status = null;
	private $__tmp = null;
	public $name = 'Comment';
	public $useTable = "comments";
	public $primaryKey = 'id';
	public $order = array('Comment.created' => 'DESC');
	public $actsAs = array(
		'Comment.BBCode' => array(
			'fields' => array('body')
		)
	);

	public $belongsTo  = array(
		'Node' => array(
			'className' => 'Node.Node',
			'fields' => array('id', 'slug', 'title'),
			'counterCache' => true,
			'counterScope' => array('Comment.status' => 1)
		),
		'User' => array(
			'className' => 'User.User',
			'fields' => array('id', 'username', 'name', 'email', 'avatar')
		)
	);

	public $validate = array(
		'name' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Commenter name required.'
		),
		'mail' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'email',
			'message' => 'Invalid email.'
		),
		'homepage' => array(
			'required' => false,
			'allowEmpty' => true,
			'rule' => array('url', true),
			'message' => 'Invalid homepage URL.'
		),
		'body' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'Comment body can not be empty.'
		)
	);

	public function beforeValidate($options = array()) {
		if (!isset($this->data['Comment']['node_id'])) {
			return false;
		}

		if (Configure::read('Modules.Comment.settings.use_recaptcha') &&
			Configure::read('Modules.Comment.settings.recaptcha.private_key') &&
			Configure::read('Modules.Comment.settings.recaptcha.public_key')
		) {
			if (!defined('RECAPTCHA_API_SERVER')) {
				App::import('Lib', 'Comment.Recaptcha');
			}

			$recaptcha = recaptcha_check_answer(
				Configure::read('Modules.Comment.settings.recaptcha.private_key'),
				env('REMOTE_ADDR'),
				$this->data['Comment']['recaptcha_challenge_field'],
				$this->data['Comment']['recaptcha_response_field']
			);

			if (!$recaptcha->is_valid) {
				CakeSession::write('invalid_recaptcha', true);

				return false;
			}
		}

		$this->Node->recursive = 1;
		$this->__tmp['nodeData'] = $this->Node->findById($this->data['Comment']['node_id']);

		if (!$this->__tmp['nodeData']) {
			return false;
		}

		App::uses('CakeSession', 'Model/Datasource');

		$userId = CakeSession::read('Auth.User.id');

		if (!$userId) {
			// anonymous
			switch ($this->__tmp['nodeData']['NodeType']['comments_anonymous']) {
				// name
				case 0:
					// mail not sent, not requierd OR name sent but not required
					unset($this->validate['name'], $this->validate['mail']);

					if (empty($this->data['Comment']['name'])) {
						$this->data['Comment']['name'] = __t('Anonymous');
					}
				break;

				// name, email, host
				// mail optional, can be empty, if it is not -> must be validated | name optional
				case 1:
					$this->validate['mail']['allowEmpty'] = true;

					if (empty($this->data['Comment']['name'])) {
						$this->data['Comment']['name'] = __t('Anonymous');
					}
				break;

				// name*, email*, host
				// mail/name required
				case 2:
					// already set as class attribute
				break;
			}

			$this->data['Comment']['status'] = 0; // anonymous comments must always be approved by administrators
			$this->data['Comment']['user_id'] = 0; // belongs to no one
		} else {
			unset($this->validate['name'], $this->validate['mail'], $this->validate['homepage']);

			if (in_array(1, (array)CakeSession::read('Auth.User.role_id'))) {
				$this->data['Comment']['status'] = 1;
			} else {
				$this->data['Comment']['status'] = intval($this->__tmp['nodeData']['NodeType']['comments_approve']);
			}

			$this->data['Comment']['user_id'] = $userId;
		}

		$r = $this->hook('comment_before_validate', $this, array('collectReturn' => true, 'break' => true, 'breakOn' => false));

		return !in_array(false, (array)$r, true);
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['Comment']['node_id'])) {
			Cache::delete("node_{$this->__tmp['nodeData']['Node']['slug']}");
		}

		/* new comment */
		if (!isset($this->data['Comment']['id'])) {
			// prepare body
			$this->data['Comment']['body'] = html_entity_decode(strip_tags($this->data['Comment']['body']));

			// prepare subject
			if (!isset($this->data['Comment']['subject']) || empty($this->data['Comment']['subject'])) {
				$this->data['Comment']['subject'] = $this->__defaultSubject($this->data['Comment']['body']);
			}

			// prepare hostname
			$this->data['Comment']['hostname'] = env('REMOTE_ADDR');
		}

		if (Configure::read('Modules.Comment.settings.use_akismet') &&
			Configure::read('Modules.Comment.settings.akismet.key')
		) {
			if (!class_exists('Akismet')) {
				App::import('Lib', 'Comment.Akismet');
			}

			$akismet = new Akismet(Router::url('/'), Configure::read('Modules.Comment.settings.akismet.key'));
			$akismet->setCommentAuthor(@$this->data['Comment']['name']);
			$akismet->setCommentAuthorEmail(@$this->data['Comment']['mail']);
			$akismet->setCommentAuthorURL(@$this->data['Comment']['homepage']);
			$akismet->setCommentContent(@$this->data['Comment']['body']);
			$akismet->setPermalink(Router::url("/{$this->__tmp['nodeData']['Node']['node_type_id']}/{$this->__tmp['nodeData']['Node']['slug']}.html"));

			if ($akismet->isCommentSpam()) {
				$this->data['Comment']['status'] = 0;

				if (Configure::read('Modules.Comment.settings.akismet.action') == 'mark') {
					$this->data['Comment']['subject'] = '-- SPAM -- ' . $this->data['Comment']['subject'];
				} else {
					$this->__tmp['deleteSpam'] = true;
				}
			}
		}

		$this->status = $this->data['Comment']['status'];
		$r = $this->hook('comment_before_save', $this, array('collectReturn' => true, 'break' => true, 'breakOn' => false));

		return !in_array(false, (array)$r, true);
	}

	public function afterSave($created) {
		if (isset($this->__tmp['deleteSpam'])) {
			$this->delete($this->id);
		}
	}

	public function approve($id = false) {
		$this->__toggleComment($id, 1);
	}

	public function unapprove($id = false) {
		$this->__toggleComment($id, 0);
	}

	private function __toggleComment($id, $newStatus) {
		$newStatus = intval($newStatus);

		if (!$id) {
			$id = $this->id;
		}

		$options = array(
			'conditions' => array(
				'Comment.id' => $id
			),
			'fields' => array('id', 'status'),
			'recursive' => -1
		);

		if ($c = $this->find('first', $options)) {
			$c['Comment']['status'] = $newStatus;
			$this->save($c, false);
		} else {
			return false;
		}	
	}

	private function __defaultSubject($string, $len = 30) {
		// ignore quotes
		$__string = $string;
		$string = preg_replace('#\[quote(.*?)\](.*)\[/quote\]#U', '', $string);
		$string = $this->bb_parse($string);
		$string = html_entity_decode(strip_tags($string));

		if (strlen($string) <= $len) {
			$string = trim($string);
			$string = str_replace('[/quote]', '', $string);
			$string = trim(str_replace('[quote]', '', $string));
			$string = empty($string) ? __t('No subject') : $string;

			return $string;
		}

		$string = substr($string, 0, $len + 1);

		if ($last_space = strrpos($string, ' ')) {
			$string = substr($string, 0, $last_space);
		} else {
			$string = substr($string, 0, $len);
		}

		$string = trim($string);
		$string = str_replace('[/quote]', '', $string);
		$string = trim(str_replace('[quote]', '', $string));
		$string = empty($string) ? __t('No subject') : $string;

		return $string;
	}
}