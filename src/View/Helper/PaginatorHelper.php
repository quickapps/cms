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

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper as CakePaginatorHelper;
use QuickApps\Event\HookAwareTrait;

/**
 * Paginator helper library.
 *
 */
class PaginatorHelper extends CakePaginatorHelper
{

    use HookAwareTrait;

    /**
     * {@inheritDoc}
     */
    public function beforeRender(Event $event, $viewFile)
    {
        $this->alter('PaginatorHelper.beforeRender', $viewFile);
        return parent::beforeRender($event, $viewFile);
    }

    /**
     * {@inheritDoc}
     */
    public function params($model = null)
    {
        $this->alter('PaginatorHelper.params', $model);
        return parent::params($model);
    }

    /**
     * {@inheritDoc}
     */
    public function param($key, $model = null)
    {
        $this->alter('PaginatorHelper.param', $key, $model);
        return parent::param($key, $model);
    }

    /**
     * {@inheritDoc}
     */
    public function options(array $options = [])
    {
        $this->alter('PaginatorHelper.options', $options);
        return parent::options($options);
    }

    /**
     * {@inheritDoc}
     */
    public function current($model = null)
    {
        $this->alter('PaginatorHelper.current', $model);
        return parent::current($model);
    }

    /**
     * {@inheritDoc}
     */
    public function sortKey($model = null, array $options = [])
    {
        $this->alter('PaginatorHelper.sortKey', $model, $options);
        return parent::sortKey($model, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function sortDir($model = null, array $options = [])
    {
        $this->alter('PaginatorHelper.sortDir', $model, $options);
        return parent::sortDir($model, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function prev($title = '<< Previous', array $options = [])
    {
        $this->alter('PaginatorHelper.prev', $title, $options);
        return parent::prev($title, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function next($title = 'Next >>', array $options = [])
    {
        $this->alter('PaginatorHelper.next', $title, $options);
        return parent::next($title, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function sort($key, $title = null, array $options = [])
    {
        $this->alter('PaginatorHelper.sort', $key, $title, $options);
        return parent::sort($key, $title, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function generateUrl(array $options = [], $model = null, $full = false)
    {
        $this->alter('PaginatorHelper.generateUrl', $options, $model, $full);
        return parent::generateUrl($options, $model, $full);
    }

    /**
     * {@inheritDoc}
     */
    public function hasPrev($model = null)
    {
        $this->alter('PaginatorHelper.hasPrev', $model);
        return parent::hasPrev($model);
    }

    /**
     * {@inheritDoc}
     */
    public function hasNext($model = null)
    {
        $this->alter('PaginatorHelper.hasNext', $model);
        return parent::hasNext($model);
    }

    /**
     * {@inheritDoc}
     */
    public function counter($options = [])
    {
        $this->alter('PaginatorHelper.counter', $options);
        return parent::counter($options);
    }

    /**
     * {@inheritDoc}
     */
    public function numbers(array $options = [])
    {
        $this->alter('PaginatorHelper.numbers', $options);
        return parent::numbers($options);
    }

    /**
     * {@inheritDoc}
     */
    public function first($first = '<< first', array $options = [])
    {
        $this->alter('PaginatorHelper.first', $first, $options);
        return parent::first($first, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function last($last = 'last >>', array $options = array())
    {
        $this->alter('PaginatorHelper.last', $last, $options);
        return parent::last($last, $options);
    }
}
