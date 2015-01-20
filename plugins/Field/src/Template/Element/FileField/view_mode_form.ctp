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

$formats = [
	'link' => __d('field', 'Link to file'),
	'table' => __d('field', 'Table of Files'),
	'url' => __d('field', 'File URL'),
];

echo $this->Form->input('formatter', [
	'label' => __d('field', 'Files format'),
	'type' => 'select',
	'options' => $formats,
	'empty' => false
]);

