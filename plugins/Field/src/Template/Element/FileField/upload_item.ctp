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
 * Mustache template that represents a single file item in the list of
 * uploaded files within an instance of File Field Handler.
 *
 * Valid mustache placeholder are described below:
 *
 * - (string) instance_name: Field instance machine-name. e.g. `article-image`.
 * - (string) uid: Unique ID for this file item.
 * - (string) number: Number of this file item within field instance.
 * - (bool) perm: Whether this file is already saved on DB, or it was just uploaded.
 * - (bool) show_description: Whether to show file description or not.
 * - (bool) show_icon: Whether to show the icon or not.
 * - (string) icon_url: URL of the file icon.
 * - (string) link: File's link.
 * - (string) mime_icon: The icon PNG file name. e.g. `pdf.png`
 * - (string) file_name: File's name, including its extension. e.g. `document.pdf`
 * - (string) file_size: File's size. e.g. `400 KB`
 */
?>

<script id="file-item-template" type="x-tmpl-mustache">
	<div id="{{uid}}" class="alert alert-info {{#perm}}is-perm{{/perm}} file-item" data-number="{{number}}">
		{{#show_icon}}
		<img src="{{&icon_url}}" class="file-icon" />
		{{/show_icon}}

		<a href="{{&link}}" target="_blank" class="file-link">{{file_name}}</a>
		<span class="file-size">({{file_size}})</span>
		<button class="btn btn-danger btn-xs" onclick="FileField.remove('{{uid}}'); return false;"><?php echo __d('field', 'Remove'); ?></button>

		{{#show_description}}
		<hr />
		<input type="text" name=":{{instance_name}}[{{number}}][description]" value="{{description}}" class="file-description form-control input-sm" placeholder="<?php echo __d('field', 'File description'); ?>" />
		{{/show_description}}

		<input type="hidden" name=":{{instance_name}}[{{number}}][mime_icon]" value="{{mime_icon}}" class="mime-icon" />
		<input type="hidden" name=":{{instance_name}}[{{number}}][file_name]" value="{{file_name}}" class="file-name" />
		<input type="hidden" name=":{{instance_name}}[{{number}}][file_size]" value="{{file_size}}" class="file-size" />
	</div>
</script>
