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

$settings = $field->metadata->settings;
$settings['JSON'] = [
	'instance' => [
		'id' => $field->metadata->field_instance_id,
		'name' => $field->name,
		'showDescription' => empty($field->metadata->settings['description']),
		'showThumbnail' => !empty($field->metadata->settings['preview']),
		'showTitle' => !empty($field->metadata->settings['title_attr']),
		'showAlt' => !empty($field->metadata->settings['alt_attr']),
		'thumbnailSize' => $field->metadata->settings['preview'],
		'itemFormatter' => 'imageFieldItemFormatter',
	],
	'uploader' => [
		'buttonText' => __d('field', 'Upload Image'),
		'uploadLimit' => $field->metadata->settings['multi'] - count((array)$field->extra),
		'uploader' => $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'upload', 'prefix' => false, $field->name], true),
		'remover' => $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'delete', 'prefix' => false, $field->name], true),
		'errorMessages' => [
			502 => 'The file {{file.name}} could not be uploaded: invalid image given.'
		]
	]
];

$field->metadata->set('settings', $settings);
echo $this->element('Field.ImageField/upload_item');
echo $this->element('Field.FileField/edit', compact('field'));
