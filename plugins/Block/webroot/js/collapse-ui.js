$(document).ready(function () {
	$.cookie.raw = true;
	$('.sortable').sortable().disableSelection();
	$('#accordion').on('shown.bs.collapse', function () {
		$('div.panel-collapse').each(function () {
			$div = $(this);
			if ($div.hasClass('in')) {
				$.removeCookie('blockCollapseLatestExpanded');
				$.cookie('blockCollapseLatestExpanded', $div.attr('id'));
				return false;
			}
		});
	});

	var hash = window.location.hash.substr(1);
	if (hash && $.inArray(hash, ['front-theme', 'back-theme', 'unused-blocks']) >= 0) {
		$('#' + hash).collapse('show');
	} else {
		var prev = $.cookie('blockCollapseLatestExpanded');
		prev = prev ? '#' + prev : '#front-theme';
		$(prev).collapse('show');
	}
});