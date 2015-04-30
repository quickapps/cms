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
namespace QuickApps\View\Helper;

use Cake\View\Helper\FormHelper as CakeFormHelper;
use Cake\View\View;
use QuickApps\Event\HookAwareTrait;

/**
 * Form helper library.
 *
 * Overwrites CakePHP's Form Helper and adds alter hooks to every method,
 * so plugins may alter form elements rendering cycle.
 */
class FormHelper extends CakeFormHelper
{

    use HookAwareTrait;

    /**
     * Used by input() method.
     *
     * @var bool
     */
    protected $_isRendering = false;

    /**
     * Prefix to prepends to every "name" attribute of rendered inputs.
     *
     * @var string
     */
    protected $_prefix = '';

    /**
     * Construct the widgets and binds the default context providers
     *
     * @param \Cake\View\View $View The View this helper is being attached to.
     * @param array $config Configuration settings for the helper.
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);
        $this->addWidget('color_picker', ['ColorPicker', '_view']);
        $this->addWidget('font_panel', ['FontPanel', '_view']);
    }

    /**
     * {@inheritDoc}
     */
    public function input($fieldName, array $options = [])
    {
        if ($fieldName instanceof \Field\Model\Entity\Field) {
            if (!$this->_isRendering) {
                $this->_isRendering = true;
                $event = $this->trigger(["Field.{$fieldName->metadata->handler}.Entity.edit", $this->_View], $fieldName, $options);
                $this->_isRendering = false;
                return $event->result;
            } else {
                $options += ['value' => $fieldName->value, 'label' => $fieldName->label];
                if ($fieldName->metadata->required) {
                    $options['label'] .= ' *';
                    $options['required'] = 'required';
                }
                return $this->input($fieldName->name, $options);
            }
        }
        return parent::input($fieldName, $options);
    }

    /**
     * Sets/Read input prefix.
     *
     * ### Example:
     *
     * ```php
     * $this->Form->prefix('settings:');
     * echo $this->Form->input('site_name');
     * // outputs: <input type="text" name="settings:site_name" />
     *
     * echo $this->Form->prefix();
     * // outputs: "settings:"
     *
     * $this->Form->prefix('');
     * echo $this->Form->prefix();
     * // outputs: <empty string>
     * ```
     *
     * @param string|null $prefix The prefix to be set, or leave empty to unset any
     *  previous prefix
     * @return string Actual prefix always
     */
    public function prefix($prefix = null)
    {
        if ($prefix !== null) {
            $this->_prefix = $prefix;
        }
        return $this->_prefix;
    }
}
