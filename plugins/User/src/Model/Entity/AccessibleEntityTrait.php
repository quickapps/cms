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

use Cake\Datasource\EntityInterface;

/**
 * Provides "isAccessible()" method to entities.
 *
 * Used by entities that provide role-based accessibility. For instance, Content entities
 * can be restricted to certain user roles.
 *
 * Entity must have a relationship with "User.Roles" model.
 */
trait AccessibleEntityTrait
{

    /**
     * Whether this entity can be accessed by current user based on his/her roles.
     *
     * @param array|null $roles Array list of roles to checks against to, or NULL to
     *  automatically use entity's "roles" property
     * @return bool False if user has no permissions to see this entity due to
     *  role restrictions, true otherwise
     */
    public function isAccessibleByUser($roles = null)
    {
        if ($roles === null) {
            $roles = $this->get('roles');
        }

        if (empty($roles)) {
            return true;
        }

        $entityRolesID = [];
        foreach ((array)$roles as $role) {
            if ($role instanceof EntityInterface) {
                $entityRolesID[] = $role->get('id');
            } elseif (is_array($role) && array_key_exists('id', $role)) {
                $entityRolesID[] = $role['id'];
            } elseif (is_integer($role)) {
                $entityRolesID[] = $role;
            }
        }

        $intersect = array_intersect($entityRolesID, (array)user()->get('role_ids'));

        return !empty($intersect);
    }
}
