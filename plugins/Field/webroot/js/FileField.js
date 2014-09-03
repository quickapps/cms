/**
 * File field handler for Uploadify.
 *
 * @author Christopher Castro <chri@quickapps.es>
 * @link http://www.quickappscms.org
 */
FileField = {

/**
 * Holds all uploader instances created.
 *
 * @type {Object}
 */
	_instances: {},

/**
 * Default settings for each instance created. This settings are merged with
 * custom settings when creating a new instance using the "init()" method.
 * 
 * @type {Object}
 */
	defaultSettings: {
		instance: {
			id: null,
			name: null,
			showDescription: false,
		},
		uploader: {
			swf: null,
			uploader: null,
			remover: null,
			mimeIconsBaseURL: null,
			itemTemplate: '<div id="${fileID}" class="uploadify-queue-item">\
				<div class="cancel">\
					<a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">X</a>\
				</div>\
				<span class="fileName">${fileName} (${fileSize})</span><span class="data"></span>\
				<div class="uploadify-progress">\
					<div class="uploadify-progress-bar"><!--Progress Bar--></div>\
				</div>\
			</div>',
			errorMessages: {
				400: 'The file {{file.name}} could not be uploaded: invalid field instance.',
				422: 'The file {{file.name}} could not be uploaded: invalid file extension.',
				500: 'The file {{file.name}} could not be uploaded: internal server error.',
			},
			uploadLimitDown: function () {
				this.uploadLimit = this.uploadLimit - 1;
			},
			uploadLimitUp: function () {
				this.uploadLimit = this.uploadLimit + 1;
			},
		}

	},

/**
 * Initializes the uploader for the given field instance.
 *
 * ### Valid settings options are:
 *
 * - instance
 *     - id (string): REQUIRED unique ID to identify the field handler this
 *       uploader will be attached to. If not given an exception will be throw.
 *     - name (string): REQUIRED field instance's machine-name. If not
 *       given an exception will be throw.
 *     - showDescription (bool): Whether to show field instance help hint
 *       or not. Defaults to false.
 * - uploader: Any valid options accepted by uploadify class (check their 
 *   [documentation](http://www.uploadify.com/documentation/)), in addition to
 *   these options there is also: 
 *     - remover (string): URL of the script that handles file deletions.
 *     - errorMessages (object): Error messages, indexed by HTTP code (codes are
 *       returned by the uploader script as HTTP response codes). You can use
 *       mustache placeholder, valid place holders are: {{file}}, {{errorCode}},
 *       {{errorMsg}} and {{errorString}}.
 *     - mimeIconsBaseURL (string): Base URL to use for file mime-icons.
 * 
 * @param {Object} settings Object of settings as described above.
 * @return void
 */
	init: function (settings) {
		var self = this;
		settings = $.extend(true, {}, self.defaultSettings, settings);

		if (!settings.instance.id) {
			throw "Missing instance.id option.";
		} else if (!settings.instance.name) {
			throw "Missing instance.name option.";
		} else if (!settings.uploader.swf) {
			throw "Missing uploader.swf option, must be set to an URL.";
		} else if (!settings.uploader.uploader) {
			throw "Missing uploader.uploader option, must be set to an URL.";
		} else if (!settings.uploader.remover) {
			throw "Missing uploader.remover option, must be set to an URL.";
		}

		var uploadifyOptions = $.extend(true, {}, settings.uploader, {
			onUploadStart: function (file) {
				if (settings.uploader.uploadLimit <= 0) {
					alert('The file "' + file.name + '" will not be upload, upload limit reached');
					$('#' + settings.instance.id + '-uploader').uploadify('cancel', file.id, true);
				}
			},
			onUploadSuccess: function (file, data, response) {
				r = $.parseJSON(data);
				r.number = $('#' + settings.instance.id + ' ul.files-list li').length + 1;
				self._onUploadSuccess(r, settings);
			},
			onUploadError: function (file, errorCode, errorMsg, errorString) {
				if (settings.uploader.errorMessages[errorMsg]) {
					var message = Mustache.render(settings.uploader.errorMessages[errorMsg], {
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
				self._onSelect(file, settings);
			},
			onSWFReady: function() {
				if (!settings.uploader.uploadLimit) {
					$('#' + settings.instance.id + '-uploader').uploadify('disable', true);
				}
			}
		});

		uploadifyOptions.uploadLimit = 999;
		var uploader = $('#' + settings.instance.id + '-uploader').uploadify(uploadifyOptions);
		self.setInstance(settings.instance.id, {
			settings: settings,
			uploader: uploader,
		});
	},

/**
 * Gets an instance information by ID.
 * 
 * @param {String} id Instance ID
 * @return {Object}
 */
	getInstance: function (id) {
		var self = this;
		if (self._instances[id]) {
			return self._instances[id];
		}
		return {};
	},

/**
 * Registers a new instance.
 * 
 * @param {String} id Instance ID
 * @param {Object} settings Settings used by this instance
 * @return void
 */
	setInstance: function (id, options) {
		var self = this;
		self._instances[id] = options;
	},

/**
 * Deletes the given file.
 * 
 * If file was recently uploaded (not yet attached to entity) will is deleted
 * from server immediately. Otherwise, if file is already attached to an entity
 * it will be deleted ONLY after user clicks `SAVE` in the entity edit form.
 *
 * @param {String} id ID of the file-item element
 * @return void
 */
	remove: function (id) {
		var self = this;
		var parent = $('#' + id).closest('.file-handler');
		var settings = self.getInstance(parent.attr('id')).settings;
		var fileName =  $('#' + id + ' input.file-name').val();

		if (!$('#' + id).hasClass('is-perm')) {
			$.ajax(settings.uploader.remover + '?file=' + fileName)
				.done(function () {
					$('#' + id).parent().remove(); // remove <li>
					settings.uploader.uploadLimitUp();
					self._afterRemove(settings);
				});
		} else {
			$('#' + id).parent().remove();
			settings.uploader.uploadLimitUp();
			this._afterRemove(settings);
		}
	},
/**
 * Triggered by uploadify when each file is selected for the queue.
 * 
 * @param {Object} file The selected file
 * @param {Object} settings Instance settings
 * @return void
 */
	_onSelect: function (file, settings) {
		if (settings.uploader.uploadLimit <= 0) {
			$('#' + settings.instance.id + '-uploader').uploadify('cancel', '*');
			alert('You are allowed to upload up to ' + settings.uploader.uploadLimit + ' files.');
		}
	},

/**
 * Triggered by uploadify when a file is successfully uploaded.
 * 
 * @param {Object} response The server response, expected to be a JSON object
 * @param {Object} settings Instance settings
 * @return void
 */
	_onUploadSuccess: function (response, settings) {
		var self = this;
		var view = {
			uid: settings.instance.id + '-f' + response.number,
			number: response.number,
			icon_url: settings.uploader.mimeIconsBaseURL + 'field/img/file-icons/' + response.mime_icon,
			link: response.file_url,
			file_name: response.file_name,
			file_size: response.file_size,
			instance_name: settings.instance.name,
			mime_icon: response.mime_icon,
			file_name: response.file_name,
			file_size: response.file_size,
			description: '',
			show_icon: true,
			show_description: settings.instance.showDescription,
		};
		var template = '<li>' + Mustache.render($('#file-item-template').html(), view) + '</li>';
		$('#' + settings.instance.id + '-files-list').append(template);

		settings.uploader.uploadLimitDown();
		if (!settings.uploader.uploadLimit) {
			$('#' + settings.instance.id + '-uploader').uploadify('disable', true);
		}
		$('#' + settings.instance.id + ' ul.files-list').show();
	},

/**
 * Triggered after a field was deleted.
 * 
 * @param  {Object} settings Settings of the instance that file belonged to
 * @return void
 */
	_afterRemove: function (settings) {
		if (settings.uploader.uploadLimit > 0) {
			var filesInList = $('#' + settings.instance.id + ' ul.files-list li').length;
			$('#' + settings.instance.id + '-uploader').uploadify('disable', false);

			if (!filesInList) {
				$('#' + settings.instance.id + ' ul.files-list').hide();
			} else {
				$('#' + settings.instance.id + ' ul.files-list').show();
			}
		}
	}
}
