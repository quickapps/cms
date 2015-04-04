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
            itemFormatter: null,
        },
        uploader: {
            swf: null,
            uploader: null,
            remover: null,
            debug: false,
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
                504: 'The file {{file.name}} could not be uploaded: invalid field instance.',
                501: 'The file {{file.name}} could not be uploaded: invalid file extension.',
                502: 'The file {{file.name}} could not be uploaded: internal server error.',
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
 *       each uploaded item. Defaults to "file-item-template".
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
                    $('#FileField-' + settings.instance.id + '-uploader').uploadify('cancel', file.id, true);
                }
            },
            onUploadSuccess: function (file, data, response) {
                r = $.parseJSON(data);
                r.number = $('#FileField-' + settings.instance.id + ' ul.files-list li').length + 1;
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
                    $('#FileField-' + settings.instance.id + '-uploader').uploadify('disable', true);
                }
            }
        });

        uploadifyOptions.uploadLimit = 999;
        var uploader = $('#FileField-' + settings.instance.id + '-uploader').uploadify(uploadifyOptions);
        self.setInstance(settings.instance.id, {
            settings: settings,
            uploader: uploader,
        });

        $.event.trigger({
            type: 'FileField-' + settings.instance.id + '-init',
            message: 'FileField-' + settings.instance.id + ' ready to upload',
            time: new Date()
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
        if (self._instances['FileField-' + id]) {
            return self._instances['FileField-' + id];
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
        self._instances['FileField-' + id] = options;
    },

/**
 * Renders file item.
 *
 * @param {Integer} instanceID File instance ID
 * @param {Object} view View options to mustache template
 * @return {String}
 */
    renderItem: function (instanceID, view) {
        var self = this;
        var settings = self.getInstance(instanceID).settings.instance;
        var number = self._getNextNumber(instanceID);

        view.uid = 'FileField-' + settings.id + '-item-' + number;
        view.number = number;

        console.log("Rendering item #" + number);

        if (typeof window[settings.itemFormatter] == 'function') {
            return window[settings.itemFormatter](view, settings);
        } else {
            return Mustache.render($('#file-item-template').html(), view);
        }
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
        var settings = self.getInstance(parent.attr('id').split('-')[1]).settings;
        var fileName =  $('#' + id + ' input.file-name').val();

        if (!$('#' + id).hasClass('is-perm')) {
            $.ajax(settings.uploader.remover + '?file=' + fileName)
                .done(function () {
                    $('#' + id).parent().remove(); // remove <li>
                    settings.uploader.uploadLimitUp();
                    self._afterRemove(settings);
                });
        } else {
            $('#' + id).parent().remove(); // remove <li>
            settings.uploader.uploadLimitUp();
            this._afterRemove(settings);
        }
    },

/**
 * Calculates the next number within a field instance files list. This value is
 * used to properly fill form inputs arrays, as an instance can have multiple
 * files they must be index by an integer number when sending the POST data.
 *
 * @param {Integer} instanceID Instance ID for which calculate the next number
 * @return {Integer}
 */
    _getNextNumber: function (instanceID) {
        var self = this;
        var settings = self.getInstance(instanceID).settings.instance;
        var numbers = [];
        var number = 0;

        $('#FileField-' + settings.id + ' ul.files-list li div').each(function () {
            $div = $(this);
            num = parseInt($div.data('number'));
            if (!isNaN(num)) {
                numbers.push(num);
            }
        });

        if (numbers.length > 0) {
            numbers.sort(function (a, b) {
                return b - a;
            });
            number = numbers[0] + 1;
        }


        return number;
    },

/**
 * Triggered after a field was deleted.
 *
 * @param  {Object} settings Settings of the instance that file belonged to
 * @return void
 */
    _afterRemove: function (settings) {
        if (settings.uploader.uploadLimit > 0) {
            var filesInList = $('#FileField-' + settings.instance.id + ' ul.files-list li').length;
            $('#FileField-' + settings.instance.id + '-uploader').uploadify('disable', false);

            if (!filesInList) {
                $('#FileField-' + settings.instance.id + ' ul.files-list').hide();
            } else {
                $('#FileField-' + settings.instance.id + ' ul.files-list').show();
            }
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
            $('#FileField-' + settings.instance.id + '-uploader').uploadify('cancel', '*');
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
        var template = '<li>' + self.renderItem(settings.instance.id, view) + '</li>';
        $('#FileField-' + settings.instance.id + '-files-list').append(template);

        settings.uploader.uploadLimitDown();
        if (!settings.uploader.uploadLimit) {
            $('#FileField-' + settings.instance.id + '-uploader').uploadify('disable', true);
        }
        $('#FileField-' + settings.instance.id + ' ul.files-list').show();
    },

}
