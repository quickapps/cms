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
namespace CMS\Error;

use Cake\Error\ExceptionRenderer as CakeExceptionRenderer;

/**
 * Exception Renderer.
 *
 * Wrapper for Cake's ExceptionRenderer. Allows to render maintenance page and
 * to use shortcodes and hooks in error templates.
 */
class ExceptionRenderer extends CakeExceptionRenderer
{

    /**
     * {@inheritDoc}
     */
    public function __construct(\Exception $exception)
    {
        parent::__construct($exception);
        $this->controller->viewClass = 'CMS\View\View';
    }

    /**
     * {@inheritDoc}
     */
    protected function _message(\Exception $exception, $code)
    {
        if ($code === 503) {
            return $this->error->getMessage();
        }
        return parent::_message($exception, $code);
    }

    /**
     * {@inheritDoc}
     */
    protected function _template(\Exception $exception, $method, $code)
    {
        if ($code === 503) {
            return 'maintenance';
        }
        return parent::_template($exception, $method, $code);
    }
}
