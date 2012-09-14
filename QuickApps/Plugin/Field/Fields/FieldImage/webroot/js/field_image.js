/**
 * File Field handler for Uploadify
 *
 * @author Christopher Castro <chri@quickapps.es>
 * @link http://www.quickappscms.org
 */
QuickApps.field_image = {
	instances: {}		
};

QuickApps.field_image.setupField = function (_field_id, settings) {
	$(document).ready(function() {
		field_id = '#' + _field_id;

		QuickApps.field_image.instances[_field_id] = {'settings': settings};
		
		$(field_id + 'Uploader').uploadify({
			'uploader'  : QuickApps.field_image.uploader,
			'script'	: QuickApps.settings.base_url + 'field_image/uploadify/upload/' + field_id.replace('#FieldDataFieldImage', '') + '/session_id:' + QuickApps.field_image.session_id,
			'cancelImg' : QuickApps.field_image.cancelImg,
			'queueID'   : settings['queueID'],
			'multi'	 	: settings['multi'],
			'buttonText': QuickApps.__t('Upload'),
			'queueSizeLimit' : settings['queueSizeLimit'],
			'auto'		: true,
			'fileExt'   : settings['fileExt'],
			'fileDesc'  : settings['fileDesc'],
			'onComplete': function(event, ID, fileObj, response, data) {
				var r = $.parseJSON(response);
				r.ID = ID;

				QuickApps.field_image.afterUpload(_field_id, r);
			},
			'onSelectOnce'	: function(event, data) {
				QuickApps.field_image.onSelectOnce(_field_id, data);
			}
		});
	});
};

QuickApps.field_image.onSelectOnce = function (_field_id, data) {
	field_id = '#' + _field_id;
	var settings = QuickApps.field_image.instances[_field_id].settings;

	if (data.filesSelected > settings.can_upload) {
		$(field_id + 'Uploader').uploadifyClearQueue();
		alert(data.filesSelected + ' files selected. You are allowed to select ' + settings.can_upload + ' as max.');
	}
};

QuickApps.field_image.afterUpload = function (_field_id, response) {
	field_id = '#' + _field_id;
	var uploaded = new Array();
	var uploaded_value = $(field_id + 'UploadedPath').attr('value');
	var __uploaded = new Array();
	var settings = QuickApps.field_image.instances[_field_id].settings;

	if (uploaded_value == '') {
		$(field_id + 'UploadedPath').attr('value', '[]');
	}

	try {
		var __uploaded = $.parseJSON($(field_id + 'UploadedPath').attr('value'));
	} catch(e) {

	}

	if ($.isArray(__uploaded)) {
		uploaded = __uploaded;
		var upCount = uploaded.length;
	} else {
		var upCount = 0;
	}

	uploaded[upCount] = response.file_name;

	var node_id = _field_id + '_' + response.ID;
	var mime_icon = QuickApps.settings.base_url + 'field_file/img/icons/' + response.mime_icon;
	mime_icon = mime_icon.replace('/' + QuickApps.settings.locale.code + '/', '/');

	// html
	var html = '';
	html += '<li>';
	html += '<div class="snippet" id="' + node_id + '">';

	if (settings.preview) {
		preview_url = QuickApps.settings.base_url + 'field_image/uploadify/preview/' + field_id.replace('#FieldDataFieldImage', '') + '/' + response.file_name + '/' + response.preview_width + '/' + response.preview_height;

		html += '<div class="preview">';
		html += '<img src="' + preview_url + '?nc=' + Math.floor(Math.random()*11) + '" width="' + response.preview_width + '" height="' + response.preview_height + '" />';
		html += '</div>';
	}

	html += '<div class="info">';
	html += '<img class="file-icon" src="' + mime_icon + '" />';
	html += '<span class="file-name"><a href="' + response.file_url + '" target="_blank">' + response.file_name + '</a></span>';
	html += '<span class="file-size">(' + response.file_size + ')</span>';
	html += '<div class="submit"><input type="submit" value="' + QuickApps.__t('Remove') + '" onClick="QuickApps.field_image.remove(\'' + node_id + '\'); return false;" /></div>';

	// inputs
	var instance_id = settings.instance_id;
	var base_input_name = 'data[FieldData][FieldImage][' + instance_id + '][data][files][' + response.ID + ']';

	html += '<input type="hidden" class="mime_icon" name="' + base_input_name + '[mime_icon]" value="' + response.mime_icon + '" />';
	html += '<input type="hidden" class="file_name" name="' + base_input_name + '[file_name]" value="' + response.file_name + '" />';
	html += '<input type="hidden" class="file_size" name="' + base_input_name + '[file_size]" value="' + response.file_size + '" />';

	if (settings.title) {
		html += '<div class="input text"><label for="' + node_id + '_title">' + QuickApps.__t('Title') +'</label>';
		html += '<input type="text" id="' + node_id + '_title" class="title" name="' + base_input_name + '[title]" value="" />';
		html += '</div>';
		html += '<em>' + QuickApps.__t('The title is used as a tool tip when the user hovers the mouse over the image.') + '</em>';
	}

	if (settings.alt) {
		html += '<div class="input text"><label for="' + node_id + '_alt">' + QuickApps.__t('Alternate text') +'</label>';
		html += '<input type="text" id="' + node_id + '_alt" class="alt" name="' + base_input_name + '[alt]" value="" />';
		html += '</div>';
		html += '<em>' + QuickApps.__t('This text will be used by screen readers, search engines, or when the image cannot be loaded.') + '</em>';
	}

	// close conteiner
	html += '</div>';	// li div.snippet div.info
	html += '</div>';	// li div.snippet
	html += '</li>';	// li

	// insert new node
	$(field_id + ' .images-list').append(html);	
	$(field_id + ' .images-list').show();

	try {
		$(field_id + 'UploadedPath').attr('value', $.toJSON(uploaded));
	} catch(e){

	}

	settings.can_upload--;

	// toggle off uploader
	if (!settings.can_upload) {
		$(field_id + ' div.uploader').hide();
	}
};

