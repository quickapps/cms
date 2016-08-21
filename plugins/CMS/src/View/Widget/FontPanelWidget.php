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
namespace CMS\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\WidgetInterface;

/**
 * Color picker input class.
 *
 * Provides a JavaScript color wheel for picking colors.
 */
class FontPanelWidget implements WidgetInterface
{

    /**
     * StringTemplate instance.
     *
     * @var \Cake\View\StringTemplate
     */
    protected $_templates;

    /**
     * Instance of View.
     *
     * @var \Cake\View\View
     */
    protected $_View = null;

    /**
     * Constructor.
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     * @param \Cake\View\View $view Instance of View
     */
    public function __construct($templates, $view)
    {
        $this->_templates = $templates;
        $this->_View = $view;
    }

    /**
     * Render a color picker widget.
     *
     * @param array $data The data to build an input with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'name' => '',
            'val' => null,
            'type' => 'text',
            'escape' => true,
        ];
        $data['value'] = $data['val'];
        $data['readonly'] = 'readonly';
        unset($data['val']);
        $this->_loadAssets();

        $data['class'] = !empty($data['class']) ? "{$data['class']} fontselector" : 'fontselector';

        return $this->_templates->format('input', [
            'name' => $data['name'],
            'type' => 'text',
            'attrs' => $this->_templates->formatAttributes(
                $data,
                ['name', 'type']
            ),
        ]) . '<p id="' . $data['id'] . '-preview" style="font:' . $data['value'] . ';">Example text</p>';
    }

    /**
     * {@inheritDoc}
     */
    public function secureFields(array $data)
    {
        return [$data['name']];
    }

    /**
     * Load all CSS and JS assets required for this widget.
     *
     * @return void
     */
    protected function _loadAssets()
    {
        $this->_View->Html->css('System./font-panel/fontpanel.css', ['block' => true]);
        $this->_View->Html->script('System./font-panel/fontpanel.js', ['block' => true]);
        $this->_View->Html->script('System./font-panel/font-panel-init.js', ['block' => true]);
    }
}
