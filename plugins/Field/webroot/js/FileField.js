/**
 * File field handler for Uploadify
 *
 * @author Christopher Castro <chri@quickapps.es>
 * @link http://www.quickappscms.org
 */
FileField = {
	baseUrl: '',
	swf: '',
	uploader: '',
	cancelImg: '',
	instances: {},
	setupField: function (settings) {
		settings.maxUploadsDown = function () {
			this.maxUploads = this.maxUploads - 1;
		};

		settings.maxUploadsUp = function () {
			this.maxUploads = this.maxUploads + 1;
		};

		var uploader = $('#' + settings.instanceID + '-uploader').uploadify({
			'swf': FileField.swf,
			'uploader': FileField.baseUrl + 'admin/field/uploadify/upload/' + settings.instance_name,
			'cancelImg': FileField.cancelImg,
			'queueID': settings.queueID,
			'multi': settings.multi,
			'buttonText': settings.buttonText,
			'queueSizeLimit': settings.queueSizeLimit,
			'auto': true,
			'fileTypeExts': settings.fileTypeExts,
			'fileTypeDesc': settings.fileTypeDesc,
			'onUploadSuccess': function(file, data, response) {
				r = $.parseJSON(data);
				r.number = $('#' + settings.instanceID + ' ul.files-list li').length + 1;
				FileField.onUploadSuccess(r, settings);
			},
			'onSelect': function(file) {
				FileField.onSelect(file, settings);
			}
		});

		FileField.instances[settings.instanceID] = {
			settings: settings,
			uploader: uploader,
		};
	},
	onSelect: function (file, settings) {
		if (settings.maxUploads <= 0) {
			$('#' + settings.instanceID + '-uploader').uploadifyClearQueue();
			alert('You are allowed to upload up to ' + settings.maxUploads + ' files.');
		}
	},
	onUploadSuccess: function (response, settings) {
		var view = {
			uid: settings.instanceID + '-f' + response.number,
			number: response.number,
			icon_url: FileField.baseUrl + 'field/img/file-icons/' + response.mime_icon,
			link: response.file_url,
			file_name: response.file_name,
			file_size: response.file_size,
			instance_name: settings.instance_name,
			mime_icon: response.mime_icon,
			file_name: response.file_name,
			file_size: response.file_size,
			description: '',
			show_icon: true,
			show_description: settings.show_description,
		};
		var template = '<li>' + Mustache.render($('#file-item-template').html(), view) + '</li>';
		$('#' + settings.instanceID + '-files-list').append(template);

		settings.maxUploadsDown();
		if (!settings.maxUploads) {
			$('#' + settings.instanceID + ' div.uploader').hide();
		}

		$('#' + settings.instanceID + ' ul.files-list').show();
	},

	/**
	 * Delete file for the specified field instance, if file has been just uploaded
	 * then is deleted from server. Otherwise, File will be deleted ONLY after user
	 * clicks `SAVE` in the entity edit form.
	 *
	 * @param string id
	 */
	remove: function (id) {
		var parent = $('#' + id).closest('.file-handler');
		var info = FileField.instances[parent.attr('id')];
		var fileName =  $('#' + id + ' input.file-name').val();

		if (!$('#' + id).hasClass('is-perm')) {
			$.ajax(FileField.baseUrl + 'admin/field/uploadify/delete/' + info.settings.instance_name + '?file=' + fileName)
				.done(function() {
					$('#' + id).parent().remove(); // remove <li>
					info.settings.maxUploadsUp();

					if (info.settings.maxUploads > 0) {
						var filesInList = $('#' + parent.attr('id') + ' ul.files-list li').length;
						$('#' + parent.attr('id') + ' div.uploader').show();

						if (!filesInList) {
							$('#' + parent.attr('id') + ' ul.files-list').hide();
						} else {
							$('#' + parent.attr('id') + ' ul.files-list').show();
						}
					}
				});
		} else {
			$('#' + id).parent().remove();
		}
	}
}
