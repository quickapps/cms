<script type="text/javascript" charset="utf-8">
	var funcNum = window.location.search.replace(/^.*CKEditorFuncNum=(\d+).*$/, "$1");

	function filterURL(url){
		if (url.match(/\/get_file\//i)) {
			var p = url.split('file=')[1];

			if (url.match(/\/webroot\//i)) {
				var appName = p.split('/')[0];
				var wr = p.split('/webroot/')[1];

				if (url.match(/type\=theme/i)) {
					url = '<?php echo $this->Html->url('/'); ?>theme/' + appName + '/' + wr;
				} else {
					appName = appName.replace(/([A-Z])/g, function($1) {
						return '_' + $1.toLowerCase();
					}).replace(/^_/i, '').replace(/(_){2,}/g, '_');

					url = '<?php echo $this->Html->url('/'); ?>' + appName + '/' + wr;
				}
			}
		}

		return url;
	}

	$(document).ready(function() {
		$('#finder').elfinder({
			url : '<?php echo $this->Html->url(['plugin' => 'wysiwyg', 'controller' => 'el_finder', 'action' => 'connector', 'prefix' => 'admin']); ?>',
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

<div id="finder">finder</div>
