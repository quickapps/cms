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
namespace Bootstrap\Aspect;

use Cake\View\Helper\FormHelper;
use CMS\Aspect\Aspect;
use CMS\Core\StaticCacheTrait;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;

/**
 * Applies some Twitter Bootstrap CSS styles to Form elements.
 *
 * By default all CSS and HTML code generated automatically by QuickAppsCMS follow
 * Twitter Bootstrap's conventions.
 *
 * If you need disable Twitter Bootstrap's CSS classes to be applied, you can set
 * the special `bootstrap` key as FALSE in your array of options. For example:
 *
 * ```php
 * echo $this->Form->create($userEntity, ['bootstrap' => false]);
 *     echo $this->Form->input('user_name', ['bootstrap' => false]);
 * echo $this->Form->end();
 * ```
 */
class FormHelperAspect extends Aspect
{

    use StaticCacheTrait;

    /**
     * Custom templates for FormHelper.
     *
     * @var array
     */
    protected $_templates = [
        'checkboxFormGroup' => '{{label}}',
        'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
        'dateWidget' => '<div class="row">
            <div class="col-sm-3">{{year}}</div>
            <div class="col-sm-3">{{month}}</div>
            <div class="col-sm-3">{{day}}</div>
            <div class="col-sm-3">{{hour}}</div>
            <div class="col-sm-3">{{minute}}</div>
            <div class="col-sm-3">{{second}}</div>
            <div class="col-sm-3">{{meridian}}</div>
        </div>',
        'error' => '<em class="help-block text-danger">{{content}}</em>',
        'errorList' => '<ul class="text-danger">{{content}}</ul>',
        'inputContainer' => '<div class="form-group {{type}} {{required}}">{{content}}</div>',
        'inputContainerError' => '<div class="form-group has-error has-feedback {{type}}{{required}}">{{content}}<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>{{error}}</div>',
        'label' => '<label{{attrs}}>{{text}}</label>',
        'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}> ',
        'radioWrapper' => '<br />{{label}}',
        'submitContainer' => '{{content}}',
    ];

    /**
     * Adds custom templates on Form::create().
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Invocation
     * @Around("execution(public Cake\View\Helper\FormHelper->create(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function alterCreate(MethodInvocation $invocation)
    {
        $helper = $invocation->getThis();
        list($model, $options) = array_pad($invocation->getArguments(), 2, null);
        $options = (array)$options;
        $bootstrap = isset($options['bootstrap']) ? (bool)$options['bootstrap'] : true;

        if ($bootstrap) {
            $this->_addTemplates($helper);
        }

        if (isset($options['bootstrap'])) {
            unset($options['bootstrap']);
        }

        $this->setProperty($invocation, 'arguments', [$model, $options]);

        return $invocation->proceed();
    }

    /**
     * Appends some CSS classes to generic input (text, textarea, select) elements.
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Invocation
     * @Around("execution(public Cake\View\Helper\FormHelper->input(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function alterInput(MethodInvocation $invocation)
    {
        $helper = $invocation->getThis();
        list($fieldName, $options) = array_pad($invocation->getArguments(), 2, null);
        $options = (array)$options;

        if (empty($options['type']) ||
            !in_array($options['type'], ['textarea', 'select', 'button', 'submit', 'checkbox'])
        ) {
            $options = $this->_addClass($helper, $options, 'form-control');
        }

        $this->_addTemplates($helper);
        $this->setProperty($invocation, 'arguments', [$fieldName, $options]);

        return $invocation->proceed();
    }

    /**
     * Appends some CSS classes to textarea elements.
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Invocation
     * @Around("execution(public Cake\View\Helper\FormHelper->textarea(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function alterTextarea(MethodInvocation $invocation)
    {
        $helper = $invocation->getThis();
        list($fieldName, $options) = array_pad($invocation->getArguments(), 2, null);
        $options = (array)$options;
        $options = $this->_addClass($helper, $options, 'form-control');

        $this->_addTemplates($helper);
        $this->setProperty($invocation, 'arguments', [$fieldName, $options]);

        return $invocation->proceed();
    }

    /**
     * Appends some CSS classes to select elements.
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Invocation
     * @Around("execution(public Cake\View\Helper\FormHelper->select(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function alterSelectbox(MethodInvocation $invocation)
    {
        $helper = $invocation->getThis();
        list($fieldName, $options, $attributes) = array_pad($invocation->getArguments(), 3, null);
        $options = $options === null ? [] : $options;
        $attributes = (array)$attributes;
        $attributes = $this->_addClass($helper, $attributes, 'form-control');

        $this->setProperty($invocation, 'arguments', [$fieldName, $options, $attributes]);

        return $invocation->proceed();
    }

    /**
     * Appends some CSS classes to generic buttons.
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Invocation
     * @Around("execution(public Cake\View\Helper\FormHelper->button(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function alterButton(MethodInvocation $invocation)
    {
        $helper = $invocation->getThis();
        list($title, $options) = array_pad($invocation->getArguments(), 2, null);
        $options = (array)$options;
        $options = $this->_addClass($helper, $options, 'btn btn-default');

        $this->_addTemplates($helper);
        $this->setProperty($invocation, 'arguments', [$title, $options]);

        return $invocation->proceed();
    }

    /**
     * Appends some CSS classes to submit buttons.
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Invocation
     * @Around("execution(public Cake\View\Helper\FormHelper->submit(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function alterSubmit(MethodInvocation $invocation)
    {
        $helper = $invocation->getThis();
        list($caption, $options) = array_pad($invocation->getArguments(), 2, null);
        $options = (array)$options;
        $options = $this->_addClass($helper, $options, 'btn btn-primary');

        $this->_addTemplates($helper);
        $this->setProperty($invocation, 'arguments', [$caption, $options]);

        return $invocation->proceed();
    }

    /**
     * Add custom CSS classes to array of options.
     *
     * @param \Cake\View\Helper\FormHelper $formHelper Instance of FormHelper
     * @param null|array $options Input's options
     * @param string $class CSS classes to add
     * @return array
     */
    protected function _addClass(FormHelper $formHelper, $options, $class)
    {
        $bootstrap = isset($options['bootstrap']) ? (bool)$options['bootstrap'] : true;
        if ($bootstrap) {
            $options = $formHelper->addClass((array)$options, $class);
        }

        if (isset($options['bootstrap'])) {
            unset($options['bootstrap']);
        }

        return $options;
    }

    /**
     * Add custom set of templates to FormHelper.
     *
     * @param \Cake\View\Helper\FormHelper $formHelper Instance of FormHelper
     * @return void
     */
    protected function _addTemplates(FormHelper $formHelper)
    {
        if (!static::cache('bootstrapTemplates')) {
            $formHelper->templates($this->_templates);
            static::cache('bootstrapTemplates', true);
        }
    }
}
