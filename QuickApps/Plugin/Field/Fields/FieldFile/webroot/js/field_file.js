/**
 * File Field handler for Uploadify
 *
 * @author Christopher Castro <chri@quickapps.es>
 * @link http://www.quickappscms.org
 */
QuickApps.field_file = {
	instances: {}		
};

QuickApps.field_file.setupField = function (_field_id, settings) {
	$(document).ready(function() {
		field_id = '#' + _field_id;

		QuickApps.field_file.instances[_field_id] = {'settings': settings};
		
		$(field_id + 'Uploader').uploadify({
			'uploader'  : QuickApps.field_file.uploader,
			'script'	: QuickApps.settings.base_url + 'field_file/uploadify/upload/' + field_id.replace('#FieldDataFieldFile', '') + '/session_id:' + QuickApps.field_file.session_id,
			'cancelImg' : QuickApps.field_file.cancelImg,
			'queueID'   : settings['queueID'],
			'multi'	 : settings['multi'],
			'buttonText': QuickApps.__t('Upload'),
			'queueSizeLimit' : settings['queueSizeLimit'],
			'auto'	  : true,
			'fileExt'   : settings['fileExt'],
			'fileDesc'  : settings['fileDesc'],
			'onComplete': function(event, ID, fileObj, response, data) {
				var r = $.parseJSON(response);
				r.ID = ID;

				QuickApps.field_file.afterUpload(_field_id, r);
			},
			'onSelectOnce'	: function(event, data) {
				QuickApps.field_file.onSelectOnce(_field_id, data);
			}
		});
	});
};

QuickApps.field_file.onSelectOnce = function (_field_id, data) {
	field_id = '#' + _field_id;
	var settings = QuickApps.field_file.instances[_field_id].settings;

	if (data.filesSelected > settings.can_upload) {
		$(field_id + 'Uploader').uploadifyClearQueue();
		alert(data.filesSelected + ' files selected. You are allowed to select ' + settings.can_upload + ' as max.');
	}
};

QuickApps.field_file.afterUpload = function (_field_id, response) {
	field_id = '#' + _field_id;
	var uploaded = new Array();
	var uploaded_value = $(field_id + 'UploadedPath').attr('value');
	var __uploaded = new Array();
	var settings = QuickApps.field_file.instances[_field_id].settings;
	
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
	html += '<img class="file-icon" src="' + mime_icon + '" />';
	html += '<span class="file-name"><a href="' + response.file_url + '" target="_blank">' + response.file_name + '</a></span>';
	html += '<span class="file-size">(' + response.file_size + ')</span>';
	html += '<div class="submit"><input type="submit" value="' + QuickApps.__t('Remove') + '" onClick="QuickApps.field_file.remove(\'' + node_id + '\'); return false;" /></div>';

	// inputs
	var instance_id = settings.instance_id;
	var base_input_name = 'data[FieldData][FieldFile][' + instance_id + '][data][files][' + response.ID + ']';

	html += '<input type="hidden" class="mime_icon" name="' + base_input_name + '[mime_icon]" value="' + response.mime_icon + '" />';
	html += '<input type="hidden" class="file_name" name="' + base_input_name + '[file_name]" value="' + response.file_name + '" />';
	html += '<input type="hidden" class="file_size" name="' + base_input_name + '[file_size]" value="' + response.file_size + '" />';

	if (settings.description) {
		html += '<div class="input text"><label for="' + node_id + '_description">' + QuickApps.__t('Description')  +'</label>';
		html += '<input type="text" id="' + node_id + '_description" class="description" name="' + base_input_name + '[description]" value="" />';
		html += '</div>';
	}

	// close conteiner
	html += '</div>'; // li div.snippet
	html += '</li>'; // li

	// insert new node
	$(field_id + ' .files-list').append(html);	
	$(field_id + ' .files-list').show();

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
QuickApps.field_file.remove = function (_field_id) {
	var splited_id = _field_id.split('_');
	var file_id = splited_id[1];
	var __upload_path = [];
	var settings = QuickApps.field_file.instances[splited_id[0]].settings;

	settings.__deleted = false;
	field_id = '#' + splited_id[0];
	var instance_id = field_id.replace('#FieldDataFieldFile', '');

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
					url: QuickApps.settings.base_url + 'field_file/uploadify/delete/' + value + '/' + instance_id
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
	if (QuickApps.field_file.instances[splited_id[0]].settings.__deleted == false) {
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

		if (!QuickApps.field_file.__countFiles(field_id)) {
			$(field_id + ' .files-list').hide();
		} else {
			$(field_id + ' .files-list').show();
		}
	}
};

QuickApps.field_file.__fieldResponseArray = function (response) {
	data = new Object();
	data = {
		'file_name': response.file_name,
		'mime_icon': response.mime_icon,
		'file_size': response.file_size
	};

   return data;
}

QuickApps.field_file.__countFiles = function (field_id) {
	return $(field_id + ' .snippet').length;
}