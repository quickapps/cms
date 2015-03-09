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
 * Mustache template that represents a single image item in the list of
 * uploaded files within an instance of Image Field Handler.
 *
 * Valid mustache placeholder are described below:
 *
 * - (string) instance_name: Field instance machine-name. e.g. `article-image`.
 * - (string) uid: Unique ID for this file item.
 * - (string) number: Number of this file item within field instance.
 * - (bool) perm: Whether this file is already saved on DB, or it was just uploaded.
 * - (bool) show_image: Whether to show the image thumbnail or not.
 * - (bool) show_title: Whether to show image's title input.
 * - (bool) show_alt: Whether to show image's ALT input.
 * - (string) link: File's link.
 * - (string) icon_url: URL of the file icon.
 * - (string) mime_icon: The icon PNG file name. e.g. `pdf.png`
 * - (string) file_name: File's name, including its extension. e.g. `document.pdf`
 * - (string) file_size: File's size. e.g. `400 KB`
 */
?>

<?php if (!isset($this->viewVars['__ImageFieldTemplates__'])): ?>
    <?php $this->viewVars['__ImageFieldTemplates__'] = '__LOADED__'; ?>
    <script id="image-item-template" type="x-tmpl-mustache">
        <div id="{{uid}}" class="alert alert-info {{#perm}}is-perm{{/perm}} file-item" data-number="{{number}}">
            <div class="media">
                {{#show_image}}
                <div class="pull-left text-center">
                    <a href="{{&link}}" target="_blank" class="file-link">
                        <img src="{{&thumbnail_url}}" class="file-image media-object" />
                    </a>
                    <span class="file-size"><small>({{file_size}})</small></span>
                </div>
                {{/show_image}}

                <div class="media-body">
                    {{#show_image}}
                        <p>
                            <button class="btn btn-danger btn-xs" onclick="FileField.remove('{{uid}}'); return false;"><?php echo __d('field', 'Remove'); ?></button>
                        </p>
                    {{/show_image}}

                    {{^show_image}}
                        <p>
                            <img src="{{&icon_url}}" class="file-icon" />
                            <a href="{{&link}}" target="_blank" class="file-link">{{file_name}}</a>
                            <span class="file-size">({{file_size}})</span>
                            <button class="btn btn-danger btn-xs" onclick="FileField.remove('{{uid}}'); return false;"><?php echo __d('field', 'Remove'); ?></button>
                        </p>
                    {{/show_image}}

                    {{#show_title}}
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo __d('field', 'Title'); ?></span>
                            <input type="text" name=":{{instance_name}}[{{number}}][title]" value="{{title}}" class="file-description form-control input-sm" placeholder="<?php echo __d('field', 'Image title or description'); ?>" />
                        </div>
                    </div>
                    {{/show_title}}

                    {{#show_alt}}
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo __d('field', 'Alt'); ?></span>
                            <input type="text" name=":{{instance_name}}[{{number}}][alt]" value="{{alt}}" class="file-description form-control input-sm" placeholder="<?php echo __d('field', 'Alternative text'); ?>" />
                        </div>
                    </div>
                </div>
                {{/show_alt}}
            </div>

            <input type="hidden" name=":{{instance_name}}[{{number}}][mime_icon]" value="{{mime_icon}}" class="mime-icon" />
            <input type="hidden" name=":{{instance_name}}[{{number}}][file_name]" value="{{file_name}}" class="file-name" />
            <input type="hidden" name=":{{instance_name}}[{{number}}][file_size]" value="{{file_size}}" class="file-size" />
        </div>
    </script>

    <script type="text/javascript">
        function imageFieldItemFormatter(view, settings) {
            view.show_image = settings.showThumbnail;
            view.show_title = settings.showTitle;
            view.show_alt = settings.showAlt;
            view.thumbnail_url = '<?php echo $this->Url->build(['plugin' => 'Field', 'controller' => 'image_handler', 'action' => 'thumbnail', 'prefix' => false]); ?>/' + settings.name + '?size=' + settings.thumbnailSize + '&file=' + view.file_name;

            view.thumbnail_size = 200;
            return Mustache.render($('#image-item-template').html(), view);
        }
    </script>
<?php endif; ?>