/**
 * Delete file for the specified field instance,
 * if file has been just uploaded then is deleted from server.
 * otherwise, the file will be deleted ONLY after user clicks `SAVE` in the entity edit form.
 *
 * @param string _field_id
 */
QuickApps.field_image.remove = function (_field_id) {
	var splited_id = _field_id.split('_');
	var file_id = splited_id[1];
	var __upload_path = [];
	var settings = QuickApps.field_image.instances[splited_id[0]].settings;

	settings.__deleted = false;
	field_id = '#' + splited_id[0];
	var instance_id = field_id.replace('#FieldDataFieldImage', '');

	try {
		__upload_path = $.parseJSON($(field_id + 'UploadedPath').attr('value'));
	} catch(e) {
		
	}

	var file_name = $('#' + _field_id + ' .file_name').attr('value');

	if (!$.isArray(__upload_path)) {
		var upload_path = new Array();
	} else {
		var upload_path = __upload_path;
	}

	if (upload_path.length) { // just uploaded
		var i = 0;

		$.each(upload_path, function (key, value) {
			if (value == file_name) {
				$.ajax({
					url: QuickApps.settings.base_url + 'field_image/uploadify/delete/' + value + '/' + instance_id
				});

				delete upload_path[key];
				settings.__deleted = true;
				$('div#' + _field_id).parent().remove(); // remove li div

				try {
					$(field_id + 'UploadedPath').attr('value', $.toJSON(upload_path));
				} catch(e) {}

				return false; // break 
			}
		});
	}

	// look in attached list
	if (QuickApps.field_image.instances[splited_id[0]].settings.__deleted == false) {
		$(field_id + ' div.snippet').each(function(){
			var snippet = $(this);
			if (snippet.attr('id') == _field_id) {
				snippet.parent().remove();

				return false; // break
			}
		});
	}

	settings.can_upload++;

	// toggle on uploader
	if (settings.can_upload > 0) {
		$(field_id + ' div.uploader').show();

		if (!QuickApps.field_image.__countFiles(field_id)) {
			$(field_id + ' .files-list').hide();
		} else {
			$(field_id + ' .files-list').show();
		}
	}
};

QuickApps.field_image.__fieldResponseArray = function (response) {
	data = new Object();
	data = {
		'file_name': response.file_name,
		'mime_icon': response.mime_icon,
		'file_size': response.file_size
	};

   return data;
}

QuickApps.field_image.__countFiles = function (field_id) {
	return $(field_id + ' .snippet').length;
}