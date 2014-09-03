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
?>

<?php if (!static::cache('FileFieldUploadLibs')): ?>
	<?php
		echo $this->Html->css('Field.uploadify');
		echo $this->Html->script('System.jquery-ui');
		echo $this->Html->script('System.mustache');
		echo $this->Html->script('Field.FileField');
		echo $this->Html->script('Field.uploadify/jquery.uploadify.min.js?' . time());
		static::cache('FileFieldUploadLibs', '__LOADED__');
	?>

	<script type="text/javascript">
		$(document).ready(function() {
			FileField.baseUrl = '<?php echo $this->Url->build('/', true); ?>';
			FileField.swf = '<?php echo $this->Url->build('/field/js/uploadify/uploadify.swf', true); ?>';
			FileField.cancelImg = '<?php echo $this->Url->build('/field/css/uploadify-cancel.png', true); ?>';

			$(window).on('beforeunload',function() {
				if ($('.file-handler .file-item').not('.is-perm').length > 0) {
					return '<?php echo __d('field', 'Are you sure you want to leave this page?'); ?>';
				}
			});

			$('form').on('submit', function () {
				$(window).unbind('beforeunload');
			});
		});
	</script>

	<script id="file-item-template" type="x-tmpl-mustache">
		<?php
			/**
			 * - (string) instance_name: Field instance machine-name. e.g. `article-image`.
			 * - (string) uid: Unique ID for this file item.
			 * - (bool) perm: Whether this file is already saved on DB, or it was just uploaded.
			 * - (string) number: Number of this file item within field instance.
			 * - (bool) show_icon: Whether to show the icon or not.
			 * - (string) icon_url: URL of the file icon.
			 * - (string) link: File's link.
			 * - (string) file_name: File's size.
			 * - (bool) show_description: Whether to show file description or not.
			 * - (string) mime_icon: The icon PNG file name. e.g. `pdf.png`
			 * - (string) file_name: File's name, including its extension. e.g. `document.pdf`
			 * - (string) file_size: File's size. e.g. `400 KB`
			 */
		?>
		<div id="{{instance_name}}-{{uid}}" class="alert alert-info {{#perm}}is-perm{{/perm}} file-item" data-number="{{number}}" style="cursor:move;">
			{{#show_icon}}
			<img src="{{&icon_url}}" class="file-icon" />
			{{/show_icon}}

			<a href="{{&link}}" target="_blank" class="file-link">{{file_name}}</a>
			<span class="file-size">({{file_size}})</span>
			<button class="btn btn-danger btn-xs" onclick="FileField.remove('{{instance_name}}-{{uid}}'); return false;"><?php echo __d('field', 'Remove'); ?></button>

			{{#show_description}}
			<hr />
			<input type="text" name=":{{instance_name}}[{{number}}][description]" value="{{description}}" class="file-description form-control input-sm" placeholder="<?php echo __d('field', 'File description'); ?>" />
			{{/show_description}}

			<input type="hidden" name=":{{instance_name}}[{{number}}][mime_icon]" value="{{mime_icon}}" class="mime-icon" />
			<input type="hidden" name=":{{instance_name}}[{{number}}][file_name]" value="{{file_name}}" class="file-name" />
			<input type="hidden" name=":{{instance_name}}[{{number}}][file_size]" value="{{file_size}}" class="file-size" />
		</div>
	</script>
<?php endif; ?>