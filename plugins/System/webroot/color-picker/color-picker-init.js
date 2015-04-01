/**
 * Initializes all color picker widgets.
 */
$(document).ready(function() {
    $('div.color_picker .preview').each(function () {
        var $preview = $(this);
        var $input = $('#' + $preview.data('for'));
        var initColor = $input.val();
        $preview.css('backgroundColor', initColor);
        $preview.ColorPicker({
            color: initColor,
            onChange: function (hsb, hex, rgb) {
                $input.val('#' + hex);
                $preview.css('backgroundColor', '#' + hex);
            }
       });
    });
});