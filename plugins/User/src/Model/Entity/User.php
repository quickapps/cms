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
namespace User\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Represents single "user" in "users" database table.
 *
 */
class User extends Entity {

/**
 * Gets user avatar image's URL.
 *
 * Powered by Gravatar, it uses user's email to
 * get avatar image URL from Gravatar service.
 *
 * @param array $options Array of options for Gravatar API
 * @return string URL to user's avatar
 * @link http://www.gravatar.com
 */
	protected function _getAvatar($options = []) {
		$options = (array)$options;
		$options += [
			's' => 80,
			'd' => 'mm',
			'r' => 'g'
		];

		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5(strtolower(trim($this->get('email'))));
		$url .= "?s={$options['s']}&d={$options['d']}&r={$options['r']}";

		return $url;
	}

/**
 * Hashes the password if not empty.
 * 
 * @param string $password The RAW password
 * @return string Encrypted password
 */
	protected function _setPassword($password) {
		if (!empty($password)) {
			return (new DefaultPasswordHasher)->hash($password);
		}
		return $password;
	}

/**
 * Gets an array list of role IDs this user belongs to.
 * 
 * @return array
 */
	protected function _getRoleIds() {
		$ids = [];
		if ($this->has('roles')) {
			foreach ($this->roles as $k => $role) {
				$ids[] = $role->id;
			}
		}
		return $ids;
	}

/**
 * Gets an array list of role NAMES this user belongs to.
 * 
 * @return array
 */
	protected function _getRoleNames() {
		$names = [];
		if ($this->has('roles')) {
			foreach ($this->roles as $k => $role) {
				$names[] = $role->name;
			}
		}
		return $names;
	}

}
