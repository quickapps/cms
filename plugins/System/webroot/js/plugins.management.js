$(document).ready(function () {
    $('a.toggler').click(function () {
        $a = $(this);
        $icon = $a.children('span.glyphicon');
        $a.parent().next('div.extended-info').toggle();
        if ($icon.hasClass('glyphicon-arrow-up')) {
            $icon.removeClass('glyphicon-arrow-up');
            $icon.addClass('glyphicon-arrow-down');
        } else {
            $icon.removeClass('glyphicon-arrow-down');
            $icon.addClass('glyphicon-arrow-up');
        }
        return false;
    });

    $('.filter-input').on('keyup', function() {
        var group = $('.filters a.active');
        var selector = '.plugins-list tbody tr';
        if (group.hasClass('btn-enabled')) {
            selector = '.plugins-list tbody tr.enabled';
        } else if (group.hasClass('btn-disabled')){
            selector = '.plugins-list tbody tr.disabled';
        }
        if (this.value.length < 1) {
            $('.plugins-list tbody tr').css('display', '');
        } else {
            $(selector + ":not(:contains('"+ this.value + "'))").css('display', 'none');
            $(selector + ":contains('" + this.value + "')").css('display', '');
        }
    });
});

function filterBy(type) {
    var type = type.replace('#show-', '');
    type = type == '' ? 'all' : type;
    var tr = type == 'all' ? 'tr' : 'tr.' + type;
    $('.filters a.btn').removeClass('active');
    $('.filters a.btn-' + type).addClass('active');
    $('.plugins-list tbody tr').hide();
    $('.plugins-list tbody ' + tr).show();
}

$(window).hashchange(function () {
    filterBy(location.hash);
});

$(window).hashchange();