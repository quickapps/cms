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
namespace QuickApps\View;

use Block\View\Region;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Utility\Hash;
use Cake\View\View as CakeView;
use QuickApps\Event\HookAwareTrait;
use QuickApps\Event\HooktagAwareTrait;
use QuickApps\View\ViewModeAwareTrait;

/**
 * QuickApps View class.
 *
 * Extends Cake's View class to adds some QuickAppsCMS's specific
 * functionalities such as theme regions handling, objects rendering, and more
 */
class View extends CakeView
{

    use HookAwareTrait;
    use HooktagAwareTrait;
    use ViewModeAwareTrait;

    /**
     * True when the view has been rendered.
     *
     * Used to stop infinite loops when using render() method.
     *
     * @var bool
     */
    protected $_hasRendered = false;

    /**
     * Holds all region instances created for later access.
     *
     * @var array
     */
    protected $_regions = [];

    /**
     * Constructor
     *
     * @param \Cake\Network\Request|null $request Request instance.
     * @param \Cake\Network\Response|null $response Response instance.
     * @param \Cake\Event\EventManager|null $eventManager Event manager instance.
     * @param array $viewOptions View options. See View::$_passedVars for list of
     *   options which get set as class properties.
     */
    public function __construct(
        Request $request = null,
        Response $response = null,
        EventManager $eventManager = null,
        array $viewOptions = []
    ) {
        $defaultOptions = [
            'helpers' => [
                'Url' => ['className' => 'QuickApps\View\Helper\UrlHelper'],
                'Html' => ['className' => 'QuickApps\View\Helper\HtmlHelper'],
                'Form' => ['className' => 'QuickApps\View\Helper\FormHelper'],
                'Menu' => ['className' => 'Menu\View\Helper\MenuHelper'],
                'jQuery' => ['className' => 'Jquery\View\Helper\JqueryHelper'],
            ]
        ];
        $viewOptions = Hash::merge($defaultOptions, $viewOptions);
        parent::__construct($request, $response, $eventManager, $viewOptions);
    }

    /**
     * Defines a new theme region.
     *
     * ### Usage:
     *
     * Merge `left-sidebar` and `right-sidebar` regions together, the resulting region
     * limits the number of blocks it can holds to `3`:
     *
     *     echo $this->region('left-sidebar')
     *         ->append($this->region('right-sidebar'))
     *         ->blockLimit(3);
     *
     * ### Valid options are:
     *
     * - `fixMissing`: When creating a region that is not defined by the theme, it
     *    will try to fix it by adding it to theme's regions if this option is set
     *    to TRUE. Defaults to NULL which automatically enables when `debug` is
     *    enabled. This option will not work when using QuickAppsCMS's core themes.
     *    (NOTE: This option will alter theme's `composer.json` file)
     * - `theme`: Name of the theme this regions belongs to. Defaults to auto-detect.
     *
     * @param string $name Theme's region machine-name. e.g. `left-sidebar`
     * @param array $options Additional options for region being created
     * @param bool $force Whether to skip reading from cache or not, defaults to
     *  false will get from cache if exists.
     * @return \Block\View\Region Region object
     * @see \Block\View\Region
     */
    public function region($name, $options = [], $force = false)
    {
        $this->alter('View.region', $name, $options);
        if (empty($this->_regions[$name]) || $force) {
            $this->_regions[$name] = new Region($this, $name, $options);
        }
        return $this->_regions[$name];
    }

