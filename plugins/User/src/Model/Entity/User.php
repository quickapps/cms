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

use Cake\Auth\DefaultPasswordHasher;
use Cake\Error\FatalErrorException;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use QuickApps\Core\StaticCacheTrait;

/**
 * Represents single "user" in "users" database table.
 *
 */
class User extends Entity
{

    use StaticCacheTrait;

    /**
     * Verifies this user can access the given ACO.
     *
     * @param string $aco An ACO path. e.g. `Plugin/Prefix/Controller/action`
     * @return bool True if user can access ACO, false otherwise
     */
    public function can($aco)
    {
        $cache = static::cache("can({$aco})");
        if ($cache === null) {
            $cache = TableRegistry::get('User.Permissions')->check($this, $aco);
            static::cache("can({$aco})", $cache);
        }
        return $cache;
    }

    /**
     * Gets user default-avatar image's URL.
     *
     * Powered by Gravatar, it uses user's email to get avatar image URL from
     * Gravatar service.
     *
     * @return string URL to user's avatar
     * @link http://www.gravatar.com
     */
    protected function _getAvatar()
    {
        return $this->avatar();
    }

    /**
     * Gets user avatar image's URL.
     *
     * Powered by Gravatar, it uses user's email to get avatar image URL from
     * Gravatar service.
     *
     * Use this method if you need to customize avatar's parameters such as `size`,
     * etc.
     *
     *     $user->avatar(['s' => 150]);
     *
     * @param array $options Array of options for Gravatar API
     * @return string URL to user's avatar
     * @link http://www.gravatar.com
     */
    public function avatar($options = [])
    {
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
    protected function _setPassword($password)
    {
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
    protected function _getRoleIds()
    {
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
    protected function _getRoleNames()
    {
        $names = [];
        if ($this->has('roles')) {
            foreach ($this->roles as $k => $role) {
                $names[] = $role->name;
            }
        }
        return $names;
    }

    /**
     * Generates cancel code for this user.
     *
     * @return string
     * @throws \Cake\Error\FatalErrorException When code cannot be created
     */
    protected function _getCancelCode()
    {
        if (!$this->has('password') && !$this->has('id')) {
            throw new FatalErrorException(__d('user', 'Cannot generated cancel code for this user: unknown user ID.'));
        }

        if (!$this->has('password')) {
            $password = TableRegistry::get('User.Users')
                ->get($this->id, ['fields' => ['password']])
                ->get('password');
        } else {
            $password = $this->password;
        }

        return Security::hash($password, 'md5', true);
    }
}
