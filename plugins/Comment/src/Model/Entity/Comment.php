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
namespace Comment\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use User\Model\Entity\User;

/**
 * Represents a single "comment" within "comments" table.
 *
 * @property int $id
 * @property int $entity_id
 * @property int $user_id
 * @property int $parent_id
 * @property int $rght
 * @property int $lft
 * @property string $table_alias
 * @property string $subject
 * @property string $body
 * @property string $author_name
 * @property string $author_email
 * @property string $author_web
 * @property string $author_ip
 * @property string $status
 */
class Comment extends Entity
{

    /**
     * Returns comment's author as a mock user entity. With the properties below:
     *
     * - `username`: QuickAppsCMS's `username` (the one used for login) if comment's
     *    author was a logged in user. "anonymous" otherwise.
     * - `name`: Real name of the author. `Anonymous` if not provided.
     * - `web`: Author's website (if provided).
     * - `email`: Author's email (if provided).
     *
     * @return \User\Model\Entity\User
     */
    protected function _getAuthor()
    {
        $author = [
            'username' => __d('comment', 'anonymous'),
            'name' => __d('comment', 'Anonymous'),
            'web' => __d('comment', '(no website)'),
            'email' => __d('comment', '(no email given)'),
            'ip' => $this->get('author_ip'),
        ];

        if (!empty($this->author_name)) {
            $author['name'] = $this->get('author_name');
        }

        if (!empty($this->author_web)) {
            $author['web'] = $this->get('author_web');
        }

        if (!empty($this->author_email)) {
            $author['email'] = $this->get('author_email');
        }

        if ($this->has('user') || !empty($this->user_id)) {
            $user = $this->get('user');
            if (!$user) {
                $user = TableRegistry::get('User.Users')
                    ->find()
                    ->where(['id' => $this->user_id])
                    ->first();
            }

            if ($user && $user->id) {
                $author['name'] = $user->username;
                $author['email'] = $user->email;
                if (!empty($user->web)) {
                    $author['web'] = $user->web;
                }
            }
        }

        if (empty($author['name'])) {
            $author['name'] = __d('comment', 'Anonymous');
        }

        return new User($author);
    }
}
