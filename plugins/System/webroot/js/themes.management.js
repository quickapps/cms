$(document).ready(function () {
	$('.filters a.btn').click(function () {
		$a = $(this);
		var type = $a.attr('href');
		filterBy(type);
		return true;
	});

	var hash = window.location.hash.substr(1);
	if (hash && $.inArray(hash, ['show-front', 'show-back']) >= 0) {
		filterBy(hash);
	}

	$('.filter-input').on('keyup', function() {
		var group = $('.filters a.active');
		var selector = '.front-themes .theme-box';
		if (group.hasClass('btn-back')) {
			selector = '.back-themes .theme-box';
		}

		if (this.value.length < 1) {
			$('.theme-box').css('display', '');
		} else {
			$(selector + ":not(:contains('"+ this.value + "'))").css('display', 'none');
			$(selector + ":contains('" + this.value + "')").css('display', '');
		}
	});

	function filterBy(type) {
		var type = type.replace('#', '');
		$('.filters a.btn').removeClass('active');
		if (type === 'show-front') {
			$('.filters a.btn-front').addClass('active');
			$('.themes-list').hide();
			$('.front-themes').show();
		} else if (type === 'show-back') {
			$('.filters a.btn-back').addClass('active');
			$('.themes-list').hide();
			$('.back-themes').show();
		}
	}

	filterBy('show-front');
});