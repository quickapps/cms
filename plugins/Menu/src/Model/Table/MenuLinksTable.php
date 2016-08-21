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
namespace Menu\Model\Table;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validation;
use Cake\Validation\Validator;
use Menu\Model\Entity\MenuLink;
use \ArrayObject;

/**
 * Represents "menu_links" database table.
 *
 */
class MenuLinksTable extends Table
{

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->belongsTo('Menus', ['className' => 'Menu.Menus']);
    }

    /**
     * Default validation rules set.
     *
     * @param \Cake\Validation\Validator $validator The validator object
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('url')
            ->add('url', 'checkUrl', [
                'rule' => function ($url, $context) {
                    $plainString = (
                        strpos($url, 'javascript:') === 0 ||
                        strpos($url, 'mailto:') === 0 ||
                        strpos($url, 'tel:') === 0 ||
                        strpos($url, 'sms:') === 0 ||
                        strpos($url, '#') === 0 ||
                        strpos($url, '?') === 0 ||
                        strpos($url, '//') === 0 ||
                        strpos($url, '://') !== false
                    );

                    if ($plainString) {
                        return true;
                    } else {
                        $full = Validation::url($url);
                        $internal = str_starts_with($url, '/');

                        return $full || $internal;
                    }
                },
                'message' => __d('menu', 'Invalid URL. Internal links must start with "/", e.g. "/article-my-first-article{0}"', CONTENT_EXTENSION),
                'provider' => 'table',
            ])
            ->requirePresence('title')
            ->add('title', [
                'notBlank' => [
                    'rule' => 'notBlank',
                    'message' => __d('menu', 'You need to provide a title.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('menu', 'Title need to be at least 3 characters long.'),
                ],
            ])
            ->add('activation', 'validActivation', [
                'rule' => function ($value, $context) {
                    return in_array($value, ['auto', 'any', 'none', 'php']);
                },
                'message' => __d('menu', 'Please select an activation method.'),
                'provider' => 'table',
            ])
            ->allowEmpty('active')
            ->add('active', 'validPHP', [
                'rule' => function ($value, $context) {
                    if (!empty($context['data']['activation']) && $context['data']['activation'] === 'php') {
                        return strpos($value, '<?php') !== false && strpos($value, '?>') !== false;
                    }

                    return true;
                },
                'message' => __d('menu', 'Invalid PHP code, make sure that tags "&lt;?php" & "?&gt;" are present.')
            ]);

        return $validator;
    }

    /**
     * Triggered after menu link was persisted in DB.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Menu\Model\Entity\MenuLink $link The menu link entity that was saved
     * @param \ArrayObject $options Options given as an array
     * @return void
     */
    public function afterSave(Event $event, MenuLink $link, ArrayObject $options = null)
    {
        $this->clearCache();
    }

    /**
     * Triggered after menu link was removed from DB.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Menu\Model\Entity\MenuLink $link The menu link entity that was
     *  deleted
     * @param \ArrayObject $options Options given as an array
     * @return void
     */
    public function afterDelete(Event $event, MenuLink $link, ArrayObject $options = null)
    {
        $this->clearCache();
    }

    /**
     * Clear menus cache.
     *
     * @return void
     */
    public function clearCache()
    {
        Cache::clearGroup('views', 'menus');
    }
}
