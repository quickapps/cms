<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace User\Model\Entity;

use Cake\ORM\TableRegistry;
use User\Model\Entity\User;

/**
 * Represents an logged in user.
 *
 * @property int $id
 * @property array $roleIds
 * @property array $roleNames
 * @property array $roleSlugs
 * @property array $role_ids
 * @property array $role_names
 * @property array $role_slugs
 */
class UserSession extends User
{
}
