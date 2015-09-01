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
namespace Content\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Represents a single "content_type" within "content_types" table.
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property string $title_label
 * @property array $defaults
 */
class ContentType extends Entity
{

    /**
     * Permissions list.
     *
     * @var \Cake\Collection\Collection
     */
    protected $_permissions = [];

    /**
     * Checks if current user is allowed to perform the given $action (create, edit,
     * delete, publish).
     *
     * @param string $action The action to check: create, edit, delete or
     *  publish
     * @return bool
     */
    public function userAllowed($action)
    {
        if (user()->isAdmin()) {
            return true;
        }

        $this->_loadPermissions();
        $allowedRoles = $this->_permissions
            ->filter(function ($rule) use ($action) {
                return $rule->get('action') == $action;
            })
            ->extract('role_id')
            ->toArray();
        $intersect = array_intersect($allowedRoles, user()->get('role_ids'));
        return !empty($intersect);
    }

    /**
     * Checks if the provided $roleId is allowed to perform the given $action.
     *
     * @param int $roleId Role ID
     * @param string $action Action to check: create, edit, delete or publish
     * @return bool
     */
    public function checkPermission($roleId, $action)
    {
        if ($roleId == ROLE_ID_ADMINISTRATOR) {
            return true;
        }

        $this->_loadPermissions();
        $rule = $this->_permissions
            ->filter(function ($rule) use ($roleId, $action) {
                return $rule->get('role_id') == $roleId && $rule->get('action') == $action;
            })
            ->first();
        return !empty($rule);
    }

    /**
     * Loads ll permissions rules for this content type.
     *
     * @return void
     */
    protected function _loadPermissions()
    {
        if (empty($this->_permissions)) {
            $permissions = TableRegistry::get('Content.ContentTypePermissions')
                ->find()
                ->where(['content_type_id' => $this->get('id')])
                ->toArray();
            $this->_permissions = collection($permissions);
        }
    }
}
