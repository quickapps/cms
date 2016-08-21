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
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use CMS\Event\EventDispatcherTrait;
use Menu\Model\Entity\Menu;
use \ArrayObject;

/**
 * Represents "menus" database table.
 *
 */
class MenusTable extends Table
{

    use EventDispatcherTrait;

    /**
     * Initialize a table instance. Called after the constructor.
     *
     * @param array $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config)
    {
        $this->hasMany('MenuLinks', [
            'className' => 'Menu.MenuLinks',
            'dependent' => true,
            'propertyName' => 'links',
            'sort' => ['MenuLinks.lft' => 'ASC'],
        ]);
        $this->addBehavior('Sluggable');
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
            ->add('title', [
                'notBlank' => [
                    'rule' => 'notBlank',
                    'message' => __d('menu', 'You need to provide a title.'),
                ],
                'length' => [
                    'rule' => ['minLength', 3],
                    'message' => __d('menu', 'Title need to be at least 3 characters long.'),
                ]
            ])
            ->requirePresence('handler', 'create')
            ->add('handler', 'validHandler', [
                'rule' => 'notBlank',
                'on' => 'create',
                'message' => __d('menu', 'Invalid menu handler'),
            ]);

        return $validator;
    }

    /**
     * Triggered after menu was persisted in DB.
     *
     * It will also create menu's associated block if not exists.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Menu\Model\Entity\Menu $menu The menu entity that was saved
     * @param \ArrayObject $options Options given as an array
     * @return void
     */
    public function afterSave(Event $event, Menu $menu, ArrayObject $options = null)
    {
        if ($menu->isNew()) {
            $block = TableRegistry::get('Block.Blocks')->newEntity([
                'title' => $menu->title . ' ' . __d('menu', '[menu: {0}]', $menu->id),
                'handler' => 'Menu\Widget\MenuWidget',
                'description' => (!empty($menu->description) ? $menu->description : __d('menu', 'Associated block for "{0}" menu.', $menu->title)),
                'visibility' => 'except',
                'pages' => null,
                'locale' => null,
                'status' => 0,
                'settings' => ['menu_id' => $menu->id]
            ], ['validate' => false]);
            TableRegistry::get('Block.Blocks')->save($block);
        }
        $this->clearCache();
    }

    /**
     * Triggered after menu was removed from DB.
     *
     * This method will delete any associated block the removed menu could have.
     * This operation may take a while if there are too many menus created.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Menu\Model\Entity\Menu $menu The menu entity that was deleted
     * @param \ArrayObject $options Options given as an array
     * @return void
     */
    public function afterDelete(Event $event, Menu $menu, ArrayObject $options = null)
    {
        $blocks = TableRegistry::get('Block.Blocks')
            ->find('all')
            ->select(['id', 'handler', 'settings'])
            ->where(['handler' => 'Menu\Widget\MenuWidget']);
        foreach ($blocks as $block) {
            if (!empty($menu->settings['menu_id']) && $menu->settings['menu_id'] == $menu->id) {
                TableRegistry::get('Block.Blocks')->delete($block);

                return;
            }
        }

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
