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

use Field\Utility\ImageToolbox;

echo $this->Form->input('size', [
    'label' => __d('field', 'Image style'),
    'type' => 'select',
    'options' => ImageToolbox::previewsOptions(),
    'empty' => __d('field', 'None (original image)')
]);

echo $this->Form->input('link_type', [
    'label' => __d('field', 'Link image to'),
    'type' => 'select',
    'options' => [
        'content' => __d('field', 'Content'),
        'file' => __d('field', 'Image file'),
    ],
    'empty' => __d('field', 'Nothing')
]);
