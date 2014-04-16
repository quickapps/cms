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
namespace QuickApps\View;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\View\View as CakeView;
use QuickApps\Utility\DetectorTrait;
use QuickApps\Utility\HooktagTrait;
use QuickApps\Utility\HookTrait;
use QuickApps\Utility\ViewModeTrait;

/**
 * Custom View class.
 *
 * Extends Cake's View class to adds some QuickAppsCMS's specific
 * functionalities such as alerts rendering, objects rendering, and more.
 */
class View extends CakeView {

	use DetectorTrait;
	use HooktagTrait;
	use HookTrait;
	use ViewModeTrait;

/**
 * True when the view has been rendered.
 *
 * Used to stop infinite loops when using render() method.
 *
 * @var boolean
 */
	protected $_hasRendered = false;

/**
 * Overrides Cake's view rendering method.
 * Allows the usage of `$this->render($someObject)` in views.
 *
 * **Example:**
 *
 *     \\ $node, instance of: Node\Model\Entity\Node
 *     $this->render($node);
 *     \\ $block, instance of: Block\Model\Entity\Block
 *     $this->render($block);
 *     \\ $field, instance of: Field\Model\Entity\Field
 *     $this->render($field);
 *
 * When rendering objects the `ObjectRender.<ClassName>` hook-callback is automatically fired.
 * For example, when rendering a node entity the following hook is fired asking for its HTML rendering:
 *
 *     // Will trigger: ObjectRender.QuickaApps\Node\Model\Entity\Node
 *     $someNode = TableRegistry::get('Nodes')->get(1);
 *     $this->render($someNode);
 *
 * It is not limited to Entity instances only, you can virtually define an `ObjectRender` for
 * any class name.
 *
 * You can pass an unlimited number of arguments to your `ObjectRender` as follow:
 *
 *     $this->render($someObject, arg_1, arg_2, ...., arg_n);
 *
 * Your ObjectRender may look as below:
 *
 *     public function renderMyObject(Event $event, $theObject, $arg_1, $arg_2, ..., $arg_n);
 *
 * @param mixed $view View file to render. Or an object to be rendered
 * @param mixed $layout Layout file to use when rendering view file. Or extra array of options
 * for object rendering
 * @return string HTML output of object-rendering or view file
 */
	public function render($view = null, $layout = null) {
		$html = '';
		$EventManager = EventManager::instance();

		if (is_object($view)) {
			$className = get_class($view);
			$args = func_get_args();
			array_shift($args);
			$args = array_merge([$view], (array)$args); // [entity, options]
			$event = new Event("ObjectRender.{$className}", $this, $args);
			$EventManager->dispatch($event);
			$html = $event->result;
		} else {
			$this->Html->script('System.jquery.js', ['block' => true]);

			if (!$this->_hasRendered) {
				$this->_hasRendered = true;
				$this->_setTitle();
				$this->_setDescription();
				$this->hook('View.beforeRender', $view, $layout);
				$html = parent::render($view, $layout);
				$this->alter('View.render', $html);
				$this->hook('View.afterRender', $view, $layout);
			}
		}

		return $html;
	}

/**
 * Overrides Cake's `View::element()` method.
 *
 * Fires `View.beforeElement` and `View.afterElement` hooks so plugins
 * may alter element rendering cycle.
 *
 * @param string $name Name of template file in the/app/Template/Element/ folder,
 *   or `MyPlugin.template` to use the template element from MyPlugin. If the element
 *   is not found in the plugin, the normal view path cascade will be searched.
 * @param array $data Array of data to be made available to the rendered view (i.e. the Element)
 * @param array $options Array of options. Possible keys are:
 * - `cache` - Can either be `true`, to enable caching using the config in View::$elementCache. Or an array
 *   If an array, the following keys can be used:
 *   - `config` - Used to store the cached element in a custom cache configuration.
 *   - `key` - Used to define the key used in the Cache::write(). It will be prefixed with `element_`
 * - `callbacks` - Set to true to fire beforeRender and afterRender helper callbacks for this element.
 *   Defaults to false.
 * - `ignoreMissing` - Used to allow missing elements. Set to true to not trigger notices.
 * @return string Rendered Element
 */
	public function element($name, $data = array(), $options = array()) {
		$this->hook('View.beforeElement', $name, $data, $options);
		$html = parent::element($name, $data, $options);
		$this->alter('View.element', $html);
		$this->hook('View.afterElement', $name, $data, $options);

		return $html;
	}

/**
 * Sets meta-description for layout.
 *
 * It sets `description_for_layout` view variable, and appends meta-description tag to `meta` block.
 * If `node` view variable exists it will try to extract meta-description from
 * Node being rendered (if not empty). Otherwise, site's description will be used.
 *
 * @return void
 */
	protected function _setDescription() {
		if (empty($this->viewVars['description_for_layout'])) {
			$description = '';
			$node = !empty($this->viewVars['node']) ? $this->viewVars['node'] : false;

			if ($node && ($node instanceof \Node\Model\Entity\Node) && !empty($node->description)) {
				$description = $node->description;
			}

			$description = empty($description) ? Configure::read('QuickApps.site_description') : $description;
			$this->set('description_for_layout', $description);
			$this->append('meta', $this->Html->meta('description', $description));
		}
	}

/**
 * Sets title for layout.
 *
 * It sets `title_for_layout` view-variable, if no previous title was set on controller.
 * If `node` view-variable exists it will try to extract title from
 * Node being rendered (if not empty). Otherwise, site's title will be used.
 *
 * @return void
 */
	protected function _setTitle() {
		if (empty($this->viewVars['title_for_layout'])) {
			$title = '';
			$node = !empty($this->viewVars['node']) ? $this->viewVars['node'] : false;

			if ($node && ($node instanceof \Node\Model\Entity\Node) && !empty($node->title)) {
				$title = $node->title;
			}

			$title = empty($title) ? Configure::read('QuickApps.site_title') : $title;
			$this->assign('title_for_layout', $title);
			$this->set('title_for_layout', $title);
		}
	}

/**
 * Gets current logged in user as an entity.
 *
 * This method will throw when user is not logged in.
 * So you must make sure user is logged in before using this method:
 *
 *     if ($this->is('user.logged')) {
 *         $this->user()->get('name');
 *     }
 *
 * @return \User\Model\Entity\User
 * @throws \User\Error\UserNotLoggedInException
 */
	public function user() {
		if (!$this->is('user.logged')) {
			throw new \User\Error\UserNotLoggedInException(__d('user', 'View::user(), requires User to be logged in.'));
		}
		// TODO: store an user entity as part of user's session
		return $user = new \User\Model\Entity\User([
			'id' => 1,
			'name' => 'Chris',
			'username' => 'admin',
			'email' => 'chris@quickapps.es'
		]);
	}

}
