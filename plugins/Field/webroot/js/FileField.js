/**
 * File field handler for Uploadify.
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
	defaultErrorMessages: {
		400: 'The file {{file.name}} could not be uploaded: invalid field instance.',
		422: 'The file {{file.name}} could not be uploaded: invalid file extension.',
		500: 'The file {{file.name}} could not be uploaded: internal server error.',
	},
	defaultItemTempalte: '<div id="${fileID}" class="uploadify-queue-item">\
		<div class="cancel">\
			<a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">X</a>\
		</div>\
		<span class="fileName">${fileName} (${fileSize})</span><span class="data"></span>\
		<div class="uploadify-progress">\
			<div class="uploadify-progress-bar"><!--Progress Bar--></div>\
		</div>\
	</div>',

/**
 * Initializes the uploader for the given field instance.
 *
 * ### Valid settings options are:
 *
 * - instanceID (string): REQUIRED unique ID to identify the field handler this
 *   uploader will be attached to. If not given an exception will be throw.
 * - instanceName (string): REQUIRED field instance's machine-name. If not given
 *   an exception will be throw.
 * - showDescription (bool): Whether to show field instance help hint or not.
 *   Defaults to false.
 * - fileTypeExts (string): Allowed file extensions separated by ";".
 *   e.g. "*.jpg;*.pdf;*.gif", defaults to "*.*" (any extension).
 * - uploader (string): The URL which take care of uploading the files.
 * - remover (string): The URL which take care of deleting the files.
 * - fileTypeDesc (string): The description of the selectable files. This string
 *   appears in the browse files dialog box in the file type drop down.
 * - queueID (string|bool): The ID (without the hash) of a DOM element to use as
 *   the file queue. File queue items will be appended directly to this element
 *   if defined. If this option is set to false, a file queue will be generated
 *   and the queueID option will be dynamically set. Defaults to false.
 * - multi (bool): Set to false to allow only one file selection at a time.
 *   Defaults to true.
 * - queueSizeLimit (integer): The maximum number of files that can be in the
 *   queue at one time. This does not limit the number of files that can be
 *   uploaded. To limit the number of files that can be uploaded, use the 
 *   "uploadLimit" option. Defaults to 999.
 * - uploadLimit (integer): The maximum number of files you are allowed to
 *   upload. Defaults to 999.
 * - buttonText (string): The text that will appear on the browse button. This
 *   text is rendered as HTML and may include HTML entities.
 * - fileSizeLimit (integer): The maximum size allowed for a file upload. This
 *   value can be a number or string. If it’s a string, it accepts a unit
 *   (B, KB, MB, or GB). The default unit is in KB. You can set this value to
 *   0 for no limit. Defaults to 0.
 * - errorMessages: Error messages, indexed by HTTP code (codes are returned by
 *   the uploader script as HTTP response codes). You can use mustache
 *   placeholder, valid place holders are: {{file}}, {{errorCode}}, {{errorMsg}}
 *   and {{errorString}}. Predefined messages are defined in the 
 *   `defaultErrorMessages` property of this class.
 * - itemTemplate (string): The itemTemplate option allows you to specify a
 *   special HTML template for each item that is added to the queue. Default
 *   template is set in `defaultItemTempalte` property of this class.
 * 
 * @param object settings Object of settings as described above.
 * @return void
 */
	init: function (settings) {
		var self = this;
		var defaults = {
			instanceID: null,
			instanceName: null,
			queueID: false,
			multi: true,
			fileTypeExts: '*.*',
			queueSizeLimit: 999,
			uploadLimit: 999,
			showDescription: false,
			fileSizeLimit: 0,
			itemTemplate: self.defaultItemTempalte,
			errorMessages: self.defaultErrorMessages,
			uploadLimitDown: function () {
				this.uploadLimit = this.uploadLimit - 1;
			},
			uploadLimitUp: function () {
				this.uploadLimit = this.uploadLimit + 1;
			},
		};
		settings = $.extend(defaults, settings);

		if (!settings.instanceID) {
			throw "Missing instanceID option";
		} else if (!settings.instanceName) {
			throw "Missing instanceName option";
		}

		var uploader = $('#' + settings.instanceID + '-uploader').uploadify({
			swf: self.swf,
			uploader: settings.uploader,
			queueID: settings.queueID,
			multi: settings.multi,
			buttonText: settings.buttonText,
			queueSizeLimit: settings.queueSizeLimit,
			auto: true,
			itemTemplate: settings.itemTemplate,
			fileTypeExts: settings.fileTypeExts,
			fileTypeDesc: settings.fileTypeDesc,
			fileSizeLimit: settings.fileSizeLimit,
			onUploadStart: function (file) {
				if (settings.uploadLimit <= 0) {
					alert('The file "' + file.name + '" will not be upload, upload limit reached');
					$('#' + settings.instanceID + '-uploader').uploadify('cancel', file.id, true);
				}
			},
			onUploadSuccess: function (file, data, response) {
				r = $.parseJSON(data);
				r.number = $('#' + settings.instanceID + ' ul.files-list li').length + 1;
				self.onUploadSuccess(r, settings);
			},
			onUploadError: function (file, errorCode, errorMsg, errorString) {
				if (settings.errorMessages[errorMsg]) {
					var message = Mustache.render(settings.errorMessages[errorMsg], {
						file: file,
						errorCode: errorCode,
						errorMsg: errorMsg,
						errorString: errorString,
					});
					alert(message);
				} else {
					alert('The file ' + file.name + ' could not be uploaded.');
				}
			},
			onSelect: function (file) {
				self.onSelect(file, settings);
			},
			onSWFReady: function() {
				if (!settings.uploadLimit) {
					$('#' + settings.instanceID + '-uploader').uploadify('disable', true);
				}
			}
		});

		self.instances[settings.instanceID] = {
			settings: settings,
			uploader: uploader,
		};
	},
	onSelect: function (file, settings) {
		if (settings.uploadLimit <= 0) {
			$('#' + settings.instanceID + '-uploader').uploadify('cancel', '*');
			alert('You are allowed to upload up to ' + settings.uploadLimit + ' files.');
		}
	},
	onUploadSuccess: function (response, settings) {
		var self = this;
		var view = {
			uid: settings.instanceID + '-f' + response.number,
			number: response.number,
			icon_url: self.baseUrl + 'field/img/file-icons/' + response.mime_icon,
			link: response.file_url,
			file_name: response.file_name,
			file_size: response.file_size,
			instance_name: settings.instanceName,
			mime_icon: response.mime_icon,
			file_name: response.file_name,
			file_size: response.file_size,
			description: '',
			show_icon: true,
			show_description: settings.showDescription,
		};
		var template = '<li>' + Mustache.render($('#file-item-template').html(), view) + '</li>';
		$('#' + settings.instanceID + '-files-list').append(template);

		settings.uploadLimitDown();
		if (!settings.uploadLimit) {
			$('#' + settings.instanceID + '-uploader').uploadify('disable', true);
		}
		$('#' + settings.instanceID + ' ul.files-list').show();
	},

