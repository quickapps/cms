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

use CMS\Aspect\Aspect;
use Go\Core\AspectContainer;
use Go\Core\AspectKernel;

/**
 * QuickApps Application Aspect Kernel.
 *
 * Registers all aspects classes.
 */
class AppAspect extends AspectKernel
{

    // @codingStandardsIgnoreStart
    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param \Go\Core\AspectContainer $container AOP Container instance
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
        foreach ((array)aspects() as $class) {
            $class = class_exists($class) ? new $class : null;
            if ($class instanceof Aspect) {
                $container->registerAspect($class);
            }
        }
    }
    // @codingStandardsIgnoreEnd
}
