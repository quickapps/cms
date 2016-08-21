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
namespace CMS\Aspect;

use Cake\View\Helper\FormHelper;
use CMS\Aspect\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;

/**
 * Intercepts FormHelper methods to add name prefixes.
 */
class FormHelperAspect extends Aspect
{

    /**
     * Adds prefix to every input element that may have a "name" attribute.
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Invocation
     * @Around("execution(public Cake\View\Helper\FormHelper->label|input|checkbox|radio|textarea|hidden|file|select|multiCheckbox|day|year|month|hour|minute|meridian|dateTime|time|date(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function addInputPrefix(MethodInvocation $invocation)
    {
        $helper = $invocation->getThis();
        $args = $invocation->getArguments();
        if (!empty($args[0]) &&
            is_string($args[0]) &&
            $helper instanceof FormHelper
        ) {
            $args[0] = $this->_fieldName($helper, $args[0]);
        }
        $this->setProperty($invocation, 'arguments', $args);

        return $invocation->proceed();
    }

    /**
     * Add prefix to field name if a prefix was set using FormHelper::prefix().
     *
     * @param \Cake\View\Helper\FormHelper $helper Field helper instance
     * @param string $name Field name
     * @return string Prefixed field name
     */
    protected function _fieldName(FormHelper $helper, $name)
    {
        $prefix = $helper->prefix();
        if (!empty($prefix) && strpos($name, $prefix) !== 0) {
            $name = "{$prefix}{$name}";
        }

        return $name;
    }
}
