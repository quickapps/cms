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

use Cake\Error\FatalErrorException;
use Cake\I18n\I18n;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Content\Model\Entity\ContentType;
use User\Model\Entity\AccessibleEntityTrait;
use User\Model\Entity\User;

/**
 * Represents a single "content" within "contents" table.
 *
 * @property int $id
 * @property int $content_type_id
 * @property int $translation_for
 * @property int $promote
 * @property int $sticky
 * @property int $comment_status
 * @property int $status
 * @property int $created_by
 * @property int $modified_by
 * @property string $content_type_slug
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $language
 * @property string $type
 * @property string $url
 * @property array $roles
 * @property \User\Model\Entity\User $author
 * @method bool isAccessible(array|null $roles = null)
 */
class Content extends Entity
{

    use AccessibleEntityTrait;

    /**
     * Gets content type.
     *
     * As Content Types are not dependent of Contents (deleting a content_type won't
     * remove all contents of that type). Some times we found contents without
     * `content_type`, in that cases, if no content_type is found `--unknow--` will
     * be returned.
     *
     * @return string
     */
    protected function _getType()
    {
        $name = __d('content', '(unknown)');
        if ($this->has('content_type') && $this->get('content_type')->has('name')) {
            $name = $this->get('content_type')->get('name');
        }
        return $name;
    }

    /**
     * Gets content's details page URL.
     *
     * Content's details URL's follows the syntax below:
     *
     *     http://example.com/{content-type-slug}/{content-slug}{CONTENT_EXTENSION}
     *
     * Example:
     *
     *     http://example.com/blog-article/my-first-article.html
     *
     * @return string
     */
    protected function _getUrl()
    {
        $url = Router::getRequest()->base;
        if (option('url_locale_prefix')) {
            $url .= '/' . I18n::locale();
        }

        $url .= "/{$this->content_type_slug}/{$this->slug}";
        return Router::normalize($url) . CONTENT_EXTENSION;
    }

    /**
     * Gets content's author as an User entity.
     *
     * @return \User\Model\Entity\User
     */
    protected function _getAuthor()
    {
        if ($this->has('user')) {
            return $this->get('user');
        } elseif (!empty($this->created_by)) {
            $user = TableRegistry::get('User.Users')
                ->find()
                ->where(['id' => $this->created_by])
                ->first();

            if ($user) {
                return $user;
            }
        }

        return new User([
            'username' => __d('content', 'unknown'),
            'name' => __d('content', 'Unknown'),
            'web' => __d('content', '(no website)'),
            'email' => __d('content', 'Unknown'),
        ]);
    }

    /**
     * Gets the parent content for which this content is a translation of.
     *
     * @return mixed The parent content if exists, null otherwise
     */
    public function parent()
    {
        if (!$this->has('slug')) {
            throw new FatalErrorException(__d('content', "Missing property 'slug', make sure to include it using Query::select()."));
        }

        return TableRegistry::get('Content.Contents')
            ->find()
            ->select(['id', 'slug', 'content_type_slug', 'language'])
            ->where([
                'id' => $this->translation_for,
                'status' => 1,
            ])
            ->first();
    }

    /**
     * Find if this content has a translation to the given locale code.
     *
     * @param string|null $locale Locale code for which look for translations,
     *  if not given current language code will be used
     * @return mixed Translation entity if exists, null otherwise
     * @throws Cake\Error\FatalErrorException When if any of the required
     *  properties is not present in this entity
     */
    public function translation($locale = null)
    {
        if (!$this->has('id') || !$this->has('content_type_slug')) {
            throw new FatalErrorException(__d('content', "Missing properties 'id' or 'content_type_slug', make sure to include them using Query::select()."));
        }

        if ($locale === null) {
            $locale = I18n::locale();
        }

        return TableRegistry::get('Content.Contents')
            ->find()
            ->select(['id', 'slug', 'content_type_slug', 'language'])
            ->where([
                'translation_for' => $this->id,
                'language' => $locale,
                'status' => 1,
            ])
            ->first();
    }

    /**
     * Set defaults content settings based on parent content type.
     *
     * You can provide a ContentType entity to fetch defaults values. By default if
     * none is provided it automatically fetches the information from the
     * corresponding Content Type.
     *
     * @param bool|\Content\Model\Entity\ContentType $type False for auto fetch or a
     *  ContentType entity to extract information from
     * @return void
     * @throws Cake\Error\FatalErrorException When content type was not found for
     *  this content content.
     */
    public function setDefaults($type = false)
    {
        if (!$type) {
            if (!$this->has('content_type_slug') && !$this->has('id')) {
                throw new FatalErrorException(__d('content', 'Unable to get Content-Type information.'));
            }

            if (!$this->has('content_type_slug')) {
                $contentTypeSlug = TableRegistry::get('Content.Contents')->find()
                    ->select(['content_type_slug'])
                    ->where(['id' => $this->get('id')])
                    ->first();
                $contentTypeSlug = $contentTypeSlug->content_type_slug;
            } else {
                $contentTypeSlug = $this->get('content_type_slug');
            }

            $type = TableRegistry::get('Content.ContentTypes')->find()
                ->where(['slug' => $contentTypeSlug])
                ->first();
        }

        if (!($type instanceof ContentType) || !$type->has('defaults')) {
            throw new FatalErrorException(__d('content', "Content::setDefaults() was unable to get Content Type defaults values."));
        }

        $this->set('language', $type->defaults['language']);
        $this->set('comment_status', $type->defaults['comment_status']);
        $this->set('status', $type->defaults['status']);
        $this->set('promote', $type->defaults['promote']);
        $this->set('sticky', $type->defaults['sticky']);
    }
}
