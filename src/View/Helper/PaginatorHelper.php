<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper as CakePaginatorHelper;
use QuickApps\Utility\HookTrait;

/**
 * Paginator helper library.
 *
 */
class PaginatorHelper extends CakePaginatorHelper {

	use HookTrait;

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event instance.
 * @param string $viewFile The view file being rendered.
 * @return void
 */
	public function beforeRender(Event $event, $viewFile) {
		$this->alter('PaginatorHelper.beforeRender', $viewFile);
		return parent::beforeRender($event, $viewFile);
	}

/**
 * {@inheritdoc}
 *
 * @param string $model Optional model name. Uses the default if none is specified.
 * @return array The array of paging parameters for the paginated resultset.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::params
 */
	public function params($model = null) {
		$this->alter('PaginatorHelper.params', $model);
		return parent::params($model);
	}

/**
 * {@inheritdoc}
 *
 * @param string $key Key of the paginator params array to retrieve.
 * @param string $model Optional model name. Uses the default if none is specified.
 * @return mixed Content of the requested param.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::params
 */
	public function param($key, $model = null) {
		$this->alter('PaginatorHelper.param', $key, $model);
		return parent::param($key, $model);
	}

/**
 * {@inheritdoc}
 *
 * @param array $options Default options for pagination links.
 *   See PaginatorHelper::$options for list of keys.
 * @return void
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::options
 */
	public function options(array $options = []) {
		$this->alter('PaginatorHelper.options', $options);
		return parent::options($options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $model Optional model name. Uses the default if none is specified.
 * @return string The current page number of the recordset.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::current
 */
	public function current($model = null) {
		$this->alter('PaginatorHelper.current', $model);
		return parent::current($model);
	}

/**
 * {@inheritdoc}
 *
 * @param string $model Optional model name. Uses the default if none is specified.
 * @param array $options Options for pagination links. See #options for list of keys.
 * @return string The name of the key by which the recordset is being sorted, or
 *  null if the results are not currently sorted.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::sortKey
 */
	public function sortKey($model = null, array $options = []) {
		$this->alter('PaginatorHelper.sortKey', $model, $options);
		return parent::sortKey($model, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $model Optional model name. Uses the default if none is specified.
 * @param array $options Options for pagination links. See #options for list of keys.
 * @return string The direction by which the recordset is being sorted, or
 *  null if the results are not currently sorted.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::sortDir
 */
	public function sortDir($model = null, array $options = []) {
		$this->alter('PaginatorHelper.sortDir', $model, $options);
		return parent::sortDir($model, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $title Title for the link. Defaults to '<< Previous'.
 * @param array $options Options for pagination link. See above for list of keys.
 * @return string A "previous" link or a disabled link.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::prev
 */
	public function prev($title = '<< Previous', array $options = []) {
		$this->alter('PaginatorHelper.prev', $title, $options);
		return parent::prev($title, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $title Title for the link. Defaults to 'Next >>'.
 * @param array $options Options for pagination link. See above for list of keys.
 * @return string A "next" link or $disabledTitle text if the link is disabled.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::next
 */
	public function next($title = 'Next >>', array $options = []) {
		$this->alter('PaginatorHelper.next', $title, $options);
		return parent::next($title, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string $key The name of the key that the recordset should be sorted.
 * @param string $title Title for the link. If $title is null $key will be used
 *		for the title and will be generated by inflection.
 * @param array $options Options for sorting link. See above for list of keys.
 * @return string A link sorting default by 'asc'. If the resultset is sorted 'asc' by the specified
 *  key the returned link will sort by 'desc'.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::sort
 */
	public function sort($key, $title = null, array $options = []) {
		$this->alter('PaginatorHelper.sort', $key, $title, $options);
		return parent::sort($key, $title, $options);
	}
	
/**
 * {@inheritdoc}
 *
 * @param array $options Pagination/URL options array
 * @param string $model Which model to paginate on
 * @param bool $full If true, the full base URL will be prepended to the result
 * @return mixed By default, returns a full pagination URL string for use in non-standard contexts (i.e. JavaScript)
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::url
 */
	public function generateUrl(array $options = [], $model = null, $full = false) {
		$this->alter('PaginatorHelper.generateUrl', $options, $model, $full);
		return parent::generateUrl($options, $model, $full);
	}

/**
 * {@inheritdoc}
 *
 * @param string $model Optional model name. Uses the default if none is specified.
 * @return bool True if the result set is not at the first page.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::hasPrev
 */
	public function hasPrev($model = null) {
		$this->alter('PaginatorHelper.hasPrev', $model);
		return parent::hasPrev($model);
	}

/**
 * {@inheritdoc}
 *
 * @param string $model Optional model name. Uses the default if none is specified.
 * @return bool True if the result set is not at the last page.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::hasNext
 */
	public function hasNext($model = null) {
		$this->alter('PaginatorHelper.hasNext', $model);
		return parent::hasNext($model);
	}

/**
 * {@inheritdoc}
 *
 * @param string|array $options Options for the counter string. See #options for list of keys.
 * @return string Counter string.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::counter
 */
	public function counter($options = []) {
		$this->alter('PaginatorHelper.counter', $options);
		return parent::counter($options);
	}

/**
 * {@inheritdoc}
 *
 * @param array $options Options for the numbers.
 * @return string numbers string.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::numbers
 */
	public function numbers(array $options = []) {
		$this->alter('PaginatorHelper.numbers', $options);
		return parent::numbers($options);
	}

/**
 * {@inheritdoc}
 *
 * @param string|int $first if string use as label for the link. If numeric, the number of page links
 *   you want at the beginning of the range.
 * @param array $options An array of options.
 * @return string numbers string.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::first
 */
	public function first($first = '<< first', array $options = []) {
		$this->alter('PaginatorHelper.first', $first, $options);
		return parent::first($first, $options);
	}

/**
 * {@inheritdoc}
 *
 * @param string|int $last if string use as label for the link, if numeric print page numbers
 * @param array $options Array of options
 * @return string numbers string.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/paginator.html#PaginatorHelper::last
 */
	public function last($last = 'last >>', array $options = array()) {
		$this->alter('PaginatorHelper.last', $last, $options);
		return parent::last($last, $options);
	}

}
