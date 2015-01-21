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
 */
class UserSession extends User
{

    /**
     * Gets an array list of role IDs this user belongs to.
     *
     * @return array
     */
    protected function _getRoleIds()
    {
        $ids = [];
        foreach ($this->roles as $role) {
            $ids[] = $role->id;
        }
        return $ids;
    }

    /**
     * Gets an array list of role NAMES this user belongs to.
     *
     * @return array
     */
    protected function _getRoleNames()
    {
        $names = [];
        foreach ($this->roles as $role) {
            $names[] = $role->name;
        }
        return $names;
    }

    /**
     * Gets an array list of role NAMES this user belongs to.
     *
     * @return array
     */
    protected function _getRoleSlugs()
    {
        $slugs = [];
        foreach ($this->roles as $role) {
            $slugs[] = $role->slug;
        }
        return $slugs;
    }
}