    /**
     * Overrides Cake's view rendering method. Allows the usage of
     * `$this->render($someObject)` in views.
     *
     * **Example:**
     *
     *     // $node, instance of: Node\Model\Entity\Node
     *     $this->render($node);
     *     // $block, instance of: Block\Model\Entity\Block
     *     $this->render($block);
     *     // $field, instance of: Field\Model\Entity\Field
     *     $this->render($field);
     *
     * When rendering objects the `Render.<ClassName>` event is automatically fired.
     * For example, when rendering a node entity the following event is fired asking
     * for its HTML rendering:
     *
     *     // Will trigger: Render.QuickaApps\Node\Model\Entity\Node
     *     $someNode = TableRegistry::get('Nodes')->get(1);
     *     $this->render($someNode);
     *
     * It is not limited to Entity instances only, you can virtually define a `Render`
     * for any class name.
     *
     * You can pass an unlimited number of arguments to your `Render` as follow:
     *
     *     $this->render($someObject, arg_1, arg_2, ...., arg_n);
     *
     * Your Render event-handler may look as below:
     *
     *     public function renderMyObject(Event $event, $theObject, $arg_1, $arg_2, ..., $arg_n);
     *
     * @param mixed $view View file to render. Or an object to be rendered
     * @param mixed $layout Layout file to use when rendering view file. Or extra
     *  array of options for object rendering
     * @return string HTML output of object-rendering or view file
     */
    public function render($view = null, $layout = null)
    {
        $html = "";

        if (is_object($view)) {
            $className = get_class($view);
            $args = func_get_args();
            array_shift($args);
            $args = array_merge([$view], (array)$args); // [entity, options]
            $event = new Event("Render.{$className}", $this, $args);
            EventManager::instance()->dispatch($event);
            $html = $event->result;
        } else {
            $this->alter('View.render', $view, $layout);
            if (isset($this->jQuery)) {
                $this->jQuery->load(['block' => true]);
            }

            if (!$this->_hasRendered) {
                $this->_hasRendered = true;
                $this->_setTitle();
                $this->_setDescription();
                $html = parent::render($view, $layout);
            }
        }

        return $html;
    }

    /**
     * Overrides Cake's `View::element()` method.
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
    public function element($name, array $data = [], array $options = [])
    {
        $this->alter('View.element', $name, $data, $options);
        $html = parent::element($name, $data, $options);
        return $html;
    }

    /**
     * Overrides Cake's `View::_getLayoutFileName()` method.
     *
     * Adds fallback functionality.
     *
     * @param string $name The name of the layout to find.
     * @return string Filename for layout file (.ctp).
     */
    protected function _getLayoutFileName($name = null)
    {
        try {
            $filename = parent::_getLayoutFileName($name);
        } catch (\Exception $e) {
            $filename = parent::_getLayoutFileName('default');
        }

        return $filename;
    }

    /**
     * Sets title for layout.
     *
     * It sets `title_for_layout` view variable, if no previous title was set on controller.
     * It will try to extract title from the Node being rendered (if not empty).
     * Otherwise, site's title will be used.
     *
     * @return void
     */
    protected function _setTitle()
    {
        if (empty($this->viewVars['title_for_layout'])) {
            $title = '';

            if (!empty($this->viewVars['node']) &&
                ($this->viewVars['node'] instanceof \Node\Model\Entity\Node) &&
                !empty($this->viewVars['node']->title)
            ) {
                $title = $this->viewVars['node']->title;
            } else {
                foreach ($this->viewVars as $var) {
                    if (is_object($var) &&
                        ($var instanceof \Node\Model\Entity\Node) &&
                        !empty($var->title)
                    ) {
                        $title = $var->title;
                        break;
                    }
                }
            }

            $title = empty($title) ? option('site_title') : $title;
            $this->assign('title', $title);
            $this->set('title_for_layout', $title);
        } else {
            $this->assign('title', $this->viewVars['title_for_layout']);
        }
    }

    /**
     * Sets meta-description for layout.
     *
     * It sets `description_for_layout` view-variable, and appends meta-description
     * tag to `meta` block. It will try to extract meta-description from the Node
     * being rendered (if not empty). Otherwise, site's description will be used.
     *
     * @return void
     */
    protected function _setDescription()
    {
        if (empty($this->viewVars['description_for_layout'])) {
            $description = '';

            if (!empty($this->viewVars['node']) &&
                ($this->viewVars['node'] instanceof \Node\Model\Entity\Node) &&
                !empty($this->viewVars['node']->description)
            ) {
                $title = $this->viewVars['node']->description;
            } else {
                foreach ($this->viewVars as $var) {
                    if (is_object($var) &&
                        ($var instanceof \Node\Model\Entity\Node) &&
                        !empty($var->title)
                    ) {
                        $title = $var->description;
                        break;
                    }
                }
            }

            $description = empty($description) ? option('site_description') : $description;
            $this->assign('description', $description);
            $this->set('description_for_layout', $description);
            $this->append('meta', $this->Html->meta('description', $description));
        } else {
            $this->assign('description', $this->viewVars['description_for_layout']);
        }
    }
}
