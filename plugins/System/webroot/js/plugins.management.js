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

	$('.filters a.btn').click(function () {
		$a = $(this);
		var type = $a.attr('href');
		filterBy(type);
		return true;
	});

	var hash = window.location.hash.substr(1);
	if (hash && $.inArray(hash, ['show-all', 'show-enabled', 'show-disabled']) >= 0) {
		filterBy(hash);
	}

	$('.filter-input').on('keyup', function() {
		var group = $('.filters a.active');
		var selector = '.plugin-panel';
		if (group.hasClass('btn-enabled')) {
			selector = '.panel-enabled';
		} else if (group.hasClass('btn-disabled')){
			selector = '.panel-disabled';
		}
		if (this.value.length < 1) {
			$('.plugin-panel').css('display', '');
		} else {
			$(selector + ":not(:contains('"+ this.value + "'))").css('display', 'none');
			$(selector + ":contains('" + this.value + "')").css('display', '');
		}
	});

	function filterBy(type) {
		var type = type.replace('#', '');
		$('.filters a.btn').removeClass('active');
		if (type === 'show-all') {
			$('.plugins-list .panel').show();
			$('.filters a.btn-all').addClass('active');
		} else if (type === 'show-enabled') {
			$('.plugins-list .panel').hide();
			$('.plugins-list .panel-enabled').show();
			$('.filters a.btn-enabled').addClass('active');
		} else if (type === 'show-disabled') {
			$('.plugins-list .panel').hide();
			$('.plugins-list .panel-disabled').show();
			$('.filters a.btn-disabled').addClass('active');
		}
	}

	filterBy('show-all');
});