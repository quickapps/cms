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