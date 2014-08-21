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

<div class="elfinder"><?php echo __d('wysiwyg', 'Please enable JavaScript to use ElFinder plugin.'); ?></div>

<script type="text/javascript" charset="utf-8">
	var funcNum = window.location.search.replace(/^.*CKEditorFuncNum=(\d+).*$/, "$1");

	function filterURL(url) {
		if (url.match(/\/webroot\//i)) {
			var url = decodeURIComponent(url);
			var p = url.split('file=')[1];
			if (url.match(/\/webroot\//i)) {
				var pluginName = p.split('/webroot/')[0]
					.replace('/', '')
					.replace('#', '');
				var asset = p.split('/webroot/')[1];

				pluginName = pluginName.replace(/([A-Z])/g, function($1) { return '_' + $1.toLowerCase(); })
					.replace(/^_/i, '')
					.replace(/(_){2,}/g, '_');
				url = '<?php echo $this->Url->build('/', true); ?>' + pluginName + '/' + asset;
			}
		}

		return url;
	}

	$(document).ready(function() {
		$('div.elfinder').elfinder({
			url : '<?php echo $this->Url->build(['plugin' => 'Wysiwyg', 'controller' => 'finder', 'action' => 'connector', 'prefix' => 'admin']); ?>',
			dateFormat: '<?php echo __d('wysiwyg', 'M d, Y h:i A'); ?>',
			fancyDateFormat: '<?php echo __d('wysiwyg', '$1 H:m:i'); ?>',
			lang: 'en',
			cookie : {
				expires: 30,
				domain: '',
				path: '/',
				secure: false,
			},
			getFileCallback : function(url) {
				window.opener.CKEDITOR.tools.callFunction(funcNum, filterURL(url));
				window.close();
			}
		}).elfinder('instance');
	});
</script>