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

/**
 * Renders the given content in RSS format.
 *
 */
if (!empty($content->_fields)) {
	foreach ($content->_fields->sortByViewMode($this->viewMode()) as $field) {
		echo $this->render($field);
    }
}
