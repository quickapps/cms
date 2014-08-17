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
namespace User\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\Cache\Cache;

/**
 * Represents "permissions" database table.
 *
 */
class PermissionsTable extends Table {

/**
 * Initialize a table instance. Called after the constructor.
 *
 * @param array $config Configuration options passed to the constructor
 * @return void
 */
	public function initialize(array $config) {
		$this->belongsTo('Acos', [
			'className' => 'User.Acos',
			'propertyName' => 'aco',
		]);
	}

/**
 * Checks if the given $user has access to the given $path
 *
 * @param \User\Model\Entity\UserSession An user session
 * @param string $path An ACO path. e.g. `/Plugin/Controller/action`
 * @return bool true if user has access to action in ACO, false otherwise
 */
	public function check(UserSession $user, $path) {
		$acoPath = $this->Acos->node($path);

		if (!$acoPath) {
			return false;
		}

		if (!$user->role_ids) {
			return false;
		}

		$acoIDs = $acoPath->extract('id');
		foreach ($user->role_ids as $role_id) {
			$permission = $this->find()
				->where([
					'role_id' => $role_id,
					'aco_id IN' => $acoIDs,
				])
				->first();
			if ($permission) {
				return true;
			}
		}

		return false;
	}

/**
 * Clear permissions cache when permissions have changed.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function afterSave(Event $event) {
		$this->clearCache();
	}

/**
 * Clear permissions cache when permissions have changed.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function afterDelete(Event $event) {
		$this->clearCache();
	}

/**
 * Clear permissions cache for all users.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function clearCache() {
		Cache::clearGroup('acl', 'permissions');
	}

}
