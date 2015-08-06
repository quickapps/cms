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
namespace CMS\View;

use Block\View\Region;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Utility\Hash;
use Cake\View\View as CakeView;
use CMS\Core\Plugin;
use CMS\Event\EventDispatcherTrait;
use CMS\Shortcode\ShortcodeTrait;
use CMS\View\ViewModeAwareTrait;

/**
 * QuickApps View class.
 *
 * Extends Cake's View class to adds some QuickAppsCMS's specific functionalities
 * such as theme regions handling, objects rendering, and more.
 *
 * @property \Block\View\Helper\BlockHelper $Block
 */
class View extends CakeView
{
    use EventDispatcherTrait;
    use ShortcodeTrait;
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
     * {@inheritDoc}
     *
     * The following helpers will be automatically loaded:
     *
     * - Url
     * - Html
     * - Form
     * - Menu
     * - jQuery
     */
    public function __construct(
        Request $request = null,
        Response $response = null,
        EventManager $eventManager = null,
        array $viewOptions = []
    ) {
        $defaultOptions = [
            'helpers' => [
                'Form' => ['className' => 'CMS\View\Helper\FormHelper'],
                'Html' => ['className' => 'CMS\View\Helper\HtmlHelper'],
                'Menu' => ['className' => 'Menu\View\Helper\MenuHelper'],
                'jQuery' => ['className' => 'Jquery\View\Helper\JqueryHelper'],
            ]
        ];
        $viewOptions = Hash::merge($defaultOptions, $viewOptions);
        parent::__construct($request, $response, $eventManager, $viewOptions);
    }

    /**
     * Defines a new theme region to be rendered.
     *
     * ### Usage:
     *
     * Merge `left-sidebar` and `right-sidebar` regions together, the resulting
     * region limits the number of blocks it can holds to `3`:
     *
     * ```php
     * echo $this->region('left-sidebar')
     *     ->append($this->region('right-sidebar'))
     *     ->blockLimit(3);
     * ```
     *
     * ### Valid options are:
     *
     * - `fixMissing`: When creating a region that is not defined by the theme, it
     *   will try to fix it by adding it to theme's regions if this option is set to
     *   TRUE. Defaults to NULL which automatically enables when `debug` is enabled.
     *   This option will not work when using QuickAppsCMS's core themes. (NOTE:
     *   This option will alter theme's `composer.json` file).
     *
     * - `theme`: Name of the theme this regions belongs to. Defaults to auto-
     *   detect.
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
        if (empty($this->_regions[$name]) || $force) {
            $this->_regions[$name] = new Region($this, $name, $options);
        }
        return $this->_regions[$name];
    }

    /**
     * {@inheritDoc}
     *
     * Overrides Cake's view rendering method. Allows to "render" objects.
     *
     * **Example:**
     *
     * ```php
     * // $content, instance of: Content\Model\Entity\Content
     * $this->render($content);
     *
     * // $block, instance of: Block\Model\Entity\Block
     * $this->render($block);
     *
     * // $field, instance of: Field\Model\Entity\Field
     * $this->render($field);
     * ```
     *
     * When rendering objects the `Render.<ClassName>` event is automatically
     * triggered. For example, when rendering a Content Entity the following event
     * is triggered, and event handlers should provide a HTML representation of the
     * given object, it basically works as the `__toString()` magic method:
     *
     * ```php
     * $someContent = TableRegistry::get('Content.Contents')->get(1);
     * $this->render($someContent);
     * // triggers: Render.Content\Model\Entity\Content
     * ```
     *
     * It is not limited to Entity instances only, you can virtually define a
     * `Render` for any class name.
     *
     * You can pass an unlimited number of arguments to your `Render` as follow:
     *
     * ```php
     * $this->render($someObject, $arg1, $arg2, ...., $argn);
     * ```
     *
     * Your Render event-handler may look as below:
     *
     * ```php
     * public function renderMyObject(Event $event, $theObject, $arg1, $arg2, ..., $argn);
     * ```
     */
    public function render($view = null, $layout = null)
    {
        $html = '';
        if (is_object($view)) {
            $className = get_class($view);
            $args = func_get_args();
            array_shift($args);
            $args = array_merge([$view], (array)$args); // [entity, options]
            $event = new Event("Render.{$className}", $this, $args);
            EventManager::instance()->dispatch($event);
            $html = $event->result;
        } else {
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

        // parse shortcodes if not layout was applied
        if (!$layout || !$this->autoLayout) {
            $this->shortcodes($html);
        }

        return $html;
    }

    /**
     * {@inheritDoc}
     *
     * Parses shortcodes.
     */
    public function renderLayout($content, $layout = null)
    {
        $html = parent::renderLayout($content, $layout);
        return $this->shortcodes($html);
    }

    /**
     * {@inheritDoc}
     *
     * Workaround patch that allows plugins and themes provide their own independent
     * "settings.ctp" files so themes won't "override" plugin element.
     *
     * The same goes for "help.ctp" template files. So themes and plugins can
     * provide help information.
     */
    protected function _getElementFileName($name)
    {
        list($plugin, $element) = $this->pluginSplit($name);
        if ($plugin &&
            ($element === 'settings' || strpos($element, 'Help/help') !== false)
        ) {
            return Plugin::classPath($plugin) . "Template/Element/{$element}{$this->_ext}";
        }
        return parent::_getElementFileName($name);
    }

    /**
     * {@inheritDoc}
     *
     * Allow users to overwrite ANY template by placing it at site's
     * **ROOT/templates/Front** and **ROOT/templates/Back** directories. These
     * directory has the highest priority when looking for template files. So in
     * other words, this directories behaves as some sort of "primary themes". Each
     * directory represents a "Frontend" and "Backend" respectively. For common
     * templates -shared across front & back- the **ROOT/templates/Common**
     * directory can be used instead.
     */
    protected function _paths($plugin = null, $cached = true)
    {
        $paths = parent::_paths($plugin, $cached);
        $base = SITE_ROOT . '/templates/';
        $subDirectory = $this->request->isAdmin() ? 'Back/' : 'Front/';
        foreach (['Common/', $subDirectory] as $dir) {
            array_unshift($paths, "{$base}{$dir}");
            if ($plugin !== null) {
                array_unshift($paths, "{$base}{$dir}Plugin/{$plugin}/");
            }
        }
        return $paths;
    }

    /**
     * {@inheritDoc}
     *
     * Adds fallback functionality, if layout is not found it uses QuickAppsCMS's
     * `default.ctp` as it will always exists.
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
     * {@inheritDoc}
     */
    protected function _evaluate($viewFile, $dataForView)
    {
        $this->__viewFile = $viewFile;
        extract($dataForView);
        ob_start();
        include $this->__viewFile;
        unset($this->__viewFile);
        return ob_get_clean();
    }

    /**
     * Sets title for layout.
     *
     * It sets `title_for_layout` view variable, if no previous title was set on
     * controller. Site's title will be used if not found.
     *
     * @return void
     */
    protected function _setTitle()
    {
        if (empty($this->viewVars['title_for_layout'])) {
            $title = option('site_title');
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
     * tag to `meta` block. Site's description will be used if not found.
     *
     * @return void
     */
    protected function _setDescription()
    {
        if (empty($this->viewVars['description_for_layout'])) {
            $description = option('site_description');
            $this->assign('description', $description);
            $this->set('description_for_layout', $description);
            $this->append('meta', $this->Html->meta('description', $description));
        } else {
            $this->assign('description', $this->viewVars['description_for_layout']);
        }
    }
}
