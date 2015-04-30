$(document).ready(function () {
    $('a.toggler').click(function () {
        $a = $(this);
        $a.closest('div').find('.extended-info').toggle();
        if ($a.hasClass('glyphicon-arrow-up')) {
            $a.removeClass('glyphicon-arrow-up');
            $a.addClass('glyphicon-arrow-down');
        } else {
            $a.removeClass('glyphicon-arrow-down');
            $a.addClass('glyphicon-arrow-up');
        }
        return false;
    });
});

function filterBy(type) {
    var type = type.replace('#show-', '');
    var panel = type == 'all' ? '.panel' : '.panel-' + type;
    $('.filters a.btn').removeClass('active');
    $('.filters a.btn-' + type).addClass('active');
    $('.plugins-list .panel').hide();
    $('.plugins-list ' + panel).show();
}

$(window).hashchange(function () {
    filterBy(location.hash);
});

$(window).hashchange();