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
 * Provides a JavaScript color panel for picking HEX colors.
 */
class ColorPickerWidget implements WidgetInterface
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
        unset($data['val']);
        $this->_loadAssets();

        if (empty($data['id'])) {
            $data['id'] = 'color-picker-' . md5(serialize($data));
        }

        return $this->_templates->format('input', [
            'name' => $data['name'],
            'type' => 'hidden',
            'attrs' => $this->_templates->formatAttributes(
                $data,
                ['name', 'type']
            ),
        ]) . '<div class="colorSelector"><div class="preview" data-for="' . $data['id'] . '"></div></div>';
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
        $this->_View->Html->css('System./color-picker/colorpicker.css', ['block' => true]);
        $this->_View->Html->script('System./color-picker/colorpicker.js', ['block' => true]);
        $this->_View->Html->script('System./color-picker/color-picker-init.js', ['block' => true]);
    }
}