/**
 * Delete file for the specified field instance, if file has been just uploaded
 * then is deleted from server. Otherwise, File will be deleted ONLY after user
 * clicks `SAVE` in the entity edit form.
 *
 * @param string id ID of the file-item element
 */
	remove: function (id) {
		var self = this;
		var parent = $('#' + id).closest('.file-handler');
		var settings = self.instances[parent.attr('id')].settings;
		var fileName =  $('#' + id + ' input.file-name').val();

		if (!$('#' + id).hasClass('is-perm')) {
			$.ajax(settings.remover + '?file=' + fileName)
				.done(function () {
					$('#' + id).parent().remove(); // remove <li>
					settings.uploadLimitUp();
					self._afterRemove(settings);
				});
		} else {
			$('#' + id).parent().remove();
			settings.uploadLimitUp();
			this._afterRemove(settings);
		}
	},
	_afterRemove: function (settings) {
		if (settings.uploadLimit > 0) {
			var filesInList = $('#' + settings.instanceID + ' ul.files-list li').length;
			$('#' + settings.instanceID + '-uploader').uploadify('disable', false);

			if (!filesInList) {
				$('#' + settings.instanceID + ' ul.files-list').hide();
			} else {
				$('#' + settings.instanceID + ' ul.files-list').show();
			}
		}
	}
}
