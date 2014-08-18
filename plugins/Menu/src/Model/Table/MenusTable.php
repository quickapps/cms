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
namespace Menu\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Menu\Model\Entity\Menu;
use QuickApps\Core\HookTrait;

/**
 * Represents "menus" database table.
 *
 */
class MenusTable extends Table {

	use HookTrait;

/**
 * Initialize a table instance. Called after the constructor.
 *
 * @param array $config Configuration options passed to the constructor
 * @return void
 */
	public function initialize(array $config) {
		$this->hasMany('MenuLinks', [
			'className' => 'Menu.MenuLinks',
			'dependent' => true,
			'propertyName' => 'links',
			'sort' => ['MenuLinks.lft' => 'ASC'],
		]);
		$this->_setHasOne();
		$this->addBehavior('System.Sluggable');
	}

/**
 * Default validation rules set.
 *
 * @param \Cake\Validation\Validator $validator
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('title', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('node', 'You need to provide a title.'),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('node', 'Title need to be at least 3 characters long.'),
				],
			])
			->validatePresence('handler', 'create')
			->add('handler', 'validHandler', [
				'rule' => 'notEmpty',
				'on' => 'create',
				'message' => __d('menu', 'Invalid menu handler'),
			]);

		return $validator;
	}

/**
 * Triggers the "Menu.<handler>.beforeValidate" hook, so plugins may do
 * any logic their require.
 *
 * @param \Cake\Event\Event $event
 * @param \Menu\Model\Entity\Menu $block
 * @param array $options
 * @return bool False if save operation should not continue, true otherwise
 */
	public function beforeValidate(Event $event, Menu $menu, $options, Validator $validator) {
		$validator
			->add('title', 'transaction', [
				'rule' => function ($value, $context) use ($options) {
					return !empty($options['atomic']) && $options['atomic'] === true;
				},
				'message' => __d('menu', 'Illegal action, you must use "atomic => true" when saving Menu entities.')
			]);
		$menuEvent = $this->hook(["Menu.{$menu->handler}.beforeValidate", $event->subject], $menu, $options, $validator);
		if ($menuEvent->isStopped() || $menuEvent->result === false) {
			return false;
		}
		return true;
	}

/**
 * Triggers the "Menu.<handler>.afterValidate" hook, so plugins may do
 * any logic their require.
 *
 * @param \Cake\Event\Event $event
 * @param \Menu\Model\Entity\Menu $menu
 * @param array $options
 * @return void
 */
	public function afterValidate(Event $event, Menu $menu, $options, Validator $validator) {
		$this->hook(["Menu.{$menu->handler}.afterValidate", $event->subject], $menu, $options, $validator);
	}

/**
 * Triggers the "Menu.<handler>.beforeSave" hook, so plugins may do
 * any logic their require.
 *
 * @param \Cake\Event\Event $event
 * @param \Menu\Model\Entity\Menu $menu
 * @param array $options
 * @return bool False if save operation should not continue, true otherwise
 */
	public function beforeSave(Event $event, Menu $menu, $options = []) {
		$menuEvent = $this->hook(["Menu.{$menu->handler}.beforeSave", $event->subject], $menu, $options);
		if ($menuEvent->isStopped() || $menuEvent->result === false) {
			return false;
		}
		return true;
	}

/**
 * Triggers the "Menu.<handler>.afterSave" hook, so plugins may do
 * any logic their require.
 *
 * @param \Cake\Event\Event $event
 * @param \Menu\Model\Entity\Menu $menu
 * @param array $options
 * @return void
 */
	public function afterSave(Event $event, Menu $menu, $options = []) {
		if ($menu->isNew()) {
			$block = $this->Blocks->newEntity([
				'title' => $menu->title . ' ' . __d('menu', '[menu:%d]', $menu->id),
				'delta' => $menu->id,
				'handler' => $menu->handler,
				'description' => (!empty($menu->description) ? $menu->description : __d('menu', 'Associated block for "{0}" menu.', $menu->title)),
				'visibility' => 'except',
				'pages' => null,
				'locale' => null,
				'status' => 0,
			]);
			$this->Blocks->save($block, ['validate' => false]);
		}

		$this->hook(["Menu.{$menu->handler}.afterSave", $event->subject], $menu, $options);
	}

/**
 * Triggers the "Menu.<handler>.beforeDelete" hook, so plugins may do
 * any logic their require.
 *
 * @param \Cake\Event\Event $event
 * @param \Menu\Model\Entity\Menu $menu
 * @param array $options
 * @return bool False if delete operation should not continue, true otherwise
 */
	public function beforeDelete(Event $event, Menu $menu, $options = []) {
		$this->hasOne('Blocks', [
			'className' => 'Block.Blocks',
			'dependent' => true,
			'foreignKey' => 'delta',
			'propertyName' => 'block',
			'conditions' => ['Blocks.handler' => $menu->handler],
			'cascadeCallbacks' => true,
		]);

		$menuEvent = $this->hook(["Menu.{$menu->handler}.beforeDelete", $event->subject], $menu, $options);
		if ($menuEvent->isStopped() || $menuEvent->result === false) {
			return false;
		}
		return true;
	}

/**
 * Triggers the "Menu.<handler>.afterDelete" hook, so plugins may do
 * any logic their require.
 *
 * @param \Cake\Event\Event $event
 * @param \Menu\Model\Entity\Menu $menu
 * @param array $options
 * @return void
 */
	public function afterDelete(Event $event, Menu $menu, $options = []) {
		$this->_setHasOne();
		$this->hook(["Menu.{$menu->handler}.afterDelete", $event->subject], $menu, $options);
	}

/**
 * Creates the default "hasOne" association with Blocks table.
 *
 * When menu is being deleted this association is re-built in order to
 * safely remove menu's associated block **(and all copies of that block)**.
 *
 * @return void
 */
	protected function _setHasOne() {
		$this->hasOne('Blocks', [
			'className' => 'Block.Blocks',
			'dependent' => false,
			'foreignKey' => 'delta',
			'propertyName' => 'block',
			'conditions' => ['Blocks.handler = Menus.handler']
		]);
	}

}
