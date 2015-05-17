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
namespace BackendTheme\Aspect;

use Cake\View\Helper\PaginatorHelper;
use CMS\Aspect\Aspect;
use CMS\Core\StaticCacheTrait;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;

/**
 * Applies some Twitter Bootstrap CSS styles to Paginator elements.
 *
 */
class PaginatorHelperAspect extends Aspect
{

    use StaticCacheTrait;

    /**
     * Custom templates for PaginatorHelper.
     *
     * @var array
     */
    protected $_templates = [
        'nextActive' => '<li class="next"><a href="{{url}}">&raquo;</a></li>',
        'nextDisabled' => '<li class="next disabled"><a href="#">&raquo;</a></li>',
        'prevActive' => '<li class="previous"><a href="{{url}}">&laquo;</a></li>',
        'prevDisabled' => '<li class="previous disabled"><a href="#">&laquo;</a></li>',
    ];

    /**
     * Adds custom templates to PaginatorHelper::$_defaultConfig['templates'].
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Joinpoint
     * @Before("execution(public Cake\View\Helper\PaginatorHelper->prev|numbers|next(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function defaultTemplates(MethodInvocation $invocation)
    {
        if (!static::cache('bootstrapTemplates')) {
            $helper = $invocation->getThis();
            $helper->templates($this->_templates);
            static::cache('bootstrapTemplates', true);
        }
    }
}
